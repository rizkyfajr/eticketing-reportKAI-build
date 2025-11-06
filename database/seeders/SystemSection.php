<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SystemSection extends Seeder
{
  /**
  * Run the database seeds.
  *
  * @return void
  */
  public function run()
  {
    $data = [
      [
        'nama' => 'Ebr',
        'kode' => 'k01',
      ],
      [
        'nama' => 'Ekfpb',
        'kode' => 'k02',
      ],
      [
        'nama' => 'LMS',
        'kode' => 'k04',
      ],
      [
        'nama' => 'Qms',
        'kode' => 'k05',
      ],
     
    ];

    foreach ($data as $key => $value) {
      \App\Models\SystemSectionModels::create($value);
    }
  }
}
