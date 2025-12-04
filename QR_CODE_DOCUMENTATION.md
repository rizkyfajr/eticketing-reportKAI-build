# QR Code Feature - Master Data Mesin

## Deskripsi
Fitur QR Code memungkinkan user untuk scan QR code pada mesin fisik untuk otomatis mengisi data mesin di Working Report, mengurangi input manual dan meminimalisir kesalahan.

## Flow Proses

### 1. Generate QR Code
**Lokasi:** Master Data Mesin → Tombol QR (ungu)

**Proses:**
1. User klik tombol QR pada tabel master data mesin
2. System generate QR code (jika belum ada) berisi data JSON:
   ```json
   {
     "machine_id": 1,
     "name": "Tamper Mesin",
     "type": "RM-900",
     "nomor": "TMP-001",
     "no_sarana": "SAR-12345",
     "region_id": 1,
     "classification_id": 2
   }
   ```
3. QR code disimpan sebagai SVG di `storage/app/public/qrcodes/`
4. User diarahkan ke halaman preview QR code

**File Terkait:**
- `app/Http/Controllers/MasterMachineController.php` → method `generateQrCode()`, `viewQrCode()`
- `resources/js/Pages/Machine/QrCode.vue`

---

### 2. Tampilkan & Download QR Code
**Lokasi:** Halaman QR Code Detail

**Fitur:**
- **Info Mesin:** Daop, Klasifikasi, Jenis, Type, Nomor, No Sarana, Tahun MD, Umur
- **QR Code Display:** QR code dengan border hitam tebal
- **Tombol Print:** Layout khusus untuk print dengan QR lebih besar
- **Tombol Download:** Download file SVG
- **Tombol Kembali:** Kembali ke daftar mesin

**Print Layout:**
- QR code diperbesar ke 96x96 (dari 64x64 normal)
- Hanya menampilkan info penting: Nama mesin, No Sarana, Daop
- Instruksi scan di bawah QR code

**File Terkait:**
- `resources/js/Pages/Machine/QrCode.vue`
- `app/Http/Controllers/MasterMachineController.php` → method `downloadQrCode()`

---

### 3. Scan QR Code di Working Report
**Lokasi:** Working Report → Create → Section "Scan QR Code Mesin"

**Proses:**
1. User klik tombol "Scan QR Code" (ungu)
2. Browser meminta izin akses kamera
3. User arahkan kamera ke QR code
4. System decode QR → parse JSON data
5. Auto-fill field:
   - `machine_id` → trigger watcher untuk auto-fill field lain
   - `jenis_kpjr` → dari `name` + `type`
   - `nomor_mesin` → dari `nomor`
   - `nomor_sarana` → dari `no_sarana`
   - `region_id` → dari `region_id`
6. Tampil notifikasi sukses
7. Scanner otomatis tertutup

**Handler Error:**
- Kamera tidak dapat diakses → Alert error
- Format QR invalid → Alert "Format QR Code tidak valid"

**File Terkait:**
- `resources/js/Components/QrScanner.vue`
- `resources/js/Pages/WorkingReport/Create.vue` → method `handleQrScanned()`, `handleQrError()`

---

## Struktur File

### Backend

#### 1. Migration
**File:** `database/migrations/2025_12_04_091835_add_qr_code_to_master_machines_table.php`

**Schema:**
```php
Schema::table('master_machines', function (Blueprint $table) {
    $table->string('qr_code')->nullable()->after('keterangan');
});
```

#### 2. Model
**File:** `app/Models/MasterMachine.php`

**Fillable:**
```php
protected $fillable = [
    'region_id',
    'classification_id',
    'name',
    'type',
    'nomor',
    'tahun_md',
    'umur',
    'no_sarana',
    'keterangan',
    'qr_code', // Tambahan
];
```

#### 3. Controller
**File:** `app/Http/Controllers/MasterMachineController.php`

**Import:**
```php
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
```

**Methods:**

##### `generateQrCode($id)`
- Generate QR code SVG 300x300
- Data format: JSON dengan machine_id, name, type, nomor, no_sarana, region_id, classification_id
- Save ke `storage/app/public/qrcodes/qr_machine_{id}.svg`
- Update column `qr_code` di database
- Return redirect ke halaman view QR

##### `downloadQrCode($id)`
- Return QR file sebagai download
- Filename: `QR_{no_sarana}.svg`

