# SkinDiag — Sistem Pakar Diagnosa Penyakit Kulit

Sistem pakar berbasis web untuk membantu identifikasi awal penyakit kulit menggunakan **Teorema Bayes**, dibangun dengan PHP native + MySQL.

---

## Arsitektur Sistem

- **Backend**: PHP native (tanpa framework), pola MVC sederhana per-folder fitur
- **Database**: MySQL, akses via PDO (prepared statements)
- **Frontend**: Bootstrap 5, Font Awesome, custom CSS (gradient biru bertema medis)
- **Autentikasi**: Session-based, dua jenis akun terpisah (`users` untuk pengguna umum, `admin` untuk pengelola sistem)
- **Metode Diagnosa**: Teorema Bayes (Naive Bayes) — lihat detail di bagian "Logika Perhitungan Bayes"
- **Export PDF**: Dompdf (via Composer)
- **Grafik Dashboard**: Chart.js (CDN)

## Struktur Folder
dp-sistem/

├── config/

│   └── database.php          → konfigurasi koneksi PDO ke MySQL

├── includes/

│   ├── header.php / footer.php           → layout halaman publik (landing)

│   ├── admin_header.php / admin_footer.php → layout dashboard admin

│   ├── user_header.php / user_footer.php   → layout dashboard user

│   ├── auth_check.php          → middleware proteksi halaman user

│   ├── admin_auth_check.php    → middleware proteksi halaman admin

│   └── functions.php           → helper umum (redirect, flash message, sanitize, dst)

├── assets/

│   ├── css/ (style.css, auth.css, admin.css)

│   └── js/, img/

├── auth/

│   ├── login.php, register.php, logout.php

├── admin/

│   ├── dashboard.php

│   ├── gejala/        → CRUD data gejala

│   ├── penyakit/       → CRUD data penyakit & solusi

│   ├── rule/           → atur relasi gejala-penyakit (bobot Bayes)

│   ├── riwayat/        → riwayat diagnosa seluruh pengguna

│   └── akun_admin/     → kelola akun admin

├── user/

│   ├── dashboard.php

│   ├── diagnosa.php        → form pilih gejala

│   ├── hasil_diagnosa.php  → hasil hitung Bayes

│   ├── riwayat.php

│   ├── cetak_pdf.php       → export PDF hasil diagnosa

│   └── reset_session.php

├── libs/

│   └── bayes_calculator.php  → class inti perhitungan Teorema Bayes

├── vendor/        → dependency Composer (Dompdf)

├── database/

│   └── dp_sistem.sql

├── index.php      → landing page

├── 404.php

└── .htaccess

## Cara Setup dari Nol

1. **Clone/copy proyek** ke folder web server lokal Anda:
```bash
   git clone <url-repo> dp-sistem
```
   Letakkan di `htdocs` (XAMPP) atau `www` (Laragon).

2. **Buat database** baru bernama `dp_sistem` di phpMyAdmin/MySQL, lalu impor file `database/dp_sistem.sql`.

3. **Konfigurasi koneksi database** — edit `config/database.php`, sesuaikan:
```php
   $host = "localhost";
   $db_name = "dp_sistem";
   $username = "root";
   $password = "";
```

4. **Install dependency Composer** (untuk fitur export PDF):
```bash
   composer require dompdf/dompdf
```

5. **Generate hash password admin default** (jika seeder bawaan tidak cocok di environment Anda) — jalankan sekali via browser:
```php
   <?php echo password_hash('admin123', PASSWORD_BCRYPT); ?>
```
   Lalu update manual ke kolom `password` tabel `admin` via phpMyAdmin.

6. **Akses aplikasi** via browser:
http://localhost/dp-sistem/index.php

## Environment / Kebutuhan Server

- PHP >= 7.4 (disarankan 8.x)
- Ekstensi PHP aktif: `pdo_mysql`, `mbstring`, `gd` (untuk Dompdf)
- MySQL/MariaDB
- Composer (untuk Dompdf)
- Apache dengan `mod_rewrite` aktif (untuk `.htaccess` halaman 404)

