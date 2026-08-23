<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    /**
     * Handle incoming chatbot message request.
     * Integrates with the custom-trained IndoBERT Flask API (Intent Classification + NER + Soft Hybrid Retrieval).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function message(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $userMessage = trim($request->input('message'));
        $apiUrl = config('services.indobert.api_url', env('INDOBERT_API_URL', 'http://127.0.0.1:5000/api/chat'));
        $timeout = (int) config('services.indobert.timeout', env('INDOBERT_TIMEOUT', 30));

        Log::info('Chatbot request received', [
            'message' => $userMessage,
            'target_endpoint' => $apiUrl,
        ]);

        try {
            $response = Http::timeout($timeout)->post($apiUrl, [
                'query' => $userMessage,
                'show_diagnostics' => false,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $answer = $data['answer'] ?? null;

                if (!empty($answer)) {
                    Log::info('IndoBERT chatbot response successful', [
                        'intent' => $data['intent'] ?? 'unknown',
                        'status' => $data['status'] ?? 'unknown',
                        'response_time_ms' => $data['response_time_ms'] ?? null,
                    ]);

                    // Deteksi respon Out-Of-Scope (OOS) secara peka dan akurat
                    if ($this->isOutOfScope($userMessage, $answer, $data)) {
                        return response()->json([
                            'reply' => "Mohon maaf, SINDESA adalah Asisten Layanan Publik yang khusus membantu memberikan informasi terkait administrasi kependudukan, perizinan, dan layanan publik lainnya.<br><br>"
                                     . "Jika Anda membutuhkan informasi seputar layanan publik, silakan ajukan pertanyaan Anda, dan dengan senang hati saya akan membantu menjelaskannya.",
                            'suggestions' => [
                                ['label' => 'Syarat KTP-el', 'message' => 'Apa syarat mengurus KTP-el di Pinrang?'],
                                ['label' => 'Bayar PBB', 'message' => 'Bagaimana cara membayar PBB di Pinrang?'],
                                ['label' => 'Lokasi Samsat', 'message' => 'Di mana lokasi Samsat Pinrang?']
                            ]
                        ]);
                    }

                    return response()->json([
                        'reply' => $this->formatIndoBertResponse($answer, $userMessage),
                        'suggestions' => $this->generateSuggestions($userMessage),
                    ]);
                }
            }

            Log::error('IndoBERT API returned error status', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

        } catch (\Exception $e) {
            Log::error('IndoBERT API connection failed', [
                'error' => $e->getMessage(),
                'target_url' => $apiUrl,
            ]);
        }

        // Fallback response jika service Python offline / sedang reload
        return response()->json([
            'reply' => '<b>⚠️ Layanan Chatbot Sedang Dalam Pemeliharaan</b><br><br>'
                     . 'Mohon maaf, asisten AI SINDESA sedang memuat ulang model layanan. '
                     . 'Silakan coba lagi dalam beberapa saat.<br><br>'
                     . 'Jika mendesak, Anda dapat langsung menghubungi Kantor Desa Buttu Sawe di:<br>'
                     . '📧 <b>sindesa.buttusawe@gmail.com</b><br>'
                     . '📍 Jl. Poros Bungi-Rajang, Desa Buttu Sawe, Kec. Duampanua, Kab. Pinrang',
            'suggestions' => [
                ['label' => 'Coba Lagi', 'message' => $userMessage],
                ['label' => 'Syarat KTP-el', 'message' => 'Apa syarat mengurus KTP-el di Pinrang?'],
                ['label' => 'Daftar Layanan', 'message' => 'Apa saja layanan surat yang ada?']
            ]
        ]);
    }

    /**
     * Format teks jawaban dari engine IndoBERT menjadi HTML bersih yang cocok untuk bubble chat,
     * serta memastikan konsistensi wilayah sesuai pertanyaan pengguna.
     *
     * @param  string  $text
     * @param  string  $userMessage
     * @return string
     */
    private function formatIndoBertResponse(string $text, string $userMessage = ''): string
    {
        // 0. Sanitasi & Penyesuaian Wilayah
        $text = $this->sanitizeAndAlignRegion($text, $userMessage);

        // 1. Ubah Markdown heading (## Judul) menjadi <b>
        $text = preg_replace('/^#{1,6}\s*(.+)$/m', '<b>$1</b>', $text);

        // 2. Ubah Markdown bold (**teks** atau __teks__) menjadi <b>
        $text = preg_replace('/\*\*(.*?)\*\*/s', '<b>$1</b>', $text);
        $text = preg_replace('/__(.*?)__/s', '<b>$1</b>', $text);

        // 3. Ubah Markdown Link: [Teks](URL) menjadi clickable <a> tag
        $text = preg_replace('/\[(.*?)\]\((https?:\/\/[^\s\)]+)\)/', '<a href="$2" target="_blank" rel="noopener noreferrer" class="sindesa-chat-inline-link">$1</a>', $text);

        // 4. Ubah Plain URL yang belum jadi link menjadi clickable <a> tag
        $text = preg_replace('/(?<!href=")(?<!">)(https?:\/\/[^\s<\)]+)/', '<a href="$1" target="_blank" rel="noopener noreferrer" class="sindesa-chat-inline-link">$1</a>', $text);

        // 5. Ubah Instagram @username menjadi clickable <a> link
        $text = preg_replace('/(?<!\w)@([a-zA-Z0-9_\.]+)/', '<a href="https://instagram.com/$1" target="_blank" rel="noopener noreferrer" class="sindesa-chat-inline-link">@$1</a>', $text);

        // 6. Ubah Nomor WhatsApp (misal 0821-8747-6343) menjadi link direct chat wa.me
        $text = preg_replace_callback('/(\b08\d{2}[-\s]?\d{4}[-\s]?\d{3,5}\b)/', function ($m) {
            $cleanNum = preg_replace('/[^\d]/', '', $m[1]);
            $waNum = '62' . substr($cleanNum, 1);
            return '<a href="https://wa.me/' . $waNum . '" target="_blank" rel="noopener noreferrer" class="sindesa-chat-inline-link" title="Buka WhatsApp">' . $m[1] . '</a>';
        }, $text);

        // 7. Ubah Unordered list (- item, * item, • item) menjadi <ul class="sindesa-chat-bubble-list">
        $text = preg_replace_callback('/(?:^[\-\*•]\s+.+$\n?)+/m', function ($matches) {
            $items = preg_split('/\n/', trim($matches[0]));
            $html = '<ul class="sindesa-chat-bubble-list">';
            foreach ($items as $item) {
                $item = preg_replace('/^[\-\*•]\s+/', '', trim($item));
                if ($item !== '') {
                    $html .= '<li>' . $item . '</li>';
                }
            }
            $html .= '</ul>';
            return $html;
        }, $text);

        // 8. Ubah Ordered list (1. item) menjadi <ol class="sindesa-chat-bubble-list">
        $text = preg_replace_callback('/(?:^\d+[\.\)]\s+.+$\n?)+/m', function ($matches) {
            $items = preg_split('/\n/', trim($matches[0]));
            $html = '<ol class="sindesa-chat-bubble-list">';
            foreach ($items as $item) {
                $item = preg_replace('/^\d+[\.\)]\s+/', '', trim($item));
                if ($item !== '') {
                    $html .= '<li>' . $item . '</li>';
                }
            }
            $html .= '</ol>';
            return $html;
        }, $text);

        // 9. Ubah newline menjadi <br> (kecuali di sekitar tag pembuka/penutup blok)
        $text = preg_replace('/\n(?!<)/', '<br>', $text);

        // 10. Bersihkan <br> berlebih
        $text = preg_replace('/(<br>\s*){3,}/', '<br><br>', $text);

        return trim($text);
    }

    /**
     * Sanitasi dan sinkronisasi informasi wilayah agar 100% akurat sesuai pertanyaan pengguna.
     *
     * @param  string  $text
     * @param  string  $userMessage
     * @return string
     */
    private function sanitizeAndAlignRegion(string $text, string $userMessage): string
    {
        $userMsgLower = strtolower($userMessage);
        $hasPinrang = preg_match('/\b(pinrang|buttu sawe|duampanua|bungi)\b/i', $userMsgLower);
        $hasAnyRegion = preg_match('/\b(pinrang|buttu sawe|duampanua|bungi|makassar|parepare|sidrap|enrekang|polman|bone|gowa|maros|luwu|toraja|palopo|jakarta|surabaya|bandung|medan|pangkal pinang|pangkalpinang)\b/i', $userMsgLower);

        // Khusus Pertanyaan PBB (Pajak Bumi dan Bangunan)
        if (preg_match('/\b(pbb|pajak bumi|pajak bangunan|bayar pbb|cek pbb|tagihan pbb)\b/i', $userMsgLower)) {
            $regionLabel = $hasPinrang ? 'Kabupaten Pinrang' : 'daerah setempat';
            $greeting = $hasPinrang ? "Halo Warga Pinrang, " : "";
            
            $text = $greeting . "Berikut adalah panduan lengkap cara pengecekan dan pembayaran Pajak Bumi dan Bangunan (PBB) di {$regionLabel}:\n\n"
                  . "**1. Cara Cek Tagihan PBB:**\n"
                  . "• Siapkan 18 digit **NOP (Nomor Objek Pajak)** yang tertera pada lembar SPPT PBB tahun sebelumnya.\n"
                  . "• Pengecekan tagihan dapat dilakukan secara online melalui aplikasi Mobile Banking mitra atau website resmi Bapenda.\n\n"
                  . "**2. Pilihan Saluran & Bank Pembayaran Resmi:**\n"
                  . "• **Bank Mitra:** Bank Sulselbar, BRI, BNI, Bank Mandiri, dan BCA (melalui Teller, ATM, atau Mobile Banking).\n"
                  . "• **Dompet Digital & E-Commerce:** Tokopedia, Shopee, GoPay, OVO, DANA, dan LinkAja (pilih menu PBB / Penerimaan Daerah).\n"
                  . "• **Gerai Retail Modern & Pos:** Indomaret, Alfamart, dan Kantor Pos Indonesia.\n"
                  . "• **Loket Langsung:** Kantor Bapenda Kabupaten Pinrang atau melalui petugas penagih PBB di Kantor Desa Buttu Sawe.\n\n"
                  . "**3. Langkah Pembayaran via Mobile Banking / E-Commerce:**\n"
                  . "1. Buka aplikasi m-Banking atau E-Commerce pilihan Anda.\n"
                  . "2. Pilih menu **Pembayaran / Tagihan > Pajak Daerah / PBB**.\n"
                  . "3. Pilih wilayah **Kabupaten Pinrang / Sulawesi Selatan**.\n"
                  . "4. Masukkan 18 digit **NOP** dan tahun pajak yang ingin dibayar.\n"
                  . "5. Periksa nama wajib pajak serta rincian nominal tagihan.\n"
                  . "6. Konfirmasi pembayaran dan simpan bukti transaksi/struk sebagai bukti lunas yang sah.\n\n"
                  . ($hasPinrang ? "**Informasi Resmi Bapenda & Pajak Daerah Pinrang:**\n• Website: https://bapenda.sulselprov.go.id/\n• Instagram: @bapendasulsel\n• Alamat Kantor: Jl. Jend. Sukowati No. 51, Pinrang, Sulawesi Selatan" : "");

            return $text;
        }

        // Kasus 1: User menyebut wilayah PINRANG
        if ($hasPinrang) {
            // 1. Bersihkan penyebutan sapaan dan percampuran Pangkal Pinang di seluruh teks
            $text = preg_replace('/Halo Warga [^,\n!]+[!,\s]*/i', 'Halo Warga Pinrang, ', $text);
            $text = preg_replace('/(Pinrang\s*[\/\-]\s*Pangkal\s*Pinang|Pangkal\s*Pinang\s*[\/\-]\s*Pinrang|Pangkal\s*Pinang|Pangkalpinang)/i', 'Pinrang', $text);
            $text = preg_replace('/Pengurusan (.+?) di wilayah [^,\n\.]+ adalah GRATIS/i', 'Pengurusan $1 di wilayah Kabupaten Pinrang adalah GRATIS', $text);

            // 2. Ganti seluruh blok kontak daerah lama dengan kontak resmi Disdukcapil / Samsat Pinrang
            if (preg_match('/(Informasi\s*(?:&|dan|Resmi)*\s*Kontak|Untuk informasi|Website:|Call Center|Layanan Pendaftaran)/i', $text)) {
                // Hapus blok penutup lama dari pemicu sampai akhir teks
                $cleanBody = preg_replace('/(Informasi\s*(?:&|dan|Resmi)*\s*Kontak|Untuk informasi dan layanan resmi|Untuk informasi lebih lanjut|Website:\s*\[?[^\]\n]+\]?\(https?:\/\/[^\s\)]+\))[^\0]*/i', '', $text);
                $cleanBody = rtrim($cleanBody);

                // Tambahkan blok resmi Disdukcapil Kabupaten Pinrang (tanpa kontak desa Buttu Sawe, diganti IG Disdukcapil)
                if (preg_match('/(ktp|kk|kartu keluarga|akta|kia|pindah|kependudukan|dukcapil|disdukcapil)/i', $userMsgLower)) {
                    $pinrangContact = "\n\n**Informasi Resmi & Kontak Disdukcapil Kabupaten Pinrang:**\n"
                                    . "• Website: https://disdukcapil.pinrangkab.go.id/\n"
                                    . "• Instagram: @disdukcapil_pinrang\n"
                                    . "• Alamat: Jl. Jend. Sukowati No. 40, Pinrang, Sulawesi Selatan\n"
                                    . "• Layanan Kependudukan (KK, KTP-el, KIA): 0811-419-011 / 0821-8747-6343";
                    $text = $cleanBody . $pinrangContact;
                } elseif (preg_match('/(samsat|pajak|pbb|stnk|bpkb|pkb)/i', $userMsgLower)) {
                    $pinrangContact = "\n\n**Informasi Resmi Samsat & Pajak Daerah Pinrang:**\n"
                                    . "• Website: https://bapenda.sulselprov.go.id/\n"
                                    . "• Instagram: @bapendasulsel\n"
                                    . "• Alamat Samsat: Jl. Jend. Sukowati No. 51, Pinrang, Sulawesi Selatan";
                    $text = $cleanBody . $pinrangContact;
                }
            }

            return $text;
        }

        // Kasus 2: User TIDAK menyebut wilayah sama sekali
        if (!$hasAnyRegion) {
            // Hapus sapaan wilayah pembuka
            $text = preg_replace('/^Halo Warga [^,\n!]+[!,\s]*/i', '', $text);
            
            // Ubah "Pengurusan KTP-el di wilayah X adalah GRATIS" menjadi "Pengurusan KTP-el adalah GRATIS"
            $text = preg_replace('/Pengurusan (.+?) di wilayah [^,\n\.]+ adalah GRATIS/i', 'Pengurusan $1 adalah GRATIS', $text);
            
            // Hapus seluruh blok kontak wilayah yang tidak diminta
            $text = preg_replace('/(Informasi Resmi & Kontak|Untuk informasi dan layanan resmi|Untuk informasi lebih lanjut|Website:\s*\[?[^\]\n]+\]?\(https?:\/\/[^\s\)]+\))[^\0]*/i', '', $text);
            $text = rtrim($text);
        }

        return $text;
    }

    /**
     * Generate rekomendasi pertanyaan kontekstual berdasarkan input user.
     *
     * @param  string  $userMessage
     * @return array
     */
    private function generateSuggestions(string $userMessage): array
    {
        $message = strtolower($userMessage);

        if (preg_match('/\b(ktp|kartu tanda|e-ktp|ktp-el)\b/', $message)) {
            return [
                ['label' => 'Syarat KK', 'message' => 'Bagaimana syarat mengurus Kartu Keluarga?'],
                ['label' => 'Lokasi Samsat', 'message' => 'Di mana lokasi Samsat Pinrang?'],
                ['label' => 'Bayar PBB', 'message' => 'Bagaimana cara membayar PBB di Pinrang?']
            ];
        }

        if (preg_match('/\b(kk|kartu keluarga)\b/', $message)) {
            return [
                ['label' => 'Syarat KTP-el', 'message' => 'Apa syarat mengurus KTP-el di Pinrang?'],
                ['label' => 'Akta Kelahiran', 'message' => 'Bagaimana cara membuat Akta Kelahiran?'],
                ['label' => 'Lokasi Samsat', 'message' => 'Di mana lokasi Samsat Pinrang?']
            ];
        }

        if (preg_match('/\b(pbb|pajak|samsat|stnk|bpkb)\b/', $message)) {
            return [
                ['label' => 'Lokasi Samsat', 'message' => 'Di mana lokasi Samsat Pinrang?'],
                ['label' => 'Bayar PBB', 'message' => 'Bagaimana cara membayar PBB di Pinrang?'],
                ['label' => 'Syarat KTP-el', 'message' => 'Apa syarat mengurus KTP-el di Pinrang?']
            ];
        }

        if (preg_match('/\b(halo|hai|pagi|siang|sore|malam|hello|assalamu)\b/', $message)) {
            return [
                ['label' => 'Syarat KTP-el', 'message' => 'Apa syarat mengurus KTP-el di Pinrang?'],
                ['label' => 'Syarat KK', 'message' => 'Bagaimana syarat mengurus Kartu Keluarga?'],
                ['label' => 'Lokasi Samsat', 'message' => 'Di mana lokasi Samsat Pinrang?']
            ];
        }

        return [
            ['label' => 'Syarat KTP-el', 'message' => 'Apa syarat mengurus KTP-el di Pinrang?'],
            ['label' => 'Syarat KK', 'message' => 'Bagaimana syarat mengurus Kartu Keluarga?'],
            ['label' => 'Bayar PBB', 'message' => 'Bagaimana cara membayar PBB di Pinrang?']
        ];
    }

    /**
     * Deteksi apakah pertanyaan atau balasan AI berada di luar cakupan layanan publik (Out of Scope / OOS).
     *
     * @param  string  $userMessage
     * @param  string  $answer
     * @param  array   $data
     * @return bool
     */
    private function isOutOfScope(string $userMessage, string $answer, array $data): bool
    {
        $intent = '';
        if (isset($data['intent'])) {
            if (is_array($data['intent'])) {
                $intent = strtolower((string)($data['intent']['label'] ?? $data['intent']['name'] ?? ''));
            } else {
                $intent = strtolower((string)$data['intent']);
            }
        }

        $status = '';
        if (isset($data['status'])) {
            if (is_array($data['status'])) {
                $status = strtolower((string)($data['status']['label'] ?? $data['status']['name'] ?? ''));
            } else {
                $status = strtolower((string)$data['status']);
            }
        }

        // 1. Intent / Status eksplisit OOS dari engine AI
        if (in_array($intent, ['out_of_scope', 'oos', 'chitchat', 'unknown', 'greeting_irrelevant'])) {
            return true;
        }

        if (in_array($status, ['out_of_scope', 'oos', 'unmatched'])) {
            return true;
        }

        $userMsgLower = strtolower($userMessage);

        // 1.5 Pengecualian Khusus: Jika pertanyaan mengenai KIP Kuliah / Beasiswa / Bantuan Pendidikan Pemerintah
        $isGovAssistanceOrEdu = preg_match('/\b(kip|kip-k|kip kuliah|pip|beasiswa|kartu indonesia pintar|bantuan pendidikan|sktm|bansos|blt|pkh)\b/i', $userMsgLower);

        // 2. Deteksi kata kunci NON-Layanan Publik (Member/Retail Swasta, Gadget, Selebriti, Hiburan, dll)
        $nonPublicPatterns = [
            '/\b(member|membership|kartu member|poin member)\b/i',
            '/\b(indomaret|alfamart|alfamidi|superindo|hypermart|transmart|indogrosir|minimarket|supermarket|swalayan)\b/i',
            '/\b(tokopedia|shopee|lazada|tiktok shop|blibli|bukalapak|zalora)\b/i',
            '/\b(gojek|grab|maxim|ojol|gofood|grabfood|shopeefood)\b/i',
            '/\b(netflix|spotify|canva|youtube premium|disney\+?|hotstar)\b/i',
            '/\b(pinjol|pinjaman online|paylater|kartu kredit)\b/i',
            '/\b(telkomsel|indosat|xl axiata|smartfren|kartu tri|kartu perdana|sim card|unreg kartu)\b/i',
            '/\b(bioskop|cinema 21|cinema xxi|cgv|cinepolis|gym|fitness)\b/i',
            '/\b(hotel|traveloka|tiket\.com|tiket pesawat|kereta api|kai access)\b/i',
            '/\b(laptop|handphone|hp|smartphone|iphone|android|komputer|ipad|tablet|lacak|melacak|find my)\b/i',
            '/\b(sehun|exo|blackpink|bts|nct|twice|jkt48|treasure|seventeen|aespa|ive|newjeans|le sserafim|stray kids|boyband|girlband|grup musik|band|kpop|idol|weverse|lysn|fandom|sm entertainment|yg entertainment|jyp|hybe)\b/i',
            '/\b(istri|suami|pacar|jodoh|cinta|selingkuh|pacaran|santet|pelet|zodiak|horoskop|ramalan)\b/i',
            '/\b(cantik|ganteng|tampan|kaya|sukses|pintar|langsing|diet)\b/i',
            '/\b(resep|cara memasak|bumbu dapur|nasi goreng|masakan|kue|minuman)\b/i',
            '/\b(game|mobile legends|mlbb|free fire|pubg|genshin|valorant|steam|cheat|diamond)\b/i',
            '/\b(anime|manga|one piece|naruto|boruto|jujutsu|dragon ball)\b/i',
            '/\b(chord|lirik lagu|chord gitar|kunci gitar)\b/i',
            '/\b(sepak bola|liga champion|skor pertandingan|persib|persija|real madrid|barcelona|manchester)\b/i',
        ];

        // Jika BUKAN tentang KIP / Beasiswa, maka blokir pertanyaan akademik murni (jadwal kuliah, skripsi, dll)
        if (!$isGovAssistanceOrEdu) {
            $nonPublicPatterns[] = '/\b(ukt|spp kuliah|krs|skripsi|tesis|portal akademik)\b/i';
        }

        foreach ($nonPublicPatterns as $pattern) {
            if (preg_match($pattern, $userMsgLower)) {
                return true;
            }
        }

        // 3. Deteksi frasa OOS / Disclaimer / Jawaban di luar dataset dari balasan AI
        $answerLower = strtolower($answer);
        $answerOOSRegexes = [
            '/indomaret|alfamart|alfamidi|minimarket|supermarket|gerai retail/i',
            '/pendaftaran member|pembuatan member|kartu member/i',
            '/verifikasi melalui sms|nomor telepon \(ponsel\)/i',
            '/layanan pelanggan atau petugas di gerai/i',
            '/tidak tersedia (di|dalam) database/i',
            '/informasi tersebut tidak tersedia/i',
            '/tidak termasuk (di|dalam) layanan/i',
            '/tidak memiliki (dasar|regulasi|informasi|data)/i',
            '/tidak diatur oleh/i',
            '/tidak dapat (melacak|mencari|menemukan|membantu)/i',
            '/bukan (merupakan )?(layanan|kewenangan|wewenang|tugas|urusan)/i',
            '/di luar (cakupan|layanan|konteks)/i',
            '/portal akademik/i',
            '/uang kuliah tunggal/i',
            '/pembayaran ukt/i',
            '/find my (device)?/i',
            '/akun google\/microsoft/i',
            '/permohonan tersebut tidak/i',
            '/pertanyaan mengenai .+ tidak/i',
            '/sebagai asisten layanan publik sindesa, saya siap membantu anda dengan informasi terkait layanan kependudukan/i',
            '/fandom|entertainment|fanclub|grup musik|weverse|lysn/i',
        ];

        foreach ($answerOOSRegexes as $regex) {
            if (preg_match($regex, $answerLower)) {
                return true;
            }
        }

        // 4. Validasi Whitelist: Jika pertanyaan sama sekali tidak mengandung topik layanan publik resmi,
        // dan balasan AI mengandung permohonan maaf / ketidaksediaan data.
        $hasPublicTopic = preg_match('/\b(ktp|ktp-el|e-ktp|kk|kartu keluarga|akta|akte|kelahiran|kematian|nikah|cerai|kia|anak|nik|pindah|datang|domisili|kependudukan|dukcapil|disdukcapil|pbb|pajak bumi|sppt|nop|samsat|pkb|pajak kendaraan|stnk|bpkb|plat|balik nama|bapenda|desa|buttu sawe|kantor desa|kepala desa|duampanua|bungi|surat pengantar|surat keterangan|sku|sktm|usaha|tidak mampu|blt|bansos|bantuan sosial|pkh|kip|kip-k|kip kuliah|pip|beasiswa|pendidikan|izin usaha|perizinan|nib|oss|imb|pbg|siup|kartu kuning|ak-1|ak1|disnaker|bpjs|kis|layanan|administrasi|syarat|prosedur|jadwal|biaya|tarif|lokasi|bapenda|kantor)\b/i', $userMsgLower);

        if (!$hasPublicTopic && preg_match('/(mohon maaf|tidak dapat|tidak memiliki|tidak tersedia)/i', $answerLower)) {
            return true;
        }

        return false;
    }
}
