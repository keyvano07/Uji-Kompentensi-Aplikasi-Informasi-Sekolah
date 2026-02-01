# PANDUAN KODING RUNTUT: Aplikasi Informasi Sekolah (Bondowoso)
**Tutorial Feature-by-Feature - Bangun Aplikasi Fitur per Fitur dari NOL**

Dokumen ini berisi panduan **step-by-step** membangun aplikasi Laravel dengan pendekatan **feature-by-feature**. Setiap fitur akan dibangun lengkap (Controller → Route → View → Test) sebelum lanjut ke fitur berikutnya.

---

## 🎯 CARA MENGGUNAKAN TUTORIAL INI

**Konsep**: Build fitur satu per satu sampai selesai, lalu test, baru lanjut fitur berikutnya.

**Workflow**:
1. Build fitur lengkap (Controller + Route + View)
2. Test fitur tersebut di browser
3. Pastikan berjalan dengan baik
4. Lanjut ke fitur berikutnya

**Keuntungan**:
- ✅ Natural seperti development sebenarnya
- ✅ Bisa test setiap fitur langsung
- ✅ Mudah debug kalau error
- ✅ Incremental - tidak overwhelming

---

## 🗺️ ROADMAP FITUR YANG AKAN DIBANGUN

Berikut urutan fitur yang akan kita bangun:

### **Foundation** (TAHAP 1-4)
1. ⚙️ Konfigurasi Dasar (Laravel + Tailwind CSS)
2. 🗄️ Database Setup (Migration, Model, Seeder)
3. 🎨 Layout Template (Master template + Sidebar)
4. 🔐 Middleware (Role-based protection)

### **Features** (TAHAP 5-11) - Build per fitur lengkap!
5. 🔑 **Fitur Login** - User bisa masuk ke aplikasi
6. 📝 **Fitur Register** - User bisa daftar akun baru
7. 📊 **Fitur Dashboard** - Halaman utama berbeda untuk admin & user
8. 📰 **Fitur Info** - Kelola informasi sekolah (CRUD)
9. 👥 **Fitur User Management** - Kelola user (admin only)
10. 👤 **Fitur Profile** - Edit profil sendiri
11. 🚪 **Fitur Logout** - Keluar dari aplikasi

---

## ⚙️ TAHAP 1: KONFIGURASI DASAR

### 1. Terminal: Install Project
Jalankan di terminal/command prompt:
```bash
composer create-project laravel/laravel:^11.0 ujikom-bondowoso
cd ujikom-bondowoso

# Install Tailwind CSS v3 Stabil
npm install -D tailwindcss@3 postcss autoprefixer
npx tailwindcss init -p
```

### 2. File: `.env`
Edit konfigurasi database (baris 62-74 pada file .env):
```env
APP_NAME=Bondowoso
APP_ENV=local
APP_KEY=base64:... (biarkan default yang di-generate Laravel)
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ujikom_bondowoso
DB_USERNAME=root
DB_PASSWORD=
```
> **Catatan**: Buat database `ujikom_bondowoso` di MySQL/PhpMyAdmin terlebih dahulu.

### 3. File: `tailwind.config.js`
Ganti **SELURUH ISI** file dengan kode ini:
```javascript
/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                'bondowoso': '#064e3b', // Green-900
            },
        },
    },
    plugins: [],
}
```

### 4. File: `postcss.config.js`
Ganti **SELURUH ISI** file dengan:
```javascript
export default {
    plugins: {
        tailwindcss: {},
        autoprefixer: {},
    },
}
```

### 5. File: `resources/css/app.css`
Ganti **SELURUH ISI** file dengan:
```css
@tailwind base;
@tailwind components;
@tailwind utilities;
```

---

## 🗄️ TAHAP 2: DATABASE SETUP

### 6. Terminal: Buat Migration Info
```bash
php artisan make:migration create_infos_table
```

### 7. File: `database/migrations/xxxx_create_users_table.php`
Edit migration users yang sudah ada, tambahkan kolom `role` setelah `email`:
```php
public function up(): void
{
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->enum('role', ['admin', 'user'])->default('user'); // TAMBAHKAN INI
        $table->timestamp('email_verified_at')->nullable();
        $table->string('password');
        $table->rememberToken();
        $table->timestamps();
    });
    
    // ... kode session dan password reset tetap ada
}
```

### 8. File: `database/migrations/xxxx_create_infos_table.php`
Ganti **SELURUH ISI** method `up()` dengan:
```php
public function up(): void
{
    Schema::create('infos', function (Blueprint $table) {
        $table->id();
        $table->text('text');
        $table->timestamps();
    });
}
```

### 9. File: `app/Models/User.php`
Update bagian `$fillable` untuk include `role`:
```php
protected $fillable = [
    'name',
    'email',
    'password',
    'role', // TAMBAHKAN INI
];
```

