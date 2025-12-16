# Machine Hierarchy Seeder Documentation

## Overview
Seeder ini digunakan untuk mengisi data hierarki mesin (machine components) ke dalam database. Data hierarki mengikuti struktur 4 level:

1. **Level 1**: Unit Type (Tipe Mesin) - Contoh: MTT 07-16 G, MTT 08-16 GS, UNIMAT 08-275/3S, dll
2. **Level 2**: System - ENGINE, ELECTRIC, PNEUMATIC, HYDRAULIC, MECHANIC
3. **Level 3**: Subsystem - Sub-sistem dari setiap system
4. **Level 4**: Component - Komponen detail dari setiap subsystem

## Tipe Mesin yang Didukung

Seeder ini mendukung 5 tipe mesin utama berdasarkan dokumentasi sistem hierarki:

1. **MTT 07-16 G** - Plain Line Tamping Machine
2. **MTT 08-16 GS** - Plain Line Tamping Machine (dengan Crankshaft)
3. **UNIMAT 08-275/3S** - Rail Switch Tamping Machine
4. **MTT 09-16 CAT** - Plain Line Tamping Machine
5. **PBR 202/400** - Ballast Regulator Machine

## Struktur Hierarki

### Kode Hierarki
```
A              - Level 1 (Unit Type)
├── A.1        - Level 2 (ENGINE System)
│   ├── A.1.1  - Level 3 (Subsystem: Sistem Bahan Bakar)
│   │   ├── A.1.1.1 - Level 4 (Component: Fuel Tank)
│   │   ├── A.1.1.2 - Level 4 (Component: Bathing Fuel Pressure)
│   │   └── ...
│   ├── A.1.2  - Level 3 (Subsystem: Sistem Pelumasan)
│   └── ...
├── A.2        - Level 2 (ELECTRIC System)
├── A.3        - Level 2 (PNEUMATIC System)
├── A.4        - Level 2 (HYDRAULIC System)
└── A.5        - Level 2 (MECHANIC System)
```

## Detail Sistem per Mesin

### 1. MTT 07-16 G / MTT 09-16 CAT / UNIMAT 08-275/3S

#### A.1 ENGINE
- **A.1.1** Sistem Bahan Bakar
  - Fuel Tank, Bathing Fuel Pressure, Fuel Line, Scavity Pump, Fuel Filter, dll
- **A.1.2** Sistem Pelumasan
  - Oil Pump, Indicator, Oil Pan, Oil Filter, Oil Cooler, Oil Line
- **A.1.3** Sistem Pendinginan
  - Water Cooling, Wheel, Water Pump, Klem/Selang, Thermostat, Radiator
- **A.1.4** Sistem Udara dan Gas Buang/Komponen Engine
  - Air Filter, Turbo Blower, Exhaust Manifold, Intercooler, Silencer/Muffler, dll
- **A.1.5** Komponen Umum Utama
  - Cylinder Head, Valve/Knuckle, Piston, Connecting Rod, Crankshaft, dll
- **A.1.6** Sistem Kontribusi
  - Radiator, Fuel Tank, Oil Tank
- **A.1.7** Starting System
  - Starter Motor, Battery, Wire Harness

#### A.2 ELECTRIC
- **A.2.1** Sistem Starter
  - Battery, Starter Motor, Panel/Starting, Alternator, Regulator, dll
- **A.2.2** Sistem Penerangan
  - Head Lamp, Tail Lamp, Turn Signal, Brake Lamp, Switch, dll

#### A.3 PNEUMATIC
- **A.3.1** Sistem Pengereman Pneumatik
  - Air Compressor, Air Tank, Pressure Valve, Brake Valve, dll
- **A.3.2** Sistem Pengereman
  - Brake Disk, Brake Pad, Brake Calliper, Brake Cylinder, dll

#### A.4 HYDRAULIC
- **A.4.1** Traveling/Hydro
  - Hydraulic Pump, Hydraulic Motor, Hydraulic Tank, dll
- **A.4.2** Working/Hydro
  - Hydraulic Cylinder, Hydraulic Pump, Control Valve, dll

#### A.5 MECHANIC
- **A.5.1** Tamping Inspection
  - Tamping Unit, Lifting Unit, Lining Unit, Leveling Unit, dll
- **A.5.2** Jork Mekanik
  - Bogie Frame, Wheel Set, Axle Box, Spring, Coupling, Drawbar

### 2. MTT 08-16 GS (Khusus)

Sama dengan MTT 07-16 G, tetapi:
- **A.1.1** berubah dari "Sistem Bahan Bakar" menjadi "**Cranck shaft**"

### 3. PBR 202/400

#### A.1 ENGINE
- **A.1.1** Fuel System
- **A.1.2** Sistem Pelumasan
- **A.1.3** Sistem Pendinginan
- **A.1.4** Sistem Udara dan Gas Buang/Komponen Engine
- **A.1.5** Komponen Umum Utama
- **A.1.6** Sistem Kontribusi

