# Product Requirement Document (PRD)

## Sistem Informasi Akademik & Website Sekolah SDN Demakijo 1
**Arsitektur: Native PHP MVC (Zero-Magic Architecture)**

---

## 📖 Latar Belakang & Filosofi Proyek

Proyek ini adalah hasil konversi penuh sistem informasi akademik sekolah dari **Laravel** ke **Native PHP MVC**. Keputusan arsitektur ini diambil untuk meminimalkan ketergantungan pada *framework heavy-weight*, mempermudah proses pemeliharaan jangka panjang oleh pengembang junior (*junior developer*), dan mempermudah pemahaman kode bagi model AI hemat biaya (seperti Gemini Flash) untuk meminimalkan konsumsi token dan biaya operasional.

### Karakteristik Utama Arsitektur:
1.  **Zero-Magic Code**: Semua alur eksekusi kode transparan, linier, dan dibaca langsung dari entry point (`public/index.php`) ke router (`routes/web.php`) lalu ke *Controller* bersangkutan tanpa memuat ratusan service provider internal.
2.  **Eloquent ORM Standalone**: Menggunakan paket `illuminate/database` (v11) agar model database tetap 100% sama dengan standard Laravel (termasuk fitur *Active Record*, *Eloquent Relationship*, dan Query Builder).
3.  **BladeOne Template Engine**: Menggunakan engine template BladeOne yang ringan sehingga sintaks `.blade.php` (`@extends`, `@section`, `@if`, `@foreach`, `@include`, `{!! !!}`, `{{ }}`) tetap berfungsi tanpa perubahan pada file view.
4.  **Hosting-Friendly**: Dapat berjalan langsung pada shared hosting murah dengan Apache tanpa memerlukan akses terminal SSH atau modifikasi konfigurasi Nginx yang kompleks.

---

## 👥 Aktor & Hak Akses Pengguna (User Roles)

Sistem ini memiliki dua aktor utama dengan otorisasi yang terpisah secara ketat:

### 1. Pengunjung Publik (Guest Visitor)
*   **Karakteristik**: Pengguna tanpa login (masyarakat umum, calon siswa, orang tua, alumni).
*   **Tujuan**: Mencari informasi sekolah, berita terbaru, agenda kegiatan, galeri, prestasi, serta mendaftar PPDB Online atau mendaftar database alumni.
*   **Keamanan**: Tidak dapat mengakses URL dengan prefix `/admin/` atau `/dashboard`. Jika mencoba masuk, akan secara otomatis dialihkan ke `/login`.

### 2. Administrator Sekolah (Authenticated Admin)
*   **Karakteristik**: Staf sekolah atau operator IT yang telah login menggunakan email dan password valid.
*   **Tujuan**: Mengelola konten dinamis website publik, memantau pendaftar PPDB, melakukan verifikasi akun alumni baru, serta melakukan konfigurasi metadata website.
*   **Keamanan**: Memerlukan sesi aktif (`Session::get('user_id')`). Dilindungi oleh proteksi token CSRF di setiap aksi modifikasi data (POST).

---

## 🏗️ Struktur Arsitektur Core Sistem (`app/Core/`)

Junior developer dan AI harus memahami cara kerja bootstrap framework ini sebelum memodifikasi fitur:

```
[public/index.php] (Entry Point)
       │
       ▼
[App\Core\App::boot()] ──► 1. Muat .env (vlucas/phpdotenv)
       │               ──► 2. Memulai Sesi (App\Core\Session)
       │               ──► 3. Koneksi database Eloquent (illuminate/database)
       │               ──► 4. Inisialisasi BladeOne engine (App\Core\View)
       ▼
[App\Core\Router::dispatch()] ──► Cocokkan URI request dengan route di routes/web.php
       │
       ▼
[App\Controllers\...] ──► Logika Bisnis & Pengambilan Model Database
       │
       ▼
[App\Core\View::render()] ──► Gabungkan data ke Blade Template -> Kirim HTML ke Browser
```

---

## 🌐 Fitur & Halaman Publik (Frontend)

Semua halaman publik diakses tanpa login dan dirancang responsif menggunakan CSS modern.

### 1. Beranda / Welcome Page (`/`)
*   **Deskripsi**: Wajah utama website sekolah.
*   **Komponen & Konten**:
    *   *Hero Carousel*: Menampilkan slider foto sekolah dengan caption teks dinamis (dikelola admin).
    *   *Keunggulan Sekolah*: Grid 3-4 pilar utama sekolah dengan ikon dan deskripsi menarik.
    *   *Statistik Cepat*: Jumlah Guru, Siswa Aktif, dan Alumni terverifikasi.
    *   *Berita Terbaru*: Menampilkan 3 artikel berita terpopuler yang baru dirilis.
    *   *Sambutan Kepala Sekolah*: Narasi singkat dengan foto kepala sekolah.