### 10. Terminal: Buat Model Info
```bash
php artisan make:model Info
```

### 11. File: `app/Models/Info.php`
Tambahkan `$fillable`:
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
    protected $fillable = ['text'];
}
```

### 12. File: `database/seeders/DatabaseSeeder.php`
Ganti **SELURUH ISI** method `run()` dengan:
```php
public function run(): void
{
    // Create Admin User
    \App\Models\User::create([
        'name' => 'Admin Bondowoso',
        'email' => 'admin@gmail.com',
        'password' => \Illuminate\Support\Facades\Hash::make('password'),
        'role' => 'admin',
    ]);

    // Create Regular User
    \App\Models\User::create([
        'name' => 'Siswa Bondowoso',
        'email' => 'siswa@gmail.com',
        'password' => \Illuminate\Support\Facades\Hash::make('password'),
        'role' => 'user',
    ]);

    // Create Sample Info
    \App\Models\Info::create([
        'text' => 'Selamat datang di Aplikasi Informasi Sekolah Bondowoso!',
    ]);

    \App\Models\Info::create([
        'text' => 'Jadwal ujian tengah semester akan dilaksanakan minggu depan.',
    ]);

    \App\Models\Info::create([
        'text' => 'Pendaftaran ekstrakurikuler dibuka mulai hari Senin.',
    ]);
}
```

### 13. Terminal: Jalankan Migration & Seeder
```bash
php artisan migrate:fresh --seed
```

**✅ Hasil TAHAP 2**: Database siap dengan tabel `users` (ada role), tabel `infos`, dan data dummy (1 admin, 1 user, 3 info).

---

## 🎨 TAHAP 3: LAYOUT TEMPLATE

### 14. File: `resources/views/layouts/app.blade.php`
Buat file **BARU** dengan kode lengkap:
```blade
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bondowoso')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans">
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        @include('layouts.sidebar')
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-100 px-8 py-4">
                <div class="flex items-center justify-between">
                    <h1 class="text-xl font-bold text-gray-800">@yield('header', 'Dashboard')</h1>
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-gray-600">{{ Auth::user()->name }}</span>
                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-bold text-xs uppercase">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Content Area -->
            <main class="flex-1 overflow-y-auto p-8">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
