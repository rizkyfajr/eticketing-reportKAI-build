# 🚀 Setup Sistem Notifikasi Maintenance Order

## Langkah-Langkah Setup

### 1️⃣ Jalankan Migration
```bash
php artisan migrate
```

Migration akan membuat tabel `maintenance_order_notifications`.

### 2️⃣ Sesuaikan Nama Posisi/Jabatan

Buka file `app/Services/MaintenanceOrderNotificationService.php` di line 155-160:

```php
private function getKaopAndTeknisiBy($divisionId = null)
{
    $query = User::join('positions', 'users.position_id', '=', 'positions.id')
        ->whereIn('positions.position', [
            'Kepala UPT Mekanik',      // ⚠️ SESUAIKAN dengan posisi di database
            'Kepala Operator KPJR'      // ⚠️ SESUAIKAN dengan posisi di database
        ])
        ->select('users.*');
    // ...
}
```

**Posisi saat ini yang menerima notifikasi:**
- **Kepala UPT Mekanik**
- **Kepala Operator KPJR**

**Cek dulu data di tabel `positions` Anda:**
```sql
SELECT * FROM positions;
```

Kemudian sesuaikan array posisi dengan data yang ada.

### 3️⃣ Tambahkan NotificationBell di Layout

Buka file `resources/js/Layouts/DashboardLayout.vue` dan tambahkan komponen bell icon:

```vue
<script setup>
import NotificationBell from '@/Components/NotificationBell.vue'
// ... imports lainnya
</script>

<template>
  <div>
    <!-- Di bagian navbar/header -->
    <nav class="...">
      <div class="flex items-center gap-4">
        <!-- Komponen notifikasi -->
        <NotificationBell />
        
        <!-- User menu, dll -->
        <!-- ... -->
      </div>
    </nav>
    
    <!-- Content -->
    <main>
      <slot />
    </main>
  </div>
</template>
```

### 4️⃣ Test Sistem Notifikasi

1. **Login sebagai user dengan posisi KAOP/Teknisi BY**
2. **Buat Maintenance Order Baru**
   - Masuk ke menu Maintenance Order
   - Klik "Tambah Baru"
   - Isi form dan simpan
   - ✅ KAOP/Teknisi BY akan mendapat notifikasi "Input Failure"

3. **Isi Follow Up Plan**
   - Buka order yang baru dibuat
   - Isi form Follow Up Plan
   - Pilih teknisi yang akan ditugaskan
   - Simpan
   - ✅ Teknisi yang dipilih akan mendapat notifikasi "Tugas Baru"
   - ✅ KAOP/Teknisi BY akan mendapat notifikasi "Follow Up Plan"

4. **Mulai Pekerjaan**
   - Lanjutkan ke step "Mulai Perbaikan"
   - Isi form dan simpan
   - ✅ KAOP/Teknisi BY akan mendapat notifikasi "Pekerjaan Dimulai"

5. **Selesaikan Pekerjaan**
   - Lanjutkan ke step "Selesaikan Pekerjaan"
   - Isi form dan simpan
   - ✅ KAOP/Teknisi BY akan mendapat notifikasi "Pekerjaan Selesai"

### 5️⃣ Cek Notifikasi

- **Bell Icon**: Klik icon bell 🔔 di navbar
- **Badge Merah**: Menampilkan jumlah notifikasi belum dibaca
- **Dropdown**: Menampilkan 5 notifikasi terbaru
- **Halaman Notifikasi**: Akses via URL `/notifications` atau klik "Lihat Semua"

## 📊 Struktur File yang Dibuat

```
app/
├── Http/Controllers/
│   ├── MaintenanceOrderController.php (✏️ Updated)
│   └── MaintenanceOrderNotificationController.php (🆕 New)
├── Models/
│   └── MaintenanceOrderNotification.php (🆕 New)
└── Services/
    └── MaintenanceOrderNotificationService.php (🆕 New)

database/migrations/
└── 2025_11_30_000001_create_maintenance_order_notifications_table.php (🆕 New)

resources/js/
├── Components/
│   └── NotificationBell.vue (🆕 New)
└── Pages/
    └── Notifications/
        └── Index.vue (🆕 New)

routes/
└── web.php (✏️ Updated - added notification routes)

NOTIFICATION_SYSTEM.md (🆕 New - Dokumentasi lengkap)
SETUP_NOTIFIKASI.md (🆕 New - File ini)
```

## ⚙️ Konfigurasi Penting

### Posisi yang Menerima Notifikasi
Sesuaikan di `MaintenanceOrderNotificationService.php`:
- KAOP
- Teknisi BY  
- Supervisor
- (atau posisi lain sesuai database Anda)

### Interval Auto-Refresh
Sesuaikan di `NotificationBell.vue` line 65:
```javascript
// Refresh setiap 30 detik (default)
interval = setInterval(fetchNotifications, 30000)

// Ubah ke 60 detik:
interval = setInterval(fetchNotifications, 60000)
```

### Jumlah Notifikasi di Dropdown
Sesuaikan di `NotificationBell.vue` line 107:
```vue
<!-- Menampilkan 5 notifikasi terbaru (default) -->
v-for="notification in notifications.slice(0, 5)"

<!-- Ubah ke 10 notifikasi: -->
v-for="notification in notifications.slice(0, 10)"
```

## 🐛 Troubleshooting

### Notifikasi tidak muncul?
1. Cek database: `SELECT * FROM maintenance_order_notifications;`
2. Pastikan ada user dengan posisi yang sesuai
3. Cek console browser untuk error JavaScript
4. Cek log Laravel: `storage/logs/laravel.log`

### Badge count tidak update?
1. Hard refresh browser (Ctrl+Shift+R)
2. Cek route `/notifications/recent` di browser
3. Pastikan axios sudah terinstall

### Nama posisi tidak cocok?
Sesuaikan di `MaintenanceOrderNotificationService.php` method `getKaopAndTeknisiBy()`

## ✅ Checklist Setup

- [ ] Migration berhasil dijalankan
- [ ] Nama posisi di service sudah disesuaikan
- [ ] NotificationBell ditambahkan di layout
- [ ] Test create order → notifikasi terkirim
- [ ] Test follow up → notifikasi terkirim
- [ ] Test start repair → notifikasi terkirim
- [ ] Test complete → notifikasi terkirim
- [ ] Bell icon menampilkan badge count
- [ ] Dropdown notifikasi berfungsi
- [ ] Halaman /notifications dapat diakses
- [ ] Mark as read berfungsi
- [ ] Delete notifikasi berfungsi

## 📞 Support

Jika ada pertanyaan atau masalah, silakan cek:
1. `NOTIFICATION_SYSTEM.md` untuk dokumentasi lengkap
2. Console browser untuk error JavaScript
3. `storage/logs/laravel.log` untuk error backend

Selamat menggunakan sistem notifikasi! 🎉