### 2. Halaman Profil Sekolah (`/profil`)
*   **Deskripsi**: Informasi dasar sekolah.
*   **Konten**: Sambutan Kepala Sekolah, Visi, Misi, Tujuan Sekolah, dan Identitas Dasar.

### 3. Identitas Sekolah (`/identitas-sekolah`)
*   **Deskripsi**: Detail legalitas sekolah.
*   **Konten**: NPSN, NSS, Status Akreditasi, Alamat Lengkap, Koordinat Peta, Email, dan Nomor Telepon.

### 4. Sejarah Sekolah (`/sejarah`)
*   **Deskripsi**: Artikel naratif sejarah berdirinya sekolah.

### 5. Akreditasi Sekolah (`/akreditasi-sekolah`)
*   **Deskripsi**: Status akreditasi resmi sekolah lengkap dengan nilai akhir dan tahun penetapan.

### 6. Sarana & Prasarana (`/sarana-prasarana`)
*   **Deskripsi**: Menampilkan galeri inventaris fasilitas sekolah.
*   **Konten**: Grid daftar fasilitas (ruang kelas, laboratorium, lapangan olahraga, perpustakaan) beserta foto dan kondisinya.

### 7. Ekstrakurikuler (`/ekstrakurikuler`)
*   **Deskripsi**: Daftar program pengembangan diri siswa.
*   **Konten**: Menampilkan foto, nama program, pembina, dan jadwal kegiatan ekstrakurikuler.

### 8. Berita & Artikel (`/berita`)
*   **Deskripsi**: Ruang publikasi informasi terkini sekolah.
*   **Konten**: Grid artikel dengan pagination. Menampilkan gambar sampul (*thumbnail*), judul berita, kategori, tanggal terbit, dan cuplikan konten singkat.

### 9. Detail Berita (`/berita/{slug}`)
*   **Deskripsi**: Halaman membaca artikel berita secara utuh.
*   **Konten**: Gambar utama artikel, judul lengkap, kategori, tanggal terbit, isi artikel lengkap dalam format HTML, serta daftar 4 berita terkait di panel samping.

### 10. Prestasi Sekolah & Siswa (`/prestasi`)
*   **Deskripsi**: Etalase pencapaian prestasi akademik dan non-akademik.
*   **Konten**: Menampilkan nama prestasi, tingkat (kabupaten/provinsi/nasional), nama peraih, tahun, kategori, dan foto dokumentasi.

### 11. Agenda Kegiatan (`/agenda`)
*   **Deskripsi**: Kalender kegiatan sekolah mendatang.
*   **Konten**: Daftar agenda dengan detail tanggal pelaksanaan, waktu, lokasi, nama kegiatan, dan penanggung jawab.

### 12. Guru & Tenaga Kependidikan (`/guru-tendik`)
*   **Deskripsi**: Direktori staf pengajar sekolah.
*   **Konten**: Grid kartu profil guru yang menampilkan foto, nama lengkap, NIP/NUPTK, jabatan, dan status keaktifan.

### 13. Direktori Alumni (`/alumni`)
*   **Deskripsi**: Wadah penelusuran lulusan dan form pendaftaran ikatan alumni.
*   **Konten**: 
    *   *Daftar Alumni*: Tabel alumni terverifikasi yang bisa dicari berdasarkan nama atau tahun kelulusan.
    *   *Form Registrasi*: Form input nama, email, nomor HP, tahun lulus, pekerjaan saat ini, dan alamat untuk diverifikasi admin.

### 14. Komite Sekolah (`/komite-sekolah`)
*   **Deskripsi**: Informasi struktur organisasi dan kepengurusan komite sekolah.

### 15. Galeri Foto (`/galeri/foto`)
*   **Deskripsi**: Album foto visual kegiatan sekolah.
*   **Konten**: Menampilkan album-album foto (misal: "Kegiatan Pramuka", "Hari Kartini"). Klik pada album akan memunculkan grid foto dokumentasi di dalamnya.

### 16. Galeri Video (`/galeri/video`)
*   **Deskripsi**: Daftar video dokumentasi kegiatan sekolah.
*   **Konten**: Tautan/embed pemutar video dari YouTube.

### 17. Unduhan Pengumuman (`/unduhan`)
*   **Deskripsi**: Halaman unduh dokumen resmi.
*   **Konten**: Daftar berkas pengumuman/surat edaran yang dilengkapi tombol unduh berkas (`PDF/DOCX/ZIP`).

