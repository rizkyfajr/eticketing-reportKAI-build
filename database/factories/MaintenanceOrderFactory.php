<?php

namespace Database\Factories;

use App\Models\MaintenanceOrder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class MaintenanceOrderFactory extends Factory
{
    protected $model = MaintenanceOrder::class;

    public function definition(): array
    {
        // ambil id yang sudah ada; kalau belum ada, isi 1 biar ketahuan errornya lebih awal
        $wrId = DB::table('working_reports')->value('id') ?? 1;
        $mcId = DB::table('master_machines')->value('id') ?? 1;

        return [
            'working_report_id' => $wrId,
            'master_machine_id' => $mcId,
            'category'          => $this->faker->randomElement(['planned','unplanned']),
            'title'             => 'Gangguan '.$this->faker->word(),
            'problem_note'      => $this->faker->sentence(),
            'location'          => $this->faker->city(),
            'trouble_at'        => now()->subHours(rand(1,48)),
        ];
    }
}