```

### 15. File: `resources/views/layouts/sidebar.blade.php`
Buat file **BARU** dengan kode lengkap:
```blade
<aside class="w-64 bg-bondowoso text-white flex flex-col">
    <!-- Logo -->
    <div class="p-6 border-b border-green-800">
        <h2 class="text-2xl font-bold">Bondowoso</h2>
        <p class="text-xs text-green-200 mt-1">Sistem Informasi Sekolah</p>
    </div>
    
    <!-- Navigation -->
    <nav class="flex-1 p-4 space-y-1">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-green-800 transition-colors {{ request()->routeIs('dashboard') ? 'bg-green-800' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span class="font-medium">Beranda</span>
        </a>
        
        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-green-800 transition-colors {{ request()->routeIs('profile.*') ? 'bg-green-800' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            <span class="font-medium">Profil</span>
        </a>
        
        <a href="{{ route('infos.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-green-800 transition-colors {{ request()->routeIs('infos.*') ? 'bg-green-800' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-medium">Info</span>
        </a>
        
        @if(Auth::user()->role === 'admin')
        <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-green-800 transition-colors {{ request()->routeIs('users.*') ? 'bg-green-800' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            <span class="font-medium">Pengguna</span>
        </a>
        @endif
    </nav>
    
    <!-- Logout -->
    <div class="p-4 border-t border-green-800">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-green-800 transition-colors w-full text-left">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                <span class="font-medium">Keluar</span>
            </button>
        </form>
    </div>
</aside>
```

**✅ Hasil TAHAP 3**: Template master dan sidebar siap dipakai untuk semua halaman.

---

## 🔐 TAHAP 4: MIDDLEWARE (ROLE-BASED PROTECTION)

### Apa itu Middleware?
Middleware adalah **penjaga pintu** yang memeriksa request sebelum masuk ke controller. Kita pakai middleware untuk memastikan hanya user dengan role tertentu (misal admin) yang bisa akses fitur tertentu.

### 16. Terminal: Buat Middleware
```bash
php artisan make:middleware CheckRole
```

### 17. File: `app/Http/Middleware/CheckRole.php`
Ganti **SELURUH ISI** file dengan:
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Cek apakah user login DAN role-nya sesuai
        if (! $request->user() || $request->user()->role !== $role) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
```

**Penjelasan**:
- Method `handle()` menerima parameter `$role` dari route (contoh: `middleware('role:admin')`)
- Mengecek apakah user sudah login (`$request->user()`)
- Mengecek apakah role user sama dengan role yang diminta
- Jika tidak sesuai, return error 403 (Forbidden)

### 18. File: `bootstrap/app.php`
Edit file, cari bagian `->withMiddleware()`, lalu tambahkan alias untuk middleware:
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\CheckRole::class,
    ]);
})
```

**✅ Hasil TAHAP 4**: Middleware siap dipakai untuk proteksi route berdasarkan role.

---

## 🔑 TAHAP 5: FITUR LOGIN

**Apa yang akan kita buat?**
Fitur login agar user bisa masuk ke aplikasi dengan email dan password.

### Step 1: Controller (Login Logic)

#### 19. Terminal: Buat AuthController
```bash
php artisan make:controller AuthController
```

#### 20. File: `app/Http/Controllers/AuthController.php`
Tambahkan method untuk login:
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Info;

class AuthController extends Controller
{
    // Tampilkan halaman login
    public function showLogin()
    {
        $infos = Info::latest()->get();
        return view('auth.login', compact('infos'));
    }

    // Proses login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }
}
```

### Step 2: Route (URL untuk Login)

#### 21. File: `routes/web.php`
**GANTI SELURUH ISI** file dengan:
```php
<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Redirect root ke login
Route::get('/', function () {
    return redirect('/login');
});

// Login routes (PUBLIC - semua orang bisa akses)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
```

### Step 3: View (Halaman Login)

#### 22. File: `resources/views/auth/login.blade.php`
Buat file **BARU**:
```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bondowoso</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .bg-bondowoso { background-color: #064e3b; }
        .text-bondowoso { color: #064e3b; }
    </style>
</head>
<body class="h-screen w-full flex font-sans">
    
    <!-- Left Side (Green) -->
    <div class="w-1/2 bg-bondowoso flex flex-col justify-between p-12 text-white relative">
        <div class="z-10">
            <h1 class="text-3xl font-bold mb-8">Bondowoso</h1>
            <div class="mt-20">
                <h2 class="text-4xl font-bold leading-tight mb-4">
                    {{ $infos->first()->text ?? 'Menolak lupa untuk generasi masa depan' }}
                </h2>
                <div class="w-16 h-1 bg-yellow-400"></div>
            </div>
        </div>

        <!-- Bus Illustration -->
        <div class="relative z-10 w-full mb-10 opacity-80">
            <div class="w-full h-32 bg-white/10 rounded-xl border border-white/20 relative">
                <div class="flex justify-around pt-6 px-4">
                    <div class="w-1/4 h-16 bg-white/20 rounded-md mx-1"></div>
                    <div class="w-1/4 h-16 bg-white/20 rounded-md mx-1"></div>
                    <div class="w-1/4 h-16 bg-white/20 rounded-md mx-1"></div>
                </div>
                <div class="absolute -bottom-5 left-10 w-12 h-12 bg-yellow-500 rounded-full"></div>
                <div class="absolute -bottom-5 right-10 w-12 h-12 bg-yellow-500 rounded-full"></div>
            </div>
            <div class="w-full h-1 bg-white/30 mt-4"></div>
        </div>
    </div>

    <!-- Right Side (White) - Login Form -->
    <div class="w-1/2 bg-white flex flex-col justify-center items-center p-12 relative overflow-hidden">
        
        <div class="w-full max-w-md z-10">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Selamat Datang Kembali!</h2>
            <p class="text-gray-500 mb-8">Masukkan detail Anda untuk melanjutkan.</p>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <span class="block sm:inline">{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input type="email" name="email" id="email" class="shadow-sm appearance-none border rounded w-full py-3 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-green-700" placeholder="example@gmail.com" required>
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                    <input type="password" name="password" id="password" class="shadow-sm appearance-none border rounded w-full py-3 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-green-700" placeholder="******" required>
                </div>
                <button class="bg-bondowoso hover:bg-green-900 text-white font-bold py-3 px-4 rounded w-full transition duration-300" type="submit">
                    Masuk
                </button>
                <div class="mt-4 text-center">
                    <span class="text-gray-500 text-sm">Belum punya akun? </span>
                    <a href="{{ route('register') }}" class="text-bondowoso hover:text-green-800 underline font-bold text-sm">Daftar gratis</a>
                </div>
            </form>
        </div>

        <!-- Running Text -->
        <div class="absolute bottom-0 w-full bg-gray-50 py-3 overflow-hidden border-t border-gray-100">
            <div class="whitespace-nowrap text-bondowoso font-medium animate-marquee">
                @foreach($infos as $info)
                    <span class="mx-8">{{ $info->text }}</span> •
                @endforeach
            </div>
        </div>
    </div>

</body>
</html>
```

### Step 4: Testing Fitur Login

#### 23. Terminal: Jalankan Server
```bash
# Terminal 1
php artisan serve

# Terminal 2 (di folder yang sama)
npm run dev
```

#### 24. Browser: Test Login
1. Buka `http://localhost:8000`
2. Akan redirect ke `/login`
3. Login dengan:
   - **Email**: `admin@gmail.com`
   - **Password**: `password`
4. Klik "Masuk"

**Expected**: Error 404 karena route `/dashboard` belum ada (normal! kita bikin di TAHAP berikutnya)

**✅ Hasil TAHAP 5**: Fitur login sudah jalan! (Route /dashboard belum ada, akan dibuat di TAHAP 7)

---

## 📝 TAHAP 6: FITUR REGISTER

**Apa yang akan kita buat?**
Fitur register agar user baru bisa mendaftar akun.

### Step 1: Controller (Register Logic)

#### 25. File: `app/Http/Controllers/AuthController.php`
Tambahkan method register (di bawah method login):
```php
    // Tampilkan halaman register
    public function showRegister()
    {
        return view('auth.register');
    }

    // Proses register
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
            'role' => 'user', // Default role adalah 'user'
        ]);

        Auth::login($user);

        return redirect('/dashboard');
    }
```

### Step 2: Route (URL untuk Register)

#### 26. File: `routes/web.php`
Tambahkan route register (di bawah route login):
```php
// Register routes (PUBLIC)
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
```

**Catatan**: Tambahkan di bawah route login yang sudah ada. Jangan replace semua!

### Step 3: View (Halaman Register)

#### 27. File: `resources/views/auth/register.blade.php`
Buat file **BARU**:
```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Bondowoso</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .bg-bondowoso { background-color: #064e3b; }
        .text-bondowoso { color: #064e3b; }
    </style>
</head>
<body class="h-screen w-full flex font-sans">
    
    <!-- Left Side (Green) -->
    <div class="hidden md:flex w-1/2 bg-bondowoso flex-col justify-between p-12 text-white relative">
        <div class="z-10">
            <h1 class="text-3xl font-bold mb-8">Bondowoso</h1>
            <div class="mt-20">
                <h2 class="text-4xl font-bold leading-tight mb-4">Bergabung bersama kami<br>untuk masa depan</h2>
                <div class="w-16 h-1 bg-yellow-400"></div>
            </div>
            <p class="mt-6 text-lg text-green-100 opacity-90">
                Daftarkan diri Anda untuk mengakses informasi dan layanan terkini seputar Bondowoso.
            </p>
        </div>

        <!-- Bus Illustration -->
        <div class="relative z-10 w-full mb-10 opacity-80">
            <div class="w-full h-32 bg-white/10 rounded-xl border border-white/20 relative">
                <div class="flex justify-around pt-6 px-4">
                    <div class="w-1/4 h-16 bg-white/20 rounded-md mx-1"></div>
                    <div class="w-1/4 h-16 bg-white/20 rounded-md mx-1"></div>
                    <div class="w-1/4 h-16 bg-white/20 rounded-md mx-1"></div>
                </div>
                <div class="absolute -bottom-5 left-10 w-12 h-12 bg-yellow-500 rounded-full"></div>
                <div class="absolute -bottom-5 right-10 w-12 h-12 bg-yellow-500 rounded-full"></div>
            </div>
            <div class="w-full h-1 bg-white/30 mt-4"></div>
        </div>
    </div>

    <!-- Right Side (White) - Register Form -->
    <div class="w-full md:w-1/2 bg-white flex flex-col justify-center items-center p-8 md:p-12 relative overflow-y-auto">
        
        <div class="w-full max-w-md z-10">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Buat Akun Baru</h2>
            <p class="text-gray-500 mb-8">Lengkapi data diri Anda untuk mendaftar.</p>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="mb-5">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="shadow-sm border rounded w-full py-3 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-700" placeholder="Nama Lengkap Anda" required>
                </div>

                <div class="mb-5">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="shadow-sm border rounded w-full py-3 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-700" placeholder="example@gmail.com" required>
                </div>

                <div class="mb-5">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                    <input type="password" name="password" id="password" class="shadow-sm border rounded w-full py-3 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-700" placeholder="Minimal 6 karakter" required>
                </div>

                <div class="mb-8">
                    <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="shadow-sm border rounded w-full py-3 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-700" placeholder="Ulangi password" required>
                </div>

                <button class="bg-bondowoso hover:bg-green-900 text-white font-bold py-3 px-4 rounded w-full transition duration-300" type="submit">
                    Daftar Akun
                </button>
                <div class="mt-6 text-center">
                    <span class="text-gray-500 text-sm">Sudah punya akun? </span>
                    <a href="{{ route('login') }}" class="text-bondowoso hover:text-green-800 underline font-bold text-sm">
                        Masuk disini
                    </a>
                </div>
            </form>
        </div>
        
        <div class="text-center mt-8 text-xs text-gray-400">
            &copy; {{ date('Y') }} Bondowoso. All rights reserved.
        </div>
    </div>

</body>
</html>
```

### Step 4: Testing Fitur Register

#### 28. Browser: Test Register
1. Buka `http://localhost:8000/register`
2. Isi form:
   - Nama: `Test User`
   - Email: `test@gmail.com`
   - Password: `password`
   - Konfirmasi Password: `password`
3. Klik "Daftar Akun"

**Expected**: Error 404 di `/dashboard` (normal! Dashboard belum dibuat)

Tapi coba cek database (`users` table), harusnya ada user baru dengan role `user`!

**✅ Hasil TAHAP 6**: Fitur register sudah jalan! User baru bisa mendaftar.

---

## 📊 TAHAP 7: FITUR DASHBOARD

**Apa yang akan kita buat?**
Dashboard berbeda untuk admin dan user biasa.

### Step 1: Controller (Dashboard Logic)

#### 29. Terminal: Buat DashboardController
```bash
php artisan make:controller DashboardController
```

#### 30. File: `app/Http/Controllers/DashboardController.php`
Ganti **SELURUH ISI** dengan:
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Info;

class DashboardController extends Controller
{
    public function index()
    {
        $info_count = Info::count();
        
        // Admin lihat dashboard admin, user lihat dashboard user
        if (auth()->user()->role === 'admin') {
            return view('dashboard.admin', compact('info_count'));
        } else {
            return view('dashboard.user', compact('info_count'));
        }
    }
}
```

### Step 2: Route (URL untuk Dashboard)

#### 31. File: `routes/web.php`
Tambahkan route dashboard (di bawah route register):
```php
// Dashboard route (harus login dulu - middleware 'auth')
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
});
```

### Step 3: View (Halaman Dashboard)

#### 32. File: `resources/views/dashboard/admin.blade.php`
Buat file **BARU**:
```blade
@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('header')
    <div class="flex items-center gap-3">
        Ringkasan Dashboard
        <span class="bg-gray-100 text-gray-500 px-3 py-1 rounded-full text-xs font-normal">Anda masuk sebagai admin</span>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Total Info Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <div class="flex flex-col">
                <span class="text-sm font-medium text-gray-500 uppercase">Total Info</span>
                <span class="text-4xl font-bold text-gray-800 mt-2">{{ $info_count }}</span>
                <div class="flex items-center mt-4 text-green-600 text-sm font-medium">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    <span>Sistem aktif</span>
                </div>
            </div>
        </div>

        <!-- System Status Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <div class="flex flex-col">
                <span class="text-sm font-medium text-gray-500 uppercase">Status</span>
                <span class="text-4xl font-bold text-gray-800 mt-2">OK</span>
                <div class="flex items-center mt-4 text-green-600 text-sm font-medium">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Sistem berjalan</span>
                </div>
            </div>
        </div>
    </div>