### 18. PPDB Online (`/ppdb-online`)
*   **Deskripsi**: Gerbang pendaftaran Peserta Didik Baru secara daring.
*   **Konten**:
    *   *Form Pendaftaran*: Formulir biodata calon siswa, data orang tua (ayah/ibu), alamat, asal sekolah TK, dan unggahan berkas persyaratan.
    *   *Halaman Cek Status*: Form pencarian status pendaftaran menggunakan Nomor Pendaftaran untuk memantau status Verifikasi/Diterima/Ditolak.

---

## 🔐 Fitur & Modul Backend Admin (Prefix `/admin/*`)

Semua modul admin memerlukan verifikasi sesi aktif `Auth::check()`. Jika sesi berakhir, pengguna langsung dilempar ke `/login`.

### 1. Dashboard (`/dashboard`)
*   **Rute**: `Router::get('/dashboard', 'Admin\DashboardController@index')`
*   **Deskripsi**: Panel pemantauan statistik utama.
*   **Konten**: 
    *   Widget hitung total: Siswa, Guru, Alumni, Pendaftar PPDB Baru.
    *   Log Aktivitas Terkini (tindakan administrator lain).
    *   Visualisasi data/grafik pendaftar PPDB per tahun.

### 2. CRUD Kategori Berita (`/admin/kategori-berita`)
*   **Deskripsi**: Mengelola kategori tulisan untuk berita.
*   **Fields**: Nama Kategori, Slug.

### 3. CRUD Kelola Berita (`/admin/berita`)
*   **Deskripsi**: Publikasi artikel website.
*   **Fields**: Judul, Slug, Kategori (Relasi), Konten (Rich Text Editor), Gambar Sampul (Upload), Status Publish (Draft/Publish).

### 4. CRUD Kelola Album (`/admin/album`)
*   **Deskripsi**: Pengelompokan galeri foto berdasarkan acara/kegiatan.
*   **Fields**: Nama Album, Deskripsi, Cover Album.

### 5. CRUD Kelola Foto Galeri (`/admin/galeri`)
*   **Deskripsi**: Mengunggah banyak foto ke dalam suatu album.
*   **Fields**: Album ID (Relasi), File Foto (Multiple Upload), Keterangan.

### 6. CRUD Pengumuman (`/admin/pengumuman`)
*   **Deskripsi**: Mengelola lembar pengumuman publik dan dokumen unduhan.
*   **Fields**: Judul, Isi Pengumuman, File Lampiran (Upload PDF/Docx), Status Aktif.

### 7. CRUD Agenda Kegiatan (`/admin/agenda`)
*   **Deskripsi**: Mengelola kalender akademik sekolah.
*   **Fields**: Nama Kegiatan, Deskripsi, Tanggal Mulai, Tanggal Selesai, Waktu, Tempat.

### 8. CRUD Data Guru (`/admin/guru`)
*   **Deskripsi**: Mengelola profil tenaga pengajar.
*   **Fields**: NIP, Nama Lengkap, Gelar, Jabatan, Jenis Kelamin, Foto (Upload).

### 9. CRUD Data Siswa (`/admin/siswa`)
*   **Deskripsi**: Mengelola basis data siswa aktif.
*   **Fields**: NIS/NISN, Nama, Kelas, Jenis Kelamin, Alamat.

### 10. CRUD Prestasi (`/admin/prestasi`)
*   **Deskripsi**: Mengelola rekaman prestasi sekolah.
*   **Fields**: Judul Prestasi, Nama Peraih, Tingkat, Kategori, Tahun, Foto Dokumentasi.

### 11. CRUD Fasilitas (`/admin/fasilitas`)
*   **Deskripsi**: Inventaris sarana prasarana sekolah.
*   **Fields**: Nama Fasilitas, Jumlah, Kondisi (Baik/Rusak), Foto.

### 12. CRUD Ekstrakurikuler (`/admin/ekstrakurikuler`)
*   **Deskripsi**: Mengelola ekstrakurikuler aktif.
*   **Fields**: Nama Ekstra, Pembina, Hari Latihan, Waktu, Foto Kegiatan.

### 13. Manajemen PPDB (`/admin/ppdb`)
*   **Deskripsi**: Memantau dan melakukan aksi validasi pendaftaran siswa baru.
*   **Fitur**: 
    *   Melihat daftar pendaftar PPDB secara detail.
    *   Mengubah status pendaftaran: `Pending` -> `Diverifikasi` / `Diterima` / `Ditolak`.
    *   Mengunduh berkas lampiran persyaratan pendaftar.

### 14. CRUD Komite Sekolah (`/admin/komite`)
*   **Deskripsi**: Mengelola susunan komite sekolah.
*   **Fields**: Nama, Jabatan, Urutan Tampilan, Foto.

