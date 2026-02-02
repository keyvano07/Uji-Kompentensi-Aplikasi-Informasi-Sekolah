# Master Guide: Tutorial Lengkap Pembuatan Aplikasi Informasi Sekolah
**Level: Pemula - Menengah**
**Oleh: Keyvano**

Dokumen ini dirancang khusus untuk memandu Anda membuat aplikasi ini dari nol mutlak hingga jadi. Setiap langkah disertai penjelasan detail dan **Solusi Error** jika terjadi masalah.

---

## DAFTAR ISI
1. [Persiapan Lingkungan Kerja](#bab-1-persiapan-lingkungan-kerja)
2. [Instalasi & Konfigurasi Awal](#bab-2-instalasi--konfigurasi-awal)
3. [Database & Migrasi](#bab-3-database--migrasi)
4. [Backend: Logika & Model](#bab-4-backend-logika--model)
5. [Frontend: Styling dengan Tailwind](#bab-5-frontend-styling-dengan-tailwind)
6. [Fitur: Otentikasi (Login & Register)](#bab-6-fitur-otentikasi)
7. [Fitur: Dashboard Multi-Role](#bab-7-fitur-dashboard-multi-role)
8. [Fitur: Manajemen Data (CRUD + Ajax)](#bab-8-fitur-manajemen-data)
9. [Deployment & Finalisasi](#bab-9-deployment--finalisasi)

---

## BAB 1: Persiapan Lingkungan Kerja

Sebelum mulai, pastikan tool ini sudah terinstall.

1.  **Laragon** (atau XAMPP): Untuk server lokal (Apache/Nginx) dan Database (MySQL).
    *   *Rekomendasi:* Gunakan Laragon karena lebih ringan dan mudah ganti versi PHP.
2.  **Composer**: Manajer paket untuk PHP (wajib untuk Laravel).
3.  **Node.js**: Wajib untuk menjalankan Vite (CSS/JS bundle).
4.  **VS Code**: Text Editor.

### 🛑 Kemungkinan Error (Persiapan)
-   **Error:** `'composer' is not recognized...`
    *   **Solusi:** Anda belum install Composer, atau path belum masuk Environment Variables Windows. Re-install Composer dan centang "Add to Path".
-   **Error:** `'npm' is not recognized...`
    *   **Solusi:** Install Node.js dari situs resminya (versi LTS disarankan).

---

## BAB 2: Instalasi & Konfigurasi Awal

### 1. Membuat Project Laravel
Buka terminal (Command Prompt/Terminal di VS Code). Arahkan ke folder `www` (Laragon) atau `htdocs` (XAMPP).

```bash
# Perintah membuat project Laravel 11
composer create-project laravel/laravel:^11.0 ujikom-bondowoso
```

Tunggu proses download selesai. Ini butuh internet.

### 2. Masuk ke Folder Project
```bash
cd ujikom-bondowoso
```

### 3. Cek Versi Laravel
```bash
php artisan --version
# Output harusnya: Laravel Framework 11.x.x
```

### 🛑 Kemungkinan Error (Instalasi)
-   **Error:** `Your requirements could not be resolved to an installable set of packages.`
    *   **Solusi:** Versi PHP Anda terlalu lama. Laravel 11 butuh PHP 8.2 ke atas. Update PHP di Laragon/XAMPP.
-   **Error:** Koneksi internet putus saat install.
    *   **Solusi:** Hapus folder project yang setengah jadi, lalu jalankan perintah `composer create-project` lagi.

---

## BAB 3: Database & Migrasi

### 1. Buat Database
Buka **HeidiSQL** (bawaan Laragon) atau **phpMyAdmin**.
Buat database baru dengan nama: `ujikom_bondowoso`.

### 2. Koneksikan Laravel (.env)
Buka file `.env` di VS Code. Cari baris ini dan ubah:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ujikom_bondowoso  <-- Ganti ini
DB_USERNAME=root
DB_PASSWORD=                  <-- Kosongkan jika default
```

### 3. Siapkan Migrasi (Tabel)

**Tabel Users (Modifikasi):**
Kita butuh kolom `role` (Admin/User) dan `status` (Active/Inactive).
Buka file di `database/migrations/xxxx_create_users_table.php`.

```php
// Tambahkan baris ini di dalam Schema::create
$table->enum('role', ['admin', 'user'])->default('user');
$table->enum('status', ['active', 'inactive'])->default('active');
```

**Tabel Profiles (Baru):**
Untuk foto profil dan bio user.
```bash
php artisan make:model Profile -m
```
Buka file migration `xxxx_create_profiles_table.php`:
```php
$table->foreignId('user_id')->constrained('users')->onDelete('cascade');
$table->string('foto')->nullable();
$table->text('bio')->nullable();
$table->timestamp('updated_at')->nullable();
```

**Tabel Infos (Baru):**
Untuk teks berjalan dengan judul dan tipe.
```bash
php artisan make:model Info -m
```
Buka file migration `xxxx_create_infos_table.php`:
```php
$table->string('judul');
$table->enum('tipe', ['info', 'pengumuman', 'berita'])->default('info');
$table->text('text'); // Kolom untuk isi pengumuman
```

### 4. Eksekusi Migrasi
```bash
php artisan migrate
```
*Ini akan membuat tabel-tabel di database Anda.*

### 5. Setup Storage untuk Upload Foto
```bash
php artisan storage:link
```
*Perintah ini membuat symbolic link dari `storage/app/public` ke `public/storage`, agar file yang diupload bisa diakses dari browser.*

### 🛑 Kemungkinan Error (Database)
-   **Error:** `SQLSTATE[HY000] [1049] Unknown database 'ujikom_bondowoso'`
    *   **Solusi:** Anda lupa membuat database di HeidiSQL/phpMyAdmin. Buat dulu!
-   **Error:** `Access denied for user 'root'@'localhost'`
    *   **Solusi:** Cek password di file `.env`. Kalau XAMPP mungkin ada passwordnya, kalau Laragon biasanya kosong.

---

## BAB 4: Backend: Logika & Model

### 1. Setup Model
Agar data bisa diisi (Mass Assignment), kita harus atur `$fillable`.

**File:** `app/Models/User.php`
```php
protected $fillable = [
    'name',
    'email',
    'password',
    'role',
    'status', // <-- Tambahkan ini
];

// Tambahkan relationship
public function profile() {
    return $this->hasOne(Profile::class);
}
```

**File:** `app/Models/Profile.php`
```php
protected $fillable = ['user_id', 'foto', 'bio'];

public $timestamps = false;
const UPDATED_AT = 'updated_at';

public function user() {
    return $this->belongsTo(User::class);
}
```

**File:** `app/Models/Info.php`
```php
protected $fillable = ['judul', 'tipe', 'text'];
```

### 2. Seeder (Data Dummy)
Agar tidak capek daftar manual saat testing.
**File:** `database/seeders/DatabaseSeeder.php`

```php
use App\Models\User;

public function run(): void
{
    // Buat 1 Admin
    User::create([
        'name' => 'Administrator',
        'email' => 'admin@gmail.com',
        'password' => bcrypt('password'), // Password terenkripsi
        'role' => 'admin',
    ]);
    
    // Buat 1 User Biasa
    User::create([
        'name' => 'Siswa',
        'email' => 'siswa@gmail.com',
        'password' => bcrypt('password'),
        'role' => 'user',
    ]);
}
```
Jalankan: `php artisan db:seed`

---

## BAB 5: Frontend: Styling dengan Tailwind

### 1. Install Tailwind
```bash
npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init -p
```

### 2. Konfigurasi (PENTING)
**File:** `tailwind.config.js`
Tambahkan path file agar Tailwind membaca class yang kita tulis di view Blade.

```javascript
content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
],
theme: {
    extend: {
        colors: {
            'bondowoso': '#064e3b', // Warna hijau kustom kita
        }
    },
},
```

**File:** `resources/css/app.css`
Isi dengan directive Tailwind:
```css
@import "tailwindcss";
```

### 3. Menjalankan Frontend
Anda **WAJIB** menjalankan perintah ini di terminal terpisah selama coding (jangan dimatikan):
```bash
npm run dev
```

### 4. Blade Components (Opsional - untuk Simplifikasi)
Untuk menyederhanakan class Tailwind yang panjang, buat komponen reusable:

**File:** `resources/views/components/button.blade.php`
```blade
@props(['variant' => 'primary'])

@php
$classes = match($variant) {
    'primary' => 'bg-green-900 hover:bg-green-800 text-white',
    'secondary' => 'bg-gray-300 hover:bg-gray-400 text-gray-800',
    'danger' => 'bg-red-600 hover:bg-red-700 text-white',
    default => 'bg-green-900 hover:bg-green-800 text-white',
};
@endphp

<button {{ $attributes->merge(['class' => "px-6 py-2 text-sm font-medium rounded-lg transition-colors $classes"]) }}>
    {{ $slot }}
</button>
```

**Cara pakai:**
```blade
<x-button>Simpan</x-button>
<x-button variant="secondary">Batal</x-button>
<x-button variant="danger">Hapus</x-button>
```

### 🛑 Kemungkinan Error (Frontend)
-   **Error:** Tampilan web hancur/polos, tidak ada style.
    *   **Penyebab:** `npm run dev` tidak dijalankan.
    *   **Solusi:** Jalankan `npm run dev`. Atau jika di hosting, jalankan `npm run build`.
-   **Error:** `@vite(['resources/css/app.css'])` not found.
    *   **Solusi:** Pastikan Anda sudah install node modules (`npm install`).

---

## BAB 6: Fitur Otentikasi

Daripada pakai starter kit (Breeze/Jetstream) yang ribet di-custom, kita buat manual agar paham alurnya.

**Langkah:**
1.  Buat `AuthController`: `php artisan make:controller AuthController`
2.  Buat Route di `routes/web.php`.
3.  Buat View di `resources/views/auth/login.blade.php`.

**Tips Login:**
-   Gunakan `Auth::attempt(['email' => $email, 'password' => $password])`.
-   Jika gagal, gunakan `return back()->withErrors(...)`.

**Logika Mata (Show Password):**
Ini murni JavaScript Frontend.
```javascript
function togglePassword() {
    let input = document.getElementById('password');
    // Jika type="password", ubah jadi "text". Begitu sebaliknya.
    input.type = input.type === "password" ? "text" : "password";
}
```

---

## BAB 7: Fitur Dashboard Multi-Role

Kita ingin Admin dan User melihat halaman yang berbeda.

**File:** `app/Http/Controllers/DashboardController.php`
```php
public function index() {
    if (auth()->user()->role === 'admin') {
        return view('dashboard.admin');
    } else {
        return view('dashboard.user');
    }
}
```

*Jangan lupa buat 2 file view tersebut di folder `resources/views/dashboard/`.*

### 🛑 Kemungkinan Error (Auth & Role)
-   **Error:** Login selalu gagal padahal password benar.
    *   **Penyebab:** Saat seeding/create user manual, password tidak di-hash.
    *   **Solusi:** Pastikan pakai `bcrypt('password')` atau `Hash::make('password')` saat input data ke DB.
-   **Error:** User biasa bisa masuk halaman Admin.
    *   **Solusi:** Anda butuh **Middleware**. Buat middleware `EnsureUserHasRole` atau cek manual di setiap controller: `if(auth()->user()->role !== 'admin') abort(403);`.

---

## BAB 8: Fitur Manajemen Data (CRUD + Ajax + File Upload)

### Studi Kasus 1: Upload Foto Profil

**Controller (`UserController`):**
```php
public function store(Request $request) {
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6',
        'role' => 'required|in:admin,user',
        'foto' => 'nullable|image|max:2048', // Max 2MB
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'role' => $request->role,
        'status' => 'active',
    ]);

    // Handle foto upload
    if ($request->hasFile('foto')) {
        $fotoPath = $request->file('foto')->store('profiles', 'public');
        
        $user->profile()->create([
            'foto' => $fotoPath,
            'bio' => $request->bio,
        ]);
    }

    return back()->with('success', 'User berhasil ditambahkan.');
}
```

**View - Form Upload:**
```blade
<form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="foto" accept="image/*">
    <textarea name="bio" placeholder="Bio..."></textarea>
    <button type="submit">Simpan</button>
</form>
```

**Tips Penting:**
- Jangan lupa `enctype="multipart/form-data"` di form tag!
- Gunakan `hasFile()` untuk cek apakah file ada yang diupload
- `store('profiles', 'public')` = simpan di `storage/app/public/profiles`
- Path hasil upload bisa diakses via `/storage/profiles/namafile.jpg`

### Studi Kasus 2: Prevent Double Submit

**Masalah:** User klik tombol "Simpan" beberapa kali, data jadi double.

**Solusi JavaScript:**
```javascript
function handleFormSubmit(form) {
    const submitBtn = form.querySelector('button[type="submit"]');
    if (submitBtn.disabled) {
        return false; // Already submitting
    }
    submitBtn.disabled = true;
    submitBtn.textContent = 'Menyimpan...';
    return true;
}
```

**Di Form:**
```blade
<form onsubmit="return handleFormSubmit(this)">
```

### Studi Kasus 3: Manajemen User (Ubah Role Instan)

1.  **View (`index.blade.php`)**:
    Di tabel, kita pasang dropdown.
    ```html
    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf @method('PUT')
        <select name="role" onchange="this.form.submit()">
            <option value="admin">Admin</option>
            <option value="user">User</option>
        </select>
    </form>
    ```
    *Trik:* `onchange="this.form.submit()"` membuat form langsung terkirim saat opsi dipilih.

2.  **Controller (`update`)**:
    ```php
    public function update(Request $request, User $user) {
        $user->update([
            'role' => $request->role
        ]);
        return back()->with('success', 'Role berubah!');
    }
    ```

### Studi Kasus: Live Search (Ajax)

Kita mau cari user tanpa tekan Enter.

1.  **HTML**: Input Search diberi ID `searchInput`.
2.  **JavaScript**:
    ```javascript
    document.getElementById('searchInput').addEventListener('input', function() {
        let query = this.value;
        // Fetch ke URL server
        fetch(`/users?search=${query}`)
            .then(response => response.text())
            .then(html => {
                // Ambil hanya bagian tabel dari response HTML
                // Lalu tempel ke halaman sekarang
                let parser = new DOMParser();
                let doc = parser.parseFromString(html, 'text/html');
                document.querySelector('tbody').innerHTML = doc.querySelector('tbody').innerHTML;
            });
    });
    ```
    *Catatan:* Di controller `index`, kita harus menangani `request('search')`.

---

## BAB 9: Troubleshooting Umum & Tips Sukses

1.  **Error Permission (Laravel Log)**
    *   *Pesan:* `The stream or file ".../laravel.log" could not be opened: failed to open stream: Permission denied.`
    *   *Solusi:* Hapus file `storage/logs/laravel.log` lalu jalankan ulang server.

2.  **Error "Target class [...] does not exist"**
    *   *Penyebab:* Lupa `use App\Http\Controllers\NamaController;` di `web.php`.
    *   *Solusi:* Cek bagian atas file `routes/web.php`, pastikan controller di-import.

3.  **Halaman Not Found (404)**
    *   *Penyebab:* Route belum didefinisikan atau method (GET/POST) salah.
    *   *Solusi:* Cek daftar route dengan `php artisan route:list`.

4.  **Tampilan CSS tidak berubah**
    *   *Solusi:* Browser cache. Tekan `Ctrl + Shift + R` untuk Hard Refresh.

---
**Selamat! Anda sudah memiliki panduan lengkap.**
Ikuti langkah demi langkah di atas dengan teliti. Jangan terburu-buru. Coding butuh ketelitian. 
*Happy Coding!*
