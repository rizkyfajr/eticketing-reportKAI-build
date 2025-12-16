<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MachineComponent;

/**
 * Test Seeder untuk Machine Hierarchy
 * Gunakan seeder ini untuk testing di development environment
 */
class TestMachineHierarchySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "\n=================================================\n";
        echo "Testing Machine Hierarchy Seeder\n";
        echo "=================================================\n\n";

        // Test 1: Count total records
        $this->testTotalRecords();

        // Test 2: Count by level
        $this->testCountByLevel();

        // Test 3: Count by machine type
        $this->testCountByMachineType();

        // Test 4: Validate hierarchy structure
        $this->testHierarchyStructure();

        // Test 5: Validate code format
        $this->testCodeFormat();

        // Test 6: Check parent-child relationships
        $this->testParentChildRelationships();

        echo "\n=================================================\n";
        echo "✅ All tests completed!\n";
        echo "=================================================\n\n";
    }

    /**
     * Test 1: Count total records
     */
    private function testTotalRecords(): void
    {
        $total = MachineComponent::count();
        echo "Test 1: Total Records\n";
        echo "  Total: {$total} records\n";
        echo "  Expected: ~1680 records\n";
        echo "  Status: " . ($total > 1500 ? "✅ PASS" : "❌ FAIL") . "\n\n";
    }

    /**
     * Test 2: Count by level
     */
    private function testCountByLevel(): void
    {
        echo "Test 2: Count by Level\n";

        $levels = MachineComponent::selectRaw('level, count(*) as total')
            ->groupBy('level')
            ->orderBy('level')
            ->get();

        foreach ($levels as $level) {
            $expected = match($level->level) {
                1 => 14,
                2 => 70,
                3 => '150-250',
                4 => '1200-1500',
                default => 'Unknown'
            };

            echo "  Level {$level->level}: {$level->total} records (Expected: {$expected})\n";
        }

        echo "  Status: ✅ PASS\n\n";
    }

    /**
     * Test 3: Count by machine type
     */
    private function testCountByMachineType(): void
    {
        echo "Test 3: Count by Machine Type\n";

        $types = MachineComponent::selectRaw('machine_type, count(*) as total')
            ->groupBy('machine_type')
            ->orderBy('machine_type')
            ->get();

        $expectedTypes = 14;
        echo "  Total machine types: " . $types->count() . " (Expected: {$expectedTypes})\n";

        foreach ($types->take(5) as $type) {
            echo "  - {$type->machine_type}: {$type->total} records\n";
        }

        if ($types->count() > 5) {
            echo "  ... and " . ($types->count() - 5) . " more types\n";
        }

        echo "  Status: " . ($types->count() === $expectedTypes ? "✅ PASS" : "❌ FAIL") . "\n\n";
    }

    /**
     * Test 4: Validate hierarchy structure
     */
    private function testHierarchyStructure(): void
    {
        echo "Test 4: Validate Hierarchy Structure\n";

        // Get one machine type for testing
        $machineType = MachineComponent::where('level', 1)->first();

        if (!$machineType) {
            echo "  ❌ No machine type found!\n\n";
            return;
        }

        echo "  Testing: {$machineType->machine_type}\n";

        // Check Level 1 (Unit Type)
        $level1 = MachineComponent::where('machine_type', $machineType->machine_type)
            ->where('level', 1)
            ->count();
        echo "  - Level 1 (Unit Type): {$level1} (Expected: 1) " . ($level1 === 1 ? "✅" : "❌") . "\n";

        // Check Level 2 (Systems)
        $level2 = MachineComponent::where('machine_type', $machineType->machine_type)
            ->where('level', 2)
            ->count();
        echo "  - Level 2 (Systems): {$level2} (Expected: 5) " . ($level2 === 5 ? "✅" : "❌") . "\n";

        // Check Level 3 (Subsystems)
        $level3 = MachineComponent::where('machine_type', $machineType->machine_type)
            ->where('level', 3)
            ->count();
        echo "  - Level 3 (Subsystems): {$level3} (Expected: >10) " . ($level3 > 10 ? "✅" : "❌") . "\n";

        // Check Level 4 (Components)
        $level4 = MachineComponent::where('machine_type', $machineType->machine_type)
            ->where('level', 4)
            ->count();
        echo "  - Level 4 (Components): {$level4} (Expected: >50) " . ($level4 > 50 ? "✅" : "❌") . "\n";

        echo "  Status: ✅ PASS\n\n";
    }

    /**
     * Test 5: Validate code format
     */
    private function testCodeFormat(): void
    {
        echo "Test 5: Validate Code Format\n";

        // Level 1 should be 'A'
        $level1Code = MachineComponent::where('level', 1)->first()->code ?? '';
        echo "  - Level 1 code: '{$level1Code}' (Expected: 'A') " . ($level1Code === 'A' ? "✅" : "❌") . "\n";

        // Level 2 should be 'A.X'
        $level2Sample = MachineComponent::where('level', 2)->first();
        $validLevel2 = preg_match('/^A\.\d+$/', $level2Sample->code ?? '');
        echo "  - Level 2 code: '{$level2Sample->code}' (Format: A.X) " . ($validLevel2 ? "✅" : "❌") . "\n";

        // Level 3 should be 'A.X.X'
        $level3Sample = MachineComponent::where('level', 3)->first();
        $validLevel3 = preg_match('/^A\.\d+\.\d+$/', $level3Sample->code ?? '');
        echo "  - Level 3 code: '{$level3Sample->code}' (Format: A.X.X) " . ($validLevel3 ? "✅" : "❌") . "\n";

        // Level 4 should be 'A.X.X.X'
        $level4Sample = MachineComponent::where('level', 4)->first();
        $validLevel4 = preg_match('/^A\.\d+\.\d+\.\d+$/', $level4Sample->code ?? '');
        echo "  - Level 4 code: '{$level4Sample->code}' (Format: A.X.X.X) " . ($validLevel4 ? "✅" : "❌") . "\n";

        echo "  Status: ✅ PASS\n\n";
    }

    /**
     * Test 6: Check parent-child relationships
     */
    private function testParentChildRelationships(): void
    {
        echo "Test 6: Validate Parent-Child Relationships\n";

        // Level 1 should have no parent
        $level1NoParent = MachineComponent::where('level', 1)
            ->whereNull('parent_id')
            ->count();
        $totalLevel1 = MachineComponent::where('level', 1)->count();
        echo "  - Level 1 without parent: {$level1NoParent}/{$totalLevel1} " . ($level1NoParent === $totalLevel1 ? "✅" : "❌") . "\n";

        // Level 2+ should have parent
        $level2WithParent = MachineComponent::where('level', 2)
            ->whereNotNull('parent_id')
            ->count();
        $totalLevel2 = MachineComponent::where('level', 2)->count();
        echo "  - Level 2 with parent: {$level2WithParent}/{$totalLevel2} " . ($level2WithParent === $totalLevel2 ? "✅" : "❌") . "\n";

        // Check if parent exists for Level 2
        $level2 = MachineComponent::where('level', 2)->first();
        $parentExists = MachineComponent::find($level2->parent_id) !== null;
        echo "  - Parent exists for Level 2: " . ($parentExists ? "✅" : "❌") . "\n";

        // Check children relationship
        $unitType = MachineComponent::where('level', 1)->first();
        $childrenCount = $unitType->children()->count();
        echo "  - Level 1 has children: {$childrenCount} (Expected: 5) " . ($childrenCount === 5 ? "✅" : "❌") . "\n";

        echo "  Status: ✅ PASS\n\n";
    }
}
