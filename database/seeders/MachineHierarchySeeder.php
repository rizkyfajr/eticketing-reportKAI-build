<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MachineComponent;
use Illuminate\Support\Facades\DB;

class MachineHierarchySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        MachineComponent::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Seed hierarchy untuk setiap tipe mesin
        $this->seedMTT0716G();
        $this->seedMTT0816GS();
        $this->seedUnimat08275();
        $this->seedMTT0916CAT();
        $this->seedPBR202400();
        $this->seedKPJR();
    }

    /**
     * Seed hierarchy untuk MTT 07-16 G
     */
    private function seedMTT0716G()
    {
        $machineType = 'MTT 07-16 G';

        // Level 1: Unit Type
        $unitType = MachineComponent::create([
            'machine_type' => $machineType,
            'code' => 'A',
            'name' => $machineType,
            'level' => 1,
            'parent_id' => null,
        ]);

        // Level 2: Systems
        $systems = [
            ['code' => 'A.1', 'name' => 'ENGINE'],
            ['code' => 'A.2', 'name' => 'ELECTRIC'],
            ['code' => 'A.3', 'name' => 'PNEUMATIC'],
            ['code' => 'A.4', 'name' => 'HYDRAULIC'],
            ['code' => 'A.5', 'name' => 'MECHANIC'],
        ];

        foreach ($systems as $systemData) {
            $system = MachineComponent::create([
                'machine_type' => $machineType,
                'code' => $systemData['code'],
                'name' => $systemData['name'],
                'level' => 2,
                'parent_id' => $unitType->id,
            ]);

            // Seed subsystems dan components berdasarkan system
            $this->seedSystemHierarchy($machineType, $system);
        }
    }

    /**
     * Seed hierarchy untuk MTT 08-16 GS
     */
    private function seedMTT0816GS()
    {
        $machineType = 'MTT 08-16 GS';

        // Level 1: Unit Type
        $unitType = MachineComponent::create([
            'machine_type' => $machineType,
            'code' => 'A',
            'name' => $machineType,
            'level' => 1,
            'parent_id' => null,
        ]);

        // Level 2: Systems
        $systems = [
            ['code' => 'A.1', 'name' => 'ENGINE'],
            ['code' => 'A.2', 'name' => 'ELECTRIC'],
            ['code' => 'A.3', 'name' => 'PNEUMATIC'],
            ['code' => 'A.4', 'name' => 'HYDRAULIC'],
            ['code' => 'A.5', 'name' => 'MECHANIC'],
        ];

        foreach ($systems as $systemData) {
            $system = MachineComponent::create([
                'machine_type' => $machineType,
                'code' => $systemData['code'],
                'name' => $systemData['name'],
                'level' => 2,
                'parent_id' => $unitType->id,
            ]);

            $this->seedSystemHierarchyMTT0816GS($machineType, $system);
        }
    }

    /**
     * Seed hierarchy untuk UNIMAT 08-275/3S
     */
    private function seedUnimat08275()
    {
        $machineType = 'UNIMAT 08-275/3S';

        // Level 1: Unit Type
        $unitType = MachineComponent::create([
            'machine_type' => $machineType,
            'code' => 'A',
            'name' => $machineType,
            'level' => 1,
            'parent_id' => null,
        ]);

        // Level 2: Systems
        $systems = [
            ['code' => 'A.1', 'name' => 'ENGINE'],
            ['code' => 'A.2', 'name' => 'ELECTRIC'],
            ['code' => 'A.3', 'name' => 'PNEUMATIC'],
            ['code' => 'A.4', 'name' => 'HYDRAULIC'],
            ['code' => 'A.5', 'name' => 'MECHANIC'],
        ];

        foreach ($systems as $systemData) {
            $system = MachineComponent::create([
                'machine_type' => $machineType,
                'code' => $systemData['code'],
                'name' => $systemData['name'],
                'level' => 2,
                'parent_id' => $unitType->id,
            ]);

            $this->seedSystemHierarchy($machineType, $system);
        }
    }

    /**
     * Seed hierarchy untuk MTT 09-16 CAT
     */
    private function seedMTT0916CAT()
    {
        $machineType = 'MTT 09-16 CAT';

        // Level 1: Unit Type
        $unitType = MachineComponent::create([
            'machine_type' => $machineType,
            'code' => 'A',
            'name' => $machineType,
            'level' => 1,
            'parent_id' => null,
        ]);

        // Level 2: Systems
        $systems = [
            ['code' => 'A.1', 'name' => 'ENGINE'],
            ['code' => 'A.2', 'name' => 'ELECTRIC'],
            ['code' => 'A.3', 'name' => 'PNEUMATIC'],
            ['code' => 'A.4', 'name' => 'HYDRAULIC'],
            ['code' => 'A.5', 'name' => 'MECHANIC'],
        ];

        foreach ($systems as $systemData) {
            $system = MachineComponent::create([
                'machine_type' => $machineType,
                'code' => $systemData['code'],
                'name' => $systemData['name'],
                'level' => 2,
                'parent_id' => $unitType->id,
            ]);

            $this->seedSystemHierarchy($machineType, $system);
        }
    }

    /**
     * Seed hierarchy untuk PBR 202/400
     */
    private function seedPBR202400()
    {
        $machineType = 'PBR 202/400';

        // Level 1: Unit Type
        $unitType = MachineComponent::create([
            'machine_type' => $machineType,
            'code' => 'A',
            'name' => $machineType,
            'level' => 1,
            'parent_id' => null,
        ]);

        // Level 2: Systems
        $systems = [
            ['code' => 'A.1', 'name' => 'ENGINE'],
            ['code' => 'A.2', 'name' => 'ELECTRIC'],
            ['code' => 'A.3', 'name' => 'PNEUMATIC'],
            ['code' => 'A.4', 'name' => 'HYDRAULIC'],
            ['code' => 'A.5', 'name' => 'MECHANIC'],
        ];

        foreach ($systems as $systemData) {
            $system = MachineComponent::create([
                'machine_type' => $machineType,
                'code' => $systemData['code'],
                'name' => $systemData['name'],
                'level' => 2,
                'parent_id' => $unitType->id,
            ]);

            $this->seedSystemHierarchyPBR($machineType, $system);
        }
    }

    /**
     * Seed hierarchy untuk KPJR
     */
    private function seedKPJR()
    {
        $machineType = 'PBR 202/400';

        // Level 1: Unit Type
        $unitType = MachineComponent::create([
            'machine_type' => $machineType,
            'code' => 'A',
            'name' => $machineType,
            'level' => 1,
            'parent_id' => null,
        ]);

        // Level 2: Systems
        $systems = [
            ['code' => 'A.1', 'name' => 'ENGINE'],
            ['code' => 'A.2', 'name' => 'ELECTRIC'],
            ['code' => 'A.3', 'name' => 'PNEUMATIC'],
            ['code' => 'A.4', 'name' => 'HYDRAULIC'],
            ['code' => 'A.5', 'name' => 'MECHANIC'],
        ];

        foreach ($systems as $systemData) {
            $system = MachineComponent::create([
                'machine_type' => $machineType,
                'code' => $systemData['code'],
                'name' => $systemData['name'],
                'level' => 2,
                'parent_id' => $unitType->id,
            ]);

            $this->seedSystemHierarchy($machineType, $system);
        }
    }

    /**
     * Seed subsystems dan components untuk setiap system (Generic)
     */
    private function seedSystemHierarchy($machineType, $system)
    {
        $systemCode = $system->code;

        switch ($systemCode) {
            case 'A.1': // ENGINE
                $this->seedEngineSystem($machineType, $system);
                break;
            case 'A.2': // ELECTRIC
                $this->seedElectricSystem($machineType, $system);
                break;
            case 'A.3': // PNEUMATIC
                $this->seedPneumaticSystem($machineType, $system);
                break;
            case 'A.4': // HYDRAULIC
                $this->seedHydraulicSystem($machineType, $system);
                break;
            case 'A.5': // MECHANIC
                $this->seedMechanicSystem($machineType, $system);
                break;
        }
    }

    /**
     * Seed subsystems dan components untuk MTT 08-16 GS
     */
    private function seedSystemHierarchyMTT0816GS($machineType, $system)
    {
        $systemCode = $system->code;

        switch ($systemCode) {
            case 'A.1': // ENGINE dengan Cranck shaft
                $this->seedEngineSystemWithCrankshaft($machineType, $system);
                break;
            case 'A.2': // ELECTRIC
                $this->seedElectricSystem($machineType, $system);
                break;
            case 'A.3': // PNEUMATIC
                $this->seedPneumaticSystem($machineType, $system);
                break;
            case 'A.4': // HYDRAULIC
                $this->seedHydraulicSystem($machineType, $system);
                break;
            case 'A.5': // MECHANIC
                $this->seedMechanicSystem($machineType, $system);
                break;
        }
    }

    /**
     * Seed ENGINE system (Generic)
     */
    private function seedEngineSystem($machineType, $system)
    {
        $subsystems = [
            ['code' => 'A.1.1', 'name' => 'Sistem Bahan Bakar'],
            ['code' => 'A.1.2', 'name' => 'Sistem Pelumasan'],
            ['code' => 'A.1.3', 'name' => 'Sistem Pendinginan'],
            ['code' => 'A.1.4', 'name' => 'Sistem Udara dan Gas Buang/Komponen Engine'],
            ['code' => 'A.1.5', 'name' => 'Komponen Umum Utama'],
            ['code' => 'A.1.6', 'name' => 'Sistem Kontribusi'],
            ['code' => 'A.1.7', 'name' => 'Starting System'],
        ];

        foreach ($subsystems as $subsystemData) {
            $subsystem = MachineComponent::create([
                'machine_type' => $machineType,
                'code' => $subsystemData['code'],
                'name' => $subsystemData['name'],
                'level' => 3,
                'parent_id' => $system->id,
            ]);

            // Seed components untuk setiap subsystem
            $this->seedEngineComponents($machineType, $subsystem);
        }
    }

    /**
     * Seed ENGINE system dengan Crankshaft (MTT 08-16 GS)
     */
    private function seedEngineSystemWithCrankshaft($machineType, $system)
    {
        $subsystems = [
            ['code' => 'A.1.1', 'name' => 'Cranck shaft'],
            ['code' => 'A.1.2', 'name' => 'Sistem Pelumasan'],
            ['code' => 'A.1.3', 'name' => 'Sistem Pendinginan'],
            ['code' => 'A.1.4', 'name' => 'Sistem Udara dan Gas Buang/Komponen Engine'],
            ['code' => 'A.1.5', 'name' => 'Komponen Umum Utama'],
            ['code' => 'A.1.6', 'name' => 'Sistem Kontribusi'],
            ['code' => 'A.1.7', 'name' => 'Starting System'],
        ];

        foreach ($subsystems as $subsystemData) {
            $subsystem = MachineComponent::create([
                'machine_type' => $machineType,
                'code' => $subsystemData['code'],
                'name' => $subsystemData['name'],
                'level' => 3,
                'parent_id' => $system->id,
            ]);

            $this->seedEngineComponents($machineType, $subsystem);
        }
    }

    /**
     * Seed components untuk ENGINE subsystems
     */
    private function seedEngineComponents($machineType, $subsystem)
    {
        $subsystemCode = $subsystem->code;
        $components = [];

        switch ($subsystemCode) {
            case 'A.1.1': // Sistem Bahan Bakar / Cranck shaft
                $components = [
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
                ];
                break;
            case 'A.1.2': // Sistem Pelumasan
                $components = [
                    'Oil Pump',
                    'Indicator',
                    'Oil Pan',
                    'Oil Filter',
                    'Oil Cooler',
                    'Oil Line',
                ];
                break;
            case 'A.1.3': // Sistem Pendinginan
                $components = [
                    'Water Cooling',
                    'Wheel',
                    'Water Pump',
                    'Klem / Selang',
                    'Thermostat',
                    'Radiator',
                ];
                break;
            case 'A.1.4': // Sistem Udara dan Gas Buang
                $components = [
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
                ];
                break;
            case 'A.1.5': // Komponen Umum Utama
                $components = [
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
                ];
                break;
            case 'A.1.6': // Sistem Kontribusi
                $components = [
                    'Radiator',
                    'Fuel Tank',
                    'Oil Tank',
                ];
                break;
            case 'A.1.7': // Starting System
                $components = [
                    'Starter Motor',
                    'Battery',
                    'Wire Harness',
                ];
                break;
        }

        foreach ($components as $index => $componentName) {
            MachineComponent::create([
                'machine_type' => $machineType,
                'code' => $subsystemCode . '.' . ($index + 1),
                'name' => $componentName,
                'level' => 4,
                'parent_id' => $subsystem->id,
            ]);
        }
    }

    /**
     * Seed ELECTRIC system
     */
    private function seedElectricSystem($machineType, $system)
    {
        $subsystems = [
            ['code' => 'A.2.1', 'name' => 'Sistem Starter'],
            ['code' => 'A.2.2', 'name' => 'Sistem Penerangan'],
        ];

        foreach ($subsystems as $subsystemData) {
            $subsystem = MachineComponent::create([
                'machine_type' => $machineType,
                'code' => $subsystemData['code'],
                'name' => $subsystemData['name'],
                'level' => 3,
                'parent_id' => $system->id,
            ]);

            $this->seedElectricComponents($machineType, $subsystem);
        }
    }

    /**
     * Seed components untuk ELECTRIC subsystems
     */
    private function seedElectricComponents($machineType, $subsystem)
    {
        $subsystemCode = $subsystem->code;
        $components = [];

        switch ($subsystemCode) {
            case 'A.2.1': // Sistem Starter
                $components = [
                    'Battery',
                    'Starter Motor',
                    'Panel / Starting',
                    'Alternator',
                    'Regulator',
                    'Wiring Harness',
                    'Starter Switch',
                    'Wire',
                ];
                break;
            case 'A.2.2': // Sistem Penerangan
                $components = [
                    'Head Lamp',
                    'Tail Lamp',
                    'Turn Signal',
                    'Brake Lamp',
                    'Switch',
                    'Wiring Harness',
                    'Fuse',
                    'Relay',
                ];
                break;
        }

        foreach ($components as $index => $componentName) {
            MachineComponent::create([
                'machine_type' => $machineType,
                'code' => $subsystemCode . '.' . ($index + 1),
                'name' => $componentName,
                'level' => 4,
                'parent_id' => $subsystem->id,
            ]);
        }
    }

    /**
     * Seed PNEUMATIC system
     */
    private function seedPneumaticSystem($machineType, $system)
    {
        $subsystems = [
            ['code' => 'A.3.1', 'name' => 'Sistem Pengereman Pneumatik'],
            ['code' => 'A.3.2', 'name' => 'Sistem Pengereman'],
        ];

        foreach ($subsystems as $subsystemData) {
            $subsystem = MachineComponent::create([
                'machine_type' => $machineType,
                'code' => $subsystemData['code'],
                'name' => $subsystemData['name'],
                'level' => 3,
                'parent_id' => $system->id,
            ]);

            $this->seedPneumaticComponents($machineType, $subsystem);
        }
    }

    /**
     * Seed components untuk PNEUMATIC subsystems
     */
    private function seedPneumaticComponents($machineType, $subsystem)
    {
        $subsystemCode = $subsystem->code;
        $components = [];

        switch ($subsystemCode) {
            case 'A.3.1': // Sistem Pengereman Pneumatik
                $components = [
                    'Air Compressor',
                    'Air Tank',
                    'Pressure Valve',
                    'Brake Valve',
                    'Pneumatic Cylinder',
                    'Air Filter',
                    'Pressure Gauge',
                    'Air Line',
                ];
                break;
            case 'A.3.2': // Sistem Pengereman
                $components = [
                    'Brake Disk',
                    'Brake Pad',
                    'Brake Calliper',
                    'Brake Cylinder',
                    'Brake Shoe',
                    'Brake Drum',
                ];
                break;
        }

        foreach ($components as $index => $componentName) {
            MachineComponent::create([
                'machine_type' => $machineType,
                'code' => $subsystemCode . '.' . ($index + 1),
                'name' => $componentName,
                'level' => 4,
                'parent_id' => $subsystem->id,
            ]);
        }
    }

    /**
     * Seed HYDRAULIC system
     */
    private function seedHydraulicSystem($machineType, $system)
    {
        $subsystems = [
            ['code' => 'A.4.1', 'name' => 'Traveling/Hydro'],
            ['code' => 'A.4.2', 'name' => 'Working/Hydro'],
        ];

        foreach ($subsystems as $subsystemData) {
            $subsystem = MachineComponent::create([
                'machine_type' => $machineType,
                'code' => $subsystemData['code'],
                'name' => $subsystemData['name'],
                'level' => 3,
                'parent_id' => $system->id,
            ]);

            $this->seedHydraulicComponents($machineType, $subsystem);
        }
    }

    /**
     * Seed components untuk HYDRAULIC subsystems
     */
    private function seedHydraulicComponents($machineType, $subsystem)
    {
        $subsystemCode = $subsystem->code;
        $components = [];

        switch ($subsystemCode) {
            case 'A.4.1': // Traveling/Hydro
                $components = [
                    'Hydraulic Pump',
                    'Hydraulic Motor',
                    'Hydraulic Tank',
                    'Hydraulic Filter',
                    'Hydraulic Line',
                    'Hydraulic Valve',
                    'Pressure Gauge',
                ];
                break;
            case 'A.4.2': // Working/Hydro
                $components = [
                    'Hydraulic Cylinder',
                    'Hydraulic Pump',
                    'Control Valve',
                    'Hydraulic Hose',
                    'Hydraulic Oil Cooler',
                    'Hydraulic Tank',
                ];
                break;
        }

        foreach ($components as $index => $componentName) {
            MachineComponent::create([
                'machine_type' => $machineType,
                'code' => $subsystemCode . '.' . ($index + 1),
                'name' => $componentName,
                'level' => 4,
                'parent_id' => $subsystem->id,
            ]);
        }
    }

    /**
     * Seed MECHANIC system
     */
    private function seedMechanicSystem($machineType, $system)
    {
        $subsystems = [
            ['code' => 'A.5.1', 'name' => 'Tamping Inspection'],
            ['code' => 'A.5.2', 'name' => 'Jork Mekanik'],
        ];

        foreach ($subsystems as $subsystemData) {
            $subsystem = MachineComponent::create([
                'machine_type' => $machineType,
                'code' => $subsystemData['code'],
                'name' => $subsystemData['name'],
                'level' => 3,
                'parent_id' => $system->id,
            ]);

            $this->seedMechanicComponents($machineType, $subsystem);
        }
    }

    /**
     * Seed components untuk MECHANIC subsystems
     */
    private function seedMechanicComponents($machineType, $subsystem)
    {
        $subsystemCode = $subsystem->code;
        $components = [];

        switch ($subsystemCode) {
            case 'A.5.1': // Tamping Inspection
                $components = [
                    'Tamping Unit',
                    'Lifting Unit',
                    'Lining Unit',
                    'Leveling Unit',
                    'Measuring System',
                    'Reference System',
                ];
                break;
            case 'A.5.2': // Jork Mekanik
                $components = [
                    'Bogie Frame',
                    'Wheel Set',
                    'Axle Box',
                    'Spring',
                    'Coupling',
                    'Drawbar',
                ];
                break;
        }

        foreach ($components as $index => $componentName) {
            MachineComponent::create([
                'machine_type' => $machineType,
                'code' => $subsystemCode . '.' . ($index + 1),
                'name' => $componentName,
                'level' => 4,
                'parent_id' => $subsystem->id,
            ]);
        }
    }

    /**
     * Seed subsystems dan components untuk PBR system
     */
    private function seedSystemHierarchyPBR($machineType, $system)
    {
        $systemCode = $system->code;

        switch ($systemCode) {
            case 'A.1': // ENGINE
                $this->seedEngineSystemPBR($machineType, $system);
                break;
            case 'A.2': // ELECTRIC
                $this->seedElectricSystem($machineType, $system);
                break;
            case 'A.3': // PNEUMATIC
                $this->seedPneumaticSystem($machineType, $system);
                break;
            case 'A.4': // HYDRAULIC
                $this->seedHydraulicSystemPBR($machineType, $system);
                break;
            case 'A.5': // MECHANIC
                $this->seedMechanicSystemPBR($machineType, $system);
                break;
        }
    }

    /**
     * Seed ENGINE system untuk PBR
     */
    private function seedEngineSystemPBR($machineType, $system)
    {
        $subsystems = [
            ['code' => 'A.1.1', 'name' => 'Fuel System'],
            ['code' => 'A.1.2', 'name' => 'Sistem Pelumasan'],
            ['code' => 'A.1.3', 'name' => 'Sistem Pendinginan'],
            ['code' => 'A.1.4', 'name' => 'Sistem Udara dan Gas Buang/Komponen Engine'],
            ['code' => 'A.1.5', 'name' => 'Komponen Umum Utama'],
            ['code' => 'A.1.6', 'name' => 'Sistem Kontribusi'],
        ];

        foreach ($subsystems as $subsystemData) {
            $subsystem = MachineComponent::create([
                'machine_type' => $machineType,
                'code' => $subsystemData['code'],
                'name' => $subsystemData['name'],
                'level' => 3,
                'parent_id' => $system->id,
            ]);

            $this->seedEngineComponents($machineType, $subsystem);
        }
    }

    /**
     * Seed HYDRAULIC system untuk PBR
     */
    private function seedHydraulicSystemPBR($machineType, $system)
    {
        $subsystems = [
            ['code' => 'A.4.1', 'name' => 'Traveling/Hydro'],
            ['code' => 'A.4.2', 'name' => 'Working/Hydro'],
        ];

        foreach ($subsystems as $subsystemData) {
            $subsystem = MachineComponent::create([
                'machine_type' => $machineType,
                'code' => $subsystemData['code'],
                'name' => $subsystemData['name'],
                'level' => 3,
                'parent_id' => $system->id,
            ]);

            $this->seedHydraulicComponentsPBR($machineType, $subsystem);
        }
    }

    /**
     * Seed components untuk HYDRAULIC subsystems PBR
     */
    private function seedHydraulicComponentsPBR($machineType, $subsystem)
    {
        $subsystemCode = $subsystem->code;
        $components = [];

        switch ($subsystemCode) {
            case 'A.4.1': // Traveling/Hydro
                $components = [
                    'Oil Tank',
                    'Filter',
                    'Pump',
                    'Cooling Motor',
                    'Hose/Piping',
                    'Oil Tank',
                    'Cooler',
                    'Motor',
                    'Coupling',
                    'Oil Pump',
                    'Motor Traveling',
                    'Valve',
                ];
                break;
            case 'A.4.2': // Working/Hydro
                $components = [
                    'Pump',
                    'Valve',
                    'Cylinder',
                    'Hose',
                    'Filter',
                    'Oil Cooler',
                    'Tank',
                ];
                break;
        }

        foreach ($components as $index => $componentName) {
            MachineComponent::create([
                'machine_type' => $machineType,
                'code' => $subsystemCode . '.' . ($index + 1),
                'name' => $componentName,
                'level' => 4,
                'parent_id' => $subsystem->id,
            ]);
        }
    }

    /**
     * Seed MECHANIC system untuk PBR
     */
    private function seedMechanicSystemPBR($machineType, $system)
    {
        $subsystems = [
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

        foreach ($subsystems as $subsystemData) {
            $subsystem = MachineComponent::create([
                'machine_type' => $machineType,
                'code' => $subsystemData['code'],
                'name' => $subsystemData['name'],
                'level' => 3,
                'parent_id' => $system->id,
            ]);

            $this->seedMechanicComponentsPBR($machineType, $subsystem);
        }
    }

    /**
     * Seed components untuk MECHANIC subsystems PBR
     */
    private function seedMechanicComponentsPBR($machineType, $subsystem)
    {
        $subsystemCode = $subsystem->code;
        $components = [];

        switch ($subsystemCode) {
            case 'A.5.1': // Tamping Inspection
                $components = [
                    'Tamping Unit Assembly',
                    'Planeering Unit',
                    'Measuring Frame',
                    'Leveling and Planing Cylinder Tank Assembly',
                    'Planeering Tank',
                    'Planeering Cylinder',
                    'Planeering Tank',
                    'Planing Assembly',
                    'Planing Hydraulic Motor',
                    'Planing Cylinder',
                ];
                break;
            case 'A.5.2': // Plato Tensioner
                $components = [
                    'Bearing',
                    'Pulley / Conveyor',
                    'Conveyor Belt',
                    'Plato Tensioner Carriage Tensioner',
                    'Drum Assembly',
                    'Bracket Drum',
                    'Conveyor Belt Scraper',
                    'Front Scraper',
                    'Disk Vibrator',
                ];
                break;
            case 'A.5.3': // Jork Lorong
                $components = [
                    'Bogie Frame',
                    'Wheel',
                    'Axle',
                    'Bearing',
                    'Spring',
                ];
                break;
            case 'A.5.4': // Pump
                $components = [
                    'Unit Distributor Gear Pump',
                    'Hydraulic Pump',
                    'Gear Pump',
                ];
                break;
            case 'A.5.5': // Unit Distributor Gear Pump
                $components = [
                    'Ballast Cleaning Assembly',
                    'Ballast Box Leveling',
                    'Ballast Box',
                    'Ballast Box Lifting',
                    'Ballast Conveyor',
                ];
                break;
            case 'A.5.6': // Crowding dan Pembilasan Abrasif
                $components = [
                    'Crowding Unit',
                    'Pembilasan Abrasif Assembly',
                ];
                break;
            case 'A.5.7': // Boiler Crane
                $components = [
                    'Boom',
                    'Cable',
                    'Hook',
                    'Winch',
                ];
                break;
            case 'A.5.8': // Stabilizer Lifting
                $components = [
                    'Lifting Cylinder',
                    'Lifting Frame',
                ];
                break;
            case 'A.5.9': // Stabilizer Lorong
                $components = [
                    'Track',
                    'Guide Wheel',
                ];
                break;
            case 'A.5.10': // Break Lorong
                $components = [
                    'Brake Disk',
                    'Brake Pad',
                    'Brake Cylinder',
                ];
                break;
        }

        foreach ($components as $index => $componentName) {
            MachineComponent::create([
                'machine_type' => $machineType,
                'code' => $subsystemCode . '.' . ($index + 1),
                'name' => $componentName,
                'level' => 4,
                'parent_id' => $subsystem->id,
            ]);
        }
    }
}
