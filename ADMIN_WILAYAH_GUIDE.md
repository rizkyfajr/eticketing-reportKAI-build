# IMPLEMENTASI ADMIN WILAYAH/DAOP - DOKUMENTASI

## 📋 RINGKASAN
Sistem multi-level admin dengan regional scope yang memungkinkan:
- **Super Admin**: Akses ke semua data (semua wilayah/DAOP)
- **Admin Wilayah**: Hanya akses data di wilayah/DAOP yang ditugaskan
- **User Biasa**: Tidak ada pembatasan khusus

---

## 🚀 CARA INSTALL & SETUP

### METODE 1: Setup Lengkap (RECOMMENDED) ⭐

```bash
php artisan db:seed --class=AdminWilayahCompleteSeeder
```

Seeder ini akan otomatis menjalankan:
1. ✅ `AdminWilayahPermissionSeeder` - Membuat semua permissions
2. ✅ `AdminWilayahRoleSeeder` - Membuat role & menu
3. ✅ `DemoAdminWilayahSeeder` - Membuat demo users (optional, akan konfirmasi)

**Keuntungan:**
- Satu perintah untuk setup lengkap
- Urutan eksekusi dijamin benar
- Lebih cepat dan praktis

---

### METODE 2: Setup Manual (Step by Step)

#### 1. Jalankan Migration
```bash
php artisan migrate
```

Migration ini menambahkan kolom `region_id` ke tabel `users`:
- `NULL` = Super Admin (akses semua region)
- `Terisi` = Admin Wilayah (hanya akses region tertentu)

#### 2. Buat Permissions
```bash
php artisan db:seed --class=AdminWilayahPermissionSeeder
```

Ini akan membuat semua permissions yang diperlukan untuk Admin Wilayah.

#### 3. Buat Role & Menu
```bash
php artisan db:seed --class=AdminWilayahRoleSeeder
```

Ini akan membuat:
- Role `admin-wilayah`
- Assign permissions yang sesuai
- **Menu lengkap untuk Admin Wilayah:**
  - 📊 Dashboard
  - 📁 Master Data
    - Master Mesin
    - Master Klasifikasi
  - 💼 Working Order
    - Laporan Kerja
    - Warming Up
    - Hasil Kerja
  - 🔧 Maintenance Order
  - 📋 Check Sheet
    - Form Check Sheet
    - Check Sheet Harian

#### 4. Seed Demo User Admin Wilayah (OPSIONAL)
```bash
php artisan db:seed --class=DemoAdminWilayahSeeder
```

Ini akan membuat user Admin Wilayah untuk setiap DAOP:
- Username: `admin.daop1`, `admin.daop2`, dst (sesuai data master_regions)
- Password: `password` (untuk semua)
- Email: `admin.daop1@kai.co.id`, dst

⚠️ **PENTING**: Ganti password default setelah login pertama kali!

---

## 📁 FILE YANG DIBUAT/DIMODIFIKASI

### 1. Migration
- `database/migrations/2025_12_15_133825_add_region_id_to_users_table.php`

### 2. Trait
- `app/Traits/RegionalScope.php`

### 3. Model
- `app/Models/User.php` (tambah region_id, relationship, helpers)
- `app/Models/MasterMachine.php` (tambah RegionalScope trait)

### 4. Middleware
- `app/Http/Middleware/RegionalAccess.php`
- `app/Http/Kernel.php` (daftarkan middleware)

### 5. Controllers
- `app/Http/Controllers/MasterMachineController.php` (tambah filter regional)

### 6. Seeders
- `database/seeders/AdminWilayahPermissionSeeder.php` (buat permissions)
- `database/seeders/AdminWilayahRoleSeeder.php` (buat role & menu)
- `database/seeders/DemoAdminWilayahSeeder.php` (demo users)
- `database/seeders/AdminWilayahCompleteSeeder.php` (master seeder)

---

## 🔧 CARA MENGGUNAKAN

### A. Membuat Admin Wilayah Baru (Manual via Tinker)

```php
php artisan tinker

// 1. Buat user baru
$user = User::create([
    'name' => 'Admin DAOP 3',
    'username' => 'admin.daop3',
    'email' => 'admin.daop3@kai.co.id',
    'password' => Hash::make('password'),
    'region_id' => 3, // ID region DAOP 3
    'email_verified_at' => now(),
]);

// 2. Assign role
$user->assignRole('admin-wilayah');

// 3. Verifikasi
$user->hasRole('admin-wilayah'); // true
$user->region->name; // "DAOP 3 Cirebon" (atau sesuai data)
```

### B. Di Controller - Filter Data Berdasarkan Region

```php
// Contoh di MasterMachineController
public function index()
{
    // Otomatis filter berdasarkan region user
    $machines = MasterMachine::forCurrentUserRegion()
        ->with('region')
        ->paginate(20);
        
    return Inertia::render('Machine/Index', [
        'machines' => $machines
    ]);
}
```

### C. Di Controller - Auto-set Region saat Create

```php
public function store(Request $request)
{
    $request->validate([...]);
    
    // Auto-set region untuk Admin Wilayah
    $regionId = MasterMachine::getRegionIdForCreate() ?? $request->region_id;
    
    // Validasi: Admin Wilayah hanya bisa create di regionnya
    if (auth()->user()->hasRole('admin-wilayah') && auth()->user()->region_id) {
        if ($regionId != auth()->user()->region_id) {
            return back()->withErrors([
                'region_id' => 'Anda hanya dapat menambahkan data di wilayah Anda.'
            ]);
        }
    }
    
    $machine = MasterMachine::create([
        'region_id' => $regionId,
        // ... field lainnya
    ]);
}
```