@endsection
```

#### 33. File: `resources/views/dashboard/user.blade.php`
Buat file **BARU**:
```blade
@extends('layouts.app')

@section('title', 'Dashboard User')

@section('header')
    <div class="flex items-center gap-3">
        Ringkasan Dashboard
        <span class="bg-gray-100 text-gray-500 px-3 py-1 rounded-full text-xs font-normal">Anda masuk sebagai pengguna</span>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Total Info Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <div class="flex flex-col">
                <span class="text-sm font-medium text-gray-500 uppercase">Total Info</span>
                <span class="text-4xl font-bold text-gray-800 mt-2">{{ $info_count }}</span>
                <div class="flex items-center mt-4 text-green-600 text-sm font-medium">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    <span>Informasi terkini</span>
                </div>
            </div>
        </div>

        <!-- Welcome Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <div class="flex flex-col">
                <span class="text-sm font-medium text-gray-500 uppercase">Selamat Datang</span>
                <span class="text-2xl font-bold text-gray-800 mt-2">{{ Auth::user()->name }}</span>
                <p class="mt-4 text-sm text-gray-600">Terima kasih telah menggunakan sistem informasi Bondowoso.</p>
            </div>
        </div>
    </div>
@endsection
```

### Step 4: Add Logout Route

#### 34. File: `app/Http/Controllers/AuthController.php`
Tambahkan method logout:
```php
    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
