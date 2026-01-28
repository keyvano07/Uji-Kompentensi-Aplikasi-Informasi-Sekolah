# Dokumentasi Teknis & Log Perbaikan

## 1. Error Build Vite: `@tailwindcss/postcss` Missing

### Gejala
Saat menjalankan `npm run dev`, muncul error:
```
[Error] Loading PostCSS Plugin failed: Cannot find module '@tailwindcss/postcss'
```

### Penyebab
Proyek menggunakan Tailwind CSS versi 4 (atau konfigurasi yang mengharapkannya), tetapi paket adapter PostCSS `@tailwindcss/postcss` belum terinstall.

### Solusi
Menjalankan perintah instalasi dependency:
```bash
npm install -D @tailwindcss/postcss
```

---

## 2. Apache & phpMyAdmin Tidak Bisa Diakses (Laragon)

### Gejala
- Browser menampilkan `ERR_CONNECTION_REFUSED` saat membuka `http://localhost/phpmyadmin` atau `http://localhost:8085/phpmyadmin`.
- Indikator Apache di Laragon berwarna hijau (Running), tetapi pengecekan port (`netstat`) menunjukkan tidak ada servis yang berjalan di port 80 atau 8085.
- Log error Apache (`error.log`) kosong atau tidak menunjukkan aktivitas terbaru, tanda Apache gagal saat proses *startup* awal.

### Diagnosa Lanjutan (Deep Dive)
Setelah pengecekan manual via terminal (`httpd.exe -t`), ditemukan error sintaks pada konfigurasi Apache:
```
Syntax error on line 24 of C:/laragon/etc/apache2/fcgid.conf:
Wrapper C:/laragon/bin/php/php-8.3.16-Win32-vs16-x64/php-cgi.exe cannot be accessed
```
Ternyata, Laragon bersikeras menggunakan folder **PHP 8.3.16**, namun folder tersebut **kosong/rusak** (file `php-cgi.exe` hilang). Meskipun versi PHP di UI Laragon sudah diubah ke 8.3.30 (yang sehat), konfigurasi internal Laragon (`fcgid.conf` dan `mod_php.conf`) tidak terupdate dengan benar atau kembali ke pengaturan lama yang rusak.

### Solusi
Karena Laragon terus me-reset konfigurasi ke folder yang rusak, solusi permanen yang dilakukan adalah:
1. **Pindah Port:** Mengubah port Apache ke `8085` untuk menghindari konflik port 80 umum.
2. **Perbaikan Manual File Konfigurasi:** Mengedit `C:/laragon/etc/apache2/mod_php.conf` dan `fcgid.conf` agar menunjuk ke path PHP yang benar.
3. **Penyalinan File Binary PHP:** Menyalin seluruh isi folder PHP yang sehat (`php-8.3.30...`) ke dalam folder yang dicari oleh Laragon (`php-8.3.16...`). Ini "membohongi" sistem agar tetap berjalan meskipun konfigurasi Laragon mengarah ke nama folder yang lama.
4. **Kill Process:** Mematikan paksa process `httpd.exe` yang mungkin tersangkut (`taskkill`).

### Hasil
Apache berhasil berjalan normal (Syntax OK) dan phpMyAdmin dapat diakses kembali di `http://localhost:8085/phpmyadmin`.
