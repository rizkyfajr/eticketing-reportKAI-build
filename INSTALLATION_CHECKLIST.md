# ✅ INSTALLATION CHECKLIST

Checklist untuk memastikan instalasi berjalan lancar tanpa error.

---

## 📋 CHECKLIST SEBELUM INSTALASI

### Prasyarat:
- [ ] PHP 8.1+ sudah terinstall (`php -v`)
- [ ] Composer sudah terinstall (`composer -V`)
- [ ] Node.js & NPM sudah terinstall (`node -v` dan `npm -v`)
- [ ] MySQL/MariaDB sudah terinstall dan running
- [ ] Git sudah terinstall (`git --version`)

### Database:
- [ ] Database `eticketing_kai` sudah dibuat
- [ ] User database punya akses penuh ke database tersebut
- [ ] File `.env` sudah dikonfigurasi dengan benar

---

## 📋 CHECKLIST SAAT INSTALASI

### 1. Clone & Dependencies
- [ ] Repository berhasil di-clone
- [ ] `composer install` berhasil tanpa error
- [ ] `npm install` berhasil tanpa error

### 2. Environment Setup
- [ ] File `.env` berhasil di-copy dari `.env.example`
- [ ] `php artisan key:generate` berhasil
- [ ] Database credentials di `.env` sudah benar
- [ ] Koneksi database berhasil (test dengan `php artisan migrate:status`)

### 3. Migration
- [ ] `php artisan migrate` berhasil tanpa error
- [ ] Semua tabel berhasil dibuat (cek di database/phpMyAdmin)

### 4. Seeding (URUTAN PENTING!)
- [ ] `php artisan db:seed --class=InitialSeeder` ✓
- [ ] `php artisan db:seed --class=MenuSeeder` ✓
- [ ] `php artisan db:seed --class=MasterRegionSeeder` ✓
- [ ] `php artisan db:seed --class=MasterClassificationSeeder` ✓
- [ ] `php artisan db:seed --class=MasterDataPermissionSeeder` ✓
- [ ] `php artisan db:seed --class=AdminWilayahCompleteSeeder` ✓

### 5. Cache & Build
- [ ] `php artisan cache:clear` ✓
- [ ] `php artisan config:clear` ✓
- [ ] `php artisan route:clear` ✓
- [ ] `npm run build` (production) atau `npm run dev` (development) ✓

---

## 📋 CHECKLIST SETELAH INSTALASI

### Test Login Super Admin:
- [ ] Buka `http://localhost:8000/login`
- [ ] Login dengan username: `su` password: `password`
- [ ] Login berhasil
- [ ] Dashboard muncul dengan benar
- [ ] Menu Builtin muncul (Permission, Role, User, Menu)
- [ ] Menu Activities muncul
- [ ] Menu Laporin muncul

### Test Login Admin Wilayah:
- [ ] Logout dari Super Admin
- [ ] Login dengan username: `admin.daop1jakarta` password: `password`
- [ ] Login berhasil
- [ ] Dashboard muncul
- [ ] Menu Master Data muncul
- [ ] Menu Working Order muncul
- [ ] Menu Maintenance Order muncul
- [ ] Menu Check Sheet muncul
- [ ] Menu Builtin **TIDAK muncul** ❌
- [ ] Hanya melihat data dari DAOP 1 Jakarta

### Test Regional Filtering:
- [ ] Login sebagai Admin Wilayah DAOP 1
- [ ] Buka Master Mesin
- [ ] Hanya melihat mesin dengan region DAOP 1
- [ ] Tidak bisa edit/delete mesin dari DAOP lain

### Test Health Certificate:
- [ ] Login sebagai Admin Wilayah
- [ ] Dashboard terbuka
- [ ] Modal "Upload Surat Keterangan Sehat" **TIDAK muncul** ✓
- [ ] Modal "Checksheet Kesehatan" **TIDAK muncul** ✓

---

## 📋 CHECKLIST VERIFIKASI DATABASE