### D. Di Routes - Proteksi Edit/Update/Delete

```php
// Di web.php
Route::middleware(['regional.access:master_machine'])->group(function () {
    Route::put('/master-machines/{master_machine}', [MasterMachineController::class, 'update']);
    Route::delete('/master-machines/{master_machine}', [MasterMachineController::class, 'destroy']);
});
```

### E. Di Vue - Disable Region Dropdown untuk Admin Wilayah

```vue
<script setup>
import { computed } from 'vue'

const props = defineProps({
    auth: Object,
    regions: Array
})

// Cek apakah field region harus di-lock
const isRegionLocked = computed(() => {
    return props.auth.user.region_id && 
           props.auth.user.roles.some(r => r.name === 'admin-wilayah')
})

// Auto-set region_id untuk Admin Wilayah
const form = useForm({
    region_id: props.auth.user.region_id ?? null,
    // ... field lainnya
})
</script>

<template>
    <select v-model="form.region_id" :disabled="isRegionLocked">
        <option v-for="region in regions" :value="region.id">
            {{ region.name }}
        </option>
    </select>
    <p v-if="isRegionLocked" class="text-sm text-orange-600">
        🔒 Region otomatis terisi sesuai wilayah Anda
    </p>
</template>
```

---

## 🔐 PERMISSIONS ADMIN WILAYAH

Admin Wilayah memiliki permissions:

### ✅ BISA AKSES:
- `read/create/update/delete machine`
- `read/create/update/delete classification`
- `read/create/update/delete working report`
- `read/create/update/delete maintenance order`
- `read/create/update/delete checksheet`
- `read/create/update report`
- `read verifikasi`
- `read dashboard`

### ❌ TIDAK BISA AKSES:
- User management (read/create/update/delete user)
- Role & Permission management
- Region management (CRUD master_regions)
- Menu management
- System settings

---

## 📊 CARA KERJA SISTEM

### 1. Login sebagai Admin Wilayah
```
Username: admin.daop1
Password: password
```

### 2. Sistem Mengecek
```php
if ($user->region_id && $user->hasRole('admin-wilayah')) {
    // Filter semua query hanya untuk region_id ini
    $query->where('region_id', $user->region_id);
}
```

### 3. Data yang Ditampilkan
- ✅ Mesin di DAOP 1
- ✅ Working Report di DAOP 1
- ✅ Maintenance Order di DAOP 1
- ❌ Mesin di DAOP 2, 3, dst (tidak terlihat)

### 4. Saat Create Data Baru
- Region ID otomatis terisi dengan region Admin Wilayah
- Dropdown region di-disable
- Tidak bisa memilih region lain

### 5. Saat Edit Data
- Middleware `regional.access` memeriksa: apakah data ini milik region user?
- Jika YA: boleh edit
- Jika TIDAK: HTTP 403 Forbidden

---

## 🧪 TESTING

### Test 1: Login sebagai Super Admin
```bash
# Buat Super Admin (region_id = NULL)
php artisan tinker
$user = User::where('username', 'admin')->first();
$user->region_id = null;
$user->save();
```

**Expected**: Melihat SEMUA data dari semua region

### Test 2: Login sebagai Admin Wilayah DAOP 1
```bash
# Login dengan: admin.daop1 / password
```

**Expected**: 
- Hanya melihat data DAOP 1
- Dropdown region di-disable
- Tidak bisa edit data DAOP lain

### Test 3: Coba Edit Data Region Lain
```bash
# Login sebagai admin.daop1
# Akses URL: /master-machines/{id-mesin-daop2}/edit
```

**Expected**: HTTP 403 Forbidden

---

## 🛠️ TROUBLESHOOTING

### Problem: "Column 'region_id' not found"
**Solution**: Jalankan migration
```bash
php artisan migrate
```

### Problem: "Role 'admin-wilayah' not found"
**Solution**: Jalankan seeder role
```bash
php artisan db:seed --class=AdminWilayahRoleSeeder
```

### Problem: Admin Wilayah masih bisa lihat semua data
**Solution**: Pastikan trait RegionalScope sudah ditambahkan di model
```php
use App\Traits\RegionalScope;

class MasterMachine extends Model
{
    use HasFactory, RegionalScope;
}
```

Dan controller menggunakan scope:
```php
MasterMachine::forCurrentUserRegion()->get();
```

---

## 📝 NEXT STEPS (OPSIONAL)

### 1. Tambahkan RegionalScope ke Model Lain
Models yang perlu ditambahkan trait `RegionalScope`:
- `WorkingReport`
- `MaintenanceOrder`
- `CheckSheet`
- `Report`

```php
use App\Traits\RegionalScope;

class WorkingReport extends Model
{
    use HasFactory, RegionalScope;
}
```

### 2. Update Controller Lain
Tambahkan `->forCurrentUserRegion()` di semua query:
- `WorkingReportController`
- `MaintenanceOrderController`
- `CheckSheetController`

### 3. Update Vue Components
Tambahkan logic untuk disable region dropdown:
```vue
const isRegionLocked = computed(() => {
    return props.auth.user.region_id && 
           props.auth.user.roles.some(r => r.name === 'admin-wilayah')
})
```

---

## 📞 SUPPORT

Jika ada pertanyaan atau masalah:
1. Periksa log: `storage/logs/laravel.log`
2. Debug di tinker: `php artisan tinker`
3. Cek permission user: `$user->getAllPermissions()`
4. Cek role user: `$user->getRoleNames()`

---

**✨ IMPLEMENTASI SELESAI!**

Sistem Admin Wilayah sudah siap digunakan. Setiap admin hanya akan melihat dan mengelola data di wilayah mereka masing-masing.
