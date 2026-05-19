# SDN Demakijo 1 — Sistem Informasi Sekolah (Native PHP MVC)

> 🎓 **Sistem Informasi Akademik & Website Sekolah SDN Demakijo 1, Sleman, Yogyakarta**
> Dibangun dengan arsitektur **Native PHP MVC** yang ringan, transparan, dan mudah dipelihara oleh junior programmer maupun AI model berbiaya rendah.

[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D8.2-blue)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green)](LICENSE)
[![Status](https://img.shields.io/badge/Status-Production%20Ready-brightgreen)]()

---

## 📋 Daftar Isi

1. [Tentang Proyek](#-tentang-proyek)
2. [Fitur Lengkap](#-fitur-lengkap)
3. [Arsitektur Sistem](#-arsitektur-sistem)
4. [Cara Instalasi](#️-cara-instalasi)
   - [Lokal (Development)](#1-lokal-development)
   - [Shared Hosting / cPanel](#2-shared-hosting--cpanel)
   - [VPS dengan Nginx](#3-vps-dengan-nginx)
5. [Panduan Pengembangan](#-panduan-pengembangan)
6. [Panduan untuk AI / Junior Programmer](#-panduan-untuk-ai--junior-programmer)
7. [Struktur Direktori](#-struktur-direktori)
8. [Kontribusi](#-kontribusi)

---

## 🏫 Tentang Proyek

Repositori ini adalah hasil konversi penuh dari **Laravel** ke arsitektur **Native PHP MVC** untuk memudahkan maintenance jangka panjang. Keunggulan utamanya:

- ✅ **Zero Magic Code** — Semua alur eksekusi bisa dibaca langsung tanpa memahami Laravel internals
- ✅ **Standalone Eloquent ORM** — Model database tetap sama 100% seperti Laravel
- ✅ **BladeOne Template Engine** — Sintaks Blade (`@if`, `@foreach`, `@extends`) tetap berfungsi
- ✅ **Ringan** — Tidak memuat ribuan service provider, hanya apa yang dibutuhkan
- ✅ **Hosting-Friendly** — Bisa jalan di shared hosting cPanel tanpa konfigurasi khusus

---

## 🚀 Fitur Lengkap

### 🌐 Frontend Publik
| Halaman | URL | Keterangan |
|---------|-----|-----------|
| Beranda | `/` | Hero slider, keunggulan sekolah, berita terbaru, statistik |
| Profil Sekolah | `/profil` | Sambutan kepsek, visi, misi, identitas |
| Identitas Sekolah | `/identitas-sekolah` | Data lengkap sekolah |
| Sejarah Sekolah | `/sejarah` | Narasi sejarah berdirinya sekolah |
| Akreditasi | `/akreditasi-sekolah` | Sertifikat & nilai akreditasi |
| Berita & Artikel | `/berita` | Daftar berita yang dipublikasikan admin |
| Detail Berita | `/berita/{slug}` | Konten berita dengan berita terkait |
| Prestasi | `/prestasi` | Daftar prestasi siswa & sekolah |
| Agenda | `/agenda` | Kalender kegiatan sekolah |
| Guru & Tendik | `/guru-tendik` | Daftar dan profil guru |
| Sarana & Prasarana | `/sarana-prasarana` | Fasilitas sekolah |
| Ekstrakurikuler | `/ekstrakurikuler` | Program ekstrakulikuler |
| Galeri Foto | `/galeri/foto` | Album foto kegiatan |
| Galeri Video | `/galeri/video` | Tautan video kegiatan |
| Komite Sekolah | `/komite-sekolah` | Struktur komite |
| Alumni | `/alumni` | Daftar alumni & form pendaftaran |
| Pengumuman / Unduhan | `/unduhan` | File unduhan pengumuman |
| PPDB Online | `/ppdb-online` | Form pendaftaran peserta didik baru |

### 🔐 Backend Admin (Login Required)
| Menu | URL | Keterangan |
|------|-----|-----------|
| Dashboard | `/dashboard` | Statistik lengkap + tabel aktivitas terkini |
| Kelola Berita | `/admin/berita` | CRUD berita dengan upload gambar |
| Kategori Berita | `/admin/kategori-berita` | Manajemen kategori |
| Kelola Album | `/admin/album` | CRUD album galeri foto |
| Kelola Foto | `/admin/galeri` | Upload dan hapus foto per album |
| Pengumuman | `/admin/pengumuman` | CRUD pengumuman + lampiran file |
| Agenda | `/admin/agenda` | Jadwal kegiatan sekolah |
| Data Guru | `/admin/guru` | CRUD data guru & tendik |
| Data Siswa | `/admin/siswa` | CRUD data siswa aktif |
| Prestasi | `/admin/prestasi` | Input prestasi |
| Fasilitas | `/admin/fasilitas` | Manajemen sarana prasarana |
| Ekstrakurikuler | `/admin/ekstrakurikuler` | Program ekskul |
| Komite Sekolah | `/admin/komite` | Struktur komite |
| Alumni | `/admin/alumni` | Verifikasi alumni |
| PPDB | `/admin/ppdb` | Manajemen pendaftaran siswa baru |
| Keunggulan Sekolah | `/admin/keunggulan` | Konten keunggulan di beranda |
| Manajemen User | `/admin/user` | CRUD akun pengguna |
| Pengaturan Website | `/admin/pengaturan` | Logo, slider, info sekolah, kontak |

---

## 🏗️ Arsitektur Sistem

Aplikasi ini menggunakan pola **Model-View-Controller (MVC)** murni tanpa full-stack framework.

```
[Browser/Client]
       │
       ▼
[public/index.php]  ← Entry point tunggal
       │
       ▼
[App\Core\App::boot()]  ← Bootstrap: env, session, database, view engine
       │
       ▼
[App\Core\Router]  ← Pencocokan URI ke Controller@method
       │
       ▼
[App\Controllers\...]  ← Logika bisnis
       │         │
       ▼         ▼
[App\Models\...] [App\Core\View]  ← Eloquent ORM + BladeOne render
       │
       ▼
[MySQL Database]
```

### Komponen Core (`app/Core/`)

| File | Fungsi |
|------|--------|
| `App.php` | Bootstrap: load .env, session, database, view engine |
| `Router.php` | Routing native: `Router::get('/path', 'Controller@method')` |
| `Controller.php` | Base controller: `view()`, `json()`, `input()` |
| `Model.php` | Extends `Illuminate\Database\Eloquent\Model` |
| `View.php` | Wrapper BladeOne: `View::render('nama.view', $data)` |
| `Request.php` | HTTP request: `input()`, `hasFile()`, `file()->store()`, `validate()`, `filled()`, `except()` |
| `Security.php` | CSRF token generate & verify, XSS filter |
| `Session.php` | Session management: `set()`, `get()`, `setFlash()`, `getFlash()` |
| `Auth.php` | Login/logout/check: `Auth::attempt()`, `Auth::user()`, `Auth::check()` |
| `Validator.php` | Validasi input: `required`, `email`, `min:N`, `unique:tabel` |
| `LaravelFacades.php` | Kompatibilitas: `Storage`, `Cache`, `Log`, `DB`, `Hash`, `File` |

### Dependensi (`composer.json`)

```json
{
  "require": {
    "illuminate/database": "^11.0",
    "eftec/bladeone": "^4.9",
    "vlucas/phpdotenv": "^5.6"
  }
}
```

---

## ⚙️ Cara Instalasi

### 1. Lokal (Development)

**Kebutuhan sistem:**
- PHP >= 8.2
- Composer
- MySQL / MariaDB
- Ekstensi PHP: `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`

**Langkah-langkah:**

```bash
# 1. Clone repositori
git clone https://github.com/MediaComptech/demakijo1-konversi.git
cd demakijo1-konversi

# 2. Install dependensi
composer install

# 3. Konfigurasi environment
cp .env.example .env
# Edit .env sesuai database lokal Anda:
# DB_DATABASE=demakijo1
# DB_USERNAME=root
# DB_PASSWORD=
# APP_URL=http://localhost:8000

# 4. Import database (gunakan file dari repo Demakijo-1 asli)
# mysql -u root -p demakijo1 < database.sql

# 5. Jalankan server development
php -S localhost:8000 -t public

# 6. Buka di browser
# http://localhost:8000
```

**Akses admin default:**
- URL: `http://localhost:8000/login`
- Email: `admin@sdndemakijo1.sch.id`
- Password: `12345`

---

### 2. Shared Hosting / cPanel

Cara ini paling umum untuk hosting Indonesia (Niagahoster, IDCloudHost, Dewaweb, dll).

**Langkah-langkah:**

**A. Upload file:**
```
Struktur di server (contoh di bawah public_html):

/home/username/
├── demakijo1-konversi/        ← upload SEMUA file di sini (kecuali folder public/)
│   ├── app/
│   ├── config/
│   ├── routes/
│   ├── vendor/
│   ├── .env
│   └── ...
└── public_html/               ← upload ISI FOLDER public/ ke sini
    ├── index.php              ← edit path-nya (lihat poin B)
    ├── .htaccess
    ├── css/
    ├── js/
    └── storage/               ← buat folder ini, beri permission 775
```

**B. Edit `public_html/index.php`:**
```php
<?php
// Sesuaikan path ke folder project Anda
require __DIR__ . '/../demakijo1-konversi/vendor/autoload.php';
// ... sisa kode tetap sama
```

**C. Setup `.env`:**
```env
APP_URL=https://namadomain.com
DB_HOST=localhost
DB_DATABASE=username_namadb
DB_USERNAME=username_dbuser
DB_PASSWORD=passworddb
```

**D. Buat folder storage:**
```
Buat folder: public_html/storage/uploads/ (permission 775)
Buat folder: public_html/storage/logo/ (permission 775)
Buat folder: public_html/storage/slider/ (permission 775)
Buat folder: public_html/storage/dokumen/ (permission 775)
```

**E. Pastikan `.htaccess` ada di `public_html/`:**
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php?$1 [QSA,L]
</IfModule>
```

---

### 3. VPS dengan Nginx

**Konfigurasi Nginx (`/etc/nginx/sites-available/demakijo1`):**

```nginx
server {
    listen 80;
    server_name namadomain.com www.namadomain.com;
    root /var/www/demakijo1-konversi/public;

    index index.php index.html;

    # Routing semua request ke index.php
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP-FPM
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Keamanan: blokir akses ke file sensitif
    location ~ /\.(env|git|htaccess) {
        deny all;
    }

    # Cache static assets
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|woff|woff2)$ {
        expires 30d;
        add_header Cache-Control "public, no-transform";
    }

    # Upload limit
    client_max_body_size 10M;
}
```

**Aktifkan site:**
```bash
ln -s /etc/nginx/sites-available/demakijo1 /etc/nginx/sites-enabled/
nginx -t
systemctl reload nginx
```

**Permission folder:**
```bash
chown -R www-data:www-data /var/www/demakijo1-konversi
chmod -R 755 /var/www/demakijo1-konversi
chmod -R 775 /var/www/demakijo1-konversi/public/storage
```

---

## 💻 Panduan Pengembangan

### Menambah Route Baru

Buka `routes/web.php`:
```php
// Route GET sederhana
Router::get('/kontak', function () {
    return \App\Core\View::render('kontak');
});

// Route dengan Controller
Router::get('/kontak', 'KontakController@index');
Router::post('/kontak/kirim', 'KontakController@kirim');
```

### Menambah Controller Baru

Buat file `app/Controllers/KontakController.php`:
```php
<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;

class KontakController extends Controller
{
    public function index()
    {
        return $this->view('kontak', ['title' => 'Hubungi Kami']);
    }

    public function kirim(Request $request)
    {
        $nama  = $request->nama;
        $pesan = $request->pesan;
        // ... proses data ...
        redirect('/kontak')->with('success', 'Pesan terkirim!');
    }
}
```

### Menggunakan Model (Eloquent)

```php
// Ambil semua data
$berita = \App\Models\Berita::all();

// Dengan relasi
$berita = \App\Models\Berita::with('kategori')->where('is_published', true)->latest()->get();

// Buat data baru
\App\Models\Berita::create(['judul' => 'Judul Baru', 'konten' => '...']);

// Update
$model = \App\Models\Berita::find($id);
$model->update(['judul' => 'Judul Baru']);

// Hapus
\App\Models\Berita::find($id)->delete();
```

### Upload File

```php
// Di Controller:
if ($request->hasFile('foto')) {
    // Tersimpan di public/storage/uploads/namafile.jpg
    $path = $request->file('foto')->store('uploads', 'public');
    // $path = 'uploads/abc123.jpg'
}

// Di View (Blade):
<img src="{{ asset('storage/' . $guru->foto) }}">
```

### Proteksi CSRF

Wajib di setiap form POST:
```blade
<form method="POST" action="/admin/berita">
    {!! csrf_field() !!}
    <!-- field form lainnya -->
</form>
```

### Flash Message

```php
// Set pesan:
redirect('/admin/berita')->with('success', 'Data berhasil disimpan!');

// Di view (Blade):
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
```

---

## 🤖 Panduan untuk AI / Junior Programmer

> Panduan ini dirancang agar AI model (seperti GPT, Claude, Gemini) dan junior programmer bisa langsung produktif tanpa perlu memahami seluruh codebase.

### Cara Debugging Cepat

1. **Error 500 / Blank Page** → Cek `storage/logs/error.log` atau aktifkan error display di `.env`:
   ```env
   APP_DEBUG=true
   ```

2. **Error "Class not found"** → Jalankan: `composer dump-autoload`

3. **Error view tidak ditemukan** → Pastikan nama file view sesuai. `view('backend.berita.index')` → cari di `app/Views/backend/berita/index.blade.php`

4. **Session / CSRF error** → Pastikan `Session::start()` dipanggil di `App::boot()` dan form menggunakan `{!! csrf_field() !!}`

### Aturan Dasar (Jangan Dilanggar!)

| ✅ LAKUKAN | ❌ JANGAN |
|-----------|----------|
| `redirect('/admin/berita')` | `redirect('admin.berita.index')` |
| `use App\Core\Controller;` | `use App\Http\Controllers\Controller;` |
| `$request->file('foto')->store('uploads', 'public')` | Akses `$_FILES` langsung |
| `Auth::check()` di setiap constructor controller backend | Tidak ada pengecekan auth |
| `\App\Core\View::render('nama.view', $data)` atau `view('nama.view', $data)` | Memanggil Blade langsung |

### Alur Menambah Fitur CRUD Baru

1. **Buat Model** di `app/Models/NamaModel.php`
2. **Buat Controller** di `app/Controllers/Backend/NamaController.php`
3. **Tambah Route** di `routes/web.php`
4. **Buat Views** di `app/Views/backend/nama/` (index, create, edit)
5. **Test** via browser

### Cara Menambah Field ke Tabel

Gunakan file migrasi manual atau modifikasi langsung di phpMyAdmin / DBeaver. Kemudian tambahkan field ke array `$fillable` di Model.

---

## 📂 Struktur Direktori

```
demakijo1-konversi/
├── app/
│   ├── Controllers/
│   │   ├── Admin/            ← Dashboard controller
│   │   ├── Backend/          ← 18 CRUD controllers (admin)
│   │   └── AuthController.php, PpdbPublikController.php, ...
│   ├── Core/                 ← Framework inti (App, Router, Auth, dll)
│   ├── Helpers/              ← GlobalHelper.php (url, asset, redirect, dll)
│   ├── Models/               ← 22 Eloquent models
│   └── Views/
│       ├── auth/             ← Halaman login
│       ├── backend/          ← 18 modul backend CRUD
│       ├── layouts/          ← Layout admin & publik
│       └── publik/           ← 18+ halaman frontend publik
├── config/
│   ├── app.php               ← Konfigurasi aplikasi
│   └── database.php          ← Konfigurasi database
├── docs/
│   └── arsitektur.md         ← Dokumentasi teknis arsitektur
├── public/
│   ├── index.php             ← Entry point
│   ├── .htaccess             ← URL rewriting
│   ├── css/, js/, images/   ← Static assets
│   └── storage/              ← File upload (gambar, dokumen)
├── routes/
│   └── web.php               ← Semua definisi routing
├── storage/
│   └── cache/                ← Cache view BladeOne
├── vendor/                   ← Composer dependencies
├── .env                      ← Konfigurasi environment (tidak di-commit)
├── .env.example              ← Template konfigurasi
└── composer.json
```

---

## 🤝 Kontribusi

Jika menemukan bug atau ingin menambahkan fitur:

1. Buat **Issue** terlebih dahulu untuk mendiskusikan perubahan
2. Fork repositori
3. Buat branch baru: `git checkout -b feature/nama-fitur`
4. Commit perubahan: `git commit -m "feat: tambah fitur X"`
5. Push dan buat **Pull Request**

---

*Dikembangkan oleh [MediaComptech](https://github.com/MediaComptech) untuk SDN Demakijo 1, Sleman, Yogyakarta.*
*Blueprint arsitektur ini dirancang agar mudah dipelihara oleh siapa saja — baik developer berpengalaman, junior programmer, maupun AI model.*