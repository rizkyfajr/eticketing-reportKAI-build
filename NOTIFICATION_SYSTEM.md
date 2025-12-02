# Sistem Notifikasi Maintenance Order

## 📋 Overview

Sistem notifikasi otomatis untuk Maintenance Order yang mengirimkan notifikasi ke KAOP/Teknisi BY berdasarkan workflow/flow pekerjaan.

## 🔄 Flow Notifikasi

Berdasarkan diagram flow yang Anda berikan, sistem notifikasi bekerja sebagai berikut:

### 1. Input Failure (Unplanned/Planned) ➡️ Notifikasi ke KAOP/Teknisi BY
**Trigger:** Saat maintenance order baru dibuat (status: BARU)
**Penerima:** Semua user dengan posisi KAOP atau Teknisi BY di divisi yang sama
**Tipe:** `input_failure`
**Contoh Notifikasi:**
- Judul: `[Gangguan Baru] Kerusakan Motor Tamping`
- Pesan: `Data Gangguan baru pada mesin MTT-01 - KA123. Segera lakukan follow up plan.`

### 2. Follow Up Plan ➡️ Notifikasi ke Teknisi yang Ditugaskan
**Trigger:** Saat follow up plan dibuat (status: DIPROSES)
**Penerima:** 
- Teknisi yang ditugaskan (follow_up_by_id)
- KAOP/Teknisi BY (untuk monitoring)
**Tipe:** `follow_up_plan`
**Contoh Notifikasi:**
- Judul: `[Tugas Baru] Kerusakan Motor Tamping`
- Pesan: `Anda ditugaskan untuk menangani pekerjaan ini. Rencana: Penggantian bearing. Estimasi selesai: 30/11/2025 14:00`

### 3. Start to Repair ➡️ Notifikasi ke KAOP/Teknisi BY
**Trigger:** Saat pekerjaan mulai dikerjakan (status: DIKERJAKAN)
**Penerima:** Semua user dengan posisi KAOP atau Teknisi BY di divisi yang sama
**Tipe:** `start_repair`
**Contoh Notifikasi:**
- Judul: `[Pekerjaan Dimulai] Kerusakan Motor Tamping`
- Pesan: `Pekerjaan pada mesin MTT-01 telah dimulai oleh Ahmad Rizky.`

### 4. Repair Complete ➡️ Notifikasi ke KAOP/Teknisi BY
**Trigger:** Saat pekerjaan selesai (status: SELESAI)
**Penerima:** Semua user dengan posisi KAOP atau Teknisi BY di divisi yang sama
**Tipe:** `repair_complete`
**Contoh Notifikasi:**
- Judul: `[Pekerjaan Selesai] Kerusakan Motor Tamping`
- Pesan: `Pekerjaan pada mesin MTT-01 telah diselesaikan oleh Ahmad Rizky. Catatan: Bearing telah diganti dan diuji coba.`

## 🗄️ Database Schema

```sql
CREATE TABLE maintenance_order_notifications (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    maintenance_order_id BIGINT NOT NULL,
    user_id BIGINT NOT NULL,
    type VARCHAR(255) NOT NULL, -- input_failure, follow_up_plan, start_repair, repair_complete
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    status VARCHAR(255) DEFAULT 'BARU', -- BARU, DIPROSES, DIKERJAKAN, SELESAI
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (maintenance_order_id) REFERENCES maintenance_orders(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX (user_id, read_at)
);
```

## 🎯 Posisi/Jabatan yang Menerima Notifikasi

Berdasarkan data positions di database Anda, sistem mencari user dengan posisi:
- **Kepala UPT Mekanik** (Atasan/Supervisor untuk maintenance)
- **Kepala Operator KPJR** (Atasan/Supervisor untuk operasional)

Anda dapat menyesuaikan di `MaintenanceOrderNotificationService.php`:

```php
private function getKaopAndTeknisiBy($divisionId = null)
{
    $query = User::join('positions', 'users.position_id', '=', 'positions.id')
        ->whereIn('positions.position', [
            'Kepala UPT Mekanik',
            'Kepala Operator KPJR'
        ])
        ->select('users.*');

    if ($divisionId) {
        $query->where('users.division_id', $divisionId);
    }

    return $query->get();
}
```

## 🔧 Cara Menggunakan

### 1. Jalankan Migration
```bash
php artisan migrate
```

### 2. Pastikan Data Positions Sudah Ada
Cek tabel `positions` memiliki data:
- KAOP
- Teknisi BY
- Supervisor

### 3. Assign Posisi ke User
Pastikan user memiliki `position_id` dan `division_id` yang sesuai di tabel `users`.

### 4. Test Notifikasi
1. Buat maintenance order baru → KAOP/Teknisi BY dapat notifikasi
2. Isi follow up plan → Teknisi yang ditugaskan dapat notifikasi
3. Mulai pekerjaan → KAOP/Teknisi BY dapat notifikasi
4. Selesaikan pekerjaan → KAOP/Teknisi BY dapat notifikasi

## 🌐 API Endpoints

### GET /notifications
Halaman daftar semua notifikasi (Inertia)

### GET /notifications/recent
API untuk mengambil notifikasi terbaru (JSON)
Response:
```json
{
  "notifications": [...],
  "unreadCount": 5
}
```

### POST /notifications/{id}/read
Mark notifikasi sebagai sudah dibaca

### POST /notifications/read-all
Mark semua notifikasi user sebagai sudah dibaca

### DELETE /notifications/{id}
Hapus notifikasi

## 🎨 Komponen UI

### 1. NotificationBell.vue
Komponen bell icon di navbar dengan dropdown notifikasi terbaru.

**Cara menggunakan:**
```vue
<template>
  <DashboardLayout>
    <!-- Tambahkan di navbar -->
    <NotificationBell />
  </DashboardLayout>
</template>

<script setup>
import NotificationBell from '@/Components/NotificationBell.vue'
</script>
```

### 2. Notifications/Index.vue
Halaman lengkap untuk melihat semua notifikasi dengan pagination.

## 📱 Features

### ✅ Auto Refresh
Notifikasi di bell icon otomatis refresh setiap 30 detik.

### ✅ Real-time Badge Count
Badge merah menampilkan jumlah notifikasi yang belum dibaca.

### ✅ Filter by Division
Notifikasi hanya dikirim ke user di divisi yang sama dengan mesin.

### ✅ Clickable Notification
Klik notifikasi langsung membuka detail maintenance order.

### ✅ Mark as Read
- Single: klik "Tandai Dibaca" per notifikasi
- Bulk: klik "Tandai Semua Dibaca" di halaman notifikasi

### ✅ Delete Notification
User bisa menghapus notifikasi yang tidak diperlukan.

## 🔍 Troubleshooting

### Notifikasi tidak terkirim?
1. Cek apakah ada user dengan posisi KAOP/Teknisi BY di database
2. Pastikan user memiliki `position_id` yang benar
3. Cek `division_id` mesin dan user sudah sesuai

### Posisi tidak sesuai?
Edit method `getKaopAndTeknisiBy()` di `MaintenanceOrderNotificationService.php` dan sesuaikan nama posisi dengan data di tabel `positions`.

### Bell icon tidak muncul?
Pastikan komponen `NotificationBell.vue` sudah diimport di layout utama.

## 📝 Customization

### Mengubah Nama Posisi
Edit file: `app/Services/MaintenanceOrderNotificationService.php`

```php
private function getKaopAndTeknisiBy($divisionId = null)
{
    $query = User::whereHas('positions', function ($q) {
        // Sesuaikan dengan nama posisi di database Anda
        $q->whereIn('position', [
            'Nama Posisi 1', 
            'Nama Posisi 2',
            'Nama Posisi 3'
        ]);
    });
    // ...
}
```

### Menambah Tipe Notifikasi Baru
1. Edit migration: tambah tipe di enum `type`
2. Edit service: tambah method notifikasi baru
3. Edit controller: panggil method di action yang sesuai
4. Edit komponen: tambah icon dan styling untuk tipe baru

## 🚀 Future Improvements

- [ ] Push notification via websocket
- [ ] Email notification
- [ ] WhatsApp notification via API
- [ ] Notifikasi untuk update checksheet
- [ ] Filter notifikasi by type
- [ ] Export history notifikasi
