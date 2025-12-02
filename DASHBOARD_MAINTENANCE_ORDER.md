# 📊 Dashboard Maintenance Order

Dashboard untuk monitoring dan tracking **Maintenance Order** berdasarkan flow workflow TECO KUPT MEKANIK.

---

## 🎯 Fitur Dashboard

### 1️⃣ **Statistik Cards (5 Metrik Utama)**

#### 📌 Total Kerusakan (Biru)
- Menampilkan **total semua maintenance order** yang pernah dibuat
- Icon: Warning Triangle
- Query: `MaintenanceOrder::count()`

#### ⏳ Menunggu Follow Up (Kuning)
- Menampilkan **jumlah kerusakan yang statusnya `pending`**
- Artinya: Sudah input kerusakan tapi belum ada follow up plan
- Query: `MaintenanceOrder::where('status', 'pending')->count()`

#### 🔧 Sedang Diperbaiki (Orange)
- Menampilkan **jumlah kerusakan yang statusnya `in_progress`**
- Artinya: Sudah dimulai proses perbaikan
- Query: `MaintenanceOrder::where('status', 'in_progress')->count()`

#### ✅ Selesai Diperbaiki (Hijau)
- Menampilkan **jumlah kerusakan yang statusnya `completed`**
- Artinya: Perbaikan sudah selesai
- Query: `MaintenanceOrder::where('status', 'completed')->count()`

#### 🚨 Kerusakan Kritis (Merah)
- Menampilkan **jumlah kerusakan dengan severity `critical`**
- Query: `MaintenanceOrder::where('severity', 'critical')->count()`

---

### 2️⃣ **MTTR (Mean Time To Repair)**

**MTTR** = Rata-rata waktu yang dibutuhkan untuk memperbaiki kerusakan.

#### Cara Hitung:
```php
1. Ambil semua maintenance order yang sudah completed
2. Hitung selisih waktu antara `failure_date` dan `repair_complete_date`
3. Rata-ratakan semua durasi tersebut
```

#### Contoh Perhitungan:
- Order 1: 5 jam 30 menit (330 menit)
- Order 2: 3 jam 15 menit (195 menit)
- Order 3: 4 jam 45 menit (285 menit)
- **Total**: 810 menit ÷ 3 = **270 menit** = **4 jam 30 menit**

#### Tampilan:
```
4 jam 30 menit
Rata-rata waktu perbaikan
```

---

### 3️⃣ **Recent Maintenance Orders**

Menampilkan **5 maintenance order terbaru** dengan informasi:

- **Nama Mesin**: `[Nomor] Nama Mesin`
- **Deskripsi Kerusakan**: Maksimal 50 karakter
- **Status Badge**: 
  - 🟡 Pending (kuning)
  - 🟠 In Progress (orange)
  - 🟢 Completed (hijau)
- **Severity Badge**:
  - 🔴 Critical (merah)
  - 🟠 High (orange)
  - 🟡 Medium (kuning)
  - 🔵 Low (biru)
- **Dibuat oleh**: Nama user + tanggal
- **Link**: "Lihat Semua →" ke halaman index maintenance order

---

## 📂 File yang Dimodifikasi

### 1. `app/Http/Controllers/DashboardController.php`

**Ditambahkan:**
```php
// Import model
use App\Models\MaintenanceOrder;

// Di method index(), sebelum return Inertia::render()
$maintenanceStats = [
    'total_failures' => MaintenanceOrder::count(),
    'pending_followup' => MaintenanceOrder::where('status', 'pending')->count(),
    'in_progress' => MaintenanceOrder::where('status', 'in_progress')->count(),
    'completed' => MaintenanceOrder::where('status', 'completed')->count(),
    'critical_failures' => MaintenanceOrder::where('severity', 'critical')->count(),
];

// Hitung MTTR
$completedOrders = MaintenanceOrder::where('status', 'completed')
    ->whereNotNull('failure_date')
    ->whereNotNull('repair_complete_date')
    ->get();

$totalRepairMinutes = 0;
$repairCount = 0;

foreach ($completedOrders as $order) {
    try {
        $failureDate = Carbon::parse($order->failure_date);
        $completeDate = Carbon::parse($order->repair_complete_date);
        $totalRepairMinutes += $failureDate->diffInMinutes($completeDate);
        $repairCount++;
    } catch (\Exception $e) {
        // Skip jika parsing gagal
    }
}

$avgRepairTime = $repairCount > 0 ? floor($totalRepairMinutes / $repairCount) : 0;
$maintenanceStats['avg_repair_hours'] = floor($avgRepairTime / 60);
$maintenanceStats['avg_repair_minutes'] = $avgRepairTime % 60;

// Recent orders
$recentMaintenanceOrders = MaintenanceOrder::with(['machine:id,name,nomor', 'user:id,name'])
    ->latest()
    ->take(5)
    ->get()
    ->map(function ($order) {
        return [
            'id' => $order->id,
            'machine_name' => $order->machine ? '[' . $order->machine->nomor . '] ' . $order->machine->name : 'N/A',
            'failure_description' => \Str::limit($order->failure_description ?? '-', 50),
            'status' => $order->status,
            'severity' => $order->severity,
            'created_by' => $order->user->name ?? 'Unknown',
            'created_at' => $order->created_at->format('d M Y H:i'),
        ];
    });

// Pass ke view
return Inertia::render('Dashboard', [
    // ... existing props
    'maintenanceStats' => $maintenanceStats,
    'recentMaintenanceOrders' => $recentMaintenanceOrders,
]);
```

