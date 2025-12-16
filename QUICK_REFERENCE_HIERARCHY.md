# Quick Reference: Machine Hierarchy

## 🚀 Quick Start

```bash
# Windows - Double click:
seed-hierarchy.bat

# Manual:
php artisan db:seed --class=MachineHierarchyExtendedSeeder
```

---

## 📋 Struktur Hierarki

```
A                    Level 1: Unit Type (MTT 07-16 G, dll)
├── A.1              Level 2: ENGINE
│   ├── A.1.1        Level 3: Sistem Bahan Bakar / Cranck shaft
│   │   ├── A.1.1.1  Level 4: Fuel Tank
│   │   ├── A.1.1.2  Level 4: Bathing Fuel Pressure
│   │   └── ...
│   ├── A.1.2        Level 3: Sistem Pelumasan
│   ├── A.1.3        Level 3: Sistem Pendinginan
│   ├── A.1.4        Level 3: Sistem Udara dan Gas Buang
│   ├── A.1.5        Level 3: Komponen Umum Utama
│   ├── A.1.6        Level 3: Sistem Kontribusi
│   └── A.1.7        Level 3: Starting System
├── A.2              Level 2: ELECTRIC
│   ├── A.2.1        Level 3: Sistem Starter
│   └── A.2.2        Level 3: Sistem Penerangan
├── A.3              Level 2: PNEUMATIC
│   ├── A.3.1        Level 3: Sistem Pengereman Pneumatik
│   └── A.3.2        Level 3: Sistem Pengereman
├── A.4              Level 2: HYDRAULIC
│   ├── A.4.1        Level 3: Traveling/Hydro
│   └── A.4.2        Level 3: Working/Hydro
└── A.5              Level 2: MECHANIC
    ├── A.5.1        Level 3: Tamping Inspection
    └── A.5.2        Level 3: Jork Mekanik
    └── ...          (PBR: up to A.5.10)
```

---

## 🏭 14 Tipe Mesin

| No | Machine Type       | Category          | Special Notes |
|----|-------------------|-------------------|---------------|
| 1  | MTT 07-16 G       | Tamping           | Standard      |
| 2  | MTT 08-16 GS      | Tamping           | ⭐ Crankshaft |
| 3  | MTT 08-275/3S-12  | Switch Tamping    | Standard      |
| 4  | MTT 09-16 CAT     | Tamping           | Standard      |
| 5  | MTT 09-32 CSM     | Tamping           | Standard      |
| 6  | MTT B40-DE        | Tamping           | Standard      |
| 7  | UNIMAT 08-275/3S  | Switch Tamping    | Standard      |
| 8  | PBR 202           | Ballast Regulator | ⭐ 10 Mech Sub |
| 9  | PBR 400           | Ballast Regulator | ⭐ 10 Mech Sub |
| 10 | PBR 400 U-RS      | Ballast Regulator | ⭐ 10 Mech Sub |
| 11 | SSP 203           | Profiling         | Standard      |
| 12 | USP 303           | Profiling         | Standard      |
| 13 | VDM 800 GS        | Stabilization     | Standard      |
| 14 | TG 80-4           | Material Transport| Standard      |

---

## 💡 Common Queries

### Get all systems
```php
MachineComponent::where('machine_type', 'MTT 07-16 G')
    ->where('level', 2)
    ->get();
```

### Get ENGINE hierarchy
```php
MachineComponent::where('code', 'A.1')
    ->with('children.children')
    ->first();
```

### Get all components
```php
MachineComponent::where('level', 4)
    ->where('machine_type', 'MTT 07-16 G')
    ->get();
```

### Count by level
```php
MachineComponent::selectRaw('level, count(*) as total')
    ->groupBy('level')
    ->get();
```

---

## 🔧 Common Commands

```bash
# Run seeder
php artisan db:seed --class=MachineHierarchyExtendedSeeder

# Count data
php artisan tinker --execute="echo MachineComponent::count()"

# Truncate
php artisan tinker --execute="DB::statement('SET FOREIGN_KEY_CHECKS=0'); MachineComponent::truncate(); DB::statement('SET FOREIGN_KEY_CHECKS=1');"

# View data
php artisan tinker
MachineComponent::where('level', 1)->get()
```

---

## 📊 Expected Data Count

| Level | Description | Count |
|-------|-------------|-------|
| 1     | Unit Types  | 14    |
| 2     | Systems     | 70    |
| 3     | Subsystems  | ~196  |
| 4     | Components  | ~1400 |
| **TOTAL** |         | **~1680** |

---

## ⚠️ Important

- ✅ Auto truncate before seeding
- ✅ Foreign keys handled
- ✅ Production ready
- ⚠️ Always backup first!

---

## 📖 Full Documentation

- `SUMMARY_MACHINE_HIERARCHY.md` - Summary
- `PANDUAN_MACHINE_HIERARCHY.md` - Complete guide
- `MACHINE_HIERARCHY_SEEDER.md` - Technical docs
