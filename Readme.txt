# Rencana: Finalisasi Konversi Native PHP MVC

## Latar Belakang
Konversi dari Laravel ke Native PHP MVC sudah mencapai ~80%. Namun masih ada beberapa isu sinkronisasi yang perlu diperbaiki sebelum PR final dapat dibuat.

## Masalah yang Ditemukan

### 1. Semua Backend Controller: Wrong Namespace
Semua 18 controller backend masih menggunakan namespace Laravel yang salah:
```php
// SALAH (Laravel)
use App\Http\Controllers\Controller;
// BENAR (Native MVC)
use App\Core\Controller;
```

### 2. Backend Controllers: File Upload `->store()` tidak tersedia
`$request->file('foto')->store(...)` adalah method milik Laravel `UploadedFile`. Di `Request` native, `file()` mengembalikan array `$_FILES` biasa — method `.store()` tidak ada. Perlu diganti dengan logika upload PHP native.

### 3. `Request->except()` tidak ada
Method `$request->except('_token')` tidak didefinisikan di `App\Core\Request`.

### 4. `Request->validate()` tidak ada
`UserController` memanggil `$request->validate([...])` yang belum diimplementasikan.

### 5. `Request->filled()` tidak ada
`UserController` memanggil `$request->filled('password')`.

### 6. `Illuminate\Support\Facades\Hash` tidak tersedia
Digunakan di `UserController` dan `Auth.php` (perlu dicek), padahal Standalone Eloquent tidak otomatis load Hash.

### 7. `Illuminate\Support\Str` digunakan langsung
Tersedia via `illuminate/support` tapi perlu dicek apakah sudah di-require.

## Rencana Perbaikan

### Fase A: Perbaiki `App\Core\Request`
Tambahkan method yang hilang:
- `except(string ...$keys)` — filter array POST
- `validate(array $rules)` — validasi sederhana (wrapper `Validator`)
- `filled($key)` — cek nilai tidak kosong

### Fase B: Perbaiki File Upload di `LaravelFacades.php`
Tambahkan mock `UploadedFile`-like object agar `$request->file('foto')->store('uploads', 'public')` bisa bekerja dengan memindahkan file ke `public/storage/uploads/`.

### Fase C: Perbaiki namespace di semua 18 Backend Controller
Ganti `App\Http\Controllers\Controller` → `App\Core\Controller`.

### Fase D: Tambahkan `Hash` alias ke `LaravelFacades.php`
Daftarkan `Illuminate\Hashing\BcryptHasher` sebagai `Hash` static class.

### Fase E: Perbaiki `Auth` di `App.php`
Sudah ada `class_alias`, tapi perlu pastikan Auth facade juga tersedia di namespace `App\Http\Controllers`.

### Fase F: Update `README.md`
Buat README komprehensif dalam Bahasa Indonesia yang menjelaskan:
- Arsitektur Native PHP MVC
- Semua fitur lengkap
- Cara instalasi di Shared Hosting (cPanel)
- Cara instalasi di VPS (Nginx)
- Panduan pengembangan untuk junior programmer/AI

### Fase G: Test via Browser
Test semua halaman frontend dan backend dengan browser agent.

### Fase H: Commit, Push & Buat PR GitHub

## Open Questions
> [!IMPORTANT]
> Apakah untuk file upload backend, path penyimpanan yang diinginkan adalah `public/storage/uploads/` (seperti Laravel symlink), atau langsung ke `public/uploads/`?

> [!NOTE]
> Semua controller saat ini belum ada pengecekan `Auth::check()` di masing-masing method. Apakah middleware auth cukup dari constructor `DashboardController`, atau setiap controller backend perlu auth check tersendiri?
