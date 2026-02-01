# 🏫 Aplikasi Informasi Sekolah (Bondowoso)

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)

**Aplikasi manajemen informasi sekolah dengan role-based access control**  
*Dibangun dengan Laravel 11 + Tailwind CSS v3*

[📖 Tutorial Lengkap](STEP_BY_STEP_CODING_GUIDE.md) • [🚀 Demo](#demo) • [📋 Fitur](#fitur-utama)

</div>

---

## ✨ Fitur Utama

### 🔐 Authentication
- ✅ **Login & Register** dengan UI modern
- ✅ Password visibility toggle (show/hide)
- ✅ Session management
- ✅ Running text informasi di halaman login

### 👥 Role-Based Access Control
- 👑 **Admin** - Akses penuh untuk kelola sistem
- 👤 **User** - Akses terbatas untuk melihat informasi

### 📊 Dashboard
- Dashboard berbeda untuk Admin dan User
- Ringkasan sistem dan statistik
- Welcome message personal

### 📰 Manajemen Informasi (Info)
- ✅ **Admin**: CRUD informasi lengkap
- ✅ **User**: View informasi saja
- ✅ Live search (AJAX)
- ✅ Modal untuk tambah/edit data

### 👥 Manajemen User (Admin Only)
- ✅ Lihat semua user dengan pagination
- ✅ Search user by nama/email
- ✅ Ubah role user (Admin/User) inline dengan dropdown
- ✅ Hapus user (tidak bisa hapus diri sendiri)
- 🔒 Protected dengan middleware `role:admin`

### 👤 Profil User
- ✅ Edit nama dan email
- ✅ Role ditampilkan (read-only)
- ✅ Validation dan flash messages

---

## 🛠️ Tech Stack

| Technology | Version | Purpose |
|-----------|---------|---------|
| **Laravel** | 11.x | Backend Framework |
| **Tailwind CSS** | 3.x | Utility-first CSS |
| **MySQL** | 8.0+ | Database |
| **Vite** | 5.x | Frontend Build Tool |
| **Blade** | - | Templating Engine |

---

## 📋 Persyaratan Sistem

- PHP >= 8.2
- Composer >= 2.0
- Node.js >= 18.x & NPM
- MySQL >= 8.0
- Git

---

## 🚀 Cara Instalasi

### 1. Clone Repository
```bash
git clone https://github.com/username/ujikom-bondowoso.git
cd ujikom-bondowoso
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### 3. Konfigurasi Environment
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

Edit file `.env` untuk konfigurasi database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ujikom_bondowoso
DB_USERNAME=root
DB_PASSWORD=
```

> **📝 Note**: Buat database `ujikom_bondowoso` di MySQL terlebih dahulu!

### 4. Migrasi & Seeding Database
```bash
php artisan migrate:fresh --seed
```

Data dummy yang dibuat:
- **Admin**: `admin@gmail.com` / `password`
- **User**: `siswa@gmail.com` / `password`
- **Info**: 3 informasi sample

### 5. Jalankan Aplikasi
Buka **dua terminal berbeda**:

```bash
# Terminal 1: Laravel Development Server
php artisan serve
```

```bash
# Terminal 2: Vite (Tailwind CSS Compiler)
npm run dev
```

Buka browser dan akses: **http://localhost:8000**

---

## 🎯 Workflow Development (Feature-by-Feature)

Aplikasi ini dibangun dengan pendekatan **feature-by-feature**, bukan component-by-component. Artinya setiap fitur dibangun lengkap (Controller → Route → View → Test) sebelum lanjut ke fitur berikutnya.

### Foundation (TAHAP 1-4)
1. ⚙️ **Konfigurasi Dasar** - Laravel + Tailwind CSS setup
2. 🗄️ **Database Setup** - Migration, Model, Seeder
3. 🎨 **Layout Template** - Master template + Sidebar
4. 🔐 **Middleware** - Role-based protection

### Features (TAHAP 5-10)
5. 🔑 **Login** - Authentication dengan email/password
6. 📝 **Register** - Pendaftaran user baru
7. 📊 **Dashboard** - Halaman utama berbeda untuk admin & user
8. 📰 **Info CRUD** - Kelola informasi (admin), view (user)
9. 👥 **User Management** - Kelola user (admin only)
10. 👤 **Profile** - Edit profil sendiri

> **📖 Tutorial Lengkap**: Lihat [`STEP_BY_STEP_CODING_GUIDE.md`](STEP_BY_STEP_CODING_GUIDE.md) untuk panduan detail building aplikasi dari nol!

---

## 📁 Struktur Folder

```
ujikom-bondowoso/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php          # Login, Register, Logout
│   │   │   ├── DashboardController.php     # Dashboard
│   │   │   ├── InfoController.php          # CRUD Info
│   │   │   ├── UserController.php          # CRUD User (Admin)
│   │   │   └── ProfileController.php       # Edit Profile
│   │   └── Middleware/
│   │       └── CheckRole.php               # Role-based protection
│   └── Models/
│       ├── User.php                        # User model (dengan role)
│       └── Info.php                        # Info model
├── database/
│   ├── migrations/
│   │   ├── xxxx_create_users_table.php     # + kolom role
│   │   └── xxxx_create_infos_table.php     # Tabel info
│   └── seeders/
│       └── DatabaseSeeder.php              # Data dummy
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── app.blade.php               # Master template
│   │   │   └── sidebar.blade.php           # Sidebar navigation
│   │   ├── auth/
│   │   │   ├── login.blade.php             # Halaman login
│   │   │   └── register.blade.php          # Halaman register
│   │   ├── dashboard/
│   │   │   ├── admin.blade.php             # Dashboard admin
│   │   │   └── user.blade.php              # Dashboard user
│   │   ├── infos/
│   │   │   └── index.blade.php             # Kelola info
│   │   ├── users/
│   │   │   └── index.blade.php             # Kelola user
│   │   └── profile/
│   │       └── edit.blade.php              # Edit profil
│   └── css/
│       └── app.css                         # Tailwind directives
├── routes/
│   └── web.php                             # Route definitions
├── tailwind.config.js                      # Tailwind config
└── .env                                    # Environment variables
```

---

## 🔒 Middleware & Authorization

### CheckRole Middleware
Middleware custom untuk proteksi route berdasarkan role:

```php
// Registrasi di bootstrap/app.php
$middleware->alias([
    'role' => \App\Http\Middleware\CheckRole::class,
]);

// Penggunaan di routes/web.php
Route::middleware('role:admin')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    // ... route admin lainnya
});
```

**Cara Kerja**:
1. Request masuk ke route yang di-protect
2. Middleware mengecek apakah user sudah login
3. Middleware mengecek apakah role user sesuai
4. Jika tidak → Error 403 Forbidden
5. Jika sesuai → Request dilanjutkan ke controller

---

## 🧪 Testing

### Login Testing
```
✅ Public access: / → redirect ke /login
✅ Login admin: admin@gmail.com / password → Dashboard Admin
✅ Login user: siswa@gmail.com / password → Dashboard User
✅ Invalid credentials → Error message
```

### Feature Testing
```
✅ Register user baru → Auto login ke dashboard
✅ Dashboard berbeda untuk admin vs user
✅ Admin bisa CRUD info, user hanya view
✅ Admin bisa CRUD user, user error 403
✅ Semua user bisa edit profil sendiri
✅ Logout → Redirect ke login
```

### Authorization Testing
```
✅ User coba akses /users → 403 Forbidden
✅ Admin akses /users → Success
✅ Sidebar "Pengguna" hanya muncul untuk admin
```

---

## 🎓 Apa yang Dipelajari?

Dengan membangun aplikasi ini, Anda akan belajar:

### Laravel Fundamentals
- ✅ Project setup & configuration
- ✅ Migration & Eloquent ORM
- ✅ Database Seeder
- ✅ MVC Pattern

### Authentication & Authorization
- ✅ Custom login/register (tanpa starter kit)
- ✅ Session management
- ✅ Middleware custom
- ✅ Role-based access control

### CRUD Operations
- ✅ Create, Read, Update, Delete
- ✅ Form validation
- ✅ Flash messages
- ✅ Eloquent relationships

### Frontend Development
- ✅ Blade templating
- ✅ Layout inheritance
- ✅ Tailwind CSS utility classes
- ✅ Responsive design
- ✅ Modal implementation
- ✅ AJAX live search

### Best Practices
- ✅ Feature-by-feature development
- ✅ Incremental testing
- ✅ Clean code organization
- ✅ Security (password hashing, CSRF protection)

---

## 📸 Screenshots

### Halaman Login
![Login Page](screenshots/login.png)

### Dashboard Admin
![Admin Dashboard](screenshots/dashboard-admin.png)

### Manajemen User (Admin)
![User Management](screenshots/users.png)

### Manajemen Info
![Info Management](screenshots/infos.png)

---

## 🤝 Kontribusi

Kontribusi sangat diterima! Jika Anda ingin berkontribusi:

1. Fork repository ini
2. Buat branch fitur baru (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

---

## 📝 License

Project ini dibuat untuk keperluan pembelajaran dan ujian kompetensi.

---

## 👨‍💻 Author

**Keyvano**

- GitHub: [@keyvano07](https://github.com/keyvano07)
- Repository: [Uji-Kompentensi-Aplikasi-Informasi-Sekolah](https://github.com/keyvano07/Uji-Kompentensi-Aplikasi-Informasi-Sekolah)

---

## 🙏 Acknowledgments

- Laravel Framework
- Tailwind CSS
- Font Awesome (untuk icons)
- Semua kontributor open source

---

## 📚 Dokumentasi Lengkap

Untuk tutorial step-by-step lengkap membangun aplikasi ini dari nol, lihat:

📖 **[STEP_BY_STEP_CODING_GUIDE.md](STEP_BY_STEP_CODING_GUIDE.md)**

Tutorial ini mencakup:
- Instalasi dan konfigurasi dasar
- Database setup lengkap
- Setiap fitur dibangun lengkap (Controller → Route → View → Test)
- Code lengkap dan siap copy-paste
- Testing guide untuk setiap fitur

---

<div align="center">

**Dibuat dengan ❤️ menggunakan Laravel & Tailwind CSS**

⭐ Star repository ini jika bermanfaat!

</div>