##### `viewQrCode($id)`
- Auto-generate QR jika belum ada
- Load machine dengan relasi region & classification
- Render view `Machine/QrCode` dengan data machine & QR URL

#### 4. Routes
**File:** `routes/web.php`

```php
Route::post('/master-machines/{id}/generate-qr', [MasterMachineController::class, 'generateQrCode'])
    ->name('master-machines.generate-qr');

Route::get('/master-machines/{id}/download-qr', [MasterMachineController::class, 'downloadQrCode'])
    ->name('master-machines.download-qr');

Route::get('/master-machines/{id}/view-qr', [MasterMachineController::class, 'viewQrCode'])
    ->name('master-machines.view-qr');
```

---

### Frontend

#### 1. Machine Index - Tombol QR
**File:** `resources/js/Pages/Machine/Index.vue`

**Template:**
```vue
<Button
  v-if="can('read machine')"
  @click.prevent="viewQrCode(machine)"
  class="bg-purple-600 hover:bg-purple-800"
  title="Lihat QR Code"
>
  <Icon name="qrcode" />
  <p class="font-bold text-xs">QR</p>
</Button>
```

**Script:**
```javascript
const viewQrCode = (machine) => {
    Inertia.visit(route('master-machines.view-qr', machine.id))
}
```

#### 2. QR Code Display Page
**File:** `resources/js/Pages/Machine/QrCode.vue`

**Props:**
```javascript
const { machine, qrCodeUrl } = defineProps({
    machine: Object,
    qrCodeUrl: String,
})
```

**Features:**
- Info panel dengan grid 2 kolom
- QR display dengan border 4px purple
- 3 tombol: Print, Download, Kembali
- Print stylesheet untuk layout khusus print

**Print CSS:**
```css
@media print {
    .no-print { display: none !important; }
    .print-only { display: block !important; }
}
```

#### 3. QR Scanner Component
**File:** `resources/js/Components/QrScanner.vue`

**Library:** `html5-qrcode`

**Props:** None (komponen standalone)

**Events:**
- `@scanned` → emit ketika QR berhasil di-scan (parameter: data JSON)
- `@error` → emit ketika ada error (parameter: error message)

**State:**
- `isScanning` → Boolean status scanner aktif/tidak
- `html5QrCode` → Instance scanner

**Methods:**

##### `startScanner()`
```javascript
const config = {
  fps: 10,
  qrbox: { width: 250, height: 250 },
  aspectRatio: 1.0
}

await html5QrCode.start(
  { facingMode: "environment" }, // Gunakan kamera belakang
  config,
  onScanSuccess,
  onScanError
)
```

##### `stopScanner()`
```javascript
if (html5QrCode && html5QrCode.isScanning) {
    await html5QrCode.stop()
    html5QrCode.clear()
}
```

**UI:**
- Tombol "Scan QR Code" (purple) → trigger `startScanner()`
- Tombol "Tutup Scanner" (red) → trigger `stopScanner()`
- Camera preview area (id: `qr-reader`)

#### 4. Working Report Integration
**File:** `resources/js/Pages/WorkingReport/Create.vue`

**Import:**
```javascript
import QrScanner from '@/Components/QrScanner.vue'
```

**Template Position:**
Setelah field "Nama Mesin", sebelum field "Wilayah"

```vue
<div class="mb-4 p-4 bg-purple-50 border border-purple-200 rounded-lg">
  <div class="flex items-center justify-between mb-3">
    <h3 class="text-sm font-bold text-purple-800">
      Scan QR Code Mesin
    </h3>
    <p class="text-xs text-gray-600">
      Scan QR code untuk input otomatis data mesin
    </p>
  </div>
  <QrScanner 
    @scanned="handleQrScanned" 
    @error="handleQrError"
  />
</div>
```

**Handlers:**

##### `handleQrScanned(data)`
```javascript
const handleQrScanned = (data) => {
  try {
    // Set machine_id dari QR code
    form.machine_id = data.machine_id
    
    // Auto-fill field lainnya (via watcher)
    form.jenis_kpjr = `${data.name} ${data.type}`
    form.nomor_mesin = data.nomor || ''
    form.nomor_sarana = data.no_sarana || ''
    form.region_id = data.region_id || ''
    
    Swal.fire({
      icon: 'success',
      title: 'Berhasil!',
      text: 'Data mesin berhasil di-scan dari QR Code',
      timer: 1500,
      showConfirmButton: false,
    })
  } catch (err) {
    console.error('Error processing QR data:', err)
  }
}
```

