
@once
    <link rel="stylesheet" href="{{ asset('css/chatbot-widget.css') }}?v={{ time() }}">
@endonce

<div
    id="sindesa-chat-shell"
    class="sindesa-chat-shell"
    data-endpoint="{{ route('chatbot.message') }}"
    data-csrf="{{ csrf_token() }}"
>
    <section
        id="sindesa-chat-window"
        class="sindesa-chat-window"
        aria-label="Asisten Layanan Publik SINDESA"
        aria-hidden="true"
    >
        <header class="sindesa-chat-header">
            <div class="sindesa-chat-avatar sindesa-chat-avatar--header" aria-hidden="true">
                <i class="fas fa-robot"></i>
            </div>

            <div class="sindesa-chat-heading">
                <h2>Asisten Layanan Publik SINDESA</h2>
               <p> <span class="sindesa-chat-online-dot"></span> Online</p>
            </div>

            <div class="sindesa-chat-header-actions">
                <button
                    id="sindesa-chat-about-btn"
                    class="sindesa-chat-icon-button"
                    type="button"
                    aria-label="Tentang Chatbot AI SINDESA"
                    title="Tentang Chatbot AI SINDESA"
                    onclick="window.openSindesaAboutPanel()"
                >
                    <i class="fas fa-user-circle" aria-hidden="true"></i>
                </button>

                <button
                    id="sindesa-chat-expand"
                    class="sindesa-chat-icon-button"
                    type="button"
                    aria-label="Perluas percakapan"
                    aria-pressed="false"
                    title="Perluas percakapan"
                    onclick="window.toggleSindesaExpandChat()"
                >
                    <i id="sindesa-chat-expand-icon" class="fas fa-expand" aria-hidden="true"></i>
                </button>

                <button
                    id="sindesa-chat-close"
                    class="sindesa-chat-icon-button"
                    type="button"
                    aria-label="Tutup percakapan"
                    title="Tutup percakapan"
                    onclick="window.toggleSindesaChatWindow(false)"
                >
                    <i class="fas fa-times" aria-hidden="true"></i>
                </button>
            </div>
        </header>

        <!-- Halaman Khusus Profil & Penjelasan Chatbot AI SINDESA (Berukuran Penuh Sesuai UI Chatbot) -->
        <div id="sindesa-chat-about-panel" class="sindesa-chat-about-panel" aria-hidden="true" style="display: none;">
            <header class="sindesa-chat-about-header">
                <div class="sindesa-chat-about-header-title">
                    <i class="fas fa-robot"></i>
                    <div>
                        <h3>Tentang Chatbot AI</h3>
                        <p>Asisten Layanan Publik Desa Buttu Sawe</p>
                    </div>
                </div>
                <button
                    id="sindesa-chat-about-close-top"
                    class="sindesa-chat-icon-button"
                    type="button"
                    aria-label="Kembali ke chat"
                    title="Kembali ke chat"
                    onclick="window.closeSindesaAboutPanel()"
                >
                    <i class="fas fa-times" aria-hidden="true"></i>
                </button>
            </header>

            <div class="sindesa-chat-about-body">
                <div class="sindesa-chat-about-badge">
                    <i class="fas fa-microchip"></i> Model AI IndoBERT • 13.925 Dataset
                </div>
                
                <div class="sindesa-chat-about-intro">
                    <h4>Asisten Cerdas SINDESA</h4>
                    <p>Sistem kecerdasan buatan berbasis Natural Language Understanding (NLU) yang siap membantu warga Desa Buttu Sawe dan masyarakat umum memperoleh panduan pelayanan publik secara cepat dan akurat.</p>
                </div>

                <div class="sindesa-chat-about-section">
                    <h5><i class="fas fa-brain"></i> Teknologi IndoBERT (NLU)</h5>
                    <p>Chatbot ini menggunakan model <b>IndoBERT</b> yang dilatih secara khusus untuk memahami pertanyaan bahasa Indonesia sehari-hari, mengekstrak maksud pengguna, jenis layanan, instansi, hingga wilayah yang ditanyakan.</p>
                </div>

                <div class="sindesa-chat-about-section">
                    <h5><i class="fas fa-database"></i> 13.925 Basis Pengetahuan Resmi</h5>
                    <p>Terhubung langsung dengan 13.925 data layanan publik resmi dari Disdukcapil (KTP, KK, Akta), Bapenda/Samsat (Pajak Kendaraan, Balik Nama), Perizinan Daerah, dan Administrasi Desa.</p>
                </div>

                <div class="sindesa-chat-about-section">
                    <h5><i class="fas fa-check-double"></i> Kemampuan Asisten</h5>
                    <ul class="sindesa-chat-about-list">
                        <li>Memberikan rincian persyaratan berkas & prosedur pengurusan.</li>
                        <li>Menginformasikan status biaya layanan (GRATIS / Tarif Resmi).</li>
                        <li>Memberikan alamat kantor & kontak instansi jika wilayah disebutkan.</li>
                        <li>Layanan tanya jawab otomatis aktif 24 jam nonstop.</li>
                    </ul>
                </div>

                <div class="sindesa-chat-about-section sindesa-chat-about-section--tips">
                    <h5><i class="fas fa-exclamation-triangle"></i> Tanyakan Secara Lengkap</h5>
                    <p>Chatbot ini tidak mengingat konteks pertanyaan sebelumnya. Agar jawaban lebih tepat, tuliskan setiap pertanyaan secara lengkap dan sertakan layanan, lokasi, serta detail yang diperlukan.</p>
                </div>

                <div class="sindesa-chat-about-actions">
                    <button
                        id="sindesa-chat-about-back"
                        type="button"
                        class="sindesa-chat-about-btn-back"
                        onclick="window.closeSindesaAboutPanel()"
                    >
                        <i class="fas fa-arrow-left"></i> Kembali ke Percakapan Chat
                    </button>
                </div>
            </div>
        </div>

        <div id="sindesa-chat-messages" class="sindesa-chat-messages" aria-live="polite">
            <div class="sindesa-chat-row sindesa-chat-row--bot sindesa-chat-pop">
                <div class="sindesa-chat-avatar sindesa-chat-avatar--bot" aria-hidden="true">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="sindesa-chat-bubble sindesa-chat-bubble--bot">Selamat datang di Asisten Layanan Publik SINDESA Desa Buttu Sawe. Saya dapat membantu mencari informasi persyaratan, prosedur, status, kendala, durasi, jadwal, lokasi, pembaruan, dan pembayaran layanan berdasarkan dokumen, layanan, instansi, serta wilayah yang Anda sebutkan.

