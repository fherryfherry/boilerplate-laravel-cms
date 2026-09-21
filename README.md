# CRUD Admin Laravel Project

Proyek ini adalah sistem manajemen (Admin Panel) sederhana yang dibangun dengan Laravel 12, mengintegrasikan tema kustom dengan fitur Dark Mode dan dialog konfirmasi modern.

## 🏗️ Arsitektur & Struktur Proyek

Proyek ini mengikuti pola arsitektur **MVC (Model-View-Controller)** standar Laravel dengan beberapa penyesuaian untuk area admin:

- **Namespace Admin**: Controller admin dikelompokkan dalam `App\Http\Controllers\Admin` untuk pemisahan logika yang lebih bersih.
- **Resourceful Routing**: Menggunakan `Route::resource` untuk manajemen produk.
- **Blade Templating**: Sistem layouting menggunakan `@extends`, `@section`, dan `@yield` untuk reusability komponen UI.
- **Middleware Auth**: Mengamankan rute `/admin/*` menggunakan middleware `auth` bawaan Laravel.
- **RBAC Layer**: Authorization menggunakan `spatie/laravel-permission` dengan guard `web`, permission granular per action, dan bypass penuh untuk role `super-admin`.

## 🛠️ Tech Stack & Library

### Backend
- **Framework**: Laravel 12.x
- **Database**: SQLite (Default, tersimpan di `database/database.sqlite`)
- **PHP**: ^8.1
- **Authorization**: [spatie/laravel-permission](https://spatie.be/docs/laravel-permission)

### Frontend
- **CSS Framework**: [Tailwind CSS](https://tailwindcss.com/) (via CDN dengan konfigurasi kustom)
- **Icons**: [Material Symbols Outlined](https://fonts.google.com/icons) (Google Fonts)
- **Fonts**: Space Grotesk & Public Sans
- **Dialog & Notifications**: [SweetAlert2](https://sweetalert2.github.io/)

## ⚙️ Konfigurasi Utama

- **Dark Mode**: Implementasi menggunakan class-based dark mode Tailwind yang statusnya disimpan di `localStorage` browser.
- **Permission Registry**: Semua permission inti didefinisikan terpusat di `App\Support\AccessControl`.
- **Admin Navigation Layout**: Layout navigasi admin bisa dipilih lewat env `ADMIN_NAV_LAYOUT` dengan opsi `sidebar` atau `topbar`.
- **Admin Login Layout**: Tampilan halaman login bisa dipilih lewat env `ADMIN_LOGIN_LAYOUT` dengan tiga model UI.

- **Database**: Dikonfigurasi di file `.env` menggunakan `DB_CONNECTION=sqlite`.
- **Auth Guard**: Menggunakan guard `web` standar dengan redirect login kustom ke `/admin/login`.
- **Route Provider**: `HOME` path diubah ke `/admin/dashboard` di `App\Providers\RouteServiceProvider`.

### Navigation Layout via ENV

Admin panel mendukung dua mode navigasi desktop:

- `ADMIN_NAV_LAYOUT=sidebar` → menu tampil di panel kiri
- `ADMIN_NAV_LAYOUT=topbar` → menu tampil di navbar atas

Konfigurasi ini dibaca dari `config/admin.php`.

Jika Anda mengubah nilai env pada environment yang memakai config cache, jalankan:

```bash
php artisan optimize:clear
```

Lalu restart server aplikasi bila diperlukan.

### Login Layout via ENV

Halaman login admin mendukung tiga model tampilan:

- `ADMIN_LOGIN_LAYOUT=panel` → card login tunggal di tengah
- `ADMIN_LOGIN_LAYOUT=split` → split screen dengan panel informatif di sisi kiri
- `ADMIN_LOGIN_LAYOUT=spotlight` → hero layout dengan form floating di sisi kanan

Konfigurasi ini dibaca dari `config/admin.php`.

Jika Anda mengubah nilainya pada environment yang memakai config cache, jalankan:

```bash
php artisan optimize:clear
```

Lalu refresh browser atau restart server aplikasi bila diperlukan.

## 🔐 RBAC / Role-Based Access Control

Implementasi RBAC pada proyek ini memakai `spatie/laravel-permission` dan berlaku untuk area admin web.

### Konsep Utama

- **Guard**: Semua role dan permission menggunakan guard `web`.
- **Role Sistem**: `super-admin`
- **Super Admin Bypass**: User dengan role `super-admin` otomatis lolos semua pengecekan authorization lewat `Gate::before`.
- **Permission Granular**: Permission dipisah per aksi CRUD agar fleksibel saat role bertambah.

### Permission Bawaan

Permission inti saat ini didefinisikan di `App\Support\AccessControl`:

- `dashboard.view`
- `users.view`, `users.create`, `users.update`, `users.delete`
- `roles.view`, `roles.create`, `roles.update`, `roles.delete`

### Role Bawaan

Saat menjalankan seeder, sistem akan otomatis membuat:

- role `super-admin`
- semua permission inti
- assign semua permission ke `super-admin`
- assign role `super-admin` ke user `admin@example.com`

Seeder yang menangani ini ada di `Database\Seeders\RolePermissionSeeder`.

### Route Admin yang Diproteksi

Area admin yang sekarang memakai permission:

- `/admin/dashboard` → `dashboard.view`
- `/admin/users/*` → permission `users.*` sesuai aksi
- `/admin/roles/*` → permission `roles.*` sesuai aksi
- `/admin/profile` tetap `auth` only karena bersifat self-service

### Perilaku UI

- Menu admin, baik pada mode sidebar maupun topbar, hanya menampilkan item yang memang boleh diakses user.
- Tombol action seperti **Add**, **Edit**, dan **Delete** akan di-hide jika user tidak punya permission terkait.
- Route/controller tetap memvalidasi permission walaupun tombol disembunyikan di UI.
- Unauthorized access untuk user yang sudah login akan menghasilkan **403**, bukan redirect ke login.

### Safety Rules

Aturan proteksi yang aktif saat ini:

- role `super-admin` tidak bisa diubah nama dari UI
- role `super-admin` tidak bisa dihapus
- user `super-admin` terakhir tidak bisa dihapus
- role `super-admin` terakhir tidak bisa dilepas dari akun jika itu membuat sistem tidak punya `super-admin`
- role baru selalu dipaksa memiliki `dashboard.view` agar user tidak dead-end setelah login

### Cara Pakai RBAC

#### 1. Inisialisasi database

Gunakan:

```bash
php artisan migrate:fresh --seed
```

Perintah ini akan:

- membuat tabel aplikasi
- membuat tabel RBAC package (`roles`, `permissions`, `model_has_roles`, `model_has_permissions`, `role_has_permissions`)
- membuat user admin default
- membuat role `super-admin` dan seluruh permission bawaan

#### 2. Login sebagai super-admin

Kredensial default:

- **Email**: `admin@example.com`
- **Password**: nilai `ADMIN_DEFAULT_PASSWORD` di `.env` (digenerate random saat project dibuat oleh agent-tool)

#### 3. Buat role baru

Dari admin panel:

- buka menu **Roles**
- klik **Add Role**
- isi nama role
- pilih permission yang dibutuhkan
- simpan

Catatan:

- `dashboard.view` akan selalu ikut tersimpan walaupun tidak dicentang manual
- role `super-admin` tidak dapat dikelola dari UI

#### 4. Buat user baru dan assign role

Dari admin panel:

- buka menu **Users**
- klik **Add User**
- isi `name`, `email`, `password`
- pilih satu role
- simpan

V1 memakai assignment **single-role** di form user, walaupun storage package mendukung multi-role.

#### 5. Ubah permission user

Permission user dikelola melalui role:

- edit role di menu **Roles**
- atau pindahkan user ke role lain dari menu **Users**

Proyek ini belum menyediakan direct permission assignment per user dari UI.

## 🚀 Fitur yang Tersedia

### 🔐 Autentikasi Admin
- Halaman Login kustom dengan integrasi tema.
- Mendukung 3 model tampilan login yang bisa dipilih lewat env.
- Kredensial default:
  - **Email**: `admin@example.com`
  - **Password**: nilai `ADMIN_DEFAULT_PASSWORD` di `.env` (digenerate random saat project dibuat oleh agent-tool)
- Fitur Logout aman.

### 📊 Dashboard
- Ringkasan statistik sistem (Revenue, Users, Orders, Conversion Rate).
- UI responsif dengan dukungan tema Light & Dark.

### 👤 Profil Admin
- Pengaturan informasi akun (Nama, Email) dan perubahan kata sandi.

### 📦 Manajemen Produk (CRUD)
- **Create**: Form tambah produk dengan validasi.
- **Read**: Daftar produk dengan fitur pagination.
- **Update**: Edit detail produk yang sudah ada.
- **Delete**: Penghapusan data dengan **Custom Confirmation Dialog (SweetAlert2)** yang mendukung Dark Mode.

### 👥 Manajemen User
- CRUD user admin
- Assign role ke user
- Validasi proteksi agar `super-admin` terakhir tidak bisa hilang

### 🛡️ Manajemen Role
- CRUD role
- Permission matrix per modul
- Proteksi role sistem `super-admin`

### 🌓 Fitur Tema & UI
- **Toggle Dark/Light Mode**: Berfungsi di seluruh area admin dan halaman login.
- **SweetAlert2 Integration**: Notifikasi sukses dan dialog konfirmasi yang cantik.
- **Configurable Navigation Layout**: Desktop navigation bisa dipilih antara sidebar kiri atau topbar atas lewat env.
- **Responsive Mobile Menu**: Navigasi mobile tetap memakai drawer agar konsisten di kedua mode layout.

## 🛠️ Cara Menjalankan Proyek

1. Pastikan PHP dan Composer sudah terinstall.
2. Clone/buka folder proyek.
3. Jalankan `composer install` (jika vendor belum ada).
4. Buat file database jika belum ada: `touch database/database.sqlite`.
5. Jalankan migrasi dan seeder: `php artisan migrate:fresh --seed`.
6. Jalankan server: `php artisan serve`.
7. Akses melalui browser di `http://localhost:8000/admin/login`.

## ✅ Testing

Untuk memastikan auth, RBAC, dan modul admin berjalan:

```bash
php artisan test
```

Coverage utama saat ini mencakup:

- login/logout admin
- seed role dan permission
- authorization `403`
- visibility menu/action berdasarkan permission
- CRUD users
- CRUD roles
- safety rules `super-admin`
- update profile

---

## Project Overview

This is a Laravel 13+ admin panel application built on the TanpaNgoding template.

- **Stack**: Laravel 13, SQLite, Tailwind CSS (CDN), Material Symbols, SweetAlert2
- **Frontend framework**: Plain Blade + controllers. There is no Livewire (or other reactive JS framework) installed in this project — do not scaffold Livewire components, `wire:` attributes, or `@livewire(...)`/`@livewireStyles`/`@livewireScripts` directives.
- **Auth**: Laravel built-in auth, guard `web`, login at `/admin/login`
- **RBAC**: `spatie/laravel-permission` — roles and granular permissions
- **Admin area**: `/admin/*` protected by `auth` middleware + permission checks
- **Navigation**: configurable via `ADMIN_NAV_LAYOUT=sidebar|topbar` in `.env`
- **Dark mode**: class-based Tailwind, state stored in `localStorage`

---

## Agentic Standards

### Before starting any task

1. If the task involves the admin area, open at least one existing admin module (e.g. `app/Http/Controllers/Admin/UserController.php` + its views) to understand the established patterns before writing anything.
2. If unsure about RBAC, read `app/Support/AccessControl.php` and `database/seeders/RolePermissionSeeder.php`.

### Code & pattern rules

- **Always follow existing patterns.** Do not invent new conventions unless the user explicitly requests it.
- **Plain Blade + controller** for all UI, interactive or not: a controller method returns a Blade view, admin views extend `layouts.admin`, and any interactivity (dynamic filtering, live search, modals, etc.) is done with vanilla JS/Alpine.js and fetch/AJAX calls to controller endpoints — never Livewire.
- New admin Blade views must extend `layouts.admin` and use `@section('content')`.
- All delete actions must use SweetAlert2 confirmation, consistent with existing modules.
- Sidebar/topbar menu entries must respect permission visibility (hide if user lacks permission).
- New menu entries go directly below Dashboard, not at the bottom.

### RBAC rules for new modules

When adding a new module that requires access control:
1. Add permission constants to `App\Support\AccessControl` (e.g. `products.view`, `products.create`, etc.)
2. Seed permissions in `RolePermissionSeeder` and assign to `super-admin`
3. Protect routes with `->middleware('can:permission-name')`
4. Hide action buttons in Blade if user lacks the relevant permission
5. Return `403` (not redirect) for unauthorized access from authenticated users

### Dummy data

- Every new module must have a Factory and Seeder with realistic domain-appropriate data.
- Run seeding before finishing so the preview contains usable sample data.

### Placeholder images

Do NOT use Unsplash. Use:
- Realistic: `https://picsum.photos/seed/{slug}/800/600`
- Simple: `https://placehold.co/800x600/e2e8f0/64748b?text=Label`

---

## Key files reference

| File | Purpose |
|------|---------|
| `app/Support/AccessControl.php` | Central permission registry |
| `app/Http/Controllers/Admin/` | All admin controllers |
| `resources/views/admin/` | Admin Blade views (layouts + static pages) |
| `resources/views/admin/layout.blade.php` | Main admin layout |
| `database/seeders/RolePermissionSeeder.php` | Seeds roles & permissions |
| `config/admin.php` | Navigation & login layout config |
| `routes/web.php` | All routes including admin group |

<!-- AI-WIKI-PERSISTENT-MEMORY:START -->
## Persistent Project Wiki

This project uses a markdown wiki as persistent memory for AI agents.

### Required Reading

Before answering user questions or changing the project:

1. Read `wiki/README.md`.
2. Read `wiki/LOG.md` for recent changes.
3. Search relevant files in `wiki/knowledges/` before making project-specific claims.
4. Inspect the actual project files when needed. The wiki augments code inspection; it does not replace it.

### Knowledge Capture

When you discover a validated project fact, workflow, technical decision, bugfix, architecture pattern, integration detail, or command that is likely to help future agents:

1. Create or update a focused knowledge page at `wiki/knowledges/<specific-file-name>.md`.
2. Use this structure in each knowledge page:
   - `# Topic Title`
   - `## Context`
   - `## Validated Knowledge`
   - `## How To Apply`
   - `## Related Files`
   - `## Last Verified`
3. Update the Knowledge Index in `wiki/README.md`.
4. Record the knowledge update in `wiki/LOG.md`.

### Project Change Log

Every project change must be recorded in `wiki/LOG.md` using this format:

```md
## [YYYY-MM-DD HH:mm] type | title

- Summary: What changed or was learned.
- Files: Relevant files or directories.
- Notes: Important context for future agents.
```

Supported log types: `setup`, `change`, `knowledge`, `decision`, `fix`, `note`.

### Wiki Maintenance Rules

- Keep `wiki/README.md` current as the main index and project map.
- Keep knowledge pages specific and reusable.
- Prefer updating an existing knowledge page over creating duplicates.
- Do not log secrets, credentials, private keys, tokens, or sensitive user data.
- Do not treat unverified guesses as knowledge. Mark uncertainty clearly or inspect the project until the fact is verified.
<!-- AI-WIKI-PERSISTENT-MEMORY:END -->

