# Panduan Lengkap: Machine Hierarchy Seeder

## 📋 Daftar Isi
1. [Pendahuluan](#pendahuluan)
2. [Analisis Sistem](#analisis-sistem)
3. [File yang Dibuat](#file-yang-dibuat)
4. [Cara Menggunakan](#cara-menggunakan)
5. [Struktur Data](#struktur-data)
6. [Tipe Mesin yang Didukung](#tipe-mesin-yang-didukung)
7. [Contoh Query](#contoh-query)
8. [Troubleshooting](#troubleshooting)

---

## 📖 Pendahuluan

Seeder ini dibuat untuk mengisi data master hierarki mesin pada sistem E-Ticketing Pelaporan KAI. Data hierarki mengikuti struktur 4 level berdasarkan dokumentasi sistem yang ada.

### Struktur Hierarki
```
Level 1: UNIT TYPE (Tipe Mesin)
└── Level 2: SYSTEM (Engine, Electric, Pneumatic, Hydraulic, Mechanic)
    └── Level 3: SUBSYSTEM (Sub-sistem dari setiap system)
        └── Level 4: COMPONENT (Komponen detail)
```

---

## 🔍 Analisis Sistem

### Database Structure
Berdasarkan analisis migration file:

**Tabel: `machine_components`**
```sql
CREATE TABLE machine_components (
    id BIGINT PRIMARY KEY,
    master_machine_id BIGINT NULL,  -- FK ke master_machines
    machine_type VARCHAR(255) NULL,  -- Tipe mesin
    parent_id BIGINT NULL,           -- FK self-reference
    code VARCHAR(255) NULL,          -- Kode hierarki (A, A.1, A.1.1, dll)
    name VARCHAR(255) NOT NULL,      -- Nama component
    level TINYINT DEFAULT 1,         -- Level hierarki (1-4)
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Relasi
- **Parent-Child**: Setiap component (kecuali level 1) memiliki `parent_id`
- **Machine Type**: Field `machine_type` digunakan untuk membedakan hierarki antar tipe mesin
- **Master Machine**: Optional reference ke `master_machines` table

---

## 📁 File yang Dibuat

### 1. MachineHierarchySeeder.php
**Lokasi**: `database/seeders/MachineHierarchySeeder.php`

Seeder dasar yang membuat hierarki untuk tipe mesin utama:
- MTT 07-16 G
- MTT 08-16 GS (dengan Crankshaft khusus)
- UNIMAT 08-275/3S
- MTT 09-16 CAT
- PBR 202/400
- KPJR

**Fitur**:
- Manual control untuk setiap tipe mesin
- Detail customization per system
- Suitable untuk development/testing

### 2. MachineHierarchyExtendedSeeder.php
**Lokasi**: `database/seeders/MachineHierarchyExtendedSeeder.php`

Seeder extended yang otomatis membuat hierarki untuk SEMUA tipe mesin:
- MTT 07-16 G
- MTT 08-16 GS
- MTT 08-275/3S-12
- UNIMAT 08-275/3S
- MTT 09-16 CAT
- MTT 09-32 CSM
- MTT B40-DE
- PBR 202
- PBR 400
- PBR 400 U-RS
- SSP 203
- USP 303
- VDM 800 GS
- TG 80-4

**Fitur**:
- Otomatis generate untuk semua tipe
- Conditional logic berdasarkan machine type
- Production-ready

### 3. seed-hierarchy.bat
**Lokasi**: `seed-hierarchy.bat`

Script utility untuk Windows yang menyediakan menu interaktif:
- Run seeder standar
- Run seeder extended
- Count data
- Truncate table

### 4. MACHINE_HIERARCHY_SEEDER.md
Dokumentasi lengkap tentang struktur hierarki dan cara penggunaan.

---

## 🚀 Cara Menggunakan

### Opsi 1: Menggunakan Script Batch (Windows) - **RECOMMENDED**

```bash
# Jalankan script
seed-hierarchy.bat
```

Menu yang muncul:
```
1. Jalankan Seeder Standar (MachineHierarchySeeder)
2. Jalankan Seeder Extended (MachineHierarchyExtendedSeeder)
3. Lihat jumlah data yang sudah ada
4. Truncate tabel machine_components
5. Exit
```

### Opsi 2: Menggunakan Artisan Command

#### Run Seeder Standar
```bash
php artisan db:seed --class=MachineHierarchySeeder
```

#### Run Seeder Extended (RECOMMENDED untuk Production)
```bash
php artisan db:seed --class=MachineHierarchyExtendedSeeder
```

### Opsi 3: Menambahkan ke DatabaseSeeder

Edit `database/seeders/DatabaseSeeder.php`:
```php
public function run()
{
    $this->call([
        InitialSeeder::class,
        MenuSeeder::class,
        MachineHierarchyExtendedSeeder::class, // Tambahkan ini
    ]);
}
```

Kemudian jalankan:
```bash
php artisan db:seed
```

---

## 📊 Struktur Data

### Contoh Data yang Dihasilkan

#### Level 1 - Unit Type
```
ID  | CODE | NAME           | LEVEL | PARENT_ID | MACHINE_TYPE
----|------|----------------|-------|-----------|-------------
1   | A    | MTT 07-16 G    | 1     | NULL      | MTT 07-16 G
```

#### Level 2 - System
```
ID  | CODE | NAME      | LEVEL | PARENT_ID | MACHINE_TYPE
----|------|-----------|-------|-----------|-------------
2   | A.1  | ENGINE    | 2     | 1         | MTT 07-16 G
3   | A.2  | ELECTRIC  | 2     | 1         | MTT 07-16 G
4   | A.3  | PNEUMATIC | 2     | 1         | MTT 07-16 G
5   | A.4  | HYDRAULIC | 2     | 1         | MTT 07-16 G
6   | A.5  | MECHANIC  | 2     | 1         | MTT 07-16 G
```

#### Level 3 - Subsystem (Engine)
```
ID  | CODE  | NAME                | LEVEL | PARENT_ID | MACHINE_TYPE
----|-------|---------------------|-------|-----------|-------------
7   | A.1.1 | Sistem Bahan Bakar  | 3     | 2         | MTT 07-16 G
8   | A.1.2 | Sistem Pelumasan    | 3     | 2         | MTT 07-16 G
9   | A.1.3 | Sistem Pendinginan  | 3     | 2         | MTT 07-16 G
```

#### Level 4 - Component
```
ID  | CODE    | NAME                    | LEVEL | PARENT_ID | MACHINE_TYPE
----|---------|-------------------------|-------|-----------|-------------
10  | A.1.1.1 | Fuel Tank               | 4     | 7         | MTT 07-16 G
11  | A.1.1.2 | Bathing Fuel Pressure   | 4     | 7         | MTT 07-16 G
12  | A.1.1.3 | Fuel Line               | 4     | 7         | MTT 07-16 G
```

---

## 🏭 Tipe Mesin yang Didukung

### MTT Series (Tamping Machines)
1. **MTT 07-16 G** - Plain Line Tamping
2. **MTT 08-16 GS** - Plain Line Tamping (dengan Crankshaft)
3. **MTT 08-275/3S-12** - Rail Switch Tamping
4. **MTT 09-16 CAT** - Plain Line Tamping
5. **MTT 09-32 CSM** - Plain Line Tamping
6. **MTT B40-DE** - Plain Line Tamping

### UNIMAT Series
7. **UNIMAT 08-275/3S** - Rail Switch Tamping

### PBR Series (Ballast Regulator)
8. **PBR 202** - Profiling
9. **PBR 400** - Profiling
10. **PBR 400 U-RS** - Distributing and Profiling

### Other Machines
11. **SSP 203** - Distributing and Profiling
12. **USP 303** - Profiling
13. **VDM 800 GS** - Dynamic Track Stabilization
14. **TG 80-4** - Material Transport Wagon

---

## 💡 Contoh Query

### 1. Mendapatkan semua tipe mesin
```php
$machineTypes = MachineComponent::where('level', 1)
    ->select('machine_type', 'name')
    ->get();
```

### 2. Mendapatkan semua sistem untuk MTT 07-16 G
```php
$systems = MachineComponent::where('machine_type', 'MTT 07-16 G')
    ->where('level', 2)
    ->get();
```

### 3. Mendapatkan hierarki lengkap ENGINE
```php
$engine = MachineComponent::where('machine_type', 'MTT 07-16 G')
    ->where('code', 'A.1')
    ->with('children.children')
    ->first();
```

### 4. Mendapatkan semua komponen Sistem Bahan Bakar
```php
$components = MachineComponent::where('machine_type', 'MTT 07-16 G')
    ->where('code', 'LIKE', 'A.1.1.%')
    ->where('level', 4)
    ->get();
```

### 5. Mendapatkan tree structure lengkap
```php
// Di Model MachineComponent, tambahkan method:
public function childrenRecursive()
{
    return $this->children()->with('childrenRecursive');
}

// Query:
$tree = MachineComponent::where('machine_type', 'MTT 07-16 G')
    ->whereNull('parent_id')
    ->with('childrenRecursive')
    ->first();
```

### 6. Count komponen per level
```php
$counts = MachineComponent::where('machine_type', 'MTT 07-16 G')
    ->selectRaw('level, count(*) as total')
    ->groupBy('level')
    ->get();
```

### 7. Search komponen by name
```php
$components = MachineComponent::where('machine_type', 'MTT 07-16 G')
    ->where('name', 'LIKE', '%Pump%')
    ->get();
```

---

## 🔧 Troubleshooting

### Error: Foreign key constraint fails

**Masalah**: Error saat truncate table

**Solusi**:
```bash
# Gunakan script batch, pilih opsi 4 (Truncate table)
# Atau manual via tinker:
php artisan tinker
DB::statement('SET FOREIGN_KEY_CHECKS=0');
App\Models\MachineComponent::truncate();
DB::statement('SET FOREIGN_KEY_CHECKS=1');
```

### Error: Class not found

**Masalah**: Seeder class tidak ditemukan

**Solusi**:
```bash
composer dump-autoload
php artisan optimize:clear
```

### Data tidak muncul setelah seeding

**Solusi**:
```bash
# Check apakah seeder berhasil
php artisan db:seed --class=MachineHierarchyExtendedSeeder --verbose

# Cek jumlah data
php artisan tinker
MachineComponent::count()
```

### Ingin menambah komponen baru

**Langkah**:
1. Buka file `MachineHierarchyExtendedSeeder.php`
2. Cari method yang sesuai (contoh: `getEngineComponents()`)
3. Tambahkan komponen baru ke array
4. Run seeder ulang

### Duplicate entry error

**Masalah**: Data sudah ada

**Solusi**: Seeder sudah include truncate otomatis, tapi jika masih error:
```bash
# Gunakan script batch untuk truncate dulu
seed-hierarchy.bat
# Pilih opsi 4, kemudian jalankan seeder lagi
```

---

## 📈 Statistik Data

Setelah menjalankan **MachineHierarchyExtendedSeeder**, Anda akan mendapatkan:

- **14 tipe mesin** (Level 1)
- **70 systems** (Level 2) - 5 per mesin
- **±196 subsystems** (Level 3) - Bervariasi per mesin
- **±1400+ components** (Level 4)

**Total: ±1680 records**

---

## 🎯 Best Practices

1. **Development**: Gunakan `MachineHierarchySeeder` untuk testing
2. **Production**: Gunakan `MachineHierarchyExtendedSeeder`
3. **Backup**: Selalu backup database sebelum running seeder
4. **Testing**: Jalankan di environment development dulu
5. **Verification**: Gunakan script batch untuk verify jumlah data

---

## 📝 Catatan Penting

1. ⚠️ Seeder akan **TRUNCATE** (menghapus semua data) di tabel `machine_components` sebelum insert data baru
2. 🔒 Foreign key checks di-disable sementara saat truncate
3. 🏷️ Field `machine_type` sangat penting untuk membedakan hierarki
4. 🔗 Relasi parent-child menggunakan `parent_id`
5. 📊 Field `master_machine_id` dibiarkan NULL (bisa di-update kemudian)

---

## 🆘 Support

Jika ada pertanyaan atau masalah:
1. Cek dokumentasi di `MACHINE_HIERARCHY_SEEDER.md`
2. Lihat contoh query di atas
3. Gunakan script batch untuk operasi umum
4. Hubungi tim development

---

**Dibuat**: Desember 2024  
**Versi**: 2.0  
**Status**: Production Ready ✅