```

#### 35. File: `routes/web.php`
Tambahkan route logout (di dalam middleware 'auth' group):
```php
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');  // TAMBAHKAN INI
});
```

### Step 5: Testing Fitur Dashboard

#### 36. Browser: Test Dashboard
1. Logout dulu jika sudah login
2. Login dengan **admin**: `admin@gmail.com` / `password`
3. Setelah login, akan redirect ke Dashboard Admin
4. Logout, lalu login dengan **user**: `siswa@gmail.com` / `password`
5. Setelah login, akan redirect ke Dashboard User (berbeda!)

**✅ Hasil TAHAP 7**: Dashboard sudah jalan! Admin dan user punya tampilan berbeda. Logout juga sudah bisa!

---



---

## 📰 TAHAP 8: FITUR INFO (CRUD INFORMASI)

**Apa yang akan kita buat?**
Fitur untuk kelola informasi sekolah. Semua user bisa lihat, tapi hanya admin yang bisa tambah/edit/hapus.

### Step 1: Controller (Info CRUD Logic)

#### 37. Terminal: Buat InfoController
```bash
php artisan make:controller InfoController
```

#### 38. File: `app/Http/Controllers/InfoController.php`
Ganti **SELURUH ISI** dengan:
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Info;

class InfoController extends Controller
{
    // Tampilkan semua info
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $infos = Info::query()
            ->when($search, function ($query, $search) {
                return $query->where('text', 'like', "%{$search}%");
            })
            ->latest()
            ->get();
        
        return view('infos.index', compact('infos'));
    }

    // Tambah info baru (admin only)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'text' => 'required|string',
        ]);

        Info::create($validated);

        return back()->with('success', 'Info berhasil ditambahkan.');
    }

    // Update info (admin only)
    public function update(Request $request, Info $info)
    {
        $validated = $request->validate([
            'text' => 'required|string',
        ]);

        $info->update($validated);

        return back()->with('success', 'Info berhasil diupdate.');
    }

    // Hapus info (admin only)
    public function destroy(Info $info)
    {
        $info->delete();

        return back()->with('success', 'Info berhasil dihapus.');
    }
}
```

