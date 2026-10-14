# TiketKonser

Aplikasi pemesanan tiket event berbasis Laravel. Pengunjung dapat melihat event dan memesan tiket, sedangkan pengguna yang sudah login dapat mengelola data event dan melihat pesanan.

## Persyaratan

- PHP 8.2 atau lebih baru
- Composer
- Node.js dan npm
- SQLite (konfigurasi awal) atau MySQL

## Instalasi lokal

Jalankan perintah berikut dari folder proyek.

1. Pasang dependency PHP:

   ```bash
   composer install
   ```

2. Salin konfigurasi environment:

   Windows PowerShell:

   ```powershell
   Copy-Item .env.example .env
   ```

   macOS/Linux:

   ```bash
   cp .env.example .env
   ```

3. Buat database SQLite kosong. Langkah ini diperlukan karena `.env.example` memakai SQLite:

   Windows PowerShell:

   ```powershell
   New-Item -ItemType File -Path database/database.sqlite -Force
   ```

   macOS/Linux:

   ```bash
   touch database/database.sqlite
   ```

   Jika ingin memakai MySQL, ubah `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` di `.env`, lalu buat database yang sesuai.

4. Buat application key, jalankan migrasi dan data contoh:

   ```bash
   php artisan key:generate
   php artisan migrate --seed
   ```

   Seeder membuat akun pengujian dan beberapa data event contoh. Jalankan perintah seed hanya pada database baru; menjalankannya berulang dapat menambahkan event contoh duplikat.

5. Pasang dependency frontend dan buat asset:

   ```bash
   npm install
   npm run build
   ```

6. Buat symbolic link untuk file upload agar gambar di disk `public` bisa diakses dari browser:

   ```bash
   php artisan storage:link
   ```

   Jika Laravel memberi tahu bahwa `public/storage` sudah ada, symbolic link sudah tersedia dan langkah ini dapat dilewati.

7. Jalankan server lokal:

   ```bash
   php artisan serve
   ```

   Buka alamat yang ditampilkan Artisan, biasanya <http://127.0.0.1:8000>.

## Akun pengujian

Akun berikut dibuat oleh `DatabaseSeeder` saat menjalankan `php artisan migrate --seed`:

| Peran penggunaan | Email | Kata sandi |
|---|---|---|
| Pengguna uji / pengelola event | `admin@example.com` | `password123` |

Gunakan akun ini pada halaman `/login`. Untuk membuat ulang akun pengujian pada database yang sudah ada, daftarkan akun melalui `/register`; hindari menjalankan seeder berulang kali pada database yang sudah berisi data.

## Penggunaan

- Katalog dan pemesanan tiket: `/`
- Login: `/login`
- Pengelolaan event (perlu login): `/admin/events`
- Dashboard pesanan (perlu login dan verifikasi email): `/dashboard`

Gambar event dapat diunggah dari formulir tambah/edit event. File disimpan di `storage/app/public/events`; pastikan `php artisan storage:link` sudah dijalankan agar file dapat ditampilkan di browser. Gambar boleh dikosongkan.

## Menjalankan test

```bash
php artisan test
```

> Kredensial di atas hanya untuk pengujian lokal. Ganti atau hapus akun tersebut sebelum aplikasi digunakan di lingkungan publik.