Silakan tulis kebutuhan Anda secara spesifik. Contoh: “Apa syarat mengurus KTP-el di Pinrang?”</div>
            </div>

            <div id="sindesa-chat-suggestions" class="sindesa-chat-suggestions">
                <button type="button" data-message="Apa syarat mengurus KTP-el di Pinrang?" onclick="event.preventDefault(); event.stopPropagation(); window.sindesaSendSuggestion(this);">Syarat KTP-el</button>
                <button type="button" data-message="Bagaimana cara membayar PBB di Pinrang?" onclick="event.preventDefault(); event.stopPropagation(); window.sindesaSendSuggestion(this);">Pembayaran PBB</button>
                <button type="button" data-message="Di mana lokasi Samsat Pinrang?" onclick="event.preventDefault(); event.stopPropagation(); window.sindesaSendSuggestion(this);">Lokasi Samsat</button>
            </div>
        </div>

        <footer class="sindesa-chat-footer">
            <div class="sindesa-chat-input-row">
                <textarea
                    id="sindesa-chat-input"
                    rows="1"
                    maxlength="1000"
                    placeholder="Tulis pertanyaan layanan publik..."
                    aria-label="Pesan untuk chatbot"
                ></textarea>
                <button id="sindesa-chat-send" type="button" aria-label="Kirim pesan">
                    <i class="fas fa-paper-plane" aria-hidden="true"></i>
                </button>
            </div>
            <p>© 2026 SINDESA Desa Buttu Sawe. All rights reserved.</p>
        </footer>
    </section>

    <button
        id="sindesa-chat-toggle"
        class="sindesa-chat-toggle"
        type="button"
        aria-label="Buka chatbot"
        aria-expanded="false"
        onclick="window.toggleSindesaChatWindow()"
    >
        <i id="sindesa-chat-toggle-icon" class="fas fa-comment-dots" aria-hidden="true"></i>
    </button>