## Akun Default (Seeder)

| Role  | Username/Email | Password   |
|-------|-----------------|------------|
| Admin | `admin`         | `admin123` |

> User dibuat sendiri melalui halaman Register — tidak ada seeder user default.

## Daftar Fitur yang Sudah Dibangun

| Sesi | Branch | Fitur |
|------|--------|-------|
| 1 | `feature/setup-awal` | Setup proyek, skema database, landing page |
| 2 | `feature/autentikasi` | Register & login user, login admin, session, middleware |
| 3 | `feature/admin-gejala` | CRUD Data Gejala + layout dashboard admin |
| 4 | `feature/admin-penyakit` | CRUD Data Penyakit & Solusi |
| 5 | `feature/admin-rule` | Atur relasi gejala-penyakit (bobot probabilitas Bayes) |
| 6 | `feature/engine-diagnosa` | Form diagnosa user + engine perhitungan Teorema Bayes |
| 7 | `feature/riwayat-pdf` | Riwayat diagnosa (user & admin) + export PDF |
| 8 | `feature/dashboard-grafik` | Grafik statistik dashboard admin + kelola akun admin |
| 9 | `feature/polish-ui` | Overlay sidebar mobile, loading state, empty state, halaman 404 |
| 10 | `docs/dokumentasi-penutup` | Dokumentasi developer |

## Logika Perhitungan Bayes

Lihat detail lengkap di `libs/bayes_calculator.php`. Singkatnya:

1. Admin menginput nilai **P(gejala\|penyakit)** secara manual di menu Data Relasi/Rule (berdasarkan literatur/pengetahuan pakar), dalam bentuk persentase 1-100%.
2. Saat user memilih beberapa gejala, sistem mengambil semua rule yang relevan per penyakit, lalu **mengalikan** seluruh nilai P(gejala\|penyakit) yang cocok (pendekatan Naive Bayes — asumsi gejala saling independen).
3. Prior P(penyakit) diasumsikan **sama rata** untuk semua penyakit (`1 / total_penyakit`), karena tidak ada data prevalensi epidemiologis yang tersedia.
4. Skor mentah tiap penyakit dinormalisasi (dibagi total skor semua penyakit) agar totalnya 100%.
5. Penyakit dengan persentase tertinggi ditampilkan sebagai hasil utama; 3 kemungkinan lain (jika ada) ditampilkan sebagai referensi tambahan.

## Catatan Teknis Penting

- **Keamanan password**: semua password (user & admin) di-hash dengan `password_hash()` (bcrypt) — tidak pernah disimpan plain text.
- **Proteksi akses**: setiap halaman di folder `admin/` dan `user/` wajib me-require middleware (`admin_auth_check.php` / `auth_check.php`) di baris paling atas.
- **Validasi data**: semua input user disanitasi dengan fungsi `sanitize()` (strip_tags + htmlspecialchars) sebelum disimpan, dan seluruh query database menggunakan **prepared statements** (PDO) untuk mencegah SQL Injection.
- **Akurasi hasil diagnosa** sangat bergantung pada kualitas data di menu **Data Relasi/Rule** — semakin akurat nilai probabilitas yang diinput admin (idealnya berdasarkan referensi medis/jurnal), semakin akurat pula hasil diagnosa sistem.
- **Keterbatasan sistem**: hasil diagnosa bersifat estimasi/skrining awal dan **bukan pengganti diagnosa medis profesional** — disclaimer ini sudah ditampilkan di halaman hasil diagnosa.
- **Pengembangan lanjutan yang bisa ditambahkan** (di luar scope tugas saat ini): upload foto kulit untuk analisis visual, fitur konsultasi langsung dengan dokter, multi-level Bayes dengan data prevalensi riil, dan notifikasi email hasil diagnosa.