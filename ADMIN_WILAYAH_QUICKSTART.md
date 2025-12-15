# 🚀 QUICK START - Admin Wilayah Setup

## Instalasi Cepat (1 Perintah)

```bash
php artisan db:seed --class=AdminWilayahCompleteSeeder
```

✅ Ini akan membuat:
- Semua permissions untuk Admin Wilayah
- Role `admin-wilayah`
- Menu lengkap (Dashboard, Master Data, Working Order, Maintenance Order, Check Sheet)
- Demo users untuk setiap DAOP (optional)

---

## Login Demo User

Jika Anda memilih membuat demo users, gunakan kredensial berikut:

| Username | Password | Region |
|----------|----------|--------|
| admin.daop1 | password | DAOP 1 |
| admin.daop2 | password | DAOP 2 |
| ... | password | ... |

⚠️ **Ganti password setelah login pertama!**

---

## Assign User Existing sebagai Admin Wilayah

Via **User Management** (Super Admin):

1. Login sebagai Super Admin
2. Buka menu **Builtin** → **User**
3. Edit user yang ingin dijadikan Admin Wilayah
4. Set:
   - **Role**: `admin-wilayah`
   - **Wilayah**: [Pilih DAOP/Divre] ← **WAJIB!**
5. Save

---

## Menu yang Akan Terlihat

Admin Wilayah akan melihat menu:

```
📊 Dashboard
📁 Master Data
   ├─ Master Mesin
   └─ Master Klasifikasi
💼 Working Order
   ├─ Laporan Kerja
   ├─ Warming Up
   └─ Hasil Kerja
🔧 Maintenance Order
📋 Check Sheet
   ├─ Form Check Sheet
   └─ Check Sheet Harian
```

❌ Admin Wilayah **TIDAK** akan melihat:
- User Management
- Role & Permission
- Menu Management
- Data dari wilayah lain

---

## Clear Cache (Jika Menu Belum Muncul)

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

Lalu refresh browser (Ctrl + F5)

---

## Dokumentasi Lengkap

Lihat: `ADMIN_WILAYAH_GUIDE.md`

---

**Last Updated:** 2025-12-15
