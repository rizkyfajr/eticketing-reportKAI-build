<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MasterMachine;
use App\Models\MasterRegion;
use App\Models\MasterClassification;

class MasterMachineSeeder extends Seeder
{
  /**
  * Run the database seeds.
  *
  * @return void
  */
  public function run()
  {
    // Get classification IDs
    $tampingMachine = MasterClassification::where('name', 'Tamping Machine')->first()->id;
    $ballastRegulator = MasterClassification::where('name', 'Ballast Regulator Machine')->first()->id;
    $stabilization = MasterClassification::where('name', 'Stabilization and Consolidation Machine')->first()->id;
    $materialLogistic = MasterClassification::where('name', 'Material and Logistic Machine')->first()->id;
    $dynamicTrack = MasterClassification::where('name', 'Dynamic Track Stabilization')->first()->id;
    $distributing = MasterClassification::where('name', 'Distributing and Profiling')->first()->id;

    $machines = [
      // DAOP 1 JAKARTA
      ['region_id' => 1, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT', 'nomor' => '09-16 CAT', 'tahun_md' => 1994, 'no_sarana' => 'SR 3 94 08'],
      ['region_id' => 1, 'classification_id' => $distributing, 'name' => 'Distributing and Profiling', 'type' => 'PBR', 'nomor' => '400 U-RS', 'tahun_md' => 1988, 'no_sarana' => 'SR 3 88 33', 'keterangan' => 'TSO - Pusad di Cnp'],
      ['region_id' => 1, 'classification_id' => $distributing, 'name' => 'Distributing and Profiling', 'type' => 'PBR', 'nomor' => '400 U-RS', 'tahun_md' => 1988, 'no_sarana' => 'SR 3 88 34', 'keterangan' => 'TSO - Pusad di Cnp'],
      ['region_id' => 1, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT', 'nomor' => '09-32 CSM', 'tahun_md' => 2021, 'no_sarana' => 'SR 3 20 08'],
      ['region_id' => 1, 'classification_id' => $tampingMachine, 'name' => 'Rail Switch Tamping', 'type' => 'MTT', 'nomor' => '09-32 CSM', 'tahun_md' => 2023, 'no_sarana' => 'SR 3 23 01'],
      ['region_id' => 1, 'classification_id' => $ballastRegulator, 'name' => 'Distributing and Profiling', 'type' => 'PBR', 'nomor' => '08-275/3S-12', 'tahun_md' => 2022, 'no_sarana' => 'SR 3 22 09'],
      ['region_id' => 1, 'classification_id' => $ballastRegulator, 'name' => 'Distributing and Profiling', 'type' => 'PBR', 'nomor' => '400 U-RS', 'tahun_md' => 2015, 'no_sarana' => 'SR 3 15 05'],
      ['region_id' => 1, 'classification_id' => $distributing, 'name' => 'Distributing and Profiling', 'type' => 'SSP', 'nomor' => '201', 'tahun_md' => 1994, 'no_sarana' => 'SR 3 94 09'],
      ['region_id' => 1, 'classification_id' => $materialLogistic, 'name' => 'Material Transport Wagon', 'type' => 'MTT', 'nomor' => '08-16 GSR', 'tahun_md' => 1994, 'no_sarana' => 'SR 3 94 07'],
      ['region_id' => 1, 'classification_id' => $materialLogistic, 'name' => 'Inspection Bridge', 'type' => 'BGP', 'nomor' => '003', 'tahun_md' => 1994, 'no_sarana' => 'SR 3 94 09'],

      // DAOP 2 BANDUNG
      ['region_id' => 2, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT', 'nomor' => '08-16 GSM', 'tahun_md' => 1994, 'no_sarana' => 'SR 3 94 01'],
      ['region_id' => 2, 'classification_id' => $tampingMachine, 'name' => 'Rail Switch Tamping', 'type' => 'MTT', 'nomor' => '08-275/3S-12', 'tahun_md' => 2022, 'no_sarana' => 'SR 3 22 05'],
      ['region_id' => 2, 'classification_id' => $materialLogistic, 'name' => 'Material Transport Wagon', 'type' => 'TG', 'nomor' => '80-8', 'tahun_md' => 1988, 'no_sarana' => 'SR 3 88 32'],
      ['region_id' => 2, 'classification_id' => $ballastRegulator, 'name' => 'Distributing and Profiling', 'type' => 'PBR', 'nomor' => '400 U-RS', 'tahun_md' => 2021, 'no_sarana' => 'SR 3 20 03'],
      ['region_id' => 2, 'classification_id' => $distributing, 'name' => 'Distributing and Profiling', 'type' => 'PBR', 'nomor' => '07-16 G', 'tahun_md' => 1984, 'no_sarana' => 'SR 3 84 04', 'keterangan' => 'TSO'],

      // DAOP 3 CIREBON
      ['region_id' => 3, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT', 'nomor' => '07-16 G', 'tahun_md' => 1984, 'no_sarana' => 'SR 3 84 03'],
      ['region_id' => 3, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT', 'nomor' => '09-32 CSM', 'tahun_md' => 2023, 'no_sarana' => 'SR 3 23 02'],
      ['region_id' => 3, 'classification_id' => $ballastRegulator, 'name' => 'Distributing and Profiling', 'type' => 'PBR', 'nomor' => '400 U-RS', 'tahun_md' => 2015, 'no_sarana' => 'SR 3 15 06'],
      ['region_id' => 3, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT', 'nomor' => '09-32 CSM', 'tahun_md' => 2014, 'no_sarana' => 'SR 3 15 01'],

      // DAOP 4 SEMARANG
      ['region_id' => 4, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT', 'nomor' => '09-32 CSM', 'tahun_md' => 2023, 'no_sarana' => 'SR 3 23 03'],
      ['region_id' => 4, 'classification_id' => $tampingMachine, 'name' => 'Rail Switch Tamping', 'type' => 'MTT', 'nomor' => '08-275/3S-12', 'tahun_md' => 2021, 'no_sarana' => 'SR 3 20 01'],
      ['region_id' => 4, 'classification_id' => $ballastRegulator, 'name' => 'Distributing and Profiling', 'type' => 'PBR', 'nomor' => '400 U-RS', 'tahun_md' => 2021, 'no_sarana' => 'SR 3 20 04'],
      ['region_id' => 4, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT', 'nomor' => '08-16 GS', 'tahun_md' => 1988, 'no_sarana' => 'SR 3 88 08', 'keterangan' => 'TSO'],

      // DAOP 5 PURWOKERTO
      ['region_id' => 5, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT', 'nomor' => '08-16 CAT', 'tahun_md' => 1994, 'no_sarana' => 'SR 3 94 06'],
      ['region_id' => 5, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT', 'nomor' => '08-16 GS', 'tahun_md' => 1988, 'no_sarana' => 'SR 3 88 07'],
      ['region_id' => 5, 'classification_id' => $ballastRegulator, 'name' => 'Distributing and Profiling', 'type' => 'SSP', 'nomor' => '201', 'tahun_md' => 1994, 'no_sarana' => 'SR 3 94 10'],
      ['region_id' => 5, 'classification_id' => $distributing, 'name' => 'Distributing and Profiling', 'type' => 'PBR', 'nomor' => '400 U-RS', 'tahun_md' => 2015, 'no_sarana' => 'SR 3 20 05'],

      // DAOP 6 YOGYAKARTA
      ['region_id' => 6, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT', 'nomor' => '08-16 GS', 'tahun_md' => 1988, 'no_sarana' => 'SR 3 88 06'],
      ['region_id' => 6, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT', 'nomor' => '07-16 G', 'tahun_md' => 1984, 'no_sarana' => 'SR 3 84 05'],
      ['region_id' => 6, 'classification_id' => $ballastRegulator, 'name' => 'Distributing and Profiling', 'type' => 'PBR', 'nomor' => '400 U-RS', 'tahun_md' => 2023, 'no_sarana' => 'SR 3 23 08'],
      ['region_id' => 6, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT', 'nomor' => '08-16 GS', 'tahun_md' => 1988, 'no_sarana' => 'SR 3 84 02'],
      ['region_id' => 6, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT', 'nomor' => '08-32 U', 'tahun_md' => 1988, 'no_sarana' => 'SR 3 88 04', 'keterangan' => 'Rusam Mutasi Ke Dieng & Ya'],

      // DAOP 7 MADIUN
      ['region_id' => 7, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT', 'nomor' => '08-32 U', 'tahun_md' => 1988, 'no_sarana' => 'SR 3 88 03'],
      ['region_id' => 7, 'classification_id' => $stabilization, 'name' => 'Dynamic Track Stabilization', 'type' => 'MTT', 'nomor' => '09-32 CSM', 'tahun_md' => 2021, 'no_sarana' => 'SR 3 21 06'],
      ['region_id' => 7, 'classification_id' => $ballastRegulator, 'name' => 'Distributing and Profiling', 'type' => 'PBR', 'nomor' => '202', 'tahun_md' => 1984, 'no_sarana' => 'SR 3 84 07'],
      ['region_id' => 7, 'classification_id' => $distributing, 'name' => 'Profiling', 'type' => 'PBR', 'nomor' => '400 U-RS', 'tahun_md' => 2021, 'no_sarana' => 'SR 3 20 06'],
      ['region_id' => 7, 'classification_id' => $distributing, 'name' => 'Profiling', 'type' => 'SSP', 'nomor' => '202', 'tahun_md' => 1988, 'no_sarana' => 'SR 3 88 11'],

      // DAOP 8 SURABAYA
      ['region_id' => 8, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT', 'nomor' => '07-16 G', 'tahun_md' => 1984, 'no_sarana' => 'SR 3 84 02'],
      ['region_id' => 8, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT', 'nomor' => '09-32 CSM', 'tahun_md' => 2015, 'no_sarana' => 'SR 3 21 04'],
      ['region_id' => 8, 'classification_id' => $tampingMachine, 'name' => 'Rail Switch Tamping', 'type' => 'MTT', 'nomor' => '08-275/3S-12', 'tahun_md' => 2023, 'no_sarana' => 'SR 3 23 04'],
      ['region_id' => 8, 'classification_id' => $ballastRegulator, 'name' => 'Distributing and Profiling', 'type' => 'PBR', 'nomor' => '400 U-RS', 'tahun_md' => 2021, 'no_sarana' => 'SR 3 20 07'],
      ['region_id' => 8, 'classification_id' => $ballastRegulator, 'name' => 'Distributing and Profiling', 'type' => 'PBR', 'nomor' => '400 U-RS', 'tahun_md' => 2015, 'no_sarana' => 'SR 3 15 09'],
      ['region_id' => 8, 'classification_id' => $distributing, 'name' => 'Profiling', 'type' => 'PBR', 'nomor' => '202', 'tahun_md' => 1984, 'no_sarana' => 'SR 3 84 08'],

      // DAOP 9 JEMBER
      ['region_id' => 9, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT', 'nomor' => '08-16 GS', 'tahun_md' => 1990, 'no_sarana' => 'SR 3 90 04'],
      ['region_id' => 9, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT', 'nomor' => '08-16 GS UM', 'tahun_md' => 1994, 'no_sarana' => 'SR 3 94 02'],
      ['region_id' => 9, 'classification_id' => $ballastRegulator, 'name' => 'Profiling', 'type' => 'PBR', 'nomor' => '400', 'tahun_md' => 1988, 'no_sarana' => 'SR 3 88 35'],
      ['region_id' => 9, 'classification_id' => $distributing, 'name' => 'Profiling', 'type' => 'PBR', 'nomor' => '400 U-RS', 'tahun_md' => 1988, 'no_sarana' => 'SR 3 88 31'],

      // DIVRE I SU
      ['region_id' => 10, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT', 'nomor' => '07-16 G', 'tahun_md' => 1984, 'no_sarana' => 'SR 3 84 03'],
      ['region_id' => 10, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT', 'nomor' => '08-16 CAT', 'tahun_md' => 1994, 'no_sarana' => 'SR 3 94 05', 'keterangan' => 'TSO'],
      ['region_id' => 10, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT', 'nomor' => '08-16 GS', 'tahun_md' => 1988, 'no_sarana' => 'SR 3 88 05'],
      ['region_id' => 10, 'classification_id' => $ballastRegulator, 'name' => 'Profiling', 'type' => 'PBR', 'nomor' => '202', 'tahun_md' => 1984, 'no_sarana' => 'SR 3 84 09'],
      ['region_id' => 10, 'classification_id' => $stabilization, 'name' => 'Dynamic Track Stabilization', 'type' => 'VDM', 'nomor' => '800 GS', 'tahun_md' => 1988, 'no_sarana' => 'SR 3 88 18', 'keterangan' => 'TSO'],

      // DIVRE II SB
      ['region_id' => 11, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT', 'nomor' => '08-16 GS', 'tahun_md' => 1990, 'no_sarana' => 'SR 3 90 03'],
      ['region_id' => 11, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT', 'nomor' => '08-16 GS', 'tahun_md' => 1990, 'no_sarana' => 'SR 3 90 07'],
      ['region_id' => 11, 'classification_id' => $ballastRegulator, 'name' => 'Profiling', 'type' => 'PBR', 'nomor' => '400', 'tahun_md' => 1988, 'no_sarana' => 'SR 3 88 36'],

      // DIVRE III PGRM
      ['region_id' => 12, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT', 'nomor' => '09-32 CSM', 'tahun_md' => 2021, 'no_sarana' => 'SR 3 21 03'],
      ['region_id' => 12, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT', 'nomor' => '09-32 CSM', 'tahun_md' => 2023, 'no_sarana' => 'SR 3 23 05'],
      ['region_id' => 12, 'classification_id' => $ballastRegulator, 'name' => 'Distributing and Profiling', 'type' => 'PBR', 'nomor' => '400 U-RS', 'tahun_md' => 2015, 'no_sarana' => 'SR 3 15 32'],
      ['region_id' => 12, 'classification_id' => $distributing, 'name' => 'Profiling', 'type' => 'PBR', 'nomor' => '400 U-RS', 'tahun_md' => 1990, 'no_sarana' => 'SR 3 20 05'],
      ['region_id' => 12, 'classification_id' => $distributing, 'name' => 'Distributing and Profiling', 'type' => 'PBR', 'nomor' => '400 U-RS', 'tahun_md' => 2020, 'no_sarana' => 'SR 3 20 07'],

      // DIVRE IV TNK
      ['region_id' => 13, 'classification_id' => $stabilization, 'name' => 'Material Transport Wagon', 'type' => 'TG', 'nomor' => '009', 'tahun_md' => 1990, 'no_sarana' => 'SR 3 90 12'],
      ['region_id' => 13, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT', 'nomor' => '09-16 CSM', 'tahun_md' => 2012, 'no_sarana' => 'SR 3 12 01'],
      ['region_id' => 13, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT', 'nomor' => '09-32 CSM', 'tahun_md' => 2021, 'no_sarana' => 'SR 3 21 04'],
      ['region_id' => 13, 'classification_id' => $tampingMachine, 'name' => 'Rail Switch Tamping', 'type' => 'MTT', 'nomor' => '08-275/3S-12', 'tahun_md' => 2021, 'no_sarana' => 'SR 3 21 05'],
      ['region_id' => 13, 'classification_id' => $ballastRegulator, 'name' => 'Profiling', 'type' => 'PBR', 'nomor' => '400 U-RS', 'tahun_md' => 2023, 'no_sarana' => 'SR 3 40 05'],
      ['region_id' => 13, 'classification_id' => $distributing, 'name' => 'Distributing and Profiling', 'type' => 'PBR', 'nomor' => '400 U-RS', 'tahun_md' => 2015, 'no_sarana' => 'SR 3 15 10'],
      ['region_id' => 13, 'classification_id' => $distributing, 'name' => 'Distributing and Profiling', 'type' => 'PBR', 'nomor' => '400 U-RS', 'tahun_md' => 2023, 'no_sarana' => 'SR 3 20 08'],
    ];

    foreach ($machines as $machine) {
      // Calculate umur (age)
      if (isset($machine['tahun_md'])) {
        $machine['umur'] = date('Y') - $machine['tahun_md'];
      }

      MasterMachine::create($machine);
    }
  }
}

