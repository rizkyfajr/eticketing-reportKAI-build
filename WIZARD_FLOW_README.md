# Fitur Wizard Flow - Maintenance Order

## Overview
Fitur ini memungkinkan user untuk melanjutkan proses maintenance order secara bertahap (wizard/stepper) tanpa perlu keluar masuk halaman.

## Alur Wizard

### Mode CREATE (Tambah Baru)
Ketika user membuat maintenance order baru, sistem akan menampilkan 4 langkah berurutan:

```
Step 1: Order Form (Unplanned/Planned)
   ↓ [Simpan & Lanjutkan]
Step 2: Follow Up Plan
   ↓ [Simpan & Lanjutkan] atau [Lewati] atau [Selesaikan Nanti]
Step 3: Start Repair
   ↓ [Simpan & Lanjutkan] atau [Lewati] atau [Selesaikan Nanti]
Step 4: Complete Repair
   ↓ [Selesaikan Pekerjaan] atau [Selesaikan Nanti]
```

### Mode EDIT
Ketika user mengedit order yang sudah ada, hanya form Step 1 yang ditampilkan (seperti biasa).

## Fitur

### Step Indicator
- Visual stepper di bagian atas form menunjukkan progress user
- Nomor step yang sudah dilalui berwarna biru
- Step saat ini ditandai dengan warna yang lebih terang

### Opsi Fleksibel
User memiliki 3 pilihan di setiap step (kecuali Step 1):
1. **Simpan & Lanjutkan**: Mengisi form dan melanjutkan ke step berikutnya
2. **Lewati Step Ini**: Langsung ke step berikutnya tanpa mengisi
3. **Selesaikan Nanti**: Kembali ke index, bisa melanjutkan dari halaman Detail

## Implementasi Teknis

### Frontend (Form.vue)
- State `currentStep` untuk tracking step saat ini
- State `savedOrderId` untuk menyimpan ID order yang baru dibuat
- 4 form terpisah untuk setiap step
- Conditional rendering berdasarkan `currentStep`

### Backend (MaintenanceOrderController.php)
- Method `store()` dimodifikasi untuk return `back()` dengan flash data
- Flash data berisi order yang baru dibuat untuk digunakan di step berikutnya
- Menambahkan `users` ke props untuk dropdown teknisi/KAOP

### Route
Menggunakan route yang sudah ada:
- `maintenance-orders.store` (Step 1)
- `maintenance-orders.followUp` (Step 2)
- `maintenance-orders.startRepair` (Step 3)
- `maintenance-orders.complete` (Step 4)

## User Experience

### Keuntungan
✅ User tidak perlu bolak-balik halaman
✅ Proses lebih cepat dan intuitif
✅ Visual progress yang jelas
✅ Fleksibilitas untuk skip atau selesaikan nanti

### Fallback
User tetap bisa:
- Klik "Selesaikan Nanti" kapan saja
- Melanjutkan proses dari halaman Detail (seperti sebelumnya)
- Edit order dengan cara normal

## Notes
- Wizard flow hanya aktif untuk CREATE mode
- EDIT mode tetap menggunakan flow lama (form biasa)
- Semua validasi tetap berjalan di setiap step
- Data disimpan ke database setelah setiap step (tidak ada temporary storage)
