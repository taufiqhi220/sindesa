# 🏡 SINDESA (Sistem Informasi Desa)

**SINDESA** adalah aplikasi berbasis web modern yang dirancang khusus untuk mempermudah digitalisasi pelayanan administrasi persuratan di tingkat Pemerintahan Desa. 

Sistem ini menjembatani kebutuhan administrasi Warga dengan perangkat desa (Operator & Kepala Desa) secara cepat, transparan, dan dapat dilacak, serta dilengkapi dengan fitur keamanan **Tanda Tangan Elektronik (TTE)** dan **Validasi Dokumen via QR Code**.

---

## ✨ Fitur Unggulan

- 👥 **Multi-Role Access**: Sistem memisahkan hak akses antara **Admin**, **Kepala Desa**, **Operator**, dan **Warga** demi menjaga integritas data dan privasi.
- 📄 **15 Jenis Surat Dinamis**: Mendukung berbagai jenis surat keterangan (Akta Lahir, KTP, KK, Kematian, Pindah, Usaha, SKCK, SKTM, dll) yang dapat diaktifkan/dinonaktifkan oleh Admin.
- ✍️ **Tanda Tangan Elektronik (TTE)**: Dokumen final (*PDF*) ditandatangani secara digital oleh Kepala Desa dengan dukungan stempel basah elektronik.
- 🔍 **Validasi Dokumen (QR Code)**: Setiap surat memiliki QR Code unik yang terhubung ke halaman *Dokumen Valid* guna mencegah pemalsuan administrasi.
- 📊 **Dashboard & Laporan**: Statistik pengajuan surat masuk, diproses, selesai, dan ditolak. Dilengkapi kemampuan cetak laporan bulanan (*PDF/Excel*).
- 🖨️ **Master KOP Surat**: KOP Surat dapat disesuaikan langsung dari panel Admin, termasuk logo desa, alamat, dan nomor telepon.
- 📱 **Mobile Responsive**: Tampilan dirancang khusus agar ramah diakses melalui layar sentuh *smartphone* masyarakat desa.

---

## 🛠️ Teknologi yang Digunakan

- **Framework PHP**: [Laravel](https://laravel.com/) (versi terbaru)
- **Database**: MySQL / MariaDB
- **Frontend**: HTML5, Vanilla CSS / Tailwind CSS, JavaScript, [SweetAlert2](https://sweetalert2.github.io/)
- **PDF Generator**: [barryvdh/laravel-dompdf](https://github.com/barryvdh/laravel-dompdf)
- **Data Wilayah**: [laravolt/indonesia](https://github.com/laravolt/indonesia) (Database Regional)
- **Assets Bundler**: Vite

---

## 💻 Panduan Instalasi Lokal (Development)

Untuk menjalankan SINDESA di komputer lokal (Laptop/PC), pastikan Anda telah menginstal **PHP (min. 8.2)**, **Composer**, **Node.js**, dan Server Database (seperti **XAMPP / Laragon**).

1. **Clone Repository**
   ```bash
   git clone https://github.com/USERNAME-GITHUB-ANDA/sindesa-web.git
   cd sindesa-web
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment (.env)**
   Copy file `.env.example` menjadi `.env`.
   ```bash
   cp .env.example .env
   ```
   Buka file `.env` dan sesuaikan koneksi database lokal Anda:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sindesa_local
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Generate Application Key & Build Assets**
   ```bash
   php artisan key:generate
   npm run build
   ```

5. **Migrasi Database & Seeding (Penting!)**
   Jalankan perintah ini untuk membangun tabel database dan mengisinya dengan data uji coba (Dummy).
   ```bash
   php artisan migrate:fresh --seed
   ```
   *(Perintah ini akan mencetak akun Admin, Kades, Operator, dan Warga khusus untuk keperluan testing)*.

6. **Tautkan Storage & Jalankan Server**
   ```bash
   php artisan storage:link
   php artisan serve
   ```
   Buka browser dan akses: `http://localhost:8000`

---

## 🚀 Panduan Persiapan Hosting (Production)

Saat SINDESA siap diluncurkan ke server (cPanel/VPS), Anda tidak perlu menggunakan akun *dummy*. Sistem ini sudah dibekali *script* otomatis untuk membersihkan sampah *testing* dan menyiapkan *database* murni.

Di komputer lokal Anda, sebelum di-ZIP dan di-*upload* ke hosting, jalankan:
```bash
php artisan app:prepare-hosting
```
*Script ini akan:*
1. Menghapus semua file sampah/unggah KTP uji coba.
2. Membersihkan riwayat transaksi & log uji coba.
3. Menghapus seluruh akun *Kades*, *Operator*, dan *Warga* uji coba.
4. **Hanya menyisakan 1 Akun Admin Asli**, Konfigurasi Jenis Surat, Konfigurasi Kop Surat, dan Data Wilayah Indonesia.

Setelah di-Hosting dan *Database* tersambung, jalankan perintah ini di terminal server Anda untuk menginisialisasi sistem *Production*:
```bash
php artisan migrate --force
php artisan db:seed --class=ProductionSeeder
```

**Informasi Login Admin (Production):**
- Email: `admin@desabuttusawe.id`
- Password: `Admin@Sindesa2026`
*(Wajib diganti setelah berhasil masuk)*

---

## 📄 Lisensi & Hak Cipta
Dikembangkan secara khusus untuk digitalisasi pelayanan administrasi desa.
© 2026 Hak Cipta Dilindungi.