### Cek Tabel:
- [ ] Tabel `users` ada dan terisi
- [ ] Tabel `roles` ada dan terisi
- [ ] Tabel `permissions` ada dan terisi (minimal 37 permissions)
- [ ] Tabel `menus` ada dan terisi
- [ ] Tabel `master_regions` ada dan terisi (13 regions)
- [ ] Tabel `master_classifications` ada

### Cek Data Users:
```sql
SELECT id, name, username, email, region_id FROM users;
```
Expected:
- [ ] User `su` ada dengan region_id = NULL
- [ ] User `user` ada dengan region_id = NULL
- [ ] User `admin.daop1jakarta` ada dengan region_id = 1
- [ ] User `admin.daop2bandung` ada dengan region_id = 2
- [ ] (dst untuk semua DAOP/Divre)

### Cek Roles:
```sql
SELECT id, name FROM roles;
```
Expected:
- [ ] Role `superuser` ada
- [ ] Role `admin-wilayah` ada

### Cek Permissions:
```sql
SELECT COUNT(*) FROM permissions;
```
Expected:
- [ ] Minimal 37 permissions (dari AdminWilayahPermissionSeeder)

### Cek Menus:
```sql
SELECT id, name, route_or_url, parent_id FROM menus WHERE parent_id IS NULL;
```
Expected:
- [ ] Menu Dashboard ada
- [ ] Menu Builtin ada
- [ ] Menu Activities ada
- [ ] Menu Laporin ada
- [ ] Menu Master Data ada ← **BARU (untuk Admin Wilayah)**
- [ ] Menu Working Order ada ← **BARU (untuk Admin Wilayah)**
- [ ] Menu Maintenance Order ada ← **BARU (untuk Admin Wilayah)**
- [ ] Menu Check Sheet ada ← **BARU (untuk Admin Wilayah)**

---

## 🐛 CHECKLIST JIKA ADA ERROR

### Error saat Migration:
- [ ] Cek apakah database sudah dibuat
- [ ] Cek kredensial database di `.env`
- [ ] Cek koneksi database dengan `php artisan migrate:status`
- [ ] Jika perlu, reset: `php artisan migrate:fresh`

### Error saat Seeding:
- [ ] Cek apakah seeder dijalankan sesuai urutan
- [ ] Cek apakah migration sudah selesai
- [ ] Jika ada error "Class not found": `composer dump-autoload`
- [ ] Jika ada error "Integrity constraint": Reset dan mulai lagi

### Error "Column 'created_by_id' not found":
- [ ] Pull latest code: `git pull origin master`
- [ ] Clear cache: `php artisan route:clear`
- [ ] Refresh browser

### Error "Array to string conversion":
- [ ] Pull latest code: `git pull origin master`
- [ ] Jalankan seeder lagi

### Menu tidak muncul:
- [ ] Clear cache: `php artisan cache:clear`
- [ ] Clear config: `php artisan config:clear`
- [ ] Clear route: `php artisan route:clear`
- [ ] Refresh browser (Ctrl + F5)

### Admin Wilayah melihat semua data:
- [ ] Cek region_id di tabel users
- [ ] Update jika NULL: `UPDATE users SET region_id = 1 WHERE username = 'admin.daop1jakarta';`

---

## ✅ CHECKLIST FINAL

- [ ] Super Admin bisa login dan akses semua menu
- [ ] Admin Wilayah bisa login dan hanya melihat menu yang sesuai
- [ ] Regional filtering bekerja (Admin Wilayah hanya lihat data wilayahnya)
- [ ] Health certificate modal tidak muncul untuk Admin Wilayah
- [ ] Password default sudah diganti
- [ ] Dokumentasi sudah dibaca

---

## 📞 BANTUAN

Jika masih ada masalah:
1. Cek file log: `storage/logs/laravel.log`
2. Baca dokumentasi: `SETUP_INSTALLATION_GUIDE.md`
3. Pull code terbaru: `git pull origin master`
4. Reset dan mulai dari awal: `php artisan migrate:fresh`

---

**Last Updated:** 2025-12-15  
**Version:** 1.0