##### `handleQrError(error)`
```javascript
const handleQrError = (error) => {
  Swal.fire({
    icon: 'error',
    title: 'Error!',
    text: error,
  })
}
```

---

## Dependencies

### Backend
**Package:** `simplesoftwareio/simple-qrcode` ^4.2

**Installation:**
```bash
composer require simplesoftwareio/simple-qrcode --ignore-platform-reqs
```

**Note:** Flag `--ignore-platform-reqs` diperlukan karena mismatch PHP version requirement

### Frontend
**Package:** `html5-qrcode`

**Installation:**
```bash
npm install html5-qrcode --save
```

---

## Storage Configuration

### Symbolic Link
QR code disimpan di `storage/app/public/qrcodes/` dan diakses via symbolic link:

```bash
php artisan storage:link
```

Link: `public/storage` → `storage/app/public`

URL QR Code: `asset('storage/' . $machine->qr_code)`

---

## Permission Requirements

### Browser
- **Camera Access:** Required untuk scan QR code
- User akan diminta izin saat klik tombol "Scan QR Code"
- Jika ditolak → error message muncul

### Laravel Permission
- **Read Machine:** Required untuk melihat tombol QR & halaman QR code

---

## Testing Guide

### 1. Generate QR Code
1. Login sebagai admin/superuser
2. Buka Master Data Mesin
3. Klik tombol QR (ungu) pada salah satu mesin
4. ✅ Halaman QR code muncul dengan info mesin & QR display

### 2. Download & Print
1. Dari halaman QR code
2. Klik "Download" → ✅ File SVG ter-download
3. Klik "Print" → ✅ Preview print muncul dengan layout khusus
4. Print/Save as PDF

### 3. Scan QR Code
1. Buka Working Report → Create
2. Scroll ke section "Scan QR Code Mesin"
3. Klik "Scan QR Code" (purple button)
4. Browser minta izin kamera → Allow
5. Arahkan kamera ke QR code (dari print/screen)
6. ✅ Notifikasi sukses muncul
7. ✅ Field auto-fill: machine_id, jenis_kpjr, nomor_mesin, nomor_sarana, region_id
8. Scanner otomatis tutup

### 4. Error Handling
**Test Camera Denied:**
1. Block camera permission di browser
2. Klik "Scan QR Code"
3. ✅ Alert error: "Gagal mengakses kamera..."

**Test Invalid QR:**
1. Scan QR code random (bukan dari system)
2. ✅ Alert error: "Format QR Code tidak valid"

---

## Troubleshooting

### QR Code Tidak Generate
**Symptom:** Error saat klik tombol QR

**Solution:**
1. Cek storage link: `php artisan storage:link`
2. Cek permission folder: `storage/app/public/qrcodes/` harus writable
3. Cek package installed: `composer show simplesoftwareio/simple-qrcode`

### Scanner Tidak Berfungsi
**Symptom:** Kamera tidak muncul saat klik scan

**Solution:**
1. Cek browser support: Chrome/Edge/Safari modern
2. Cek HTTPS: Camera API hanya jalan di HTTPS (atau localhost)
3. Cek permission: Allow camera access
4. Cek console error: F12 → Console tab

### Auto-fill Tidak Jalan
**Symptom:** QR berhasil scan tapi field kosong

**Solution:**
1. Cek console log error
2. Pastikan JSON QR code valid
3. Cek `machine_id` ada di database
4. Cek watcher `form.machine_id` berfungsi

---

## Future Improvements

### 1. Bulk QR Generation
Generate QR untuk semua mesin sekaligus via Artisan command:
```bash
php artisan qr:generate-all
```

### 2. Auto-generate on Create
Otomatis generate QR saat create mesin baru

### 3. QR Version Control
Regenerate QR jika data mesin berubah (nomor, type, dll)

### 4. Upload QR Scan
Allow user upload image file QR (alternatif kamera)

### 5. QR Analytics
Track berapa kali QR di-scan, kapan, oleh siapa

---

## Changelog

### Version 1.0 - 2025-12-04
- ✅ QR generation backend (controller, routes, migration)
- ✅ QR display page dengan print/download
- ✅ QR scanner component (html5-qrcode)
- ✅ Integration di Working Report Create
- ✅ Auto-fill mechanism via scan
- ✅ Error handling & validation

---

## Support

Jika ada masalah atau pertanyaan, hubungi tim development atau buat issue di repository.
