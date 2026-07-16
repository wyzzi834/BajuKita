# BajuKita

Aplikasi toko baju berbasis Laravel untuk katalog produk, checkout user, dan pengelolaan pesanan oleh operator.

## Fitur

- Katalog produk publik
- Login user, admin, dan operator
- Checkout produk oleh user
- Status pesanan menunggu konfirmasi operator
- Operator dapat mengubah status dan menghapus pesanan
- Admin dapat mengelola produk dan user

## Instalasi

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Sesuaikan konfigurasi database, admin, dan operator di file `.env`.

## Jawaban Pertanyaan: Perbedaan Login Google dan Login Manual

**Pertanyaan:**  
Script yang membedakan antara login menggunakan akun Google dengan username/email dan password biasa.

**Penanya:**  
1. M Agung Prasetyo - 241080200121  
2. Diqi Alfas Salam - 241080200114

Pada project BajuKita, proses login dibedakan menjadi dua jenis, yaitu login manual menggunakan email dan password, serta login menggunakan akun Google. Keduanya sama-sama menghasilkan session login Laravel, tetapi alur pemeriksaan datanya berbeda.

### 1. Login Manual Email dan Password

Login manual menggunakan form login biasa. User memasukkan email dan password, lalu Laravel akan mencocokkan data tersebut dengan data user yang tersimpan di tabel `users`.

File yang berkaitan dengan login manual:

```text
resources/views/auth/login.blade.php
app/Http/Controllers/Auth/LoginController.php
routes/web.php
```

Penjelasan script:

- `resources/views/auth/login.blade.php` berisi form login manual dengan input `email`, `password`, checkbox `remember`, dan tombol `Log in`.
- Form tersebut mengirim data ke `route('login')` menggunakan method `POST`.
- `routes/web.php` memanggil `Auth::routes();`, sehingga Laravel otomatis menyediakan route login standar, termasuk route `POST /login`.
- `app/Http/Controllers/Auth/LoginController.php` menggunakan trait `AuthenticatesUsers`. Trait ini menjalankan proses autentikasi standar Laravel, yaitu mengecek email dan password user.
- Jika login berhasil, method `redirectTo()` mengarahkan user sesuai role:
  - `admin` ke `/admin`
  - `operator` ke `/operator/orderan`
  - user biasa ke `/home`

Script utama yang membedakan login manual ada pada form berikut:

```php
<form action="{{ route('login') }}" method="POST">
    @csrf
    <input type="email" name="email">
    <input type="password" name="password">
    <button>Log in</button>
</form>
```

### 2. Login Menggunakan Akun Google

Login Google tidak mencocokkan password yang diketik user pada form. User diarahkan ke halaman autentikasi Google, lalu Google mengembalikan data akun seperti email, nama, dan ID Google ke aplikasi.

File yang berkaitan dengan login Google:

```text
resources/views/auth/login.blade.php
app/Http/Controllers/Auth/GoogleController.php
routes/web.php
config/services.php
app/Models/User.php
database/migrations/2026_05_15_000001_add_google_id_to_users_table.php
```

Penjelasan script:

- `resources/views/auth/login.blade.php` menampilkan tombol atau link login Google yang mengarah ke `route('google.redirect')`.
- `routes/web.php` mendefinisikan route khusus untuk login Google:
  - `/auth/google` untuk mengarahkan user ke Google.
  - `/auth/google/callback` untuk menerima hasil autentikasi dari Google.
- `app/Http/Controllers/Auth/GoogleController.php` menangani proses redirect ke Google dan callback setelah user berhasil login di Google.
- `config/services.php` menyimpan konfigurasi Google OAuth seperti `client_id`, `client_secret`, dan `redirect`.
- `app/Models/User.php` menyertakan field `google_id` agar akun Google dapat disimpan di data user.
- Migration `2026_05_15_000001_add_google_id_to_users_table.php` menambahkan kolom `google_id` ke tabel `users`.

Script utama untuk tombol login Google:

```php
<a href="{{ route('google.redirect') }}" class="text-center">
    <img src="{{ asset('assets/auth_google.png') }}" width="200" alt="">
</a>
```

Script route login Google:

```php
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');
```

Script controller login Google:

```php
return Socialite::driver('google')->stateless()->redirect();
```

Pada bagian callback, aplikasi mengambil data user dari Google. Jika email sudah ada di tabel `users`, data user tersebut digunakan. Jika belum ada, aplikasi membuat user baru berdasarkan email Google.

```php
$googleUser = $driver->user();

$user = User::firstOrNew(['email' => $googleUser->getEmail()]);
$user->name = $user->name ?: ($googleUser->getName() ?: $googleUser->getNickname() ?: 'Google User');
$user->google_id = $googleUser->getId();
$user->email_verified_at = $user->email_verified_at ?: now();
$user->save();

Auth::login($user, true);
```

### 3. Perbedaan Utama

| Aspek | Login Manual | Login Google |
| --- | --- | --- |
| Input user | Email dan password | Akun Google |
| Form login | Menggunakan input `email` dan `password` | Menggunakan tombol/link Google |
| Route | Route bawaan Laravel dari `Auth::routes()` | Route khusus `/auth/google` dan `/auth/google/callback` |
| Controller | `LoginController.php` | `GoogleController.php` |
| Proses validasi | Password dicocokkan dengan data di tabel `users` | Identitas diverifikasi melalui Google OAuth |
| Data penting | `email`, `password` | `email`, `google_id`, `email_verified_at` |

### 4. Kesimpulan

Script yang membedakan login manual dan login Google terlihat dari route, controller, dan tombol login yang digunakan. Login manual memakai form email dan password yang diproses oleh `LoginController` melalui route bawaan Laravel. Login Google memakai tombol khusus yang mengarah ke `GoogleController`, kemudian autentikasi dilakukan melalui Google OAuth menggunakan Laravel Socialite.
