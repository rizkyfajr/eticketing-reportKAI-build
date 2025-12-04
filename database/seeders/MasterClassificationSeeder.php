<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterClassificationSeeder extends Seeder
{
  /**
  * Run the database seeds.
  *
  * @return void
  */
  public function run()
  {
    $classifications = [
      ['name' => 'Tamping Machine'],
      ['name' => 'Ballast Regulator Machine'],
      ['name' => 'Stabilization and Consolidation Machine'],
      ['name' => 'Material and Logistic Machine'],
      ['name' => 'Dynamic Track Stabilization'],
      ['name' => 'Distributing and Profiling'],
    ];

    foreach ($classifications as $classification) {
      \App\Models\MasterClassification::create($classification);
    }
  }
}
