# Panduan Keamanan Aplikasi Mobile & Backend API (SINDESA Security Guidelines)

Dokumen ini berisi panduan dan standar keamanan untuk integrasi **Aplikasi Mobile (Android/iOS / Flutter)** dan **Frontend API Client** dengan sistem backend **SINDESA**.

---

## 1. Prinsip Utamakan Pertahanan Sisi Server (Backend Defense in Depth)

> **PENTING:** Proteksi di sisi client (seperti *SSL Pinning*, *Root Detection*, *Emulator Detection*, dan *App Tampering Detection*) dapat di-bypass oleh pengguna atau penyerang yang memiliki akses fisik ke perangkat menggunakan instrumen *reverse engineering* (misalnya: **Frida**, **Objection**, **Magisk / LSPosed**, atau **SSLUnpinning**).

Oleh karena itu:
- **Jangan pernah mengandalkan proteksi client-side sebagai satu-satunya sistem keamanan.**
- Setiap endpoint di backend API **wajib** melakukan verifikasi otentikasi (Token Bearer / Session Cookie) dan pengecekan otorisasi (*Authorization check*) untuk memastikan bahwa pengguna hanya dapat melihat dan memperbarui data miliknya sendiri (`user_id` / NIK yang terikat pada token otentikasi valid).

---

## 2. Pengelolaan Data PII & Penamaan File Sensitif (KTP, PDF, Profil)

1. **Penggunaan UUIDv4 untuk Nama File**:
   - Seluruh file yang diunggah (foto KTP, KK, profil, dan dokumen pengajuan PDF) **wajib** menggunakan nama acak berbasis **UUIDv4** (`Str::uuid()`).
   - **Dilarang keras** menggunakan NIK, Nama, atau Timestamp acak yang mudah ditebak (misal: `PROFIL_1234567890123456_864.jpg`) pada nama file fisik.
2. **Kontrol Akses Dokumen**:
   - Akses dokumen warga (KTP & PDF) dilindungi oleh otorisasi backend. Aplikasi mobile tidak boleh langsung membuka URL storage publik tanpa menyertakan sesi otentikasi.

---

## 3. Whitelist Domain & Validasi Hostname (Pencegahan SSRF / Remote Asset Injection)

Pada komponen penampil gambar/PDF di aplikasi mobile (misalnya Webview atau Custom PDF Viewer):
1. **Validasi Hostname**: Aplikasi mobile wajib melakukan validasi URL sebelum melakukan download/render asset. Hanya URL yang berasal dari hostname resmi **SINDESA** (`sindesa-buttusawe.com` atau domain API resmi) yang diizinkan untuk dimuat.
2. **Pencegahan Remote Injection**: Jangan izinkan aplikasi mobile memuat URL gambar/PDF dari domain luar yang tidak dikenal untuk mencegah serangan *Server-Side Request Forgery (SSRF)* atau pemuatan konten berbahaya.

---

## 4. Keamanan Sesi & Pemisahan Perangkat (Single Device Session Isolation)

1. **Pencegahan Transfer Sesi Antar Perangkat**:
   - Token otentikasi (Bearer Token / Session Cookie) tidak boleh ditransfer secara manual antar perangkat.
   - Di sisi backend, saat pengguna melakukan login di perangkat baru, sangat disarankan untuk menerapkan fungsi *Single Session* (misalnya `Auth::logoutOtherDevices($password)`) untuk membatalkan sesi lama di perangkat terdahulu.
2. **Session Lifetime / Token TTL**:
   - Token otentikasi pengguna wajib memiliki *Time-To-Live (TTL)* / masa berlaku yang terbatas (misal max 60 menit *idle time*) demi memenuhi standar kepatuhan perlindungan data pribadi (PDP / PII Compliance).

---

## 5. Sanitasi Parameter & Penggunaan Identifier

1. **Dilarang Mengirim NIK pada GET Parameter**:
   - NIK merupakan data PII sensitif. Jangan pernah mengirimkan NIK dalam query string URL (`GET /api/warga?nik=12345`).
   - Gunakan ID internal numerik / UUID dalam parameter rute dan kirim data sensitif melalui `POST`/`PUT` body payload yang terenkripsi HTTPS.
2. **Primary Key Database**:
   - Primary Key sistem menggunakan tipe `id` (bigint auto-increment/UUID). NIK digunakan semata-mata sebagai atribut unik biodata.
