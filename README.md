# Aplikasi Manajemen Informasi & Pengguna (Bondowoso)

Aplikasi web berbasis Laravel 11 untuk manajemen informasi sekolah/instansi dan pengelolaan pengguna dengan role-based access control (Admin & User).

## Fitur Utama
- **Otentikasi**: Login dan Register dengan desain kustom, fitur *show/hide password*.
- **Role Management**: Pembedaan hak akses antara **Admin** dan **User**.
- **Dashboard**: Tampilan dashboard yang berbeda sesuai role.
- **Manajemen User (Admin)**:
  - CRUD User.
  - Ubah Role (Admin/User) langsung dari tabel (Inline Edit).
  - Pencarian User.
- **Manajemen Info (Admin)**:
  - CRUD Informasi Sekolah.
  - Fitur *Live Search* (Ajax).
  - Modal untuk Tambah dan Edit data.
- **Running Text**: Menampilkan informasi terbaru di halaman login secara dinamis.
- **Profil**: Update data diri (Nama & Email).

## Persyaratan Sistem
- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL

## Cara Instalasi (Penggunaan)
1. **Clone Repository**
   ```bash
   git clone https://github.com/username/repo-name.git
   cd repo-name
   ```

2. **Instalasi Dependensi**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**
   Salin file `.env.example` menjadi `.env` dan sesuaikan konfigurasi database.
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Migrasi Database**
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Jalankan Aplikasi**
   Buka dua terminal berbeda:
   ```bash
   # Terminal 1
   php artisan serve

   # Terminal 2
   npm run dev
   ```

---

# Dokumentasi Pengerjaan Aplikasi (Tutorial Lengkap)

Berikut adalah langkah-langkah pembuatan aplikasi ini dari nol sampai selesai.

## 1. Persiapan Project (Phase 1)

### Instalasi Laravel
Membuat project Laravel baru versi 11.
```bash
composer create-project laravel/laravel:^11.0 .
```

### Konfigurasi Database
Mengatur file `.env`.
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ujikom_bondowoso
DB_USERNAME=root
DB_PASSWORD=
```

### Instalasi Tailwind CSS
Menggunakan Tailwind untuk styling.
```bash
npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init -p
```
*Catatan: Jika mengalami error dengan Vite, gunakan plugin postcss khusus:* `npm install -D @tailwindcss/postcss` *dan update `postcss.config.js`.*

Konfigurasi `tailwind.config.js`:
```js
content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
],
theme: {
    extend: {
        colors: {
            bondowoso: '#064e3b', // Custom color
        }
    },
},
```

## 2. Database & Model (Phase 2)

### Membuat Model & Migration
1. **Tabel Users** (Bawaan Laravel, tambah kolom `role`).
   ```php
   $table->enum('role', ['admin', 'user'])->default('user');
   ```
2. **Tabel Infos** (Untuk pengumuman).
   ```bash
   php artisan make:model Info -m
   ```
   *Migration:*
   ```php
   $table->text('text');
   ```

### Seeding Data
Membuat user default untuk testing.
```php
// DatabaseSeeder.php
User::create([
    'name' => 'Administrator',
    'email' => 'admin@gmail.com',
    'password' => bcrypt('password'),
    'role' => 'admin'
]);
```

## 3. Otentikasi Kustom (Phase 3)

### Controller Otentikasi
Membuat `AuthController` untuk menangani Login, Register, dan Logout secara manual (tanpa starter kit seperti Breeze/Jetstream agar lebih fleksibel ui-nya).
```bash
php artisan make:controller AuthController
```
- `showLogin()`: Menampilkan view login.
- `login()`: Proses validasi dan `Auth::attempt`.
- `logout()`: `Auth::logout` dan invalidasi session.
- `showRegister()` & `register()`: Menangani pendaftaran user baru.

### View Login & Register
- **Login**: `resources/views/auth/login.blade.php`. Mengambil data `Info` terbaru untuk running text.
- **Register**: `resources/views/auth/register.blade.php`. Form pendaftaran.
- Fitur tambahan: **Icon Mata** menggunakan JavaScript untuk *toggle visibility* password.

## 4. Layout & Dashboard (Phase 4)

### Setup Layout Utama
Membuat `resources/views/layouts/app.blade.php` dan `sidebar.blade.php`.
- Menggunakan **Flexbox** untuk layout Sidebar (kiri) dan Content (kanan).
- Menambahkan **Middleware** `role:admin` (perlu dibuat manual atau cek di Controller) untuk membatasi akses menu.

### Dashboard Berbeda
- **Admin**: Melihat ringkasan sistem.
- **User**: Melihat informasi umum.
- Logic di `DashboardController`:
  ```php
  if (Auth::user()->role == 'admin') {
      return view('dashboard.admin');
  } else {
      return view('dashboard.user');
  }
  ```

## 5. Fitur Manajemen Info (Phase 5)

### CRUD Info
- **Controller**: `InfoController`.
- **View**: `resources/views/infos/index.blade.php`.
- **Fitur Live Search**: Menggunakan AJAX (JavaScript/Fetch API) untuk update tabel tanpa reload saat mengetik di kolom pencarian.
- **Modal**: Form Tambah dan Edit menggunakan Modal CSS (Hidden/Flex toggle via JS) agar UX lebih bersih.

## 6. Fitur Manajemen User (Kelola Pengguna) (Phase 6)

### CRUD & Role Management
- **Controller**: `UserController`.
- **View**: `resources/views/users/index.blade.php`.
- **Inline Role Editing**:
  Dropdown `select` di dalam tabel yang memiliki event `onchange="this.form.submit()"`. Ini memungkinkan Admin mengubah role user (Admin/User) secara instan tanpa masuk ke halaman edit terpisah.
- **Penghapusan**: Tombol hapus dengan konfirmasi `onsubmit`.

## 7. Fitur Profil (Phase 7)

### Edit Profil
- **Controller**: `ProfileController`.
- **View**: `resources/views/profile/edit.blade.php`.
- Memungkinkan user mengganti Nama dan Email mereka sendiri. Role ditampilkan *readonly*.

---
*Dibuat oleh: Keyvano*