### 2. `resources/js/Pages/Dashboard.vue`

**Ditambahkan props:**
```js
const { maintenanceStats, recentMaintenanceOrders } = defineProps({
  // ... existing props
  maintenanceStats: Object,
  recentMaintenanceOrders: Array,
})
```

**Ditambahkan section HTML:**
- Section "Maintenance Order Performance" setelah card Tamping Machine & Ballast Regulator
- 5 Statistics cards dengan gradient background
- MTTR card dengan display jam & menit
- Recent orders list dengan badges status & severity

---

## 🎨 Desain & Styling

### Color Scheme:
- **Biru** (Total): `from-blue-500 to-blue-600`
- **Kuning** (Pending): `from-yellow-500 to-yellow-600`
- **Orange** (In Progress): `from-orange-500 to-orange-600`
- **Hijau** (Completed): `from-green-500 to-green-600`
- **Merah** (Critical): `from-red-500 to-red-600`
- **Indigo** (MTTR): `text-indigo-600`

### Icons:
- Menggunakan Heroicons (outline)
- Setiap card memiliki icon yang relevan dengan fungsinya

---

## 📊 Flow Berdasarkan Diagram

Dashboard ini mengikuti flow diagram yang kamu kirim:

```
Input Kerusakan → Follow Up → Start Repair → Repair Complete
     (ALL)         (Pending)   (In Progress)   (Completed)
```

### Mapping ke Dashboard:
1. **Total Kerusakan** = Semua yang sudah masuk sistem
2. **Menunggu Follow Up** = Status `pending` (belum ada follow up plan)
3. **Sedang Diperbaiki** = Status `in_progress` (sudah mulai repair)
4. **Selesai Diperbaiki** = Status `completed` (sudah selesai)
5. **Kerusakan Kritis** = Filter by `severity = critical`

---

## 🧪 Testing

### 1. Cek Dashboard (Tanpa Data)
```
http://localhost/dashboard
```
Seharusnya muncul:
- 5 cards dengan angka 0
- MTTR: 0 jam 0 menit
- "Belum ada maintenance order"

### 2. Buat Maintenance Order
```
1. Buat maintenance order baru
2. Pilih severity (Low/Medium/High/Critical)
3. Refresh dashboard → Total Kerusakan naik
4. Follow up → Pending berkurang
5. Start Repair → In Progress naik
6. Complete → Completed naik, MTTR mulai terhitung
```

### 3. Cek Recent Orders
- 5 order terbaru harus muncul
- Status & severity badge sesuai warna
- Link "Lihat Semua" ke `/maintenance-orders`

---

## ⚙️ Field Severity

### Nilai yang Tersedia:
- **Low** (Rendah) - Kerusakan minor, tidak urgent
- **Medium** (Sedang) - Default value, kerusakan standar
- **High** (Tinggi) - Kerusakan serius, perlu prioritas
- **Critical** (Kritis) - Kerusakan sangat serius, harus segera ditangani

### Implementasi:
```sql
-- Migration
ALTER TABLE maintenance_orders 
ADD COLUMN severity ENUM('low', 'medium', 'high', 'critical') 
DEFAULT 'medium' AFTER status;
```

### Validasi di Controller:
```php
'severity' => ['nullable', 'in:low,medium,high,critical'],
```

### Default Value:
Jika tidak diisi saat create, otomatis set ke `medium`.

---

## 🔍 Troubleshooting

### Dashboard tidak muncul metrik maintenance order:
```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Rebuild assets
npm run dev
```

### MTTR menunjukkan 0 padahal ada completed orders:
- Cek `failure_date` dan `repair_complete_date` harus terisi
- Cek format tanggal harus valid
- Lihat `laravel.log` untuk error parsing

### Recent orders kosong:
```sql
-- Cek ada data atau tidak
SELECT id, status, severity, failure_description, created_at 
FROM maintenance_orders 
ORDER BY created_at DESC 
LIMIT 5;
```

---

## 🚀 Fitur Lanjutan (Opsional)

### 1. **Filter by Division**
Tambahkan filter untuk melihat statistik per divisi:
```php
$maintenanceStats = [
    'total_failures' => MaintenanceOrder::whereHas('machine', function($q) use ($divisionId) {
        $q->where('division_id', $divisionId);
    })->count(),
    // dst...
];
```

### 2. **Chart/Grafik**
Tambahkan chart menggunakan Chart.js atau ApexCharts:
- Trend kerusakan per bulan
- Pie chart severity distribution
- Bar chart top 5 mesin bermasalah

### 3. **Export Report**
Tombol export dashboard ke PDF atau Excel untuk monthly report.

---

## 📝 Catatan Penting

✅ **Dashboard TIDAK menghilangkan fungsi existing** (MTT CSM, PBR U-RS, Readiness Assessment)  
✅ **Mengikuti flow diagram** TECO KUPT MEKANIK  
✅ **Responsive design** untuk mobile & desktop  
✅ **Real-time update** setiap ada perubahan maintenance order  

---

Selamat! Dashboard Maintenance Order sudah siap digunakan! 🎉