### 15. Verifikasi & CRUD Alumni (`/admin/alumni`)
*   **Deskripsi**: Mengelola database alumni dan melakukan verifikasi pendaftaran alumni baru dari frontend.
*   **Fitur**:
    *   Menampilkan pendaftar alumni yang berstatus `is_verified = false`.
    *   Melakukan verifikasi (mengubah `is_verified` menjadi `true`).

### 16. CRUD Manajemen User (`/admin/user`)
*   **Deskripsi**: Mengelola akun administrator yang boleh masuk ke sistem admin backend.
*   **Fields**: Nama, Email, Password (di-hash menggunakan bcrypt), Role.

### 17. Pengaturan Website (`/admin/pengaturan`)
*   **Deskripsi**: Modifikasi elemen-elemen statis dan identitas website.
*   **Fields**: Nama Sekolah, Logo Sekolah (Upload), Email Kontak, Telepon, Alamat, Sambutan Kepala Sekolah, Foto Kepala Sekolah, Link Sosial Media.

### 18. Kirim Notifikasi (`/admin/notifikasi`)
*   **Deskripsi**: Mengirimkan notifikasi push/siaran informasi ke browser siswa/guru yang berlangganan.

---

## 🗄️ Struktur Basis Data & Eloquent Models (`app/Models/`)

Model memperluas `App\Core\Model` yang secara langsung merupakan turunan dari `Illuminate\Database\Eloquent\Model`.

| Nama Model | Nama Tabel | Deskripsi Data | Relasi Utama |
| :--- | :--- | :--- | :--- |
| `User` | `users` | Akun Administrator Backend | - |
| `Berita` | `beritas` | Artikel Berita Website | `belongsTo(KategoriBerita::class)` |
| `KategoriBerita`| `kategori_beritas` | Pengelompokan Kategori Berita | `hasMany(Berita::class)` |
| `Album` | `albums` | Album Galeri Foto | `hasMany(Galeri::class)` |
| `Galeri` | `galeris` | File Foto Galeri per Album | `belongsTo(Album::class)` |
| `Guru` | `gurus` | Basis data guru dan tendik | - |
| `Siswa` | `siswas` | Basis data siswa aktif | - |
| `Alumni` | `alumnis` | Basis data lulusan/alumni | - |
| `Ppdb` | `ppdbs` | Registrasi siswa baru online | - |
| `Agenda` | `agendas` | Agenda kegiatan akademik | - |
| `Fasilitas` | `fasilitas` | Sarana prasarana sekolah | - |
| `Ekstrakurikuler`| `ekstrakurikulers`| Program ekstrakurikuler aktif | - |
| `Prestasi` | `prestasis` | Rekam jejak pencapaian prestasi | - |
| `Komite` | `komites` | Struktur pengurus komite | - |
| `Pengumuman` | `pengumumans` | Dokumen pengumuman & unduhan | - |
| `KeunggulanSekolah`|`keunggulan_sekolahs`| Poin keunggulan di Beranda | - |
| `SettingWebsite`| `setting_websites`| Metadata & konfigurasi situs | - |
| `ActivityLog` | `activity_logs` | Catatan aktivitas admin | - |
| `VisitorLog` | `visitor_logs` | Statistik kunjungan web harian | - |

---

## 📝 Panduan Praktis Pengembangan untuk AI & Junior Developer

Untuk menambahkan fitur baru, ikuti pedoman sederhana berikut:

### 1. Menambahkan Route Baru
Buka berkas `routes/web.php` dan daftarkan URL:
```php
// Rute GET biasa dengan penanganan callback/closure
Router::get('/tentang-kami', function() {
    return view('publik.tentang');
});

// Rute mengarah ke Controller
Router::get('/admin/berita', 'Backend\BeritaController@index');
```

### 2. Menulis Controller Baru
Setiap Controller harus mewarisi kelas `App\Core\Controller`.
*   Akses input POST/GET secara aman via objek `App\Core\Request`.
*   Kirim respon view menggunakan method `$this->view('nama.view', $data)`.

Contoh Controller `app/Controllers/Backend/BeritaController.php`:
```php
<?php
namespace App\Controllers\Backend;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Berita;

class BeritaController extends Controller
{
    public function index()
    {
        $berita = Berita::latest()->get();
        return $this->view('backend.berita.index', compact('berita'));
    }
}
```

### 3. Validasi Form yang Aman
Selalu gunakan Validator bawaan untuk validasi input dan gunakan fungsi `csrf_field()` untuk mengamankan form dari serangan Cross-Site Request Forgery (CSRF).

```blade
<form method="POST" action="/admin/berita">
    {!! csrf_field() !!}
    <input type="text" name="judul" required>
    <button type="submit">Simpan</button>
</form>
```
