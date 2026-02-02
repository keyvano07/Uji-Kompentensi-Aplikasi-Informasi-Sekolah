# Panduan Menyederhanakan Tailwind CSS di Blade

## 📚 Pola Penamaan Tailwind (Mudah Dihafal!)

### 1. **Spacing (Jarak)**
Format: `{properti}-{ukuran}`

```
p-4    → padding: 1rem (16px)
m-2    → margin: 0.5rem (8px)
px-6   → padding horizontal (left+right)
py-3   → padding vertical (top+bottom)
mt-4   → margin top
mb-6   → margin bottom
gap-4  → gap antara flex/grid items
```

**Skala Angka:** 1 = 0.25rem, 2 = 0.5rem, 4 = 1rem, 6 = 1.5rem, 8 = 2rem, dst.

---

### 2. **Typography (Teks)**
```
text-sm     → ukuran kecil
text-base   → ukuran normal
text-lg     → ukuran besar
text-xl     → extra large
text-2xl    → 2x extra large

font-bold      → tebal
font-medium    → sedang
font-semibold  → semi tebal

text-gray-700  → warna teks abu-abu gelap
text-white     → putih
text-green-600 → hijau
```

---

### 3. **Layout (Tata Letak)**
```
flex            → display flex
flex-col        → arah vertikal
flex-row        → arah horizontal
items-center    → pusatkan item vertikal
justify-between → ruang antara item
justify-center  → pusatkan horizontal

grid            → display grid
grid-cols-2     → 2 kolom
```

---

### 4. **Sizing (Ukuran)**
```
w-full   → width 100%
w-64     → width 16rem
h-32     → height 8rem
max-w-2xl → max-width 42rem
min-h-screen → minimum height layar penuh
```

---

### 5. **Background & Border**
```
bg-white        → background putih
bg-gray-50      → background abu-abu sangat terang
bg-green-900    → background hijau gelap

border          → border 1px
border-2        → border 2px
border-gray-300 → warna border
rounded         → border radius kecil
rounded-lg      → border radius besar
rounded-full    → border radius bulat penuh
```

---

### 6. **Shadow & Effects**
```
shadow-sm    → bayangan kecil
shadow       → bayangan normal
shadow-lg    → bayangan besar

hover:bg-gray-100  → background saat hover
focus:ring-2       → ring saat focus
transition-colors  → animasi transisi warna
```

---

## 🎯 Strategi 1: Ekstrak ke Blade Component

**Masalah:** Class terlalu panjang dan berulang
```blade
<!-- SEBELUM: Panjang & Susah -->
<button class="px-6 py-2 bg-green-900 text-white text-sm font-medium rounded-lg hover:bg-green-800 transition-colors">
    Simpan
</button>
```

**Solusi:** Buat Blade Component

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

**Cara Pakai:**
```blade
<!-- SESUDAH: Pendek & Jelas -->
<x-button>Simpan</x-button>
<x-button variant="secondary">Batal</x-button>
<x-button variant="danger">Hapus</x-button>
```

---

## 🎯 Strategi 2: Kelompokkan Class Berdasarkan Fungsi

**SEBELUM (Susah Dibaca):**
```blade
<div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 min-h-[500px] max-w-4xl mx-auto mt-8">
```

**SESUDAH (Dikelompokkan):**
```blade
<div class="
    bg-white                        <!-- Warna -->
    rounded-lg shadow-sm            <!-- Shape & Shadow -->
    border border-gray-100          <!-- Border -->
    p-6                             <!-- Padding -->
    min-h-[500px] max-w-4xl         <!-- Ukuran -->
    mx-auto mt-8                    <!-- Posisi -->
">
```

---

## 🎯 Strategi 3: Buat "Class Shortcut" dengan Component

**File:** `resources/views/components/card.blade.php`
```blade
<div {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow-sm border border-gray-100 p-6']) }}>
    {{ $slot }}
</div>
```

**File:** `resources/views/components/input.blade.php`
```blade
@props(['label', 'name', 'type' => 'text'])

<div class="mb-6">
    @if($label)
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-2">
        {{ $label }}
    </label>
    @endif
    <input 
        type="{{ $type }}" 
        name="{{ $name }}" 
        id="{{ $name }}" 
        {{ $attributes->merge(['class' => 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-shadow text-gray-700']) }}
    >
</div>
```

**Cara Pakai:**
```blade
<!-- SEBELUM -->
<div class="mb-6">
    <label class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg...">
</div>

<!-- SESUDAH -->
<x-input label="Nama" name="name" />
```

---

## 📖 Cheat Sheet: Class Yang Sering Dipakai

```
CONTAINER/CARD:
bg-white rounded-lg shadow-sm border border-gray-100 p-6

BUTTON PRIMARY:
px-6 py-2 bg-green-900 text-white rounded-lg hover:bg-green-800 transition-colors

BUTTON SECONDARY:
px-6 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400

INPUT FIELD:
w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500

FLEX CENTER:
flex items-center justify-center

SPACING STANDAR:
p-4 (padding), m-4 (margin), gap-4 (jarak antar item)
```

---

## 💡 Tips Menghafal

1. **Mulai Dari Yang Sering Dipakai:**
   - `flex items-center` → hampir selalu bareng
   - `px-4 py-2` → padding standar
   - `rounded-lg` → bentuk rounded standar

2. **Pola Prefix:**
   - `p-` = padding
   - `m-` = margin  
   - `text-` = font/warna teks
   - `bg-` = background
   - `border-` = border
   - `hover:` = saat mouse di atas
   - `focus:` = saat input di-fokus

3. **Angka = Kelipatan 4px:**
   - 1 = 4px
   - 2 = 8px
   - 4 = 16px
   - 6 = 24px
   - 8 = 32px

---

## 🚀 Implementasi Praktis

### 1. Buat Folder Components
```
resources/views/components/
├── button.blade.php
├── card.blade.php
├── input.blade.php
└── badge.blade.php
```

### 2. Ganti View Lama Secara Bertahap
Mulai dari halaman yang paling sering diedit, lalu refactor perlahan.

### 3. Dokumentasikan Pattern Anda
Buat file `DESIGN_SYSTEM.md` di root project untuk catat pattern yang Anda pakai.

---

Selamat! Sekarang Tailwind di project Anda lebih rapi dan mudah dihafal! 🎉