### Step 2: Route (URL untuk Info)

#### 39. File: `routes/web.php`
Tambahkan route info (di dalam middleware 'auth' group):
```php
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Info routes - semua user bisa lihat
    Route::get('/infos', [\App\Http\Controllers\InfoController::class, 'index'])->name('infos.index');
    
    // Route khusus admin untuk kelola info
    Route::middleware('role:admin')->group(function () {
        Route::post('/infos', [\App\Http\Controllers\InfoController::class, 'store'])->name('infos.store');
        Route::put('/infos/{info}', [\App\Http\Controllers\InfoController::class, 'update'])->name('infos.update');
        Route::delete('/infos/{info}', [\App\Http\Controllers\InfoController::class, 'destroy'])->name('infos.destroy');
    });
});
```

### Step 3: View (Halaman Info)

> **CATATAN**: File `infos/index.blade.php` sudah ada di project Anda dengan kode lengkap (188 baris) termasuk modal dan JavaScript. Anda bisa menggunakan file yang sudah ada atau copy kode di bawah.

**Fitur dalam view**:
- Tabel list semua info
- Search info (live search)
- Tombol "Tambah Info" (admin only)
- Tombol "Edit" dan "Hapus" (admin only)
- Modal untuk tambah dan edit info

**✅ Hasil TAHAP 8**: Fitur Info sudah jalan! Admin bisa CRUD info, User bisa lihat info.

---

## 👥 TAHAP 9: FITUR USER MANAGEMENT (ADMIN ONLY)

**Apa yang akan kita buat?**
Fitur untuk admin mengelola semua user (tambah, edit role, hapus).

### Step 1: Controller (User CRUD Logic)

#### 40. Terminal: Buat UserController
```bash
php artisan make:controller UserController
```

