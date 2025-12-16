<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MachineComponent;
use Illuminate\Support\Facades\DB;

/**
 * Extended Machine Hierarchy Seeder
 * Berisi data hierarki lengkap dan detail untuk semua tipe mesin
 * berdasarkan dokumentasi sistem hierarki MTT, UNIMAT, CAT, KPJR, dan PBR
 */
class MachineHierarchyExtendedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        MachineComponent::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Seed semua tipe mesin
        $this->seedAllMachineTypes();
    }

    /**
     * Seed semua tipe mesin
     */
    private function seedAllMachineTypes(): void
    {
        $machineTypes = [
            'MTT 07-16 G',
            'MTT 08-16 GS',
            'MTT 08-275/3S-12',
            'UNIMAT 08-275/3S',
            'MTT 09-16 CAT',
            'MTT 09-32 CSM',
            'MTT B40-DE',
            'PBR 202',
            'PBR 400',
            'PBR 400 U-RS',
            'SSP 203',
            'USP 303',
            'VDM 800 GS',
            'TG 80-4',
        ];

        foreach ($machineTypes as $machineType) {
            $this->seedMachineTypeHierarchy($machineType);
        }
    }

    /**
     * Seed hierarki untuk satu tipe mesin
     */
    private function seedMachineTypeHierarchy(string $machineType): void
    {
        // Level 1: Unit Type
        $unitType = MachineComponent::create([
            'machine_type' => $machineType,
            'code' => 'A',
            'name' => $machineType,
            'level' => 1,
            'parent_id' => null,
        ]);

        // Level 2: Systems
        $this->createEngineSystem($machineType, $unitType);
        $this->createElectricSystem($machineType, $unitType);
        $this->createPneumaticSystem($machineType, $unitType);
        $this->createHydraulicSystem($machineType, $unitType);
        $this->createMechanicSystem($machineType, $unitType);
    }

    /**
     * ENGINE System - A.1
     */
    private function createEngineSystem(string $machineType, $unitType): void
    {
        $system = MachineComponent::create([
            'machine_type' => $machineType,
            'code' => 'A.1',
            'name' => 'ENGINE',
            'level' => 2,
            'parent_id' => $unitType->id,
        ]);

        // Subsystems
        $subsystems = $this->getEngineSubsystems($machineType);

        foreach ($subsystems as $subData) {
            $subsystem = MachineComponent::create([
                'machine_type' => $machineType,
                'code' => $subData['code'],
                'name' => $subData['name'],
                'level' => 3,
                'parent_id' => $system->id,
            ]);

            // Components
            $components = $this->getEngineComponents($machineType, $subData['code']);
            foreach ($components as $index => $componentName) {
                MachineComponent::create([
                    'machine_type' => $machineType,
                    'code' => $subData['code'] . '.' . ($index + 1),
                    'name' => $componentName,
                    'level' => 4,
                    'parent_id' => $subsystem->id,
                ]);
            }
        }
    }

    /**
     * ELECTRIC System - A.2
     */
    private function createElectricSystem(string $machineType, $unitType): void
    {
        $system = MachineComponent::create([
            'machine_type' => $machineType,
            'code' => 'A.2',
            'name' => 'ELECTRIC',
            'level' => 2,
            'parent_id' => $unitType->id,
        ]);

        $subsystems = [
            ['code' => 'A.2.1', 'name' => 'Sistem Starter'],
            ['code' => 'A.2.2', 'name' => 'Sistem Penerangan'],
        ];

        foreach ($subsystems as $subData) {
            $subsystem = MachineComponent::create([
                'machine_type' => $machineType,
                'code' => $subData['code'],
                'name' => $subData['name'],
                'level' => 3,
                'parent_id' => $system->id,
            ]);

            $components = $this->getElectricComponents($subData['code']);
            foreach ($components as $index => $componentName) {
                MachineComponent::create([
                    'machine_type' => $machineType,
                    'code' => $subData['code'] . '.' . ($index + 1),
                    'name' => $componentName,
                    'level' => 4,
                    'parent_id' => $subsystem->id,
                ]);
            }
        }
    }

    /**
     * PNEUMATIC System - A.3
     */
    private function createPneumaticSystem(string $machineType, $unitType): void
    {
        $system = MachineComponent::create([
            'machine_type' => $machineType,
            'code' => 'A.3',
            'name' => 'PNEUMATIC',
            'level' => 2,
            'parent_id' => $unitType->id,
        ]);

        $subsystems = [
            ['code' => 'A.3.1', 'name' => 'Sistem Pengereman Pneumatik'],
            ['code' => 'A.3.2', 'name' => 'Sistem Pengereman'],
        ];

        foreach ($subsystems as $subData) {
            $subsystem = MachineComponent::create([
                'machine_type' => $machineType,
                'code' => $subData['code'],
                'name' => $subData['name'],
                'level' => 3,
                'parent_id' => $system->id,
            ]);

            $components = $this->getPneumaticComponents($subData['code']);
            foreach ($components as $index => $componentName) {
                MachineComponent::create([
                    'machine_type' => $machineType,
                    'code' => $subData['code'] . '.' . ($index + 1),
                    'name' => $componentName,
                    'level' => 4,
                    'parent_id' => $subsystem->id,
                ]);
            }
        }
    }

    /**
     * HYDRAULIC System - A.4
     */
    private function createHydraulicSystem(string $machineType, $unitType): void
    {
        $system = MachineComponent::create([
            'machine_type' => $machineType,
            'code' => 'A.4',
            'name' => 'HYDRAULIC',
            'level' => 2,
            'parent_id' => $unitType->id,
        ]);

        $subsystems = [
            ['code' => 'A.4.1', 'name' => 'Traveling/Hydro'],
            ['code' => 'A.4.2', 'name' => 'Working/Hydro'],
        ];

        foreach ($subsystems as $subData) {
            $subsystem = MachineComponent::create([
                'machine_type' => $machineType,
                'code' => $subData['code'],
                'name' => $subData['name'],
                'level' => 3,
                'parent_id' => $system->id,
            ]);

            $components = $this->getHydraulicComponents($machineType, $subData['code']);
            foreach ($components as $index => $componentName) {
                MachineComponent::create([
                    'machine_type' => $machineType,
                    'code' => $subData['code'] . '.' . ($index + 1),
                    'name' => $componentName,
                    'level' => 4,
                    'parent_id' => $subsystem->id,
                ]);
            }
        }
    }

    /**
     * MECHANIC System - A.5
     */
    private function createMechanicSystem(string $machineType, $unitType): void
    {
        $system = MachineComponent::create([
            'machine_type' => $machineType,
            'code' => 'A.5',
            'name' => 'MECHANIC',
            'level' => 2,
            'parent_id' => $unitType->id,
        ]);

        $subsystems = $this->getMechanicSubsystems($machineType);

        foreach ($subsystems as $subData) {
            $subsystem = MachineComponent::create([
                'machine_type' => $machineType,
                'code' => $subData['code'],
                'name' => $subData['name'],
                'level' => 3,
                'parent_id' => $system->id,
            ]);

            $components = $this->getMechanicComponents($machineType, $subData['code']);
            foreach ($components as $index => $componentName) {
                MachineComponent::create([
                    'machine_type' => $machineType,
                    'code' => $subData['code'] . '.' . ($index + 1),
                    'name' => $componentName,
                    'level' => 4,
                    'parent_id' => $subsystem->id,
                ]);
            }
        }
    }

    /**
     * Get Engine subsystems based on machine type
     */
    private function getEngineSubsystems(string $machineType): array
    {
        // MTT 08-16 GS memiliki "Cranck shaft" sebagai A.1.1
        if ($machineType === 'MTT 08-16 GS') {
            return [
                ['code' => 'A.1.1', 'name' => 'Cranck shaft'],
                ['code' => 'A.1.2', 'name' => 'Sistem Pelumasan'],
                ['code' => 'A.1.3', 'name' => 'Sistem Pendinginan'],
                ['code' => 'A.1.4', 'name' => 'Sistem Udara dan Gas Buang/Komponen Engine'],
                ['code' => 'A.1.5', 'name' => 'Komponen Umum Utama'],
                ['code' => 'A.1.6', 'name' => 'Sistem Kontribusi'],
                ['code' => 'A.1.7', 'name' => 'Starting System'],
            ];
        }

        // Default untuk MTT lainnya
        return [
            ['code' => 'A.1.1', 'name' => 'Sistem Bahan Bakar'],
            ['code' => 'A.1.2', 'name' => 'Sistem Pelumasan'],
            ['code' => 'A.1.3', 'name' => 'Sistem Pendinginan'],
            ['code' => 'A.1.4', 'name' => 'Sistem Udara dan Gas Buang/Komponen Engine'],
            ['code' => 'A.1.5', 'name' => 'Komponen Umum Utama'],
            ['code' => 'A.1.6', 'name' => 'Sistem Kontribusi'],
            ['code' => 'A.1.7', 'name' => 'Starting System'],
        ];
    }

    /**
     * Get Engine components
     */
    private function getEngineComponents(string $machineType, string $subsystemCode): array
    {
        $components = [
            'A.1.1' => [ // Sistem Bahan Bakar / Cranck shaft
                'Fuel Tank',
                'Bathing Fuel Pressure',
                'Fuel Line',
                'Scavity Pump',
                'Fuel Filter',
                'Fuel Transfer Pump',
                'Fuel Return Line',
                'Injection Pump',
                'Injection Tube',
                'Injection Nozzle',
                'Saringan Udara Filter Element',
            ],
            'A.1.2' => [ // Sistem Pelumasan
                'Oil Pump',
                'Indicator',
                'Oil Pan',
                'Oil Filter',
                'Oil Cooler',
                'Oil Line',
            ],
            'A.1.3' => [ // Sistem Pendinginan
                'Water Cooling',
                'Wheel',
                'Water Pump',
                'Klem / Selang',
                'Thermostat',
                'Radiator',
            ],
            'A.1.4' => [ // Sistem Udara dan Gas Buang
                'Air Filter',
                'Turbo Blower',
                'Exhaust Manifold',
                'Intercooler',
                'Silencer / Muffler',
                'Pipa / Exhaust Flange Control PO',
                'Penjepit Control PO atau Flange Control Panel',
                'Exhaust Valve',
                'Dust Collector',
                'Exhaust Pipe',
            ],
            'A.1.5' => [ // Komponen Umum Utama
                'Cylinder Head',
                'Valve / Knuckle',
                'Valve',
                'Piston',
                'Connecting Rod',
                'Crankshaft',
                'Main Bearing',
                'Connecting Rod Bearing',
                'Timing Gear',
                'Camshaft',
                'Flywheel',
                'Cylinder Block',
                'Cylinder Head Gasket',
                'Piston Ring',
            ],
            'A.1.6' => [ // Sistem Kontribusi
                'Radiator',
                'Fuel Tank',
                'Oil Tank',
            ],
            'A.1.7' => [ // Starting System
                'Starter Motor',
                'Battery',
                'Wire Harness',
            ],
        ];

        return $components[$subsystemCode] ?? [];
    }

    /**
     * Get Electric components
     */
    private function getElectricComponents(string $subsystemCode): array
    {
        $components = [
            'A.2.1' => [ // Sistem Starter
                'Battery',
                'Starter Motor',
                'Panel / Starting',
                'Alternator',
                'Regulator',
                'Wiring Harness',
                'Starter Switch',
                'Wire',
            ],
            'A.2.2' => [ // Sistem Penerangan
                'Head Lamp',
                'Tail Lamp',
                'Turn Signal',
                'Brake Lamp',
                'Switch',
                'Wiring Harness',
                'Fuse',
                'Relay',
            ],
        ];

        return $components[$subsystemCode] ?? [];
    }

    /**
     * Get Pneumatic components
     */
    private function getPneumaticComponents(string $subsystemCode): array
    {
        $components = [
            'A.3.1' => [ // Sistem Pengereman Pneumatik
                'Air Compressor',
                'Air Tank',
                'Pressure Valve',
                'Brake Valve',
                'Pneumatic Cylinder',
                'Air Filter',
                'Pressure Gauge',
                'Air Line',
            ],
            'A.3.2' => [ // Sistem Pengereman
                'Brake Disk',
                'Brake Pad',
                'Brake Calliper',
                'Brake Cylinder',
                'Brake Shoe',
                'Brake Drum',
            ],
        ];

        return $components[$subsystemCode] ?? [];
    }

    /**
     * Get Hydraulic components
     */
    private function getHydraulicComponents(string $machineType, string $subsystemCode): array
    {
        // PBR series memiliki komponen hydraulic yang berbeda
        if (str_contains($machineType, 'PBR')) {
            $components = [
                'A.4.1' => [ // Traveling/Hydro
                    'Oil Tank',
                    'Filter',
                    'Pump',
                    'Cooling Motor',
                    'Hose/Piping',
                    'Cooler',
                    'Motor',
                    'Coupling',
                    'Oil Pump',
                    'Motor Traveling',
                    'Valve',
                ],
                'A.4.2' => [ // Working/Hydro
                    'Pump',
                    'Valve',
                    'Cylinder',
                    'Hose',
                    'Filter',
                    'Oil Cooler',
                    'Tank',
                ],
            ];
        } else {
            // MTT series
            $components = [
                'A.4.1' => [ // Traveling/Hydro
                    'Hydraulic Pump',
                    'Hydraulic Motor',
                    'Hydraulic Tank',
                    'Hydraulic Filter',
                    'Hydraulic Line',
                    'Hydraulic Valve',
                    'Pressure Gauge',
                ],
                'A.4.2' => [ // Working/Hydro
                    'Hydraulic Cylinder',
                    'Hydraulic Pump',
                    'Control Valve',
                    'Hydraulic Hose',
                    'Hydraulic Oil Cooler',
                    'Hydraulic Tank',
                ],
            ];
        }

        return $components[$subsystemCode] ?? [];
    }

    /**
     * Get Mechanic subsystems based on machine type
     */
    private function getMechanicSubsystems(string $machineType): array
    {
        // PBR series memiliki subsystem mechanic yang lebih banyak
        if (str_contains($machineType, 'PBR')) {
            return [
                ['code' => 'A.5.1', 'name' => 'Tamping Inspection'],
                ['code' => 'A.5.2', 'name' => 'Plato Tensioner'],
                ['code' => 'A.5.3', 'name' => 'Jork Lorong'],
                ['code' => 'A.5.4', 'name' => 'Pump'],
                ['code' => 'A.5.5', 'name' => 'Unit Distributor Gear Pump'],
                ['code' => 'A.5.6', 'name' => 'Crowding dan Pembilasan Abrasif'],
                ['code' => 'A.5.7', 'name' => 'Boiler Crane'],
                ['code' => 'A.5.8', 'name' => 'Stabilizer Lifting'],
                ['code' => 'A.5.9', 'name' => 'Stabilizer Lorong'],
                ['code' => 'A.5.10', 'name' => 'Break Lorong'],
            ];
        }

        // MTT series
        return [
            ['code' => 'A.5.1', 'name' => 'Tamping Inspection'],
            ['code' => 'A.5.2', 'name' => 'Jork Mekanik'],
        ];
    }

    /**
     * Get Mechanic components
     */
    private function getMechanicComponents(string $machineType, string $subsystemCode): array
    {
        if (str_contains($machineType, 'PBR')) {
            $components = [
                'A.5.1' => [ // Tamping Inspection
                    'Tamping Unit Assembly',
                    'Planeering Unit',
                    'Measuring Frame',
                    'Leveling and Planing Cylinder Tank Assembly',
                    'Planeering Tank',
                    'Planeering Cylinder',
                    'Planing Assembly',
                    'Planing Hydraulic Motor',
                    'Planing Cylinder',
                ],
                'A.5.2' => [ // Plato Tensioner
                    'Bearing',
                    'Pulley / Conveyor',
                    'Conveyor Belt',
                    'Plato Tensioner Carriage Tensioner',
                    'Drum Assembly',
                    'Bracket Drum',
                    'Conveyor Belt Scraper',
                    'Front Scraper',
                    'Disk Vibrator',
                ],
                'A.5.3' => [ // Jork Lorong
                    'Bogie Frame',
                    'Wheel',
                    'Axle',
                    'Bearing',
                    'Spring',
                ],
                'A.5.4' => [ // Pump
                    'Unit Distributor Gear Pump',
                    'Hydraulic Pump',
                    'Gear Pump',
                ],
                'A.5.5' => [ // Unit Distributor Gear Pump
                    'Ballast Cleaning Assembly',
                    'Ballast Box Leveling',
                    'Ballast Box',
                    'Ballast Box Lifting',
                    'Ballast Conveyor',
                ],
                'A.5.6' => [ // Crowding dan Pembilasan Abrasif
                    'Crowding Unit',
                    'Pembilasan Abrasif Assembly',
                ],
                'A.5.7' => [ // Boiler Crane
                    'Boom',
                    'Cable',
                    'Hook',
                    'Winch',
                ],
                'A.5.8' => [ // Stabilizer Lifting
                    'Lifting Cylinder',
                    'Lifting Frame',
                ],
                'A.5.9' => [ // Stabilizer Lorong
                    'Track',
                    'Guide Wheel',
                ],
                'A.5.10' => [ // Break Lorong
                    'Brake Disk',
                    'Brake Pad',
                    'Brake Cylinder',
                ],
            ];
        } else {
            // MTT series
            $components = [
                'A.5.1' => [ // Tamping Inspection
                    'Tamping Unit',
                    'Lifting Unit',
                    'Lining Unit',
                    'Leveling Unit',
                    'Measuring System',
                    'Reference System',
                ],
                'A.5.2' => [ // Jork Mekanik
                    'Bogie Frame',
                    'Wheel Set',
                    'Axle Box',
                    'Spring',
                    'Coupling',
                    'Drawbar',
                ],
            ];
        }

        return $components[$subsystemCode] ?? [];
    }
}
