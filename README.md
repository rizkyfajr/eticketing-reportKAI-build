# E-Ticketing & Pelaporan KAI

Sistem informasi untuk manajemen laporan kerja, maintenance order, dan checksheet operasional PT KAI dengan multi-level admin (Super Admin & Admin Wilayah).

---

## ✨ Fitur Utama

- 📊 Dashboard analytics dengan statistik real-time
- 👥 Multi-level admin dengan regional scope (Super Admin & Admin Wilayah)
- 📝 Working Report & Maintenance Order management
- ✅ Checksheet & Readiness Assessment
- 🔧 Master Data Management (Mesin, Klasifikasi, Region)
- 🔐 Permission-based access control
- 🌍 Multi-region support (DAOP/Divre)
- 🌓 Dark and light themes
- 🌐 Multi-language support

---

## 📋 Requirements

- PHP 8.1 or higher
- MySQL 8.0 or higher
- Node JS 14 or higher
- Composer
- NPM

---

## 🚀 Quick Installation

### Opsi 1: Menggunakan Script Otomatis (RECOMMENDED)

**Windows:**
```bash
install.bat
```

**Linux/Mac:**
```bash
chmod +x install.sh
./install.sh
```

### Opsi 2: Manual Installation

Lihat dokumentasi lengkap di: **[SETUP_INSTALLATION_GUIDE.md](SETUP_INSTALLATION_GUIDE.md)**

#### Ringkas:

```bash
# 1. Clone & Install
git clone https://github.com/rizkyfajr/eticketing-reportKAI-build.git
cd eticketing-reportKAI-build
composer install
npm install

# 2. Setup Environment
cp .env.example .env
php artisan key:generate
# Edit .env sesuaikan database

# 3. Buat Database
# CREATE DATABASE eticketing_kai;

# 4. Migration & Seeding
php artisan migrate
php artisan db:seed --class=InitialSeeder
php artisan db:seed --class=MenuSeeder
php artisan db:seed --class=MasterRegionSeeder
php artisan db:seed --class=MasterClassificationSeeder
php artisan db:seed --class=MasterDataPermissionSeeder
php artisan db:seed --class=AdminWilayahCompleteSeeder

# 5. Clear Cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# 6. Build & Run
npm run build
php artisan serve
```

---

## 🔑 Default Login

### Super Admin
```
URL      : http://localhost:8000/login
Username : su
Password : password
```

### Admin Wilayah (Demo Users)
```
URL      : http://localhost:8000/login
Username : admin.daop1jakarta (atau daop2, daop3, dst)
Password : password
```

⚠️ **PENTING:** Ganti password default setelah login pertama kali!

---

## 📚 Dokumentasi

- **[SETUP_INSTALLATION_GUIDE.md](SETUP_INSTALLATION_GUIDE.md)** - Panduan instalasi lengkap
- **[ADMIN_WILAYAH_GUIDE.md](ADMIN_WILAYAH_GUIDE.md)** - Dokumentasi Admin Wilayah
- **[ADMIN_WILAYAH_QUICKSTART.md](ADMIN_WILAYAH_QUICKSTART.md)** - Quick reference Admin Wilayah

---

## 🎯 Akses Menu Berdasarkan Role

### Super Admin
✅ Dashboard  
✅ Builtin (Permission, Role, User, Menu, Translation)  
✅ Activities (Login History)  
✅ Laporin (Data Laporin, Laporan Terkirim, dll)  
✅ Feedback  
✅ List Kerjaan  
✅ **Akses ke SEMUA region**

### Admin Wilayah
✅ Dashboard  
✅ Master Data (Master Mesin, Master Klasifikasi)  
✅ Working Order (Laporan Kerja, Warming Up, Hasil Kerja)  
✅ Maintenance Order  
✅ Check Sheet (Form Check Sheet, Check Sheet Harian)  
❌ Builtin (User, Role, Permission, Menu)  
🔒 **Hanya akses data di region yang ditugaskan**

---

## 🔧 Development

```bash
# Development server
php artisan serve

# Vite development server (hot reload)
npm run dev

# Build untuk production
npm run build

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

---

## 🐛 Troubleshooting

Lihat troubleshooting lengkap di: **[SETUP_INSTALLATION_GUIDE.md](SETUP_INSTALLATION_GUIDE.md#troubleshooting)**

### Error Umum:

**Menu tidak muncul:**
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
# Refresh browser (Ctrl + F5)
```

**Admin Wilayah melihat semua data:**
```sql
-- Pastikan region_id terisi
UPDATE users SET region_id = 1 WHERE username = 'admin.daop1jakarta';
```

---
![permission-index](https://user-images.githubusercontent.com/59258929/195477625-455c16de-7fd2-40d8-954a-222b7d8d8bb1.png)
![permission-create](https://user-images.githubusercontent.com/59258929/195477640-ba4259c6-d59a-43c8-abb6-8bc4513da753.png)
![permission-update](https://user-images.githubusercontent.com/59258929/195477649-dac35e42-e7ad-49a8-b2e8-e5aeee23c322.png)

## Role
![role-index](https://user-images.githubusercontent.com/59258929/195477702-3e67dde0-3518-4ca0-a76d-fecf6f976c63.png)
![role-create](https://user-images.githubusercontent.com/59258929/195477686-fe3787b9-086a-4557-bdc1-8b94dc6f591c.png)
![role-update](https://user-images.githubusercontent.com/59258929/195477705-c15c5b22-c4ce-4a16-a89b-a0046b25f052.png)

## User
![user-index](https://user-images.githubusercontent.com/59258929/195477741-68baf73e-572a-44a6-8d61-8f8c272a4dfe.png)
![user-create](https://user-images.githubusercontent.com/59258929/195477735-9add4f2c-10d7-4651-bf98-29fa31a8fadb.png)
![user-update](https://user-images.githubusercontent.com/59258929/195477745-2ffb3f4a-ed40-4df0-89d9-3d75f50839b9.png)

## Menu builder
![menu-builder](https://user-images.githubusercontent.com/59258929/195477770-2e5f7591-2e3c-486c-b8d3-8d1fde75e115.png)
![menu-create](https://user-images.githubusercontent.com/59258929/195477773-024f8400-8f64-468f-b293-aca4c6eabf4b.png)
![menu-update](https://user-images.githubusercontent.com/59258929/195477776-e7270888-3e74-47e2-9a63-7c4d670a67d3.png)
![icon-picker](https://user-images.githubusercontent.com/59258929/195477764-48fdc7b9-ac34-4e00-b3e1-07d70a99a6c5.png)

## Login activity
![login-activity](https://user-images.githubusercontent.com/59258929/195477886-c80ca296-85c8-4425-befb-42411f85ec11.png)

## Translation
![translation-index](https://user-images.githubusercontent.com/59258929/195477960-4b329b2c-6ab0-4b87-802c-f38934535c75.png)