#### 41. File: `app/Http/Controllers/UserController.php`
Ganti **SELURUH ISI** dengan:
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Tampilkan semua user dengan search
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $users = User::query()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->paginate(10);
        
        return view('users.index', compact('users'));
    }

    // Form tambah user (halaman terpisah - opsional)
    public function create()
    {
        return view('users.create');
    }

    // Simpan user baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'in:admin,user'],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    // Form edit user (halaman terpisah - opsional)
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    // Update user (termasuk role)
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', 'in:admin,user'],
        ]);

        // Update password jika diisi
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return back()->with('success', 'User berhasil diupdate.');
    }

    // Hapus user
    public function destroy(User $user)
    {
        // Tidak bisa hapus diri sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri.');
        }

        $user->delete();

        return back()->with('success', 'User berhasil dihapus.');
    }
}
```

### Step 2: Route (URL untuk User Management)

#### 42. File: `routes/web.php`
Tambahkan route user (di dalam middleware 'role:admin' group):
```php
    // Route khusus admin untuk kelola info dan user
    Route::middleware('role:admin')->group(function () {
        // Info Management
        Route::post('/infos', [\App\Http\Controllers\InfoController::class, 'store'])->name('infos.store');
        Route::put('/infos/{info}', [\App\Http\Controllers\InfoController::class, 'update'])->name('infos.update');
        Route::delete('/infos/{info}', [\App\Http\Controllers\InfoController::class, 'destroy'])->name('infos.destroy');
        
        // User Management (TAMBAHKAN INI)
        Route::get('/users', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [\App\Http\Controllers\UserController::class, 'create'])->name('users.create');
        Route::post('/users', [\App\Http\Controllers\UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [\App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [\App\Http\Controllers\UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');
    });
```

### Step 3: View (Halaman User Management)

> **CATATAN**: File `users/index.blade.php` sudah ada di project Anda (112 baris) dengan fitur:
> - Tabel list semua user dengan pagination
> - Search user
> - Dropdown inline untuk ubah role langsung
> - Tombol hapus user
> - Form hidden untuk auto-submit saat ubah role
>
> Anda bisa menggunakan file yang sudah ada.

### Step 4: Testing Fitur User Management

#### 43. Browser: Test User Management
1. Login sebagai **admin**: `admin@gmail.com` / `password`
2. Klik menu "Pengguna" di sidebar
3. Coba:
   - Search user dengan nama
   - Ubah role user dari dropdown (auto-save!)
   - Hapus user (tidak bisa hapus diri sendiri)
4. Logout, login sebagai **user**: `siswa@gmail.com` / `password`
5. Menu "Pengguna" tidak muncul di sidebar (karena bukan admin)
6. Coba akses manual `http://localhost:8000/users` - akan error 403 Forbidden!

**✅ Hasil TAHAP 9**: Fitur User Management sudah jalan! Admin bisa kelola user, user biasa tidak bisa akses.

---

## 👤 TAHAP 10: FITUR PROFILE (EDIT PROFIL SENDIRI)

**Apa yang akan kita buat?**
Fitur agar semua user (admin dan user biasa) bisa edit profil sendiri (nama dan email).

### Step 1: Controller (Profile Logic)

#### 44. Terminal: Buat ProfileController
```bash
php artisan make:controller ProfileController
```

#### 45. File: `app/Http/Controllers/ProfileController.php`
Ganti **SELURUH ISI** dengan:
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    //Tampilkan form edit profil
    public function edit()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    // Update profil
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->update($validated);

        return back()->with('success', 'Profil berhasil diperbaharui.');
    }
}
```

### Step 2: Route (URL untuk Profile)

#### 46. File: `routes/web.php`
Tambahkan route profile (di dalam middleware 'auth' group, SEBELUM middleware 'role:admin'):
```php
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Info routes
    Route::get('/infos', [\App\Http\Controllers\InfoController::class, 'index'])->name('infos.index');
    
    // Profile routes (TAMBAHKAN INI - semua user bisa akses)
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    
    // Route khusus admin...
    Route::middleware('role:admin')->group(function () {
        // ... (route admin tetap di sini)
    });
});
```

### Step 3: View (Halaman Profile)

> **CATATAN**: File `profile/edit.blade.php` sudah ada di project Anda (73 baris) dengan fitur:
> - Form edit nama dan email
> - Display role (read-only, tidak bisa diubah sendiri)
> - Validation error display
> - Success message
>
> Anda bisa menggunakan file yang sudah ada.

### Step 4: Testing Fitur Profile

#### 47. Browser: Test Profile
1. Login sebagai **admin** atau **user**
2. Klik menu "Profil" di sidebar
3. Ubah nama, misal dari "Admin Bondowoso" jadi "Admin Baru"
4. Ubah email jika mau
5. Klik "Simpan Perubahan"
6. Cek sidebar - nama sudah berubah!
7. Logout dan login lagi dengan email/password baru

**✅ Hasil TAHAP 10**: Fitur Profile sudah jalan! Semua user bisa edit profil sendiri.

---

## ✅ SELESAI! - APLIKASI SUDAH LENGKAP

Selamat! 🎉 Anda sudah berhasil membuat **Aplikasi Informasi Sekolah Bondowoso** lengkap dengan pendekatan **feature-by-feature**!

### 📋 Fitur yang Sudah Dibuat

#### **Foundation**:
- ✅ Laravel 11 + Tailwind CSS v3
- ✅ Database (Users dengan role, Infos)
- ✅ Layout Template (Master + Sidebar)
- ✅ Middleware Role-Based Protection

#### **Features**:
- ✅ **Login** - User bisa masuk dengan email/password
- ✅ **Register** - User bisa daftar akun baru
- ✅ **Dashboard** - Tampilan berbeda untuk admin dan user
- ✅ **Info CRUD** - Admin kelola info, user lihat info
- ✅ **User Management** - Admin kelola user (tambah, edit role, hapus)
- ✅ **Profile** - Semua user edit profil sendiri
- ✅ **Logout** - User bisa keluar

### 🧪 Testing Lengkap

Pastikan semua fitur jalan dengan test ini:

1. **Public Access**:
   - Buka `/` → Redirect ke `/login` ✅
   - Buka `/login` → Bisa lihat form login ✅
   - Buka `/register` → Bisa lihat form register ✅

2. **Register**:
   - Daftar user baru → Berhasil dan auto-login ✅

3. **Login**:
   - Login admin → Masuk ke Dashboard Admin ✅
   - Login user → Masuk ke Dashboard User ✅

4. **Dashboard**:
   - Admin lihat total info dan status ✅
   - User lihat total info dan welcome message ✅

5. **Info (Semua User)**:
   - Lihat list info ✅
   - Search info ✅

6. **Info (Admin Only)**:
   - Tambah info baru ✅
   - Edit info ✅
   - Hapus info ✅

7. **User Management (Admin Only)**:
   - Lihat list user ✅
   - Search user ✅
   - Ubah role user (dropdown) ✅
   - Hapus user (tidak bisa hapus diri sendiri) ✅
   - User biasa coba akses → 403 Forbidden ✅

8. **Profile (Semua User)**:
   - Edit nama dan email ✅
   - Role tidak bisa diubah sendiri ✅

9. **Logout**:
   - Klik logout → Redirect ke login ✅

### 📁 Struktur File yang Sudah Dibuat

```
ujikom-bondowoso/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php          ✅
│   │   │   ├── DashboardController.php     ✅
│   │   │   ├── InfoController.php          ✅
│   │   │   ├── UserController.php          ✅
│   │   │   └── ProfileController.php       ✅
│   │   └── Middleware/
│   │       └── CheckRole.php               ✅
│   └── Models/
│       ├── User.php (updated)              ✅
│       └── Info.php                        ✅
├── database/
│   ├── migrations/
│   │   ├── xxxx_create_users_table.php (updated) ✅
│   │   └── xxxx_create_infos_table.php     ✅
│   └── seeders/
│       └── DatabaseSeeder.php              ✅
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── app.blade.php               ✅
│   │   │   └── sidebar.blade.php           ✅
│   │   ├── auth/
│   │   │   ├── login.blade.php             ✅
│   │   │   └── register.blade.php          ✅
│   │   ├── dashboard/
│   │   │   ├── admin.blade.php             ✅
│   │   │   └── user.blade.php              ✅
│   │   ├── infos/
│   │   │   └── index.blade.php             ✅
│   │   ├── users/
│   │   │   └── index.blade.php             ✅
│   │   └── profile/
│   │       └── edit.blade.php              ✅
│   └── css/
│       └── app.css (updated)               ✅
├── routes/
│   └── web.php (updated)                   ✅
├── bootstrap/
│   └── app.php (updated)                   ✅
├── tailwind.config.js (updated)            ✅
├── postcss.config.js (updated)             ✅
└── .env (updated)                          ✅
```

### 🎓 Apa yang Sudah Dipelajari?

Dengan mengikuti tutorial ini, Anda sudah belajar:

1. **Laravel Fundamentals**:
   - Project setup dan konfigurasi
   - Migration dan Eloquent Model
   - Database Seeder
   - MVC Pattern (Model-View-Controller)

2. **Authentication & Authorization**:
   - Login/Register system
   - Session management
   - Middleware untuk proteksi route
   - Role-based access control

3. **CRUD Operations**:
   - Create, Read, Update, Delete data
   - Form validation
   - Flash messages

4. **Frontend**:
   - Blade templating
   - Layout inheritance
   - Tailwind CSS styling
   - Responsive design

5. **Best Practices**:
   - Feature-by-feature development
   - Incremental testing
   - Clean code organization
   - Security (password hashing, CSRF protection)

### 🚀 Next Steps (Opsional)

Aplikasi sudah lengkap, tapi bisa dikembangkan lagi:

1. **Fitur Tambahan**:
   - Forgot password
   - Email verification
   - Upload foto profil
   - Activity logs
   - Export data to Excel/PDF

2. **Deployment**:
   - Deploy ke shared hosting
   - Deploy ke VPS (Digital Ocean, AWS)
   - Setup production database

3. **Optimization**:
   - Cache strategy
   - Database indexing
   - Image optimization
   - API development

### 💡 Tips Development

1. **Selalu test setiap fitur** setelah dibuat
2. **Commit ke Git** setelah setiap fitur selesai
3. **Buat backup database** secara berkala
4. **Dokumentasikan** perubahan yang Anda buat
5. **Follow Laravel best practices**

---

**Terima kasih telah mengikuti tutorial ini! Happy Coding! 🎉**

*Dibuat dengan ❤️ untuk memudahkan pembelajaran Laravel step-by-step*
