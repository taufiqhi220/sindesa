<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;
use App\Models\PengajuanSurat;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    /**
     * Helper: Simpan file dengan nama acak berbasis UUIDv4
     */
    private function storeFileUuid($file, string $folder = 'pengajuan'): string
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        return $file->storeAs($folder, $filename, 'public');
    }

    //View Dashboard
    public function index()
    {
        $userId = Auth::id();

        // 1. Menghitung SEMUA surat (Total)
        $totalPengajuan = PengajuanSurat::where('user_id', $userId)->count();
        
        // 2. Menghitung HANYA yang menunggu TTD Kades
        $diproses = PengajuanSurat::where('user_id', $userId)
            ->where('status', 'diproses_kades')
            ->count();
            
        // 3. Menghitung yang selesai
        $selesai = PengajuanSurat::where('user_id', $userId)
            ->where('status', 'selesai')
            ->count();

        // 4. Ambil 5 pengajuan terbaru
        $pengajuanTerbaru = PengajuanSurat::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('warga.dashboard', compact(
            'totalPengajuan', 
            'diproses', 
            'selesai', 
            'pengajuanTerbaru'
        ));
    }
    //View Riwayat
    public function riwayat()
    {
        // Ambil data pengajuan milik warga yang login, urutkan dari yang terbaru
        $pengajuans = PengajuanSurat::where('user_id', Auth::id())
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('warga.riwayat', compact('pengajuans'));
    }
    
    
    public function destroy($id)
    {
        // Cari surat berdasarkan ID dan pastikan itu milik user yang sedang login
        $pengajuan = PengajuanSurat::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // Keamanan: Hanya bisa dihapus jika belum diproses operator
        if ($pengajuan->status == 'menunggu_verifikasi') {
            $pengajuan->delete();
            return back()->with('success', 'Pengajuan surat berhasil dibatalkan.');
        }

        return back()->withErrors(['Gagal membatalkan. Surat sudah mulai diproses oleh petugas.']);
    }


    //View Terverifikasi
    public function verifikasi(Request $request)
    {
        // Tarik surat yang sedang menunggu admin ATAU sedang diproses kades
        $pengajuanVerifikasi = PengajuanSurat::where('user_id', Auth::id())
            ->whereIn('status', ['menunggu_verifikasi', 'diproses_kades'])
            ->orderBy('updated_at', 'desc')
            ->get(); // Gunakan get() karena kita pakai JS Pagination

        return view('warga.verifikasi', compact('pengajuanVerifikasi'));
    }

    //View Selesai
    // View Selesai
    public function selesai(Request $request)
    {
        // Cukup ambil semua data surat selesai milik user, urutkan dari terbaru.
        // Search, Sort, & Pagination 100% sudah di-handle oleh JavaScript di View!
        $pengajuanSelesai = PengajuanSurat::where('user_id', Auth::id())
            ->where('status', 'selesai')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('warga.selesai', compact('pengajuanSelesai'));
    }

    //Tampilkan Profil
    public function profil()
    {
        $user = Auth::user();
        $provinces = Province::all();
        
        $cities = $user->provinsi ? City::where('province_code', $user->provinsi)->get() : [];
        $districts = $user->kota ? District::where('city_code', $user->kota)->get() : [];
        $villages = $user->kecamatan ? Village::where('district_code', $user->kecamatan)->get() : [];

        return view('warga.profil', compact('user', 'provinces', 'cities', 'districts', 'villages'));
    }

    //Update Profil
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'              => 'required|string|max:255',
            'nik'               => 'required|string|size:16|unique:users,nik,'.$user->id,
            'no_kk'             => 'required|string|size:16',
            'tempat_lahir'      => 'required|string|max:255',
            'tanggal_lahir'     => 'required|date',
            'jenis_kelamin'     => 'required|in:Laki-Laki,Perempuan',
            'agama'             => 'required|string',
            'alamat_lengkap'    => 'required|string',
            'rt_rw'             => 'required|string',
            'status_perkawinan' => 'required|string',
            'provinsi'          => 'required',
            'kota'              => 'required',
            'kecamatan'         => 'required',
            'kelurahan_desa'    => 'required',
            'pekerjaan'         => 'required|string',
            'kewarganegaraan'   => 'required|in:WNI,WNA',
            'phone'             => 'required|string|max:20',
            'email'             => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'password'          => 'nullable|string|min:8|confirmed',
        ]);

        // 2. WHITELIST: Hanya ambil field yang DIIZINKAN (mencegah mass assignment role/status)
        $data = $request->only([
            'name', 'nik', 'no_kk', 'tempat_lahir', 'tanggal_lahir',
            'jenis_kelamin', 'agama', 'alamat_lengkap', 'rt_rw',
            'status_perkawinan', 'provinsi', 'kota', 'kecamatan',
            'kelurahan_desa', 'pekerjaan', 'kewarganegaraan', 'phone', 'email',
        ]);

        // 3. Proses File Foto jika ada yang diupload
        if ($request->hasFile('foto_profil')) {
            // Hapus foto lama jika sebelumnya sudah punya foto
            if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
                Storage::disk('public')->delete($user->foto_profil);
            }
            
            // Simpan foto baru ke folder storage/app/public/profil dengan UUIDv4
            $data['foto_profil'] = $this->storeFileUuid($request->file('foto_profil'), 'profil');
        }

        // 4. Proses Password jika form password diisi
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'min:8|confirmed'
            ]);
            $data['password'] = Hash::make($request->password);
        }

        // 5. Simpan ke database
        User::where('id', $user->id)->update($data);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    //View Form Akta Lahir
    public function formAktaLahir()
    {
        return view('warga.form.akta-lahir');
    }

    public function storeAktaLahir(Request $request)
    {
        $request->validate([
            'nama_anak' => 'required|string|max:255',
            'tempat_lahir_anak' => 'required|string|max:255',
            'tanggal_lahir_anak' => 'required|date',
            'jenis_kelamin_anak' => 'required|string',
            'agama_anak' => 'required|string',
            'kewarganegaraan_anak' => 'required|string',
            'anak_ke' => 'required|numeric',
            'alamat_anak' => 'required|string',
            'nama_ayah' => 'required|string|max:255',
            'nik_ayah' => 'nullable|numeric',
            'nama_ibu' => 'required|string|max:255',
            'nik_ibu' => 'nullable|numeric',
            'file_kk' => 'required|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_saksi' => 'required|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_lain' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
        ]);

        // Upload File ke folder public/pengajuan dengan nama UUIDv4
        $pathKk = $this->storeFileUuid($request->file('file_kk'), 'pengajuan');
        $pathSaksi = $this->storeFileUuid($request->file('file_saksi'), 'pengajuan');
        $pathLain = $request->hasFile('file_lain') ? $this->storeFileUuid($request->file('file_lain'), 'pengajuan') : null;

        // Gabungkan semua input menjadi JSON
        $dataTambahan = [
            'nama_anak' => $request->nama_anak,
            'tempat_lahir_anak' => $request->tempat_lahir_anak,
            'tanggal_lahir_anak' => $request->tanggal_lahir_anak,
            'jenis_kelamin_anak' => $request->jenis_kelamin_anak,
            'agama_anak' => $request->agama_anak,
            'kewarganegaraan_anak' => $request->kewarganegaraan_anak,
            'anak_ke' => $request->anak_ke,
            'alamat_anak' => $request->alamat_anak,
            'nama_ayah' => $request->nama_ayah,
            'nik_ayah' => $request->nik_ayah,
            'nama_ibu' => $request->nama_ibu,
            'nik_ibu' => $request->nik_ibu,
            'file_kk' => $pathKk,
            'file_saksi' => $pathSaksi,
            'file_lain' => $pathLain,
        ];

        // Simpan ke database
        PengajuanSurat::create([
            'user_id' => Auth::id(),
            'jenis_surat' => 'pengantar_akta_lahir',
            'keperluan' => 'Pembuatan Akta Kelahiran',
            'data_tambahan' => $dataTambahan,
            'status' => 'menunggu_verifikasi',
        ]);

        return redirect()->route('warga.riwayat')->with('success', 'Pengajuan Akta Lahir berhasil dikirim!');
    }

    public function editAktaLahir($id)
    {
        $surat = PengajuanSurat::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // Proteksi: Hanya bisa edit jika status belum diproses lebih jauh
        if (!in_array($surat->status, ['menunggu_verifikasi', 'ditolak'])) {
            return back()->withErrors('Surat sudah diproses dan tidak dapat diubah.');
        }

        return view('warga.form-edit.akta-lahir', compact('surat'));
    }

    public function updateAktaLahir(Request $request, $id)
    {
        $surat = PengajuanSurat::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'nama_anak' => 'required|string|max:255',
            'tempat_lahir_anak' => 'required',
            'tanggal_lahir_anak' => 'required|date',
            'file_kk' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_saksi' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
        ]);

        $data = $surat->data_tambahan;

        // Update data teks
        foreach ($request->except(['_token', '_method', 'file_kk', 'file_saksi', 'file_lain']) as $key => $value) {
            $data[$key] = $value;
        }

        // Update file dan Hapus file lama jika ada upload baru dengan UUIDv4
        if ($request->hasFile('file_kk')) {
            if (isset($data['file_kk'])) {
                Storage::disk('public')->delete($data['file_kk']);
            }
            $data['file_kk'] = $this->storeFileUuid($request->file('file_kk'), 'pengajuan');
        }
        
        if ($request->hasFile('file_saksi')) {
            if (isset($data['file_saksi'])) {
                Storage::disk('public')->delete($data['file_saksi']);
            }
            $data['file_saksi'] = $this->storeFileUuid($request->file('file_saksi'), 'pengajuan');
        }

        $surat->update([
            'data_tambahan' => $data,
            'status' => 'menunggu_verifikasi', // Reset status agar diperiksa ulang
        ]);

        return redirect()->route('warga.riwayat')->with('success', 'Perubahan berhasil disimpan.');
    }

    //View Form KTP
    public function formKtp()
    {
        return view('warga.form.ktp');
    }

    //Store Form KTP
    public function storeKtp(Request $request)
    {
        $request->validate([
            'nik' => 'required|numeric',
            'no_kk' => 'required|numeric',
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|string',
            'agama' => 'required|string',
            'status_perkawinan' => 'required|string',
            'pekerjaan' => 'required|string|max:255',
            'alamat' => 'required|string',
            
            'file_kk' => 'required|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_ktp_lama' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
        ]);

        $dataTambahan = [
            'nik' => $request->nik,
            'no_kk' => $request->no_kk,
            'nama' => $request->nama,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'agama' => $request->agama,
            'status_perkawinan' => $request->status_perkawinan,
            'pekerjaan' => $request->pekerjaan,
            'alamat' => $request->alamat,
            
            'file_kk' => $this->storeFileUuid($request->file('file_kk'), 'pengajuan'),
            'file_ktp_lama' => $request->hasFile('file_ktp_lama') ? $this->storeFileUuid($request->file('file_ktp_lama'), 'pengajuan') : null,
        ];

        PengajuanSurat::create([
            'user_id' => Auth::id(),
            'jenis_surat' => 'pengantar_ktp',
            'keperluan' => 'Pembuatan / Pembaharuan KTP',
            'data_tambahan' => $dataTambahan,
            'status' => 'menunggu_verifikasi',
        ]);

        return redirect()->route('warga.riwayat')->with('success', 'Pengajuan Pengantar KTP berhasil dikirim!');
    }

    //View Edit Form KTP
    public function editKtp($id)
    {
        $surat = PengajuanSurat::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // Pastikan jenis surat di database Anda adalah pengantar_ktp atau ktp
        if (!in_array($surat->jenis_surat, ['pengantar_ktp', 'ktp'])) abort(404);
        
        if (!in_array($surat->status, ['menunggu_verifikasi', 'ditolak'])) {
            return redirect()->route('warga.riwayat')->withErrors('Pengajuan ini tidak dapat direvisi.');
        }

        return view('warga.form-edit.ktp', compact('surat'));
    }

    //Update Form KTP
    public function updateKtp(Request $request, $id)
    {
        $surat = PengajuanSurat::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'nik' => 'required|numeric',
            'no_kk' => 'required|numeric',
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|string',
            'agama' => 'required|string',
            'status_perkawinan' => 'required|string',
            'pekerjaan' => 'required|string|max:255',
            'alamat' => 'required|string',
            
            'file_kk' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_ktp_lama' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'consent' => 'accepted',
        ]);

        $data = $surat->data_tambahan;

        // Loop Text Inputs
        $exceptKeys = ['_token', '_method', 'consent', 'file_kk', 'file_ktp_lama'];
        foreach ($request->except($exceptKeys) as $key => $value) {
            $data[$key] = $value;
        }

        // Loop File Inputs (Hapus yang lama, simpan yang baru dengan UUIDv4)
        if ($request->hasFile('file_kk')) {
            if (isset($data['file_kk'])) Storage::disk('public')->delete($data['file_kk']);
            $data['file_kk'] = $this->storeFileUuid($request->file('file_kk'), 'pengajuan');
        }

        if ($request->hasFile('file_ktp_lama')) {
            if (isset($data['file_ktp_lama'])) Storage::disk('public')->delete($data['file_ktp_lama']);
            $data['file_ktp_lama'] = $this->storeFileUuid($request->file('file_ktp_lama'), 'pengajuan');
        }

        $surat->update([
            'data_tambahan' => $data,
            'status' => 'menunggu_verifikasi',
        ]);

        return redirect()->route('warga.riwayat')->with('success', 'Pengajuan Pengantar KTP berhasil diperbarui.');
    }

    //View Form KK
    public function formKk()
    {
        return view('warga.form.kk');
    }

    //Store Form KK
    public function storeKk(Request $request)
    {
        $request->validate([
            'tujuan_pengajuan' => 'required|string',
            'nik' => 'required|numeric',
            'kk_lama' => 'nullable|numeric',
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|string',
            'agama' => 'required|string',
            'status_perkawinan' => 'required|string',
            'pekerjaan' => 'required|string|max:255',
            'nama_kepala_keluarga' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'rt' => 'required|numeric',
            'rw' => 'required|numeric',
            
            'file_kk_lama' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_nikah' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_lain' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
        ]);

        $dataTambahan = [
            'tujuan_pengajuan' => $request->tujuan_pengajuan,
            'nik' => $request->nik,
            'kk_lama' => $request->kk_lama,
            'nama_lengkap' => $request->nama_lengkap,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'agama' => $request->agama,
            'status_perkawinan' => $request->status_perkawinan,
            'pekerjaan' => $request->pekerjaan,
            'nama_kepala_keluarga' => $request->nama_kepala_keluarga,
            'alamat' => $request->alamat,
            'rt' => $request->rt,
            'rw' => $request->rw,
            
            'file_kk_lama' => $request->hasFile('file_kk_lama') ? $this->storeFileUuid($request->file('file_kk_lama'), 'pengajuan') : null,
            'file_nikah' => $request->hasFile('file_nikah') ? $this->storeFileUuid($request->file('file_nikah'), 'pengajuan') : null,
            'file_lain' => $request->hasFile('file_lain') ? $this->storeFileUuid($request->file('file_lain'), 'pengajuan') : null,
        ];

        PengajuanSurat::create([
            'user_id' => Auth::id(),
            'jenis_surat' => 'pengantar_kk',
            'keperluan' => 'Pengantar KK (' . $request->tujuan_pengajuan . ')',
            'data_tambahan' => $dataTambahan,
            'status' => 'menunggu_verifikasi',
        ]);

        return redirect()->route('warga.riwayat')->with('success', 'Pengajuan Pengantar KK berhasil dikirim!');
    }

    //View Edit Form KK
    public function editKk($id)
    {
        $surat = PengajuanSurat::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // Pastikan jenis surat benar
        if (!in_array($surat->jenis_surat, ['pengantar_kk', 'pengantar_kartu_keluarga', 'kk'])) abort(404);
        
        if (!in_array($surat->status, ['menunggu_verifikasi', 'ditolak'])) {
            return redirect()->route('warga.riwayat')->withErrors('Pengajuan ini tidak dapat direvisi.');
        }

        return view('warga.form-edit.kk', compact('surat'));
    }

    //Update Form KK
    public function updateKk(Request $request, $id)
    {
        $surat = PengajuanSurat::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'tujuan_pengajuan' => 'required|string',
            'nik' => 'required|numeric',
            'kk_lama' => 'nullable|numeric',
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|string',
            'agama' => 'required|string',
            'status_perkawinan' => 'required|string',
            'pekerjaan' => 'required|string|max:255',
            'nama_kepala_keluarga' => 'required|string|max:255',
            'alamat' => 'required|string',
            'rt' => 'required|numeric',
            'rw' => 'required|numeric',
            
            'file_kk_lama' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_nikah' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_lain' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'consent' => 'accepted',
        ]);

        $data = $surat->data_tambahan;

        // Loop Text Inputs
        $exceptKeys = ['_token', '_method', 'consent', 'file_kk_lama', 'file_nikah', 'file_lain'];
        foreach ($request->except($exceptKeys) as $key => $value) {
            $data[$key] = $value;
        }

        // Loop File Inputs (Hapus yang lama, simpan yang baru dengan UUIDv4)
        $fileFields = ['file_kk_lama', 'file_nikah', 'file_lain'];
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                if (isset($data[$field])) {
                    Storage::disk('public')->delete($data[$field]);
                }
                $data[$field] = $this->storeFileUuid($request->file($field), 'pengajuan');
            }
        }

        $surat->update([
            'data_tambahan' => $data,
            'status' => 'menunggu_verifikasi',
        ]);

        return redirect()->route('warga.riwayat')->with('success', 'Pengajuan Pengantar KK berhasil diperbarui.');
    }

    //View Form Kematian
    public function formKematian()
    {
        return view('warga.form.kematian');
    }

    //Store Form Kematian
    public function storeKematian(Request $request)
    {
        $request->validate([
            'nik_almarhum' => 'required|numeric',
            'kk_almarhum' => 'required|numeric',
            'nama_almarhum' => 'required|string|max:255',
            'tempat_lahir_almarhum' => 'required|string|max:255',
            'tanggal_lahir_almarhum' => 'required|date',
            'jenis_kelamin_almarhum' => 'required|string',
            'agama_almarhum' => 'required|string',
            'kewarganegaraan_almarhum' => 'required|string',
            'status_perkawinan_almarhum' => 'required|string',
            'pekerjaan_almarhum' => 'required|string|max:255',
            'alamat_almarhum' => 'required|string',

            'tanggal_kematian' => 'required|date',
            'umur_kematian' => 'required|numeric',
            'tempat_kematian' => 'required|string|max:255',
            'sebab_kematian' => 'required|string|max:255',

            'nama_pelapor' => 'required|string|max:255',
            'hubungan_pelapor' => 'required|string|max:255',

            'file_ktp_almarhum' => 'required|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_kk_almarhum' => 'required|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_ktp_pelapor' => 'required|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_rs' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
        ]);

        $dataTambahan = [
            'nik_almarhum' => $request->nik_almarhum,
            'kk_almarhum' => $request->kk_almarhum,
            'nama_almarhum' => $request->nama_almarhum,
            'tempat_lahir_almarhum' => $request->tempat_lahir_almarhum,
            'tanggal_lahir_almarhum' => $request->tanggal_lahir_almarhum,
            'jenis_kelamin_almarhum' => $request->jenis_kelamin_almarhum,
            'agama_almarhum' => $request->agama_almarhum,
            'kewarganegaraan_almarhum' => $request->kewarganegaraan_almarhum,
            'status_perkawinan_almarhum' => $request->status_perkawinan_almarhum,
            'pekerjaan_almarhum' => $request->pekerjaan_almarhum,
            'alamat_almarhum' => $request->alamat_almarhum,

            'tanggal_kematian' => $request->tanggal_kematian,
            'umur_kematian' => $request->umur_kematian,
            'tempat_kematian' => $request->tempat_kematian,
            'sebab_kematian' => $request->sebab_kematian,

            'nama_pelapor' => $request->nama_pelapor,
            'hubungan_pelapor' => $request->hubungan_pelapor,

            'file_ktp_almarhum' => $this->storeFileUuid($request->file('file_ktp_almarhum'), 'pengajuan'),
            'file_kk_almarhum' => $this->storeFileUuid($request->file('file_kk_almarhum'), 'pengajuan'),
            'file_ktp_pelapor' => $this->storeFileUuid($request->file('file_ktp_pelapor'), 'pengajuan'),
            'file_rs' => $request->hasFile('file_rs') ? $this->storeFileUuid($request->file('file_rs'), 'pengajuan') : null,
        ];

        PengajuanSurat::create([
            'user_id' => Auth::id(),
            'jenis_surat' => 'keterangan_kematian',
            'keperluan' => 'Surat Keterangan Kematian (Alm. ' . $request->nama_almarhum . ')',
            'data_tambahan' => $dataTambahan,
            'status' => 'menunggu_verifikasi',
        ]);

        return redirect()->route('warga.riwayat')->with('success', 'Pengajuan Keterangan Kematian berhasil dikirim!');
    }

    //View Edit Form Kematian
    public function editKematian($id)
    {
        $surat = PengajuanSurat::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // Pastikan enum database Anda adalah keterangan_kematian atau kematian
        if (!in_array($surat->jenis_surat, ['keterangan_kematian', 'kematian'])) abort(404);
        
        if (!in_array($surat->status, ['menunggu_verifikasi', 'ditolak'])) {
            return redirect()->route('warga.riwayat')->withErrors('Pengajuan ini tidak dapat direvisi.');
        }

        return view('warga.form-edit.kematian', compact('surat'));
    }

    //Update Form Kematian
    public function updateKematian(Request $request, $id)
    {
        $surat = PengajuanSurat::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'nik_almarhum' => 'required|numeric',
            'kk_almarhum' => 'required|numeric',
            'nama_almarhum' => 'required|string|max:255',
            'tempat_lahir_almarhum' => 'required|string|max:255',
            'tanggal_lahir_almarhum' => 'required|date',
            'jenis_kelamin_almarhum' => 'required|string',
            'agama_almarhum' => 'required|string',
            'kewarganegaraan_almarhum' => 'required|string|max:255',
            'status_perkawinan_almarhum' => 'required|string',
            'pekerjaan_almarhum' => 'required|string|max:255',
            'alamat_almarhum' => 'required|string',
            
            'tanggal_kematian' => 'required|date',
            'umur_kematian' => 'required|numeric',
            'tempat_kematian' => 'required|string|max:255',
            'sebab_kematian' => 'required|string|max:255',
            
            'nama_pelapor' => 'required|string|max:255',
            'hubungan_pelapor' => 'required|string|max:255',

            'file_ktp_almarhum' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_kk_almarhum' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_ktp_pelapor' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_rs' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'consent' => 'accepted',
        ]);

        $data = $surat->data_tambahan;

        // Loop Update data Text (Abaikan Token & Files)
        $exceptKeys = [
            '_token', '_method', 'consent', 
            'file_ktp_almarhum', 'file_kk_almarhum', 'file_ktp_pelapor', 'file_rs'
        ];
        
        foreach ($request->except($exceptKeys) as $key => $value) {
            $data[$key] = $value;
        }

        // Loop Update & Hapus Files (dengan UUIDv4)
        $fileFields = ['file_ktp_almarhum', 'file_kk_almarhum', 'file_ktp_pelapor', 'file_rs'];
        
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                if (isset($data[$field])) {
                    Storage::disk('public')->delete($data[$field]);
                }
                $data[$field] = $this->storeFileUuid($request->file($field), 'pengajuan');
            }
        }

        $surat->update([
            'data_tambahan' => $data,
            'status' => 'menunggu_verifikasi',
        ]);

        return redirect()->route('warga.riwayat')->with('success', 'Pengajuan Keterangan Kematian berhasil diperbarui.');
    }

    //View Form Pindah
    public function formPindah()
    {
        return view('warga.form.pindah');
    }

    //Store Form Pindah
    public function storePindah(Request $request)
    {
        $request->validate([
            'nik' => 'required|numeric',
            'no_kk' => 'required|numeric',
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|string',
            'agama' => 'required|string',

            'alamat_asal_dusun' => 'required|string|max:255',
            'alamat_asal_rt' => 'required|numeric',
            'alamat_asal_rw' => 'required|numeric',

            'alamat_tujuan_jalan' => 'required|string|max:255',
            'alamat_tujuan_rt' => 'required|numeric',
            'alamat_tujuan_rw' => 'required|numeric',
            'alamat_tujuan_desa' => 'required|string|max:255',
            'alamat_tujuan_kecamatan' => 'required|string|max:255',
            'alamat_tujuan_kabupaten' => 'required|string|max:255',
            'alamat_tujuan_provinsi' => 'required|string|max:255',
            'alamat_tujuan_kodepos' => 'nullable|numeric',

            'alasan_pindah' => 'required|string|max:255',
            'tanggal_pindah' => 'required|date',
            'anggota_keluarga' => 'nullable|string',

            'file_ktp' => 'required|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_kk' => 'required|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_lain' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'consent' => 'accepted',
        ]);

        $anggotaKeluarga = [];
        if ($request->has('pengikut_nama')) {
            $namas = $request->input('pengikut_nama');
            $niks = $request->input('pengikut_nik');
            $jks = $request->input('pengikut_jk');
            $tgls = $request->input('pengikut_tgl_lahir');
            $status = $request->input('pengikut_status');
            $kets = $request->input('pengikut_ket');

            for ($i = 0; $i < count($namas); $i++) {
                if (!empty($namas[$i])) {
                    $anggotaKeluarga[] = [
                        'nama' => $namas[$i],
                        'nik' => $niks[$i],
                        'jenis_kelamin' => $jks[$i],
                        'tanggal_lahir' => $tgls[$i],
                        'status_perkawinan' => $status[$i],
                        'keterangan' => $kets[$i],
                    ];
                }
            }
        }

        $dataTambahan = [
            'nik' => $request->nik,
            'no_kk' => $request->no_kk,
            'nama' => $request->nama,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'agama' => $request->agama,

            'alamat_asal' => [
                'dusun' => $request->alamat_asal_dusun,
                'rt' => $request->alamat_asal_rt,
                'rw' => $request->alamat_asal_rw,
            ],

            'alamat_tujuan' => [
                'jalan' => $request->alamat_tujuan_jalan,
                'rt' => $request->alamat_tujuan_rt,
                'rw' => $request->alamat_tujuan_rw,
                'desa' => $request->alamat_tujuan_desa,
                'kecamatan' => $request->alamat_tujuan_kecamatan,
                'kabupaten' => $request->alamat_tujuan_kabupaten,
                'provinsi' => $request->alamat_tujuan_provinsi,
                'kode_pos' => $request->alamat_tujuan_kodepos,
            ],

            'alasan_pindah' => $request->alasan_pindah,
            'tanggal_pindah' => $request->tanggal_pindah,
            'anggota_keluarga' => $anggotaKeluarga,

            'file_ktp' => $this->storeFileUuid($request->file('file_ktp'), 'pengajuan'),
            'file_kk' => $this->storeFileUuid($request->file('file_kk'), 'pengajuan'),
            'file_lain' => $request->hasFile('file_lain') ? $this->storeFileUuid($request->file('file_lain'), 'pengajuan') : null,
        ];

        PengajuanSurat::create([
            'user_id' => Auth::id(),
            'jenis_surat' => 'keterangan_pindah',
            'keperluan' => 'Pindah ke ' . $request->alamat_tujuan_kabupaten,
            'data_tambahan' => $dataTambahan,
            'status' => 'menunggu_verifikasi',
        ]);

        return redirect()->route('warga.riwayat')->with('success', 'Pengajuan Keterangan Pindah berhasil dikirim!');
    }

    //View Edit Form Pindah
    public function editPindah($id)
    {
        $surat = PengajuanSurat::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // Pastikan jenis surat di database Anda adalah keterangan_pindah atau pindah
        if (!in_array($surat->jenis_surat, ['keterangan_pindah', 'pindah'])) abort(404);
        
        if (!in_array($surat->status, ['menunggu_verifikasi', 'ditolak'])) {
            return redirect()->route('warga.riwayat')->withErrors('Pengajuan ini tidak dapat direvisi.');
        }

        return view('warga.form-edit.pindah', compact('surat'));
    }

    //Update Form Pindah
    public function updatePindah(Request $request, $id)
    {
        $surat = PengajuanSurat::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'nik' => 'required|numeric',
            'no_kk' => 'required|numeric',
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|string',
            'agama' => 'required|string',
            'status_perkawinan' => 'required|string',
            'pekerjaan' => 'required|string|max:255',
            
            'alamat_asal_dusun' => 'required|string|max:255',
            'alamat_asal_rt' => 'required|numeric',
            'alamat_asal_rw' => 'required|numeric',
            
            'alamat_tujuan_jalan' => 'required|string|max:255',
            'alamat_tujuan_rt' => 'required|numeric',
            'alamat_tujuan_rw' => 'required|numeric',
            'alamat_tujuan_desa' => 'required|string|max:255',
            'alamat_tujuan_kecamatan' => 'required|string|max:255',
            'alamat_tujuan_kabupaten' => 'required|string|max:255',
            'alamat_tujuan_provinsi' => 'required|string|max:255',
            'alamat_tujuan_kodepos' => 'nullable|numeric',
            
            'alasan_pindah' => 'required|string|max:255',
            'tanggal_pindah' => 'required|date',
            'anggota_keluarga' => 'nullable|string',
            
            'file_ktp' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_kk' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_lain' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'consent' => 'accepted',
        ]);

        $data = $surat->data_tambahan;

        // KECUALIKAN semua input alamat dan pengikut karena akan kita susun ulang jadi Array
        $exceptKeys = [
            '_token', '_method', 'consent', 'file_ktp', 'file_kk', 'file_lain',
            'alamat_asal_dusun', 'alamat_asal_rt', 'alamat_asal_rw',
            'alamat_tujuan_jalan', 'alamat_tujuan_rt', 'alamat_tujuan_rw', 'alamat_tujuan_desa',
            'alamat_tujuan_kecamatan', 'alamat_tujuan_kabupaten', 'alamat_tujuan_provinsi', 'alamat_tujuan_kodepos',
            'pengikut_nama', 'pengikut_nik', 'pengikut_jk', 'pengikut_tgl_lahir', 'pengikut_status', 'pengikut_ket'
        ];

        // 1. Loop input teks biasa
        foreach ($request->except($exceptKeys) as $key => $value) {
            $data[$key] = $value;
        }

        // 2. Susun ulang Alamat Asal ke dalam Array (Sama seperti fungsi store)
        $data['alamat_asal'] = [
            'dusun' => $request->alamat_asal_dusun,
            'rt' => $request->alamat_asal_rt,
            'rw' => $request->alamat_asal_rw,
        ];

        // 3. Susun ulang Alamat Tujuan
        $data['alamat_tujuan'] = [
            'jalan' => $request->alamat_tujuan_jalan,
            'rt' => $request->alamat_tujuan_rt,
            'rw' => $request->alamat_tujuan_rw,
            'desa' => $request->alamat_tujuan_desa,
            'kecamatan' => $request->alamat_tujuan_kecamatan,
            'kabupaten' => $request->alamat_tujuan_kabupaten,
            'provinsi' => $request->alamat_tujuan_provinsi,
            'kode_pos' => $request->alamat_tujuan_kodepos,
        ];
        
        // 4. Susun ulang Anggota Keluarga
        $anggotaKeluarga = [];
        if ($request->has('pengikut_nama')) {
            $namas = $request->input('pengikut_nama');
            $niks = $request->input('pengikut_nik');
            $jks = $request->input('pengikut_jk');
            $tgls = $request->input('pengikut_tgl_lahir');
            $status = $request->input('pengikut_status');
            $kets = $request->input('pengikut_ket');

            for ($i = 0; $i < count($namas); $i++) {
                if (!empty($namas[$i])) {
                    $anggotaKeluarga[] = [
                        'nama' => $namas[$i],
                        'nik' => $niks[$i],
                        'jenis_kelamin' => $jks[$i],
                        'tanggal_lahir' => $tgls[$i],
                        'status_perkawinan' => $status[$i],
                        'keterangan' => $kets[$i],
                    ];
                }
            }
        }
        $data['anggota_keluarga'] = $anggotaKeluarga;

        // 4. Update File (Hapus yang lama, simpan yang baru)
        if ($request->hasFile('file_ktp')) {
            if (isset($data['file_ktp'])) Storage::disk('public')->delete($data['file_ktp']);
            $data['file_ktp'] = $request->file('file_ktp')->store('pengajuan', 'public');
        }

        if ($request->hasFile('file_kk')) {
            if (isset($data['file_kk'])) Storage::disk('public')->delete($data['file_kk']);
            $data['file_kk'] = $request->file('file_kk')->store('pengajuan', 'public');
        }

        if ($request->hasFile('file_lain')) {
            if (isset($data['file_lain'])) Storage::disk('public')->delete($data['file_lain']);
            $data['file_lain'] = $request->file('file_lain')->store('pengajuan', 'public');
        }

        $surat->update([
            'data_tambahan' => $data,
            'status' => 'menunggu_verifikasi',
        ]);

        return redirect()->route('warga.riwayat')->with('success', 'Pengajuan Keterangan Pindah berhasil diperbarui.');
    }
    
    //View Form Domisili
    public function formDomisili()
    {
        return view('warga.form.domisili');
    }

    //Store Form Domisili
    public function storeDomisili(Request $request)
    {
        $request->validate([
            'nik' => 'required|numeric',
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|string',
            'status_perkawinan' => 'required|string',
            'agama' => 'required|string',
            'kewarganegaraan' => 'required|string|max:255',
            'pekerjaan' => 'required|string|max:255',
            'alamat' => 'required|string',
            
            'file_ktp' => 'required|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_kk' => 'required|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_lain' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
        ]);

        $dataTambahan = [
            'nik' => $request->nik,
            'nama' => $request->nama,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'status_perkawinan' => $request->status_perkawinan,
            'agama' => $request->agama,
            'kewarganegaraan' => $request->kewarganegaraan,
            'pekerjaan' => $request->pekerjaan,
            'alamat' => $request->alamat,
            
            'file_ktp' => $request->file('file_ktp')->store('pengajuan', 'public'),
            'file_kk' => $request->file('file_kk')->store('pengajuan', 'public'),
            'file_lain' => $request->hasFile('file_lain') ? $request->file('file_lain')->store('pengajuan', 'public') : null,
        ];

        PengajuanSurat::create([
            'user_id' => Auth::id(),
            'jenis_surat' => 'keterangan_domisili',
            'keperluan' => 'Keterangan Domisili / Tempat Tinggal',
            'data_tambahan' => $dataTambahan,
            'status' => 'menunggu_verifikasi',
        ]);

        return redirect()->route('warga.riwayat')->with('success', 'Pengajuan Surat Keterangan Domisili berhasil dikirim!');
    }

    public function editDomisili($id)
    {
        $surat = PengajuanSurat::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($surat->jenis_surat !== 'keterangan_domisili') abort(404);
        
        if (!in_array($surat->status, ['menunggu_verifikasi', 'ditolak'])) {
            return redirect()->route('warga.riwayat')->withErrors('Pengajuan ini tidak dapat direvisi.');
        }

        // Perbaikan: langsung panggil nama file domisili
        return view('warga.form-edit.domisili', compact('surat'));
    }

    public function updateDomisili(Request $request, $id)
    {
        $surat = PengajuanSurat::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($surat->jenis_surat !== 'keterangan_domisili') abort(404);
        
        if (!in_array($surat->status, ['menunggu_verifikasi', 'ditolak'])) {
            return redirect()->route('warga.riwayat')->withErrors('Pengajuan ini tidak dapat direvisi.');
        }

        $request->validate([
            'nik' => 'required|numeric',
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|string',
            'status_perkawinan' => 'required|string',
            'agama' => 'required|string',
            'kewarganegaraan' => 'required|string|max:255',
            'pekerjaan' => 'required|string|max:255',
            'alamat' => 'required|string',
            
            'file_ktp' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_kk' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_lain' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
        ]);

        $dataTambahan = $surat->data_tambahan ?? [];

        $dataTambahan['nik'] = $request->nik;
        $dataTambahan['nama'] = $request->nama;
        $dataTambahan['tempat_lahir'] = $request->tempat_lahir;
        $dataTambahan['tanggal_lahir'] = $request->tanggal_lahir;
        $dataTambahan['jenis_kelamin'] = $request->jenis_kelamin;
        $dataTambahan['status_perkawinan'] = $request->status_perkawinan;
        $dataTambahan['agama'] = $request->agama;
        $dataTambahan['kewarganegaraan'] = $request->kewarganegaraan;
        $dataTambahan['pekerjaan'] = $request->pekerjaan;
        $dataTambahan['alamat'] = $request->alamat;
        
        if ($request->hasFile('file_ktp')) {
            $dataTambahan['file_ktp'] = $request->file('file_ktp')->store('pengajuan', 'public');
        }
        if ($request->hasFile('file_kk')) {
            $dataTambahan['file_kk'] = $request->file('file_kk')->store('pengajuan', 'public');
        }
        if ($request->hasFile('file_lain')) {
            $dataTambahan['file_lain'] = $request->file('file_lain')->store('pengajuan', 'public');
        }

        $surat->data_tambahan = $dataTambahan;
        $surat->status = 'menunggu_verifikasi';
        $surat->save();

        return redirect()->route('warga.riwayat')->with('success', 'Pengajuan Surat Keterangan Domisili berhasil diperbarui!');
    }

    //View Form Belum Menikah
    public function formBelumMenikah()
    {
        return view('warga.form.belum-menikah');
    }

    //Store Form Belum Menikah
    public function storeBelumMenikah(Request $request)
    {
        $request->validate([
            'nik_bapak' => 'required|numeric',
            'nama_bapak' => 'required|string|max:255',
            'tempat_lahir_bapak' => 'required|string|max:255',
            'tanggal_lahir_bapak' => 'required|date',
            'agama_bapak' => 'required|string',
            'pekerjaan_bapak' => 'required|string|max:255',
            'alamat_bapak' => 'required|string',

            'nik_ibu' => 'required|numeric',
            'nama_ibu' => 'required|string|max:255',
            'tempat_lahir_ibu' => 'required|string|max:255',
            'tanggal_lahir_ibu' => 'required|date',
            'agama_ibu' => 'required|string',
            'pekerjaan_ibu' => 'required|string|max:255',
            'alamat_ibu' => 'required|string',

            'file_ktp' => 'required|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_kk' => 'required|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_lain' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
        ]);

        $dataTambahan = [
            // Ambil dari session warga langsung
            'nik_pemohon' => Auth::user()->nik,
            'nama_pemohon' => Auth::user()->name,
            'tempat_lahir_pemohon' => Auth::user()->tempat_lahir,
            'tanggal_lahir_pemohon' => Auth::user()->tanggal_lahir,
            'jenis_kelamin_pemohon' => Auth::user()->jenis_kelamin,
            'agama_pemohon' => Auth::user()->agama,
            'pekerjaan_pemohon' => Auth::user()->pekerjaan,
            'alamat_pemohon' => Auth::user()->alamat_lengkap,

            // Ambil dari input bapak & ibu
            'nik_bapak' => $request->nik_bapak,
            'nama_bapak' => $request->nama_bapak,
            'tempat_lahir_bapak' => $request->tempat_lahir_bapak,
            'tanggal_lahir_bapak' => $request->tanggal_lahir_bapak,
            'agama_bapak' => $request->agama_bapak,
            'pekerjaan_bapak' => $request->pekerjaan_bapak,
            'alamat_bapak' => $request->alamat_bapak,

            'nik_ibu' => $request->nik_ibu,
            'nama_ibu' => $request->nama_ibu,
            'tempat_lahir_ibu' => $request->tempat_lahir_ibu,
            'tanggal_lahir_ibu' => $request->tanggal_lahir_ibu,
            'agama_ibu' => $request->agama_ibu,
            'pekerjaan_ibu' => $request->pekerjaan_ibu,
            'alamat_ibu' => $request->alamat_ibu,

            // File
            'file_ktp' => $request->file('file_ktp')->store('pengajuan', 'public'),
            'file_kk' => $request->file('file_kk')->store('pengajuan', 'public'),
            'file_lain' => $request->hasFile('file_lain') ? $request->file('file_lain')->store('pengajuan', 'public') : null,
        ];

        PengajuanSurat::create([
            'user_id' => Auth::id(),
            'jenis_surat' => 'keterangan_belum_menikah',
            'keperluan' => 'Surat Keterangan Belum Pernah Menikah',
            'data_tambahan' => $dataTambahan,
            'status' => 'menunggu_verifikasi',
        ]);

        return redirect()->route('warga.riwayat')->with('success', 'Pengajuan Keterangan Belum Menikah berhasil dikirim!');
    }

    //View Form Edit Belum Menikah
    public function editBelumMenikah($id)
    {
        // Ubah $pengajuan menjadi $surat agar sesuai dengan file Blade
        $surat = PengajuanSurat::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($surat->jenis_surat !== 'keterangan_belum_menikah') {
            abort(404);
        }
        
        if (!in_array($surat->status, ['menunggu_verifikasi', 'ditolak'])) {
            return redirect()->route('warga.riwayat')->with('error', 'Pengajuan ini tidak dapat direvisi.');
        }

        // Compact variabel $surat
        return view('warga.form-edit.belum-menikah', [
    'surat' => $surat,
    'pengajuan' => $surat // Tambahkan ini sebagai alias
]);
    }

    //Update Form Edit Belum Menikah
    public function updateBelumMenikah(Request $request, $id)
    {
        $surat = PengajuanSurat::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'nik_bapak' => 'required|numeric',
            'nama_bapak' => 'required|string|max:255',
            'tempat_lahir_bapak' => 'required|string|max:255',
            'tanggal_lahir_bapak' => 'required|date',
            'agama_bapak' => 'required|string',
            'pekerjaan_bapak' => 'required|string|max:255',
            'alamat_bapak' => 'required|string',

            'nik_ibu' => 'required|numeric',
            'nama_ibu' => 'required|string|max:255',
            'tempat_lahir_ibu' => 'required|string|max:255',
            'tanggal_lahir_ibu' => 'required|date',
            'agama_ibu' => 'required|string',
            'pekerjaan_ibu' => 'required|string|max:255',
            'alamat_ibu' => 'required|string',

            'file_ktp' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_kk' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_lain' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
        ]);

        $data = $surat->data_tambahan;

        // 1. Update data teks secara otomatis dengan looping
        // Kita kecualikan file, token, dan method agar tidak masuk ke JSON
        $exceptKeys = ['_token', '_method', 'file_ktp', 'file_kk', 'file_lain', 'consent'];
        foreach ($request->except($exceptKeys) as $key => $value) {
            $data[$key] = $value;
        }

        // 2. Keamanan Tambahan: Pastikan data pemohon tetap mengambil dari database User
        // Ini untuk mencegah user usil yang mengubah form "readonly" lewat Inspect Element
        $data['nik_pemohon'] = Auth::user()->nik;
        $data['nama_pemohon'] = Auth::user()->name;
        $data['tempat_lahir_pemohon'] = Auth::user()->tempat_lahir;
        $data['tanggal_lahir_pemohon'] = Auth::user()->tanggal_lahir;
        $data['jenis_kelamin_pemohon'] = Auth::user()->jenis_kelamin;
        $data['agama_pemohon'] = Auth::user()->agama;
        $data['pekerjaan_pemohon'] = Auth::user()->pekerjaan;
        $data['alamat_pemohon'] = Auth::user()->alamat_lengkap;

        // 3. Update file dan hapus file lama jika ada upload baru
        if ($request->hasFile('file_ktp')) {
            if (isset($data['file_ktp'])) {
                Storage::disk('public')->delete($data['file_ktp']);
            }
            $data['file_ktp'] = $request->file('file_ktp')->store('pengajuan', 'public');
        }

        if ($request->hasFile('file_kk')) {
            if (isset($data['file_kk'])) {
                Storage::disk('public')->delete($data['file_kk']);
            }
            $data['file_kk'] = $request->file('file_kk')->store('pengajuan', 'public');
        }

        if ($request->hasFile('file_lain')) {
            if (isset($data['file_lain'])) {
                Storage::disk('public')->delete($data['file_lain']);
            }
            $data['file_lain'] = $request->file('file_lain')->store('pengajuan', 'public');
        }

        // 4. Simpan ke database
        $surat->update([
            'data_tambahan' => $data,
            'status' => 'menunggu_verifikasi', // Reset status kembali ke awal
        ]);

        return redirect()->route('warga.riwayat')->with('success', 'Pengajuan Belum Menikah berhasil diperbarui.');
    }

    //View Form Janda Duda
    public function formJandaDuda()
    {
        return view('warga.form.janda-duda');
    }

    //Store Form Janda Duda
    public function storeJandaDuda(Request $request)
    {
        $request->validate([
            'nik_pemohon' => 'required|numeric',
            'nama_pemohon' => 'required|string|max:255',
            'tempat_lahir_pemohon' => 'required|string|max:255',
            'tanggal_lahir_pemohon' => 'required|date',
            'jenis_kelamin_pemohon' => 'required|string',
            'penyebab_status' => 'required|string',
            'alamat_pemohon' => 'required|string',
            
            'nama_mantan' => 'required|string|max:255',
            'tahun_berpisah' => 'required|numeric|digits:4',
            'alamat_mantan' => 'required|string',
            
            'file_ktp' => 'required|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_kk' => 'required|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_bukti' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
        ]);

        $dataTambahan = [
            'nik_pemohon' => $request->nik_pemohon,
            'nama_pemohon' => $request->nama_pemohon,
            'tempat_lahir_pemohon' => $request->tempat_lahir_pemohon,
            'tanggal_lahir_pemohon' => $request->tanggal_lahir_pemohon,
            'jenis_kelamin_pemohon' => $request->jenis_kelamin_pemohon,
            'penyebab_status' => $request->penyebab_status,
            'alamat_pemohon' => $request->alamat_pemohon,
            
            'nama_mantan' => $request->nama_mantan,
            'tahun_berpisah' => $request->tahun_berpisah,
            'alamat_mantan' => $request->alamat_mantan,
            
            'file_ktp' => $request->file('file_ktp')->store('pengajuan', 'public'),
            'file_kk' => $request->file('file_kk')->store('pengajuan', 'public'),
            'file_bukti' => $request->hasFile('file_bukti') ? $request->file('file_bukti')->store('pengajuan', 'public') : null,
        ];

        // Tentukan kata Janda atau Duda berdasarkan jenis kelamin
        $statusTeks = ($request->jenis_kelamin_pemohon == 'Laki-laki' || $request->jenis_kelamin_pemohon == 'Laki-Laki') ? 'Duda' : 'Janda';

        PengajuanSurat::create([
            'user_id' => Auth::id(),
            'jenis_surat' => 'keterangan_janda_duda',
            'keperluan' => 'Surat Keterangan ' . $statusTeks . ' (' . $request->penyebab_status . ')',
            'data_tambahan' => $dataTambahan,
            'status' => 'menunggu_verifikasi',
        ]);

        return redirect()->route('warga.riwayat')->with('success', 'Pengajuan Keterangan ' . $statusTeks . ' berhasil dikirim!');
    }

    //View Edit Form Janda Duda
    public function editJandaDuda($id)
    {
        $surat = PengajuanSurat::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if (!in_array($surat->jenis_surat, ['keterangan_janda_duda', 'janda_duda'])) {
            abort(404);
        }
        
        if (!in_array($surat->status, ['menunggu_verifikasi', 'ditolak'])) {
            return redirect()->route('warga.riwayat')->withErrors('Pengajuan ini tidak dapat direvisi.');
        }

        return view('warga.form-edit.janda-duda', compact('surat'));
    }

    //Update Form Janda Duda
    public function updateJandaDuda(Request $request, $id)
    {
        $surat = PengajuanSurat::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'nik_pemohon' => 'required|numeric',
            'nama_pemohon' => 'required|string|max:255',
            'tempat_lahir_pemohon' => 'required|string|max:255',
            'tanggal_lahir_pemohon' => 'required|date',
            'jenis_kelamin_pemohon' => 'required|string',
            'penyebab_status' => 'required|string',
            'alamat_pemohon' => 'required|string',
            
            'nama_mantan' => 'required|string|max:255',
            'tahun_berpisah' => 'required|numeric|min:1900|max:2099',
            'alamat_mantan' => 'required|string',
            
            'file_ktp' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_kk' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_bukti' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'consent' => 'accepted',
        ]);

        $data = $surat->data_tambahan;

        // Looping untuk text input
        $exceptKeys = ['_token', '_method', 'file_ktp', 'file_kk', 'file_bukti', 'consent'];
        foreach ($request->except($exceptKeys) as $key => $value) {
            $data[$key] = $value;
        }

        // Hapus file lama dan simpan yang baru jika diunggah ulang
        if ($request->hasFile('file_ktp')) {
            if (isset($data['file_ktp'])) Storage::disk('public')->delete($data['file_ktp']);
            $data['file_ktp'] = $request->file('file_ktp')->store('pengajuan', 'public');
        }

        if ($request->hasFile('file_kk')) {
            if (isset($data['file_kk'])) Storage::disk('public')->delete($data['file_kk']);
            $data['file_kk'] = $request->file('file_kk')->store('pengajuan', 'public');
        }

        if ($request->hasFile('file_bukti')) {
            if (isset($data['file_bukti'])) Storage::disk('public')->delete($data['file_bukti']);
            $data['file_bukti'] = $request->file('file_bukti')->store('pengajuan', 'public');
        }

        $surat->update([
            'data_tambahan' => $data,
            'status' => 'menunggu_verifikasi',
        ]);

        return redirect()->route('warga.riwayat')->with('success', 'Pengajuan Janda/Duda berhasil diperbarui.');
    }

    //View Form Beda Nama
    public function formBedaNama()
    {
        return view('warga.form.beda-nama');
    }

    public function storeBedaNama(Request $request)
    {
        $request->validate([
            // VALIDASI DOKUMEN 1 (Harus ada agar bisa disimpan)
            'nik_dok1' => 'required|numeric',
            'nama_dok1' => 'required|string|max:255',
            'tempat_lahir_dok1' => 'required|string|max:255',
            'tanggal_lahir_dok1' => 'required|date',
            'jenis_kelamin_dok1' => 'required|string',
            'alamat_dok1' => 'required|string',

            // VALIDASI DOKUMEN 2
            'nama_dokumen2' => 'required|string|max:255',
            'nomor_dok2' => 'nullable|string|max:255',
            'nama_dok2' => 'required|string|max:255',
            'tempat_lahir_dok2' => 'required|string|max:255',
            'tanggal_lahir_dok2' => 'required|date',
            'jenis_kelamin_dok2' => 'required|string',
            'alamat_dok2' => 'required|string',
            
            // VALIDASI LAINNYA
            'data_berbeda' => 'required|string|max:255',
            'acuan_kebenaran' => 'required|string',
            'file_dok1' => 'required|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_dok2' => 'required|file|mimes:pdf,jpg,png,jpeg|max:5120',
        ]);

        $dataTambahan = [
            // SIMPAN DATA DOKUMEN 1 KE DATABASE
            'nik_dok1' => $request->nik_dok1,
            'nama_dok1' => $request->nama_dok1,
            'tempat_lahir_dok1' => $request->tempat_lahir_dok1,
            'tanggal_lahir_dok1' => $request->tanggal_lahir_dok1,
            'jenis_kelamin_dok1' => $request->jenis_kelamin_dok1,
            'alamat_dok1' => $request->alamat_dok1,

            // SIMPAN DATA DOKUMEN 2
            'nama_dokumen2' => $request->nama_dokumen2,
            'nomor_dok2' => $request->nomor_dok2,
            'nama_dok2' => $request->nama_dok2,
            'tempat_lahir_dok2' => $request->tempat_lahir_dok2,
            'tanggal_lahir_dok2' => $request->tanggal_lahir_dok2,
            'jenis_kelamin_dok2' => $request->jenis_kelamin_dok2,
            'alamat_dok2' => $request->alamat_dok2,
            
            // DATA TAMBAHAN LAIN
            'data_berbeda' => $request->data_berbeda,
            'acuan_kebenaran' => $request->acuan_kebenaran,
            
            // FILE
            'file_dok1' => $request->file('file_dok1')->store('pengajuan', 'public'),
            'file_dok2' => $request->file('file_dok2')->store('pengajuan', 'public'),
        ];

        PengajuanSurat::create([
            'user_id' => Auth::id(),
            'jenis_surat' => 'keterangan_beda_nama',
            'keperluan' => 'Penyamaan Data Kependudukan (' . $request->data_berbeda . ')',
            'data_tambahan' => $dataTambahan,
            'status' => 'menunggu_verifikasi',
        ]);

        return redirect()->route('warga.riwayat')->with('success', 'Pengajuan Keterangan Beda Nama berhasil dikirim!');
    }

    // View Edit Form Beda Nama
    public function editBedaNama($id)
    {
        $surat = PengajuanSurat::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // Proteksi: Hanya bisa edit jika status belum diproses lebih jauh
        if (!in_array($surat->status, ['menunggu_verifikasi', 'ditolak'])) {
            return back()->withErrors('Surat sudah diproses dan tidak dapat diubah.');
        }

        return view('warga.form-edit.beda-nama', compact('surat'));
    }

    // Update Form Beda Nama
    public function updateBedaNama(Request $request, $id)
    {
        // Cari data pengajuan milik user tersebut
        $surat = PengajuanSurat::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'nik_dok1' => 'required',
            'nama_dok1' => 'required',
            'tempat_lahir_dok1' => 'required',
            'tanggal_lahir_dok1' => 'required|date',
            'jenis_kelamin_dok1' => 'required',
            'alamat_dok1' => 'required',
            'nama_dokumen2' => 'required',
            'nama_dok2' => 'required',
            'tempat_lahir_dok2' => 'required',
            'tanggal_lahir_dok2' => 'required|date',
            'jenis_kelamin_dok2' => 'required',
            'alamat_dok2' => 'required',
            'data_berbeda' => 'required',
            'acuan_kebenaran' => 'required',
            'file_dok1' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_dok2' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
        ]);

        $data = $surat->data_tambahan;

        // Update semua input teks
        foreach ($request->except(['_token', '_method', 'file_dok1', 'file_dok2', 'consent']) as $key => $value) {
            $data[$key] = $value;
        }

        // Update file dan Hapus file lama jika ada upload baru
        if ($request->hasFile('file_dok1')) {
            if (isset($data['file_dok1'])) {
                Storage::disk('public')->delete($data['file_dok1']);
            }
            $data['file_dok1'] = $request->file('file_dok1')->store('pengajuan', 'public');
        }
        
        if ($request->hasFile('file_dok2')) {
            if (isset($data['file_dok2'])) {
                Storage::disk('public')->delete($data['file_dok2']);
            }
            $data['file_dok2'] = $request->file('file_dok2')->store('pengajuan', 'public');
        }

        $surat->update([
            'data_tambahan' => $data,
            'status' => 'menunggu_verifikasi', // Reset status agar diperiksa ulang petugas
        ]);

        return redirect()->route('warga.riwayat')->with('success', 'Pengajuan Beda Nama berhasil diperbarui.');
    }

    //View Form Kehilangan
    public function formKehilangan()
    {
        return view('warga.form.kehilangan');
    }

    //Store Form Kehilangan
    public function storeKehilangan(Request $request)
    {
        $request->validate([
            'nik_pelapor' => 'required|numeric',
            'nama_pelapor' => 'required|string|max:255',
            'tempat_lahir_pelapor' => 'required|string|max:255',
            'tanggal_lahir_pelapor' => 'required|date',
            'jenis_kelamin_pelapor' => 'required|string',
            'agama_pelapor' => 'required|string',
            'pekerjaan_pelapor' => 'required|string|max:255',
            'alamat_pelapor' => 'required|string',
            
            'rincian_hilang' => 'required|string',
            'waktu_hilang' => 'required|date',
            'lokasi_hilang' => 'required|string|max:255',
            
            'file_ktp' => 'required|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_bukti' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
        ]);

        $dataTambahan = [
            'nik_pelapor' => $request->nik_pelapor,
            'nama_pelapor' => $request->nama_pelapor,
            'tempat_lahir_pelapor' => $request->tempat_lahir_pelapor,
            'tanggal_lahir_pelapor' => $request->tanggal_lahir_pelapor,
            'jenis_kelamin_pelapor' => $request->jenis_kelamin_pelapor,
            'agama_pelapor' => $request->agama_pelapor,
            'pekerjaan_pelapor' => $request->pekerjaan_pelapor,
            'alamat_pelapor' => $request->alamat_pelapor,
            
            'rincian_hilang' => $request->rincian_hilang,
            'waktu_hilang' => $request->waktu_hilang,
            'lokasi_hilang' => $request->lokasi_hilang,
            
            'file_ktp' => $request->file('file_ktp')->store('pengajuan', 'public'),
            'file_bukti' => $request->hasFile('file_bukti') ? $request->file('file_bukti')->store('pengajuan', 'public') : null,
        ];

        PengajuanSurat::create([
            'user_id' => Auth::id(),
            'jenis_surat' => 'keterangan_kehilangan',
            'keperluan' => 'Laporan Kehilangan',
            'data_tambahan' => $dataTambahan,
            'status' => 'menunggu_verifikasi',
        ]);

        return redirect()->route('warga.riwayat')->with('success', 'Pengajuan Surat Keterangan Kehilangan berhasil dikirim!');
    }

    //View Edit Form Kehilangan
    public function editKehilangan($id)
    {
        $surat = PengajuanSurat::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if (!in_array($surat->jenis_surat, ['keterangan_kehilangan', 'kehilangan'])) abort(404);
        
        if (!in_array($surat->status, ['menunggu_verifikasi', 'ditolak'])) {
            return redirect()->route('warga.riwayat')->withErrors('Pengajuan ini tidak dapat direvisi.');
        }

        return view('warga.form-edit.kehilangan', compact('surat'));
    }

    //Update Form Kehilangan
    public function updateKehilangan(Request $request, $id)
    {
        $surat = PengajuanSurat::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'nik_pelapor' => 'required|numeric',
            'nama_pelapor' => 'required|string|max:255',
            'tempat_lahir_pelapor' => 'required|string|max:255',
            'tanggal_lahir_pelapor' => 'required|date',
            'jenis_kelamin_pelapor' => 'required|string',
            'agama_pelapor' => 'required|string',
            'pekerjaan_pelapor' => 'required|string|max:255',
            'alamat_pelapor' => 'required|string',
            
            'rincian_hilang' => 'required|string',
            'waktu_hilang' => 'required|date',
            'lokasi_hilang' => 'required|string|max:255',
            
            'file_ktp' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_bukti' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'consent' => 'accepted',
        ]);

        $data = $surat->data_tambahan;

        // Update data text (Kecualikan file dan consent)
        $exceptKeys = ['_token', '_method', 'file_ktp', 'file_bukti', 'consent'];
        foreach ($request->except($exceptKeys) as $key => $value) {
            $data[$key] = $value;
        }

        // Handle File Update & Penghapusan File Lama
        if ($request->hasFile('file_ktp')) {
            if (isset($data['file_ktp'])) Storage::disk('public')->delete($data['file_ktp']);
            $data['file_ktp'] = $request->file('file_ktp')->store('pengajuan', 'public');
        }

        if ($request->hasFile('file_bukti')) {
            if (isset($data['file_bukti'])) Storage::disk('public')->delete($data['file_bukti']);
            $data['file_bukti'] = $request->file('file_bukti')->store('pengajuan', 'public');
        }

        $surat->update([
            'data_tambahan' => $data,
            'status' => 'menunggu_verifikasi',
        ]);

        return redirect()->route('warga.riwayat')->with('success', 'Pengajuan Keterangan Kehilangan berhasil diperbarui.');
    }

    //View Form SKCK
    public function formSkck()
    {
        return view('warga.form.skck');
    }

    //Store Form SKCK
    public function storeSkck(Request $request)
    {
        $request->validate([
            'nik' => 'required|numeric',
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|string',
            'agama' => 'required|string',
            'kewarganegaraan' => 'required|string|max:255',
            'pekerjaan' => 'required|string|max:255',
            
            'alamat_dusun' => 'required|string|max:255',
            'rt' => 'required|numeric',
            'rw' => 'required|numeric',
            
            'keperluan' => 'required|string|max:255',
            
            'file_ktp' => 'required|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_kk' => 'required|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'consent' => 'accepted',
        ]);

        $dataTambahan = [
            'nik' => $request->nik,
            'nama' => $request->nama,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'agama' => $request->agama,
            'kewarganegaraan' => $request->kewarganegaraan,
            'pekerjaan' => $request->pekerjaan,
            
            'alamat_dusun' => $request->alamat_dusun,
            'rt' => $request->rt,
            'rw' => $request->rw,
            
            'keperluan' => $request->keperluan,
            
            'file_ktp' => $request->file('file_ktp')->store('pengajuan', 'public'),
            'file_kk' => $request->file('file_kk')->store('pengajuan', 'public'),
        ];

        PengajuanSurat::create([
            'user_id' => Auth::id(),
            'jenis_surat' => 'pengantar_skck',
            'keperluan' => 'Pengantar SKCK (' . $request->keperluan . ')',
            'data_tambahan' => $dataTambahan,
            'status' => 'menunggu_verifikasi',
        ]);

        return redirect()->route('warga.riwayat')->with('success', 'Pengajuan Pengantar SKCK berhasil dikirim!');
    }

    //View Edit Form SKCK
    public function editSkck($id)
    {
        $surat = PengajuanSurat::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // Pastikan jenis surat di database adalah pengantar_skck atau skck
        if (!in_array($surat->jenis_surat, ['pengantar_skck', 'skck'])) abort(404);
        
        if (!in_array($surat->status, ['menunggu_verifikasi', 'ditolak'])) {
            return redirect()->route('warga.riwayat')->withErrors('Pengajuan ini tidak dapat direvisi.');
        }

        return view('warga.form-edit.skck', compact('surat'));
    }


    //Update Form SKCK
    public function updateSkck(Request $request, $id)
    {
        $surat = PengajuanSurat::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'nik' => 'required|numeric',
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|string',
            'agama' => 'required|string',
            'kewarganegaraan' => 'required|string|max:255',
            'pekerjaan' => 'required|string|max:255',
            'alamat_dusun' => 'required|string|max:255',
            'rt' => 'required|numeric',
            'rw' => 'required|numeric',
            'keperluan' => 'required|string|max:255',
            
            'file_ktp' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_kk' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'consent' => 'accepted',
        ]);

        $data = $surat->data_tambahan;

        // Loop Text Inputs
        $exceptKeys = ['_token', '_method', 'consent', 'file_ktp', 'file_kk'];
        foreach ($request->except($exceptKeys) as $key => $value) {
            $data[$key] = $value;
        }

        // Loop File Inputs (Hapus yang lama, simpan yang baru)
        if ($request->hasFile('file_ktp')) {
            if (isset($data['file_ktp'])) Storage::disk('public')->delete($data['file_ktp']);
            $data['file_ktp'] = $request->file('file_ktp')->store('pengajuan', 'public');
        }

        if ($request->hasFile('file_kk')) {
            if (isset($data['file_kk'])) Storage::disk('public')->delete($data['file_kk']);
            $data['file_kk'] = $request->file('file_kk')->store('pengajuan', 'public');
        }

        $surat->update([
            'keperluan' => 'Pengantar SKCK (' . $request->keperluan . ')',
            'data_tambahan' => $data,
            'status' => 'menunggu_verifikasi',
        ]);

        return redirect()->route('warga.riwayat')->with('success', 'Pengajuan Pengantar SKCK berhasil diperbarui.');
    }

    //View Form Surat Usaha
    public function formUsaha()
    {
        return view('warga.form.usaha');
    }

    //Store Form Surat Usaha
    public function storeUsaha(Request $request)
    {
        $request->validate([
            'nik' => 'required|numeric',
            'no_kk' => 'required|numeric',
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|string',
            'agama' => 'required|string',
            'pekerjaan' => 'required|string|max:255',
            
            'alamat_dusun' => 'required|string|max:255',
            'rt' => 'required|numeric',
            'rw' => 'required|numeric',

            'jenis_usaha' => 'required|string|max:255',
            'usaha_sampingan' => 'nullable|string|max:255',
            'alamat_usaha' => 'required|string',

            'file_ktp' => 'required|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_kk' => 'required|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_foto_usaha' => 'nullable|file|mimes:jpg,png,jpeg|max:5120',
            'consent' => 'accepted',
        ]);

        $dataTambahan = [
            'nik' => $request->nik,
            'no_kk' => $request->no_kk,
            'nama' => $request->nama,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'agama' => $request->agama,
            'pekerjaan' => $request->pekerjaan,

            'alamat_dusun' => $request->alamat_dusun,
            'rt' => $request->rt,
            'rw' => $request->rw,

            'jenis_usaha' => $request->jenis_usaha,
            'usaha_sampingan' => $request->usaha_sampingan,
            'alamat_usaha' => $request->alamat_usaha,

            'file_ktp' => $request->file('file_ktp')->store('pengajuan', 'public'),
            'file_kk' => $request->file('file_kk')->store('pengajuan', 'public'),
            'file_foto_usaha' => $request->hasFile('file_foto_usaha') ? $request->file('file_foto_usaha')->store('pengajuan', 'public') : null,
        ];

        PengajuanSurat::create([
            'user_id' => Auth::id(),
            'jenis_surat' => 'keterangan_usaha',
            'keperluan' => 'Keterangan Usaha (' . $request->jenis_usaha . ')',
            'data_tambahan' => $dataTambahan,
            'status' => 'menunggu_verifikasi',
        ]);

        return redirect()->route('warga.riwayat')->with('success', 'Pengajuan Keterangan Usaha berhasil dikirim!');
    }

    //View Edit Form Surat Usaha
    public function editUsaha($id)
    {
        $surat = PengajuanSurat::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // Pastikan jenis surat di database Anda adalah keterangan_usaha atau usaha
        if (!in_array($surat->jenis_surat, ['keterangan_usaha', 'usaha'])) abort(404);
        
        if (!in_array($surat->status, ['menunggu_verifikasi', 'ditolak'])) {
            return redirect()->route('warga.riwayat')->withErrors('Pengajuan ini tidak dapat direvisi.');
        }

        return view('warga.form-edit.usaha', compact('surat'));
    }

    //Store Edit Form Surat Usaha
    public function updateUsaha(Request $request, $id)
    {
        $surat = PengajuanSurat::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'nik' => 'required|numeric',
            'no_kk' => 'required|numeric',
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|string',
            'agama' => 'required|string',
            'pekerjaan' => 'required|string|max:255',
            'alamat_dusun' => 'required|string|max:255',
            'rt' => 'required|numeric',
            'rw' => 'required|numeric',
            
            'jenis_usaha' => 'required|string|max:255',
            'usaha_sampingan' => 'nullable|string|max:255',
            'alamat_usaha' => 'required|string',
            
            'file_ktp' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_kk' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_foto_usaha' => 'nullable|file|mimes:jpg,png,jpeg|max:5120',
            'consent' => 'accepted',
        ]);

        $data = $surat->data_tambahan;

        // Loop Text Inputs
        $exceptKeys = ['_token', '_method', 'consent', 'file_ktp', 'file_kk', 'file_foto_usaha'];
        foreach ($request->except($exceptKeys) as $key => $value) {
            $data[$key] = $value;
        }

        // Loop File Inputs (Hapus yang lama, simpan yang baru)
        if ($request->hasFile('file_ktp')) {
            if (isset($data['file_ktp'])) Storage::disk('public')->delete($data['file_ktp']);
            $data['file_ktp'] = $request->file('file_ktp')->store('pengajuan', 'public');
        }

        if ($request->hasFile('file_kk')) {
            if (isset($data['file_kk'])) Storage::disk('public')->delete($data['file_kk']);
            $data['file_kk'] = $request->file('file_kk')->store('pengajuan', 'public');
        }

        if ($request->hasFile('file_foto_usaha')) {
            if (isset($data['file_foto_usaha'])) Storage::disk('public')->delete($data['file_foto_usaha']);
            $data['file_foto_usaha'] = $request->file('file_foto_usaha')->store('pengajuan', 'public');
        }

        $surat->update([
            'keperluan' => 'Keterangan Usaha (' . $request->jenis_usaha . ')',
            'data_tambahan' => $data,
            'status' => 'menunggu_verifikasi',
        ]);

        return redirect()->route('warga.riwayat')->with('success', 'Pengajuan Keterangan Usaha berhasil diperbarui.');
    }

    //View Form Surat Izin
    public function formIzinKeramaian()
    {
        return view('warga.form.izin-keramaian');
    }

    //Store Form Izin Keramaian
    public function storeIzinKeramaian(Request $request)
    {
        $request->validate([
            'nik_penanggung_jawab' => 'required|numeric',
            'nama_penanggung_jawab' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|string',
            'agama' => 'required|string',
            'pekerjaan' => 'required|string|max:255',
            'alamat' => 'required|string',
            
            'jenis_acara' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'lokasi_acara' => 'required|string',
            
            'file_ktp' => 'required|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_pengantar_rt' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'consent' => 'accepted'
        ]);

        $dataTambahan = [
            'nik_penanggung_jawab' => $request->nik_penanggung_jawab,
            'nama_penanggung_jawab' => $request->nama_penanggung_jawab,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'agama' => $request->agama,
            'pekerjaan' => $request->pekerjaan,
            'alamat' => $request->alamat,
            
            'jenis_acara' => $request->jenis_acara,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'lokasi_acara' => $request->lokasi_acara,
            
            'file_ktp' => $request->file('file_ktp')->store('pengajuan', 'public'),
            'file_pengantar_rt' => $request->hasFile('file_pengantar_rt') ? $request->file('file_pengantar_rt')->store('pengajuan', 'public') : null,
        ];

        PengajuanSurat::create([
            'user_id' => Auth::id(),
            'jenis_surat' => 'izin_keramaian',
            'keperluan' => 'Pengantar Izin Keramaian ke Polsek (' . $request->jenis_acara . ')',
            'data_tambahan' => $dataTambahan,
            'status' => 'menunggu_verifikasi',
        ]);

        return redirect()->route('warga.riwayat')->with('success', 'Pengajuan Izin Keramaian berhasil dikirim!');
    }

    //View Edit Form Izin Keramaian
    public function editIzinKeramaian($id)
    {
        $surat = PengajuanSurat::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // Pastikan jenis surat benar sesuai database Anda (misal: pengantar_izin_keramaian)
        if (!in_array($surat->jenis_surat, ['izin_keramaian', 'pengantar_izin_keramaian'])) {
            abort(404);
        }
        
        if (!in_array($surat->status, ['menunggu_verifikasi', 'ditolak'])) {
            return redirect()->route('warga.riwayat')->withErrors('Pengajuan ini tidak dapat direvisi.');
        }

        return view('warga.form-edit.izin-keramaian', compact('surat'));
    }

    //Update Form Izin Keramaian
    public function updateIzinKeramaian(Request $request, $id)
    {
        $surat = PengajuanSurat::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'nik_penanggung_jawab' => 'required|numeric',
            'nama_penanggung_jawab' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|string',
            'agama' => 'required|string',
            'pekerjaan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'jenis_acara' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'lokasi_acara' => 'required|string',
            
            'file_ktp' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_pengantar_rt' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'consent' => 'accepted',
        ]);

        $data = $surat->data_tambahan;

        // Looping untuk mengupdate data text
        $exceptKeys = ['_token', '_method', 'file_ktp', 'file_pengantar_rt', 'consent'];
        foreach ($request->except($exceptKeys) as $key => $value) {
            $data[$key] = $value;
        }

        // Hapus file lama dan simpan file baru jika user melakukan upload ulang
        if ($request->hasFile('file_ktp')) {
            if (isset($data['file_ktp'])) Storage::disk('public')->delete($data['file_ktp']);
            $data['file_ktp'] = $request->file('file_ktp')->store('pengajuan', 'public');
        }

        if ($request->hasFile('file_pengantar_rt')) {
            if (isset($data['file_pengantar_rt'])) Storage::disk('public')->delete($data['file_pengantar_rt']);
            $data['file_pengantar_rt'] = $request->file('file_pengantar_rt')->store('pengajuan', 'public');
        }

        $surat->update([
            'data_tambahan' => $data,
            'status' => 'menunggu_verifikasi', // Reset status agar Kades/Admin memeriksa ulang
        ]);

        return redirect()->route('warga.riwayat')->with('success', 'Pengajuan Izin Keramaian berhasil diperbarui.');
    }

    //View Form Surat Keterangan Tidak Mampu
    public function formTidakMampu()
    {
        return view('warga.form.tidak-mampu');
    }

    //Store Form Surat Keterangan Tidak Mampu
    public function storeTidakMampu(Request $request)
    {
        $request->validate([
            'nik' => 'required|numeric',
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|string',
            'agama' => 'required|string',
            'pekerjaan' => 'required|string|max:255',
            'alamat' => 'required|string',

            'no_kk' => 'required|numeric',
            'nik_kepala_keluarga' => 'required|numeric',
            'nama_kepala_keluarga' => 'required|string|max:255',
            'tempat_lahir_kk' => 'required|string|max:255',
            'tanggal_lahir_kk' => 'required|date',
            'jenis_kelamin_kk' => 'required|string',
            'agama_kk' => 'required|string',
            'pekerjaan_kk' => 'required|string|max:255',
            'alamat_kk' => 'required|string',

            'keperluan' => 'required|string|max:255',

            'file_ktp' => 'required|file|mimes:pdf,jpg,png,jpeg,zip|max:5120',
            'file_kk' => 'required|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_pengantar' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'consent' => 'accepted',
        ]);

        $dataTambahan = [
            'nik' => $request->nik,
            'nama' => $request->nama,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'agama' => $request->agama,
            'pekerjaan' => $request->pekerjaan,
            'alamat' => $request->alamat,

            'no_kk' => $request->no_kk,
            'nik_kepala_keluarga' => $request->nik_kepala_keluarga,
            'nama_kepala_keluarga' => $request->nama_kepala_keluarga,
            'tempat_lahir_kk' => $request->tempat_lahir_kk,
            'tanggal_lahir_kk' => $request->tanggal_lahir_kk,
            'jenis_kelamin_kk' => $request->jenis_kelamin_kk,
            'agama_kk' => $request->agama_kk,
            'pekerjaan_kk' => $request->pekerjaan_kk,
            'alamat_kk' => $request->alamat_kk,

            'keperluan' => $request->keperluan,

            'file_ktp' => $request->file('file_ktp')->store('pengajuan', 'public'),
            'file_kk' => $request->file('file_kk')->store('pengajuan', 'public'),
            'file_pengantar' => $request->hasFile('file_pengantar') ? $request->file('file_pengantar')->store('pengajuan', 'public') : null,
        ];

        PengajuanSurat::create([
            'user_id' => Auth::id(),
            'jenis_surat' => 'keterangan_tidak_mampu',
            'keperluan' => 'SKTM (' . $request->keperluan . ')',
            'data_tambahan' => $dataTambahan,
            'status' => 'menunggu_verifikasi',
        ]);

        return redirect()->route('warga.riwayat')->with('success', 'Pengajuan SKTM berhasil dikirim!');
    }

    //View Edit Form SKTM
    public function editTidakMampu($id)
    {
        $surat = PengajuanSurat::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // Pastikan jenis surat adalah tidak_mampu atau keterangan_tidak_mampu
        if (!in_array($surat->jenis_surat, ['keterangan_tidak_mampu', 'tidak_mampu'])) abort(404);
        
        if (!in_array($surat->status, ['menunggu_verifikasi', 'ditolak'])) {
            return redirect()->route('warga.riwayat')->withErrors('Pengajuan ini tidak dapat direvisi.');
        }

        return view('warga.form-edit.tidak-mampu', compact('surat'));
    }

    public function updateTidakMampu(Request $request, $id)
    {
        $surat = PengajuanSurat::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'nik' => 'required|numeric',
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|string',
            'agama' => 'required|string',
            'pekerjaan' => 'required|string|max:255',
            'alamat' => 'required|string',
            
            'no_kk' => 'required|numeric',
            'nik_kepala_keluarga' => 'required|numeric',
            'nama_kepala_keluarga' => 'required|string|max:255',
            'tempat_lahir_kk' => 'required|string|max:255',
            'tanggal_lahir_kk' => 'required|date',
            'jenis_kelamin_kk' => 'required|string',
            'agama_kk' => 'required|string',
            'pekerjaan_kk' => 'required|string|max:255',
            'alamat_kk' => 'required|string',
            
            'keperluan' => 'required|string|max:255',
            
            'file_ktp' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_kk' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_pengantar' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'consent' => 'accepted',
        ]);

        $data = $surat->data_tambahan;

        // Loop Text Inputs
        $exceptKeys = ['_token', '_method', 'consent', 'file_ktp', 'file_kk', 'file_pengantar'];
        foreach ($request->except($exceptKeys) as $key => $value) {
            $data[$key] = $value;
        }

        // Loop File Inputs (Hapus yang lama, simpan yang baru)
        if ($request->hasFile('file_ktp')) {
            if (isset($data['file_ktp'])) Storage::disk('public')->delete($data['file_ktp']);
            $data['file_ktp'] = $request->file('file_ktp')->store('pengajuan', 'public');
        }

        if ($request->hasFile('file_kk')) {
            if (isset($data['file_kk'])) Storage::disk('public')->delete($data['file_kk']);
            $data['file_kk'] = $request->file('file_kk')->store('pengajuan', 'public');
        }

        if ($request->hasFile('file_pengantar')) {
            if (isset($data['file_pengantar'])) Storage::disk('public')->delete($data['file_pengantar']);
            $data['file_pengantar'] = $request->file('file_pengantar')->store('pengajuan', 'public');
        }

        $surat->update([
            'keperluan' => 'SKTM (' . $request->keperluan . ')',
            'data_tambahan' => $data,
            'status' => 'menunggu_verifikasi',
        ]);

        return redirect()->route('warga.riwayat')->with('success', 'Pengajuan Keterangan Tidak Mampu berhasil diperbarui.');
    }

    //View Form Surat Keterangan Penghasilan
    public function formPenghasilan()
    {
        return view('warga.form.penghasilan');
    }

    //Store Form Surat Keterangan Penghasilan
    public function storePenghasilan(Request $request)
    {
        $request->validate([
            'nik' => 'required|numeric',
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|string',
            'agama' => 'required|string',
            'pekerjaan' => 'required|string|max:255',
            'alamat' => 'required|string',
            
            'jumlah_penghasilan' => 'required|string|max:255',
            'jumlah_tanggungan' => 'required|numeric|min:0',
            'nama_tanggungan' => 'required|string|max:255',
            
            'file_kk' => 'required|file|mimes:pdf,jpg,png,jpeg,zip|max:5120',
            'file_anak' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'consent' => 'accepted',
        ]);

        $dataTambahan = [
            'nik' => $request->nik,
            'nama' => $request->nama,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'agama' => $request->agama,
            'pekerjaan' => $request->pekerjaan,
            'alamat' => $request->alamat,
            
            'jumlah_penghasilan' => $request->jumlah_penghasilan,
            'jumlah_tanggungan' => $request->jumlah_tanggungan,
            'nama_tanggungan' => $request->nama_tanggungan,
            
            'file_kk' => $request->file('file_kk')->store('pengajuan', 'public'),
            'file_anak' => $request->hasFile('file_anak') ? $request->file('file_anak')->store('pengajuan', 'public') : null,
        ];

        PengajuanSurat::create([
            'user_id' => Auth::id(),
            'jenis_surat' => 'keterangan_penghasilan',
            'keperluan' => 'Keterangan Penghasilan (Keperluan: ' . $request->nama_tanggungan . ')',
            'data_tambahan' => $dataTambahan,
            'status' => 'menunggu_verifikasi',
        ]);

        return redirect()->route('warga.riwayat')->with('success', 'Pengajuan Keterangan Penghasilan berhasil dikirim!');
    }

    //View Edit Form Penghasilan
    public function editPenghasilan($id)
    {
        $surat = PengajuanSurat::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // Pastikan jenis surat di database Anda adalah keterangan_penghasilan atau penghasilan
        if (!in_array($surat->jenis_surat, ['keterangan_penghasilan', 'penghasilan'])) abort(404);
        
        if (!in_array($surat->status, ['menunggu_verifikasi', 'ditolak'])) {
            return redirect()->route('warga.riwayat')->withErrors('Pengajuan ini tidak dapat direvisi.');
        }

        return view('warga.form-edit.penghasilan', compact('surat'));
    }

    //Update Form Penghasilan
    public function updatePenghasilan(Request $request, $id)
    {
        $surat = PengajuanSurat::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'nik' => 'required|numeric',
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|string',
            'agama' => 'required|string',
            'pekerjaan' => 'required|string|max:255',
            'alamat' => 'required|string',
            
            'jumlah_penghasilan' => 'required|string',
            'jumlah_tanggungan' => 'required|numeric',
            'nama_tanggungan' => 'required|string|max:255',
            
            'file_kk' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'file_anak' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'consent' => 'accepted',
        ]);

        $data = $surat->data_tambahan;

        // Loop Text Inputs
        $exceptKeys = ['_token', '_method', 'consent', 'file_kk', 'file_anak'];
        foreach ($request->except($exceptKeys) as $key => $value) {
            $data[$key] = $value;
        }

        // Loop File Inputs (Hapus yang lama, simpan yang baru)
        if ($request->hasFile('file_kk')) {
            if (isset($data['file_kk'])) Storage::disk('public')->delete($data['file_kk']);
            $data['file_kk'] = $request->file('file_kk')->store('pengajuan', 'public');
        }

        if ($request->hasFile('file_anak')) {
            if (isset($data['file_anak'])) Storage::disk('public')->delete($data['file_anak']);
            $data['file_anak'] = $request->file('file_anak')->store('pengajuan', 'public');
        }

        $surat->update([
            'data_tambahan' => $data,
            'status' => 'menunggu_verifikasi',
        ]);

        return redirect()->route('warga.riwayat')->with('success', 'Pengajuan Keterangan Penghasilan berhasil diperbarui.');
    }

    // Pusat Bantuan Warga
    public function bantuan()
    {
        return view('warga.bantuan');
    }

}