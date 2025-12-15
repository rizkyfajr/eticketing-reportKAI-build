# 📦 PANDUAN INSTALASI & SETUP DATABASE

Panduan lengkap untuk instalasi project dari awal (fresh installation) tanpa error.

---

## 🔧 PRASYARAT

Sebelum mulai, pastikan sudah terinstall:

- ✅ PHP 8.1 atau lebih tinggi
- ✅ Composer
- ✅ MySQL / MariaDB
- ✅ Node.js & NPM
- ✅ Git

---

## 📋 TAHAPAN INSTALASI

### **STEP 1: Clone & Install Dependencies**

```bash
# Clone repository
git clone https://github.com/rizkyfajr/eticketing-reportKAI-build.git
cd eticketing-reportKAI-build

# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

---

### **STEP 2: Setup Environment**

```bash
# Copy file environment
copy .env.example .env    # Windows
# atau
cp .env.example .env      # Linux/Mac

# Generate application key
php artisan key:generate
```

**Edit file `.env`** sesuaikan dengan database Anda:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=eticketing_kai
DB_USERNAME=root
DB_PASSWORD=your_password_here
```

---

### **STEP 3: Buat Database**

Buka MySQL/phpMyAdmin dan buat database baru:

```sql
CREATE DATABASE eticketing_kai CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

---

### **STEP 4: Jalankan Migration (URUTAN PENTING!)**

Migration harus dijalankan dalam urutan yang benar:

```bash
# Jalankan semua migration
php artisan migrate
```

✅ **Jika berhasil**, akan muncul:

```
Migration table created successfully.
Migrating: 2014_10_12_000000_create_users_table
Migrated:  2014_10_12_000000_create_users_table (XX.XX ms)
Migrating: 2014_10_12_100000_create_password_resets_table
Migrated:  2014_10_12_100000_create_password_resets_table (XX.XX ms)
...
```

❌ **Jika ada error**, lihat [Troubleshooting](#troubleshooting) di bawah.

---

### **STEP 5: Jalankan Seeder (URUTAN SANGAT PENTING!)**

Seeder harus dijalankan dalam urutan yang tepat karena ada dependencies antar data.

#### **5.1. Seeder Dasar (Wajib)**

```bash
# 1. Initial seeder (users, roles, permissions dasar)
php artisan db:seed --class=InitialSeeder

# 2. Menu seeder (sidebar menu)
php artisan db:seed --class=MenuSeeder
```

#### **5.2. Master Data Seeder**

```bash
# 3. Master Region (DAOP/Divre) - WAJIB untuk Admin Wilayah
php artisan db:seed --class=MasterRegionSeeder

# 4. Master Classification
php artisan db:seed --class=MasterClassificationSeeder

# 5. Permissions untuk Master Data
php artisan db:seed --class=MasterDataPermissionSeeder

# 6. Master Machine (optional, bisa skip jika belum ada data)
# php artisan db:seed --class=MasterMachineSeeder

# 7. Pedoman Checksheet (optional)
# php artisan db:seed --class=PedomanSeeder
```

#### **5.3. Admin Wilayah Setup (LENGKAP - 1 PERINTAH)**

```bash
# Setup Admin Wilayah lengkap (permissions + role + menu + demo users)
php artisan db:seed --class=AdminWilayahCompleteSeeder
```

Seeder ini akan:
- ✅ Membuat 37 permissions untuk Admin Wilayah
- ✅ Membuat role `admin-wilayah`
- ✅ Membuat menu lengkap (Dashboard, Master Data, Working Order, Maintenance Order, Check Sheet)
- ✅ Menanyakan apakah ingin membuat demo users (ketik `yes` atau `no`)

**Alternatif (Manual Step-by-Step):**

Jika ingin manual:

```bash
# 1. Buat permissions
php artisan db:seed --class=AdminWilayahPermissionSeeder

# 2. Buat role & menu
php artisan db:seed --class=AdminWilayahRoleSeeder

# 3. (Optional) Buat demo users
php artisan db:seed --class=DemoAdminWilayahSeeder
```

---

### **STEP 6: Clear Cache**

```bash
# Clear semua cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# (Optional) Optimize
php artisan optimize
```

---

### **STEP 7: Build Assets**

```bash
# Development
npm run dev

# Production
npm run build
```

---

### **STEP 8: Jalankan Server**

```bash
# Opsi 1: Laravel development server
php artisan serve

# Opsi 2: Dengan Laragon/XAMPP
# Akses via: http://localhost/eticketing-reportKAI-build/public
```

---

## ✅ VERIFIKASI INSTALASI

### **Test Login Super Admin**

```
URL: http://localhost:8000/login
Username: su
Password: password
```

**Expected:**
- ✅ Berhasil login
- ✅ Melihat menu lengkap: Dashboard, Builtin, Activities, Laporin, dll.
- ✅ Akses ke User Management

### **Test Login Admin Wilayah (Jika Demo Users Dibuat)**

```
URL: http://localhost:8000/login
Username: admin.daop1jakarta
Password: password
```

**Expected:**
- ✅ Berhasil login
- ✅ Melihat menu: Dashboard, Master Data, Working Order, Maintenance Order, Check Sheet
- ❌ TIDAK melihat menu: Builtin (User, Role, Permission, Menu)
- ✅ Hanya melihat data dari DAOP 1 Jakarta

---

## 🔄 URUTAN SEEDER LENGKAP (COPY-PASTE)

Untuk fresh installation, copy-paste perintah ini satu per satu:

```bash
# Migrasi database
php artisan migrate

# Seeder dasar
php artisan db:seed --class=InitialSeeder
php artisan db:seed --class=MenuSeeder

# Master data
php artisan db:seed --class=MasterRegionSeeder
php artisan db:seed --class=MasterClassificationSeeder
php artisan db:seed --class=MasterDataPermissionSeeder

# Admin Wilayah (lengkap)
php artisan db:seed --class=AdminWilayahCompleteSeeder

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Build assets
npm run build
```

---

## 🐛 TROUBLESHOOTING

### **Error: "Class ... does not exist"**

**Penyebab:** Composer autoload belum di-refresh

**Solusi:**
```bash
composer dump-autoload
```

### **Error: "SQLSTATE[42S02]: Base table or view not found"**

**Penyebab:** Migration belum dijalankan atau gagal

**Solusi:**
```bash
# Cek status migration
php artisan migrate:status

# Rollback dan migrate ulang
php artisan migrate:fresh

# Lalu jalankan seeder lagi dari awal
```

### **Error: "SQLSTATE[23000]: Integrity constraint violation"**

**Penyebab:** Seeder dijalankan tidak sesuai urutan atau dijalankan 2x

**Solusi:**
```bash
# Reset database dan mulai dari awal
php artisan migrate:fresh

# Jalankan seeder sesuai urutan di atas
```

### **Error: "Array to string conversion" di Seeder**

**Penyebab:** Bug di seeder (sudah diperbaiki)

**Solusi:**
```bash
# Pull latest code
git pull origin master

# Atau manual edit file seeder yang error
```

### **Error: "Column 'created_by_id' not found"**

**Penyebab:** Bug di MasterMachineController (sudah diperbaiki)

**Solusi:**
```bash
# Pull latest code
git pull origin master

# Clear cache
php artisan route:clear
```

### **Menu Admin Wilayah Tidak Muncul**

**Penyebab:** Cache belum di-clear atau seeder belum dijalankan

**Solusi:**
```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Pastikan seeder sudah dijalankan
php artisan db:seed --class=AdminWilayahCompleteSeeder

# Refresh browser (Ctrl + F5)
```

### **Admin Wilayah Melihat Semua Data (Tidak Ter-filter)**

**Penyebab:** `region_id` di user kosong (NULL)

**Solusi:**
```sql
-- Cek region_id user
SELECT id, name, username, region_id FROM users WHERE username = 'admin.daop1jakarta';

-- Update jika NULL
UPDATE users SET region_id = 1 WHERE username = 'admin.daop1jakarta';
```

---

## 📂 STRUKTUR DATABASE SETELAH SEEDING

Setelah semua seeder berhasil, struktur data akan seperti ini:

### **Users:**
- `su` (Super Admin) - region_id: NULL (akses semua region)
- `user` (User Biasa) - region_id: NULL
- `admin.daop1jakarta` - `admin.daop9jember` (Admin Wilayah)
- `admin.divreisumaterautara` - `admin.divreivtanjungkarang` (Admin Wilayah)

### **Roles:**
- `superuser`
- `admin-wilayah`

### **Permissions:** 37+ permissions

### **Menus:**
- Dashboard
- Builtin (Permission, Role, User, Menu, Translation)
- Activities (Login)
- Laporin (Data Laporin, Laporan Terkirim, dll)
- Feedback
- List Kerjaan
- **Master Data** (untuk Admin Wilayah)
- **Working Order** (untuk Admin Wilayah)
- **Maintenance Order** (untuk Admin Wilayah)
- **Check Sheet** (untuk Admin Wilayah)

### **Master Regions:** 13 regions (DAOP 1-9, Divre I-IV)

---

## 📝 CATATAN PENTING

1. **Jangan skip urutan seeder** - Ada dependencies antar data
2. **MasterRegionSeeder harus dijalankan** sebelum AdminWilayahCompleteSeeder
3. **Demo users password default:** `password` - Ganti setelah login!
4. **Admin Wilayah harus punya region_id** - Jika NULL, akan melihat semua data
5. **Clear cache setelah seeding** - Agar menu baru muncul

---

## 🆘 RESET DATABASE (Fresh Start)

Jika ingin mulai dari awal lagi:

```bash
# Reset semua (HATI-HATI: menghapus semua data!)
php artisan migrate:fresh

# Lalu jalankan seeder dari awal
php artisan db:seed --class=InitialSeeder
php artisan db:seed --class=MenuSeeder
php artisan db:seed --class=MasterRegionSeeder
php artisan db:seed --class=MasterClassificationSeeder
php artisan db:seed --class=MasterDataPermissionSeeder
php artisan db:seed --class=AdminWilayahCompleteSeeder

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

---

## 📞 DUKUNGAN

Jika ada masalah yang tidak tercantum di sini:

1. Cek file log: `storage/logs/laravel.log`
2. Pastikan sudah pull code terbaru: `git pull origin master`
3. Cek dokumentasi: `ADMIN_WILAYAH_GUIDE.md`
4. Quick reference: `ADMIN_WILAYAH_QUICKSTART.md`

---

**Last Updated:** 2025-12-15  
**Version:** 1.0  
**Tested On:** PHP 8.1, MySQL 8.0, Laravel 9
