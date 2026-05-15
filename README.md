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