#### A.2 ELECTRIC
(Sama dengan MTT series)

#### A.3 PNEUMATIC
(Sama dengan MTT series)

#### A.4 HYDRAULIC
- **A.4.1** Traveling/Hydro
  - Oil Tank, Filter, Pump, Cooling Motor, Hose/Piping, dll
- **A.4.2** Working/Hydro
  - Pump, Valve, Cylinder, Hose, Filter, Oil Cooler, Tank

#### A.5 MECHANIC
- **A.5.1** Tamping Inspection
- **A.5.2** Plato Tensioner
- **A.5.3** Jork Lorong
- **A.5.4** Pump
- **A.5.5** Unit Distributor Gear Pump
- **A.5.6** Crowding dan Pembilasan Abrasif
- **A.5.7** Boiler Crane
- **A.5.8** Stabilizer Lifting
- **A.5.9** Stabilizer Lorong
- **A.5.10** Break Lorong

## Cara Menggunakan

### 1. Jalankan Seeder Individual

```bash
php artisan db:seed --class=MachineHierarchySeeder
```

### 2. Jalankan melalui DatabaseSeeder

Edit file `database/seeders/DatabaseSeeder.php`:

```php
public function run()
{
    $this->call([
        InitialSeeder::class,
        MenuSeeder::class,
        MachineHierarchySeeder::class, // Tambahkan ini
    ]);
}
```

Kemudian jalankan:

```bash
php artisan db:seed
```

### 3. Reset dan Seed Ulang

Jika ingin menghapus data lama dan seed ulang:

```bash
php artisan migrate:fresh --seed
```

**PERINGATAN**: Perintah di atas akan menghapus SEMUA data di database!

## Struktur Database

### Tabel: machine_components

| Field | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key |
| master_machine_id | bigint | Foreign key ke master_machines (nullable) |
| machine_type | varchar | Tipe mesin (MTT 07-16 G, dll) |
| parent_id | bigint | Foreign key ke parent component |
| code | varchar | Kode hierarki (A, A.1, A.1.1, dll) |
| name | varchar | Nama component |
| level | tinyint | Level hierarki (1-4) |
| created_at | timestamp | Waktu dibuat |
| updated_at | timestamp | Waktu diupdate |

## Contoh Query

### Mendapatkan semua sistem untuk MTT 07-16 G

```php
$systems = MachineComponent::where('machine_type', 'MTT 07-16 G')
    ->where('level', 2)
    ->get();
```

### Mendapatkan hierarki lengkap untuk ENGINE system

```php
$engineSystem = MachineComponent::where('machine_type', 'MTT 07-16 G')
    ->where('code', 'A.1')
    ->with('children.children')
    ->first();
```

### Mendapatkan semua komponen level 4 untuk subsistem tertentu

```php
$components = MachineComponent::where('machine_type', 'MTT 07-16 G')
    ->where('code', 'LIKE', 'A.1.1.%')
    ->where('level', 4)
    ->get();
```

## Catatan Penting

1. **Foreign Key Constraint**: Seeder akan menonaktifkan foreign key checks sementara saat truncate untuk menghindari error.

2. **Data Duplikat**: Setiap kali seeder dijalankan, data lama akan dihapus (truncate) dan diganti dengan data baru.

3. **Machine Type**: Field `machine_type` sangat penting untuk membedakan hierarki antar tipe mesin yang berbeda.

4. **Parent-Child Relationship**: Setiap komponen (kecuali level 1) memiliki parent_id yang menunjuk ke komponen parent-nya.

5. **Extensibility**: Untuk menambah tipe mesin baru, tambahkan method baru (contoh: `seedMTT0932CSM()`) dan panggil di method `run()`.

## Troubleshooting

### Error: Foreign key constraint fails

Pastikan tabel `machine_components` kosong atau jalankan dengan foreign key check disabled:

```sql
SET FOREIGN_KEY_CHECKS=0;
TRUNCATE machine_components;
SET FOREIGN_KEY_CHECKS=1;
```

### Data tidak muncul

Cek apakah seeder berhasil dijalankan:

```bash
php artisan db:seed --class=MachineHierarchySeeder --verbose
```

### Ingin menambah komponen baru

Edit method yang sesuai (contoh: `seedEngineComponents()`) dan tambahkan komponen baru ke array `$components`.

## Referensi

- Sistem Hierarki MTT (Gambar dokumentasi)
- Sistem Hierarki UNIMAT (Gambar dokumentasi)  
- Sistem Hierarki CAT (Gambar dokumentasi)
- Sistem Hierarki KPJR (Gambar dokumentasi)
- Sistem Hierarki PBR (Gambar dokumentasi)

---

**Created**: December 2024  
**Author**: Development Team  
**Version**: 1.0
