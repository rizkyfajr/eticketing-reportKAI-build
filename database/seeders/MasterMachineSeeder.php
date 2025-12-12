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
      ['region_id' => 1, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT 09-16 CAT', 'nomor' => '2725', 'tahun_md' => 1994, 'no_sarana' => 'SR 3 94 05'],
      ['region_id' => 1, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT 08-16 GS', 'nomor' => '2406', 'tahun_md' => 1988, 'no_sarana' => 'SR 3 88 10', 'keterangan' => 'TSO - Posisi di Cnp'],
      ['region_id' => 1, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT B40-DE', 'nomor' => '2061', 'tahun_md' => 1994, 'no_sarana' => 'SR 3 13 02', 'keterangan' => 'TSO - Posisi di Cnp'],
      ['region_id' => 1, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT 09-32 CSM', 'nomor' => '7095', 'tahun_md' => 2021, 'no_sarana' => 'SR 3 20 08'],
      ['region_id' => 1, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT 09-32 CSM', 'nomor' => '7377', 'tahun_md' => 2023, 'no_sarana' => 'SR 3 23 01'],
      ['region_id' => 1, 'classification_id' => $tampingMachine, 'name' => 'Rail Switch Tamping', 'type' => 'MTT 08-275/3S-12', 'nomor' => '7375', 'tahun_md' => 2022, 'no_sarana' => 'SR 3 22 09'], 
      ['region_id' => 1, 'classification_id' => $ballastRegulator, 'name' => 'Distributing and Profiling', 'type' => 'PBR 400 U-RS', 'nomor' => '6419', 'tahun_md' => 2015, 'no_sarana' => 'SR 3 15 06'],
      ['region_id' => 1, 'classification_id' => $ballastRegulator, 'name' => 'Distributing and Profiling', 'type' => 'PBR 400 U-RS', 'nomor' => '7383', 'tahun_md' => 2023, 'no_sarana' => 'SR 3 23 07'], 
      ['region_id' => 1, 'classification_id' => $ballastRegulator, 'name' => 'Distributing and Profiling', 'type' => 'SSP 203', 'nomor' => '596', 'tahun_md' => 1994, 'no_sarana' => 'SR 3 94 09'],

      // DAOP 2 BANDUNG
      ['region_id' => 2, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT 08-16 GS UM', 'nomor' => '2718', 'tahun_md' => 1994, 'no_sarana' => 'SR 3 94 01'],
      ['region_id' => 2, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT 09-32 CSM', 'nomor' => '7099', 'tahun_md' => 2021, 'no_sarana' => 'SR 3 21 05'], 
      ['region_id' => 2, 'classification_id' => $tampingMachine, 'name' => 'Rail Switch Tamping', 'type' => 'MTT 08-275/3S-12', 'nomor' => '7376', 'tahun_md' => 2022, 'no_sarana' => 'SR 3 22 10'],
      ['region_id' => 2, 'classification_id' => $materialLogistic, 'name' => 'Material Transport Wagon', 'type' => 'TG 80-4', 'nomor' => '527', 'tahun_md' => 1988, 'no_sarana' => 'SR 3 88 22'],
      ['region_id' => 2, 'classification_id' => $ballastRegulator, 'name' => 'Profiling', 'type' => 'PBR 400', 'nomor' => '494', 'tahun_md' => 1988, 'no_sarana' => 'SR 3 88 12'],
      ['region_id' => 2, 'classification_id' => $ballastRegulator, 'name' => 'Distributing and Profiling', 'type' => 'PBR 400 U-RS', 'nomor' => '7101', 'tahun_md' => 2021, 'no_sarana' => 'SR 3 20 03'],

      // DAOP 3 CIREBON
      ['region_id' => 3, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT 07-16 G', 'nomor' => '2154', 'tahun_md' => 1984, 'no_sarana' => 'SR 3 84 04', 'keterangan' => 'TSO'],
      ['region_id' => 3, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT 09-32 CSM', 'nomor' => '7096', 'tahun_md' => 2021, 'no_sarana' => 'SR 3 21 01'], 
      ['region_id' => 3, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT 09-32 CSM', 'nomor' => '7378', 'tahun_md' => 2023, 'no_sarana' => 'SR 3 23 02'],
      ['region_id' => 3, 'classification_id' => $ballastRegulator, 'name' => 'Distributing and Profiling', 'type' => 'PBR 400 U-RS', 'nomor' => '6421', 'tahun_md' => 2015, 'no_sarana' => 'SR 3 15 08'],
      ['region_id' => 3, 'classification_id' => $ballastRegulator, 'name' => 'Distributing and Profiling', 'type' => 'PBR 400 U-RS', 'nomor' => '7384', 'tahun_md' => 2023, 'no_sarana' => 'SR 3 23 08'],

      // DAOP 4 SEMARANG
      ['region_id' => 4, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT 09-32 CSM', 'nomor' => '6272', 'tahun_md' => 2014, 'no_sarana' => 'SR 3 15 01'],
      ['region_id' => 4, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT 09-32 CSM', 'nomor' => '7379', 'tahun_md' => 2023, 'no_sarana' => 'SR 3 23 03'], 
      ['region_id' => 4, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT 07-16 G', 'nomor' => '2218', 'tahun_md' => 1984, 'no_sarana' => 'SR 3 84 06'],
      ['region_id' => 4, 'classification_id' => $tampingMachine, 'name' => 'Rail Switch Tamping', 'type' => 'MTT 08-275/3S-12', 'nomor' => '7091', 'tahun_md' => 2021, 'no_sarana' => 'SR 3 20 01'],
      ['region_id' => 4, 'classification_id' => $ballastRegulator, 'name' => 'Distributing and Profiling', 'type' => 'PBR 400 U-RS', 'nomor' => '7102', 'tahun_md' => 2021, 'no_sarana' => 'SR 3 20 04'],
      ['region_id' => 4, 'classification_id' => $ballastRegulator, 'name' => 'Distributing and Profiling', 'type' => 'PBR 400 U-RS', 'nomor' => '7386', 'tahun_md' => 2023, 'no_sarana' => 'SR 3 23 10'],

      // DAOP 5 PURWOKERTO
      ['region_id' => 5, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT 08-16 GS', 'nomor' => '2404', 'tahun_md' => 1988, 'no_sarana' => 'SR 3 88 08', 'keterangan' => 'TSO'],
      ['region_id' => 5, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT 08-16 CAT', 'nomor' => '2727', 'tahun_md' => 1994, 'no_sarana' => 'SR 3 94 07'], 
      ['region_id' => 5, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT 09-32 CSM', 'nomor' => '6275', 'tahun_md' => 2015, 'no_sarana' => 'SR 3 15 04'],
      ['region_id' => 5, 'classification_id' => $ballastRegulator, 'name' => 'Distributing and Profiling', 'type' => 'SSP 203', 'nomor' => '597', 'tahun_md' => 1994, 'no_sarana' => 'SR 3 94 10'],
      ['region_id' => 5, 'classification_id' => $ballastRegulator, 'name' => 'Distributing and Profiling', 'type' => 'PBR 400 U-RS', 'nomor' => '7103', 'tahun_md' => 2020, 'no_sarana' => 'SR 3 20 05'],

      // DAOP 6 YOGYAKARTA
      ['region_id' => 6, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT 09-32 CSM', 'nomor' => '6273', 'tahun_md' => 2014, 'no_sarana' => 'SR 3 15 02'],
      ['region_id' => 6, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT 07-16 G', 'nomor' => '2217', 'tahun_md' => 1984, 'no_sarana' => 'SR 3 84 05'], 
      ['region_id' => 6, 'classification_id' => $ballastRegulator, 'name' => 'Distributing and Profiling', 'type' => 'PBR 400 U-RS', 'nomor' => '7385', 'tahun_md' => 2023, 'no_sarana' => 'SR 3 23 09'],
      ['region_id' => 6, 'classification_id' => $ballastRegulator, 'name' => 'Distributing and Profiling', 'type' => 'SSP 203', 'nomor' => '599', 'tahun_md' => 1994, 'no_sarana' => 'SR 3 94 12'],

      // DAOP 7 MADIUN
      ['region_id' => 7, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT 08-16 GS', 'nomor' => '2401', 'tahun_md' => 1988, 'no_sarana' => 'SR 3 88 05'],
      ['region_id' => 7, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT 08-32 U', 'nomor' => '2701', 'tahun_md' => 1994, 'no_sarana' => 'SR 3 94 04', 'keterangan' => 'Usulan Mutasi Ke Daop 6 Yk'],
      ['region_id' => 7, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT 08-32 U', 'nomor' => '2696', 'tahun_md' => 1994, 'no_sarana' => 'SR 3 94 03'],
      ['region_id' => 7, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT 09-32 CSM', 'nomor' => '7100', 'tahun_md' => 2021, 'no_sarana' => 'SR 3 21 06'],
      ['region_id' => 7, 'classification_id' => $stabilization, 'name' => 'Dynamic Track Stabilization', 'type' => 'VDM 800 GS', 'nomor' => '274', 'tahun_md' => 1988, 'no_sarana' => 'SR 3 88 16', 'keterangan' => 'TSO'],
      ['region_id' => 7, 'classification_id' => $ballastRegulator, 'name' => 'Distributing and Profiling', 'type' => 'PBR 202', 'nomor' => '431', 'tahun_md' => 1984, 'no_sarana' => 'SR 3 84 07'],
      ['region_id' => 7, 'classification_id' => $ballastRegulator, 'name' => 'Distributing and Profiling', 'type' => 'PBR 400 U-RS', 'nomor' => '7104', 'tahun_md' => 2021, 'no_sarana' => 'SR 3 20 06'],
      ['region_id' => 7, 'classification_id' => $ballastRegulator, 'name' => 'Profiling', 'type' => 'PBR 400', 'nomor' => '493', 'tahun_md' => 1988, 'no_sarana' => 'SR 3 88 11'],

      // DAOP 8 SURABAYA
      ['region_id' => 8, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT 07-16 G', 'nomor' => '2152', 'tahun_md' => 1984, 'no_sarana' => 'SR 3 84 02'],
      ['region_id' => 8, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT 09-32 CSM', 'nomor' => '6276', 'tahun_md' => 2015, 'no_sarana' => 'SR 3 15 05'], 
      ['region_id' => 8, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT 09-32 CSM', 'nomor' => '7380', 'tahun_md' => 2023, 'no_sarana' => 'SR 3 23 04'],
      ['region_id' => 8, 'classification_id' => $tampingMachine, 'name' => 'Rail Switch Tamping', 'type' => 'MTT 08-275/3S-12', 'nomor' => '7092', 'tahun_md' => 2021, 'no_sarana' => 'SR 3 20 02'],
      ['region_id' => 8, 'classification_id' => $ballastRegulator, 'name' => 'Distributing and Profiling', 'type' => 'PBR 400 U-RS', 'nomor' => '6420', 'tahun_md' => 2015, 'no_sarana' => 'SR 3 15 07'],
      ['region_id' => 8, 'classification_id' => $ballastRegulator, 'name' => 'Distributing and Profiling', 'type' => 'PBR 400 U-RS', 'nomor' => '6422', 'tahun_md' => 2015, 'no_sarana' => 'SR 3 15 09'], 
      ['region_id' => 8, 'classification_id' => $ballastRegulator, 'name' => 'Profiling', 'type' => 'PBR 202', 'nomor' => '452', 'tahun_md' => 1984, 'no_sarana' => 'SR 3 84 08'],

      // DAOP 9 JEMBER
      ['region_id' => 9, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT 08-16 GS', 'nomor' => '2405', 'tahun_md' => 1988, 'no_sarana' => 'SR 3 88 09'],
      ['region_id' => 9, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT 08-16 GS', 'nomor' => '2493', 'tahun_md' => 1990, 'no_sarana' => 'SR 3 90 04'],
      ['region_id' => 9, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT 08-16 GS UM', 'nomor' => '2719', 'tahun_md' => 1994, 'no_sarana' => 'SR 3 94 02'],
      ['region_id' => 9, 'classification_id' => $ballastRegulator, 'name' => 'Profiling', 'type' => 'USP 303', 'nomor' => '489', 'tahun_md' => 1988, 'no_sarana' => 'SR 3 88 20'],
      ['region_id' => 9, 'classification_id' => $ballastRegulator, 'name' => 'Profiling', 'type' => 'PBR 400', 'nomor' => '497', 'tahun_md' => 1988, 'no_sarana' => 'SR 3 88 15'],
      ['region_id' => 9, 'classification_id' => $ballastRegulator, 'name' => 'Profiling', 'type' => 'PBR 400', 'nomor' => '495', 'tahun_md' => 1988, 'no_sarana' => 'SR 3 88 13'],

      // DIVRE I SU
      ['region_id' => 10, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT 07-16 G', 'nomor' => '2151', 'tahun_md' => 1984, 'no_sarana' => 'SR 3 84 01'],
      ['region_id' => 10, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT 07-16 G', 'nomor' => '2153', 'tahun_md' => 1984, 'no_sarana' => 'SR 3 84 03', 'keterangan' => 'TSO'], 
      ['region_id' => 10, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT 08-16 CAT', 'nomor' => '2726', 'tahun_md' => 1994, 'no_sarana' => 'SR 3 94 06'],
      ['region_id' => 10, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT 08-16 CAT', 'nomor' => '2728', 'tahun_md' => 1994, 'no_sarana' => 'SR 3 94 08'],
      ['region_id' => 10, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT 08-16 GS', 'nomor' => '2402', 'tahun_md' => 1988, 'no_sarana' => 'SR 3 88 06'],
      ['region_id' => 10, 'classification_id' => $ballastRegulator, 'name' => 'Profiling', 'type' => 'PBR 202', 'nomor' => '453', 'tahun_md' => 1984, 'no_sarana' => 'SR 3 84 09'],
      ['region_id' => 10, 'classification_id' => $ballastRegulator, 'name' => 'Profiling', 'type' => 'SSP 203', 'nomor' => '598', 'tahun_md' => 1994, 'no_sarana' => 'SR 3 94 11'],
      ['region_id' => 10, 'classification_id' => $stabilization, 'name' => 'Dynamic Track Stabilization', 'type' => 'VDM 800 GS', 'nomor' => '275', 'tahun_md' => 1988, 'no_sarana' => 'SR 3 88 19', 'keterangan' => 'TSO'],

      // DIVRE II SB
      ['region_id' => 11, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT 08-16 GS', 'nomor' => '2400', 'tahun_md' => 1988, 'no_sarana' => 'SR 3 88 04'],
      ['region_id' => 11, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT 08-16 GS', 'nomor' => '2492', 'tahun_md' => 1990, 'no_sarana' => 'SR 3 90 03'],
      ['region_id' => 11, 'classification_id' => $ballastRegulator, 'name' => 'Profiling', 'type' => 'PBR 400', 'nomor' => '544', 'tahun_md' => 1990, 'no_sarana' => 'SR 3 90 07'],

      // DIVRE III PGRM
      ['region_id' => 12, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT 09-16 CSM', 'nomor' => '5915', 'tahun_md' => 2013, 'no_sarana' => 'SR 3 13 01'],
      ['region_id' => 12, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT 09-32 CSM', 'nomor' => '7097', 'tahun_md' => 2021, 'no_sarana' => 'SR 3 21 03'], 
      ['region_id' => 12, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT 09-32 CSM', 'nomor' => '7381', 'tahun_md' => 2023, 'no_sarana' => 'SR 3 23 05'],
      ['region_id' => 12, 'classification_id' => $tampingMachine, 'name' => 'Rail Switch Tamping', 'type' => 'MTT 08-275/3S-12', 'nomor' => '7093', 'tahun_md' => 2021, 'no_sarana' => 'SR 3 21 07'],
      ['region_id' => 12, 'classification_id' => $ballastRegulator, 'name' => 'Distributing and Profiling', 'type' => 'PBR 400 U-RS', 'nomor' => '6627', 'tahun_md' => 2015, 'no_sarana' => 'SR 3 15 12'],
      ['region_id' => 12, 'classification_id' => $ballastRegulator, 'name' => 'Profiling', 'type' => 'PBR 400', 'nomor' => '543', 'tahun_md' => 1990, 'no_sarana' => 'SR 3 90 06'], 
      ['region_id' => 12, 'classification_id' => $ballastRegulator, 'name' => 'Distributing and Profiling', 'type' => 'PBR 400 U-RS', 'nomor' => '7105', 'tahun_md' => 2020, 'no_sarana' => 'SR 3 20 07'],
      ['region_id' => 12, 'classification_id' => $stabilization, 'name' => 'Material Transport Wagon', 'type' => 'TG 80-4', 'nomor' => '557', 'tahun_md' => 1990, 'no_sarana' => 'SR 3 90 12'],

      // DIVRE IV TNK
      ['region_id' => 13, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT 09-16 CSM', 'nomor' => '3528', 'tahun_md' => 2012, 'no_sarana' => 'SR 3 12 01'],
      ['region_id' => 13, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT 09-32 CSM', 'nomor' => '6274', 'tahun_md' => 2015, 'no_sarana' => 'SR 3 15 03'],
      ['region_id' => 13, 'classification_id' => $tampingMachine, 'name' => 'Plain Line Tamping', 'type' => 'MTT 09-32 CSM', 'nomor' => '7098', 'tahun_md' => 2021, 'no_sarana' => 'SR 3 21 04'],
      ['region_id' => 13, 'classification_id' => $tampingMachine, 'name' => 'Rail Switch Tamping', 'type' => 'MTT 08-275/3S-12', 'nomor' => '7094', 'tahun_md' => 2021, 'no_sarana' => 'SR 3 21 08'],
      ['region_id' => 13, 'classification_id' => $ballastRegulator, 'name' => 'Profiling', 'type' => 'PBR 400', 'nomor' => '542', 'tahun_md' => 1990, 'no_sarana' => 'SR 3 90 05'],
      ['region_id' => 13, 'classification_id' => $ballastRegulator, 'name' => 'Distributing and Profiling', 'type' => 'PBR 400 U-RS', 'nomor' => '6423', 'tahun_md' => 2015, 'no_sarana' => 'SR 3 15 10'],
      ['region_id' => 13, 'classification_id' => $ballastRegulator, 'name' => 'Distributing and Profiling', 'type' => 'PBR 400 U-RS', 'nomor' => '7382', 'tahun_md' => 2023, 'no_sarana' => 'SR 3 23 06'],
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

