<div align="center">
  <img src="public/image/SINDESA_WHITE_TRANSPARNT.png" alt="SINDESA Logo" width="250" />
  <h1>Sinergi Layanan Digital Desa (SINDESA)</h1>
  <p><em>Platform Digitalisasi Pelayanan Administrasi Persuratan Desa Tingkat Lanjut</em></p>
  
  [![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
  [![PHP](https://img.shields.io/badge/PHP-8.3+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
  [![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
  [![TailwindCSS](https://img.shields.io/badge/Tailwind-CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
  [![COBIT 2019](https://img.shields.io/badge/Governance-COBIT_2019-005b9f?style=for-the-badge)](https://www.isaca.org/)
</div>

---

**SINDESA** adalah aplikasi berbasis web modern yang dirancang khusus untuk mempermudah digitalisasi pelayanan administrasi persuratan di tingkat Pemerintahan Desa. Mengubah birokrasi manual yang lambat menjadi pengalaman digital yang cepat, transparan, dan sangat efisien.

## 🌟 Mengapa Memilih SINDESA?

Sistem ini tidak hanya memfasilitasi pengajuan surat, tetapi juga dilengkapi **keamanan modern** dan **alur birokrasi yang cerdas**:
- 🏛️ **Enterprise IT Governance**: Dirancang dengan mematuhi kerangka kerja tata kelola TI **COBIT 2019** (Fokus pada Domain *APO* dan *BAI*) untuk memastikan keselarasan arsitektur Enterprise, manajemen risiko, dan pembangunan solusi sistem administrasi negara.
- 🚀 **Zero Paperwork**: Pengajuan dari genggaman ponsel warga, langsung diterima oleh perangkat desa.
- 🔐 **Keamanan Kriptografi**: Dilengkapi algoritma *Tanda Tangan Elektronik (TTE)* untuk mencegah pemalsuan tanda tangan.
- 📱 **User Experience Prima**: Antarmuka *mobile-first* yang mulus dan interaktif dengan *Micro-animations*.

## ✨ Fitur Unggulan

| Fitur | Deskripsi |
| --- | --- |
| 👥 **Multi-Role Access** | Tersedia panel khusus yang terisolasi untuk **Admin**, **Kades**, **Operator**, dan **Warga**. |
| 📄 **15 Jenis Surat Dinamis** | Modul surat yang bisa diaktifkan/dinonaktifkan (Akta Lahir, KTP, KK, Kematian, dll). |
| ✍️ **Tanda Tangan Digital** | Pengesahan dokumen final (*PDF*) secara elektronik oleh Kepala Desa. |
| 🔍 **Validasi via QR Code** | Setiap surat memiliki QR Code unik penyambung ke halaman portal validasi publik. |
| 📊 **Laporan Terpadu** | Cetak rekapitulasi data pengajuan surat bulanan dalam format *PDF/Excel*. |
| 🖨️ **Master KOP Surat** | Fleksibilitas tinggi mengatur logo, alamat, dan nomor telepon langsung dari aplikasi. |

---

## 🏛️ Keselarasan dengan Domain COBIT 2019

Untuk memastikan bahwa pengembangan sistem SINDESA memenuhi standar tata kelola teknologi informasi yang terukur, berikut adalah matriks pemetaan antara praktik inti dalam domain COBIT 2019 (APO dan BAI) dengan fitur serta aktivitas teknis yang telah diimplementasikan dalam sistem:

| Domain & Proses COBIT | Aktivitas/Praktik Standar | Bukti Implementasi Riil pada Sistem SINDESA |
| --- | --- | --- |
| **APO01** *(Manage the IT Management Framework)* | Menetapkan struktur dan kerangka kerja pengelolaan TI. | **Penggunaan Framework Terstandar & RAD**: Sistem dibangun menggunakan *framework* Laravel 13 (Arsitektur MVC) dan **Filament PHP** untuk memastikan kode terstruktur dan *maintainable*. |
| **APO02** *(Manage Strategy)* | Menyelaraskan strategi TI dengan kebutuhan organisasi. | **Digitalisasi Layanan Desa**: Fitur pengajuan surat online selaras dengan tujuan SPBE untuk efisiensi layanan publik di Desa Buttu Sawe. |
| **APO03** *(Manage Enterprise Architecture)* | Menyediakan arsitektur data dan aplikasi yang terintegrasi. | **Arsitektur Terintegrasi & Master Data Dinamis**: Implementasi basis data relasional (ERD) terpusat dengan Manajemen KOP Surat dan Jenis Surat yang *customizable*. |
| **APO12** *(Manage Risk)* | Mengidentifikasi dan memitigasi risiko keamanan dan sistem. | **Fitur Keamanan & Validasi Tingkat Lanjut**: Proteksi rute via *Middleware*, pemisahan hak akses (Spatie), serta pengamanan dokumen menggunakan **Tanda Tangan Elektronik (TTE)** dan **QR Code**. |
| **BAI02** *(Manage Requirements Definition)* | Mendefinisikan kebutuhan fungsional berdasarkan masukan pengguna. | **Analisis Kebutuhan Terpenuhi**: Perumusan fitur kompleks (Registrasi mandiri, Pelacakan Riwayat) murni mengacu pada SOP manual pelayanan desa. |
| **BAI03** *(Manage Solutions Identification and Build)* | Membangun solusi sesuai spesifikasi kebutuhan. | **Realisasi Desain Teknis**: Pembangunan fitur *auto-generate* dokumen PDF secara presisi menggunakan `dompdf` dan *dashboard* analitik pelaporan. |
| **BAI07** *(Manage Change Acceptance and Transitioning)* | Memastikan solusi diterima oleh pengguna (*User Acceptance*). | **Pengujian UAT & Interaktivitas UI**: Sistem responsif (*Mobile-Friendly*) yang dilengkapi *SweetAlert/Toast* sehingga siap diuji melalui skenario lintas peran. |

---

## 🛠️ Stack Teknologi

- **Backend Architecture**: Laravel 13 (PHP 8.3+)
- **Database Engine**: MySQL / MariaDB 
- **Frontend Layer**: Vanilla CSS, Tailwind CSS, Vanilla JS
- **UI Components**: [SweetAlert2](https://sweetalert2.github.io/), FontAwesome 6
- **PDF Engine**: [barryvdh/laravel-dompdf](https://github.com/barryvdh/laravel-dompdf)
- **Asset Bundler**: Vite (Super Fast HMR)

---

## 💻 Panduan Instalasi (Local Development)

Ikuti panduan berikut untuk menjalankan proyek SINDESA di *localhost* Anda:

### Persyaratan Sistem
* PHP >= 8.2
* Composer
* Node.js & NPM
* MySQL / MariaDB

### Langkah-langkah
1. **Kloning Repositori**
   ```bash
   git clone https://github.com/USERNAME-GITHUB-ANDA/sindesa-web.git
   cd sindesa-web
   ```
2. **Install Dependensi**
   ```bash
   composer install
   npm install
   ```
3. **Konfigurasi Environment**
   ```bash
   cp .env.example .env
   ```
   *Atur koneksi `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` di dalam file `.env`.*
4. **Generate Key & Build Assets**
   ```bash
   php artisan key:generate
   npm run build
   ```
5. **Migrasi Database & Data Dummy**
   ```bash
   php artisan migrate:fresh --seed
   ```
   *(Sistem otomatis membuatkan akun percobaan untuk Admin, Kades, Operator, dan Warga)*
6. **Hubungkan Storage & Jalankan Server**
   ```bash
   php artisan storage:link
   php artisan serve
   ```
   Akses `http://localhost:8000` di *browser* Anda.

---

## 🤝 Kontribusi
Aplikasi ini dikembangkan untuk memajukan transformasi digital administrasi Desa. Kontribusi berupa laporan *bug*, perbaikan, maupun fitur baru sangat disambut melalui fitur *Pull Request*.

## 📄 Lisensi
Sinergi Layanan Digital Desa (SINDESA) ini dilindungi oleh hak cipta. © 2026 Pemerintah Desa Buttu Sawe. Hak Cipta Dilindungi Undang-Undang.