</div>

<script>
(function() {
    if (window.__sindesaChatbotActive) return;
    window.__sindesaChatbotActive = true;

    window.sindesaIsSending = false;
    var globalLastMessage = '';
    var globalLastSendTime = 0;

    window.openSindesaAboutPanel = function() {
        var p = document.getElementById('sindesa-chat-about-panel');
        if (p) {
            p.style.setProperty('display', 'flex', 'important');
            p.style.setProperty('opacity', '1', 'important');
            p.style.setProperty('visibility', 'visible', 'important');
            p.style.setProperty('pointer-events', 'auto', 'important');
            p.setAttribute('aria-hidden', 'false');
        }
    };

    window.closeSindesaAboutPanel = function() {
        var p = document.getElementById('sindesa-chat-about-panel');
        if (p) {
            p.style.setProperty('display', 'none', 'important');
            p.style.setProperty('opacity', '0', 'important');
            p.style.setProperty('visibility', 'hidden', 'important');
            p.style.setProperty('pointer-events', 'none', 'important');
            p.setAttribute('aria-hidden', 'true');
        }
    };

    window.toggleSindesaChatWindow = function(forceState) {
        var w = document.getElementById('sindesa-chat-window');
        var t = document.getElementById('sindesa-chat-toggle');
        if (!w) return;
        
        var isHidden = w.getAttribute('aria-hidden') === 'true' || w.style.opacity === '0' || getComputedStyle(w).visibility === 'hidden';
        var show = (forceState !== undefined) ? forceState : isHidden;
        
        if (show) {
            w.setAttribute('aria-hidden', 'false');
            w.classList.add('sindesa-chat-window--active');
            w.style.setProperty('opacity', '1', 'important');
            w.style.setProperty('visibility', 'visible', 'important');
            w.style.setProperty('pointer-events', 'auto', 'important');
            w.style.setProperty('transform', 'translateY(0) scale(1)', 'important');
            if (t) t.setAttribute('aria-expanded', 'true');
            var inp = document.getElementById('sindesa-chat-input');
            if (inp) setTimeout(function() { inp.focus(); }, 150);
            scrollToBottom();
        } else {
            w.setAttribute('aria-hidden', 'true');
            w.classList.remove('sindesa-chat-window--active');
            w.style.setProperty('opacity', '0', 'important');
            w.style.setProperty('visibility', 'hidden', 'important');
            w.style.setProperty('pointer-events', 'none', 'important');
            w.style.setProperty('transform', 'translateY(20px) scale(0.95)', 'important');
            if (t) t.setAttribute('aria-expanded', 'false');
            window.closeSindesaAboutPanel();
        }
    };

    window.toggleSindesaExpandChat = function() {
        var w = document.getElementById('sindesa-chat-window');
        var icon = document.getElementById('sindesa-chat-expand-icon');
        if (!w) return;
        var isExp = w.classList.toggle('sindesa-chat-window--expanded');
        if (icon) icon.className = isExp ? 'fas fa-compress' : 'fas fa-expand';
        scrollToBottom();
    };

    function scrollToBottom() {
        var m = document.getElementById('sindesa-chat-messages');
        if (m) m.scrollTop = m.scrollHeight;
    }

    function appendUserMessage(text) {
        var m = document.getElementById('sindesa-chat-messages');
        if (!m) return;

        // Check if last user bubble in DOM has identical text within 3 seconds
        var userBubbles = m.querySelectorAll('.sindesa-chat-bubble--user');
        if (userBubbles.length > 0) {
            var lastBubble = userBubbles[userBubbles.length - 1];
            if (lastBubble && lastBubble.textContent.trim() === text.trim()) {
                if ((Date.now() - globalLastSendTime) < 3000) {
                    return; // Prevent duplicate DOM insertion
                }
            }
        }

        var row = document.createElement('div');
        row.className = 'sindesa-chat-row sindesa-chat-row--user sindesa-chat-pop';
        
        var bubble = document.createElement('div');
        bubble.className = 'sindesa-chat-bubble sindesa-chat-bubble--user';
        bubble.textContent = text;
        
        row.appendChild(bubble);
        m.appendChild(row);
        scrollToBottom();
    }

    function showTypingIndicator() {
        var m = document.getElementById('sindesa-chat-messages');
        if (!m) return null;

        // Remove any existing typing indicator so only 1 can exist
        var existing = document.getElementById('sindesa-chat-typing-row');
        if (existing) existing.remove();

        var row = document.createElement('div');
        row.id = 'sindesa-chat-typing-row';
        row.className = 'sindesa-chat-row sindesa-chat-row--bot sindesa-chat-pop';

        var avatar = document.createElement('div');
        avatar.className = 'sindesa-chat-avatar sindesa-chat-avatar--bot';
        avatar.innerHTML = '<i class="fas fa-robot"></i>';

        var bubble = document.createElement('div');
        bubble.className = 'sindesa-chat-bubble sindesa-chat-bubble--bot';
        bubble.innerHTML = '<div class="sindesa-chat-typing"><span></span><span></span><span></span></div>';

        row.appendChild(avatar);
        row.appendChild(bubble);
        m.appendChild(row);
        scrollToBottom();
        return row;
    }

    function appendBotMessage(replyHTML, suggestions) {
        var m = document.getElementById('sindesa-chat-messages');
        if (!m) return;
        var row = document.createElement('div');
        row.className = 'sindesa-chat-row sindesa-chat-row--bot sindesa-chat-pop';

        var avatar = document.createElement('div');
        avatar.className = 'sindesa-chat-avatar sindesa-chat-avatar--bot';
        avatar.innerHTML = '<i class="fas fa-robot"></i>';

        var bubble = document.createElement('div');
        bubble.className = 'sindesa-chat-bubble sindesa-chat-bubble--bot';
        bubble.innerHTML = replyHTML;

        row.appendChild(avatar);
        row.appendChild(bubble);
        m.appendChild(row);

        if (suggestions && suggestions.length > 0) {
            var suggDiv = document.createElement('div');
            suggDiv.className = 'sindesa-chat-suggestions sindesa-chat-pop';
            
            suggestions.forEach(function(sugg) {
                var btn = document.createElement('button');
                btn.type = 'button';
                btn.setAttribute('data-message', sugg.message);
                btn.textContent = sugg.label;
                btn.onclick = function(e) {
                    if (e) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                    window.sindesaSendSuggestion(this);
                };
                suggDiv.appendChild(btn);
            });
            
            m.appendChild(suggDiv);
        }

        scrollToBottom();
    }

    window.sindesaResetChat = function() {
        var m = document.getElementById('sindesa-chat-messages');
        if (!m) return;
        m.innerHTML = '<div class="sindesa-chat-row sindesa-chat-row--bot sindesa-chat-pop">'
                    + '    <div class="sindesa-chat-avatar sindesa-chat-avatar--bot" aria-hidden="true">'
                    + '        <i class="fas fa-robot"></i>'
                    + '    </div>'
                    + '    <div class="sindesa-chat-bubble sindesa-chat-bubble--bot">'
                    + '        Selamat datang di Asisten Layanan Publik SINDESA Desa Buttu Sawe. Saya dapat membantu mencari informasi persyaratan, prosedur, status, kendala, durasi, jadwal, lokasi, pembaruan, dan pembayaran layanan berdasarkan dokumen, layanan, instansi, serta wilayah yang Anda sebutkan.<br><br>'
                    + '        Silakan tulis kebutuhan Anda secara spesifik. Contoh: “Apa syarat mengurus KTP-el di Pinrang?”'
                    + '    </div>'
                    + '</div>'
                    + '<div id="sindesa-chat-suggestions" class="sindesa-chat-suggestions">'
                    + '    <button type="button" data-message="Apa syarat mengurus KTP-el di Pinrang?" onclick="event.preventDefault(); event.stopPropagation(); window.sindesaSendSuggestion(this);">Syarat KTP-el</button>'
                    + '    <button type="button" data-message="Bagaimana cara membayar PBB di Pinrang?" onclick="event.preventDefault(); event.stopPropagation(); window.sindesaSendSuggestion(this);">Pembayaran PBB</button>'
                    + '    <button type="button" data-message="Di mana lokasi Samsat Pinrang?" onclick="event.preventDefault(); event.stopPropagation(); window.sindesaSendSuggestion(this);">Lokasi Samsat</button>'
                    + '</div>';
        window.sindesaIsSending = false;
        globalLastMessage = '';
        globalLastSendTime = 0;
        var chatInput = document.getElementById('sindesa-chat-input');
        if (chatInput) {
            chatInput.value = '';
            chatInput.disabled = false;
            chatInput.focus();
        }
        var chatSend = document.getElementById('sindesa-chat-send');
        if (chatSend) chatSend.disabled = false;
        scrollToBottom();
    };

    window.sindesaTriggerMessage = function(text) {
        if (!text) return;
        var cleanText = String(text).trim();
        if (!cleanText) return;

        var now = Date.now();
        if (window.sindesaIsSending || (cleanText === globalLastMessage && (now - globalLastSendTime) < 3000)) {
            return;
        }

        window.sindesaIsSending = true;
        globalLastMessage = cleanText;
        globalLastSendTime = now;

        var chatShell = document.getElementById('sindesa-chat-shell');
        var chatInput = document.getElementById('sindesa-chat-input');
        var chatSend = document.getElementById('sindesa-chat-send');

        if (chatInput) {
            chatInput.value = '';
            chatInput.style.height = 'auto';
            chatInput.disabled = true;
        }
        if (chatSend) chatSend.disabled = true;

        document.querySelectorAll('.sindesa-chat-suggestions').forEach(function(el) {
            el.remove();
        });

        appendUserMessage(cleanText);

        var endpoint = chatShell ? chatShell.getAttribute('data-endpoint') : '/api/chatbot/message';
        var csrfToken = chatShell ? chatShell.getAttribute('data-csrf') : '';

        var typingIndicator = showTypingIndicator();
        var delayPromise = new Promise(function(resolve) { setTimeout(resolve, 500); });

        var fetchPromise = fetch(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ message: cleanText })
        }).then(function(res) {
            if (!res.ok) throw new Error('Response error');
            return res.json();
        });

        Promise.all([fetchPromise, delayPromise])
            .then(function(results) {
                var data = results[0];
                if (typingIndicator) typingIndicator.remove();
                window.sindesaIsSending = false;
                
                if (chatInput) {
                    chatInput.disabled = false;
                    setTimeout(function() { chatInput.focus(); }, 100);
                }
                if (chatSend) chatSend.disabled = false;

                appendBotMessage(data.reply, data.suggestions || []);
            })
            .catch(function(err) {
                console.error('Chatbot error:', err);
                if (typingIndicator) typingIndicator.remove();
                window.sindesaIsSending = false;
                
                if (chatInput) {
                    chatInput.disabled = false;
                    setTimeout(function() { chatInput.focus(); }, 100);
                }
                if (chatSend) chatSend.disabled = false;

                appendBotMessage('Maaf, asisten kami sedang mengalami gangguan koneksi ke server. Silakan coba kirim ulang pesan Anda beberapa saat lagi.', []);
            });
    };

    window.sindesaSendSuggestion = function(btn) {
        if (!btn || btn.dataset.sindesaClicked === 'true' || window.sindesaIsSending) return;
        btn.dataset.sindesaClicked = 'true';
        btn.disabled = true;
        btn.onclick = null;
        var msg = btn.getAttribute('data-message');
        var parent = btn.closest('.sindesa-chat-suggestions');
        if (parent) {
            parent.style.display = 'none';
            parent.remove();
        }
        if (msg) {
            window.sindesaTriggerMessage(msg);
        }
    };

    function initEvents() {
        var chatInput = document.getElementById('sindesa-chat-input');
        var chatSend = document.getElementById('sindesa-chat-send');

        if (chatInput) {
            chatInput.oninput = function() {
                chatInput.style.height = 'auto';
                chatInput.style.height = Math.min(chatInput.scrollHeight, 80) + 'px';
            };

            chatInput.onkeydown = function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    e.stopPropagation();
                    var val = chatInput.value;
                    if (val && val.trim() && !window.sindesaIsSending) {
                        window.sindesaTriggerMessage(val);
                    }
                }
            };
        }

        if (chatSend) {
            chatSend.onclick = function(e) {
                if (e) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                if (chatInput) {
                    var val = chatInput.value;
                    if (val && val.trim() && !window.sindesaIsSending) {
                        window.sindesaTriggerMessage(val);
                    }
                }
            };
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initEvents, { once: true });
    } else {
        initEvents();
    }
})();
</script>
