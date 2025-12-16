<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MachineComponent;

/**
 * Display Machine Hierarchy Tree
 * Visualisasi struktur hierarki mesin dalam bentuk tree
 */
class DisplayHierarchyTreeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $machineType = $this->argument('machine_type') ?? 'MTT 07-16 G';

        echo "\n╔══════════════════════════════════════════════════════════════╗\n";
        echo "║  MACHINE HIERARCHY TREE VISUALIZATION                       ║\n";
        echo "║  Machine Type: {$machineType}\n";
        echo "╚══════════════════════════════════════════════════════════════╝\n\n";

        $unitType = MachineComponent::where('machine_type', $machineType)
            ->whereNull('parent_id')
            ->first();

        if (!$unitType) {
            echo "❌ Machine type '{$machineType}' not found!\n\n";
            echo "Available machine types:\n";
            $types = MachineComponent::where('level', 1)
                ->pluck('machine_type')
                ->unique();
            foreach ($types as $type) {
                echo "  - {$type}\n";
            }
            return;
        }

        $this->displayTree($unitType, 0);

        echo "\n";
        $this->displayStatistics($machineType);
    }

    /**
     * Display tree recursively
     */
    private function displayTree($component, $depth = 0, $isLast = true, $prefix = ''): void
    {
        // Tree characters
        $branch = $isLast ? '└── ' : '├── ';
        $continuation = $isLast ? '    ' : '│   ';

        // Color codes for different levels
        $colors = [
            1 => "\033[1;36m", // Cyan - Unit Type
            2 => "\033[1;33m", // Yellow - System
            3 => "\033[1;32m", // Green - Subsystem
            4 => "\033[0;37m", // White - Component
        ];
        $reset = "\033[0m";

        $color = $colors[$component->level] ?? "\033[0;37m";

        // Display current component
        if ($depth === 0) {
            echo "{$color}📦 [{$component->code}] {$component->name}{$reset}\n";
        } else {
            echo "{$prefix}{$branch}{$color}[{$component->code}] {$component->name}{$reset}\n";
        }

        // Get children
        $children = $component->children()->orderBy('code')->get();

        // Display children
        foreach ($children as $index => $child) {
            $isLastChild = ($index === count($children) - 1);
            $newPrefix = $depth === 0 ? '' : $prefix . $continuation;

            // Only show first few components at level 4 to avoid clutter
            if ($child->level === 4 && $index >= 3) {
                if ($index === 3) {
                    echo "{$newPrefix}├── \033[0;90m... and " . (count($children) - 3) . " more components\033[0m\n";
                }
                continue;
            }

            $this->displayTree($child, $depth + 1, $isLastChild, $newPrefix);
        }
    }

    /**
     * Display statistics
     */
    private function displayStatistics(string $machineType): void
    {
        echo "\n╔══════════════════════════════════════════════════════════════╗\n";
        echo "║  STATISTICS                                                  ║\n";
        echo "╚══════════════════════════════════════════════════════════════╝\n\n";

        $stats = MachineComponent::where('machine_type', $machineType)
            ->selectRaw('level, count(*) as total')
            ->groupBy('level')
            ->orderBy('level')
            ->get();

        $total = 0;
        foreach ($stats as $stat) {
            $levelName = match($stat->level) {
                1 => 'Unit Type    ',
                2 => 'Systems      ',
                3 => 'Subsystems   ',
                4 => 'Components   ',
                default => "Level {$stat->level}     "
            };

            echo "  Level {$stat->level} ({$levelName}): {$stat->total} records\n";
            $total += $stat->total;
        }

        echo "\n  ────────────────────────────────────\n";
        echo "  TOTAL                    : {$total} records\n\n";
    }

    /**
     * Get command argument
     */
    private function argument(string $key): ?string
    {
        // Simple argument parsing for artisan command
        global $argv;

        if (!isset($argv)) {
            return null;
        }

        foreach ($argv as $index => $arg) {
            if ($arg === "--{$key}" && isset($argv[$index + 1])) {
                return $argv[$index + 1];
            }
        }

        return null;
    }
}
