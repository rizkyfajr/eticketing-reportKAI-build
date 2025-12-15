<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class AdminWilayahPermissionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * Seeder ini membuat semua permission yang diperlukan untuk Admin Wilayah
   * Jalankan seeder ini SEBELUM AdminWilayahRoleSeeder
   *
   * @return void
   */
  public function run()
  {
    $this->command->info('Membuat permissions untuk Admin Wilayah...');

    // Daftar permission yang diperlukan
    $permissions = [
      // Dashboard
      'read dashboard',

      // Working Report
      'create working report',
      'read working report',
      'update working report',
      'delete working report',

      // Maintenance Order
      'create maintenance order',
      'read maintenance order',
      'update maintenance order',
      'delete maintenance order',

      // Check Sheet
      'create checksheet',
      'read checksheet',
      'update checksheet',
      'delete checksheet',

      // Work Result
      'create workresult',
      'read workresult',
      'update workresult',
      'delete workresult',

      // Warming Up
      'create warmingup',
      'read warmingup',
      'update warmingup',
      'delete warmingup',

      // Report (legacy laporin)
      'create report',
      'read report',
      'update report',
      'delete report',

      // Verifikasi
      'read verifikasi',
      'create verifikasi',
      'update verifikasi',

      // Machine
      'create machine',
      'read machine',
      'update machine',
      'delete machine',

      // Classification
      'create classification',
      'read classification',
      'update classification',
      'delete classification',

      // Region (hanya read, tidak bisa CRUD)
      'read region',
    ];

    foreach ($permissions as $permissionName) {
      $permission = Permission::firstOrCreate(
        ['name' => $permissionName],
        ['guard_name' => 'web']
      );

      $this->command->info("  ✓ Permission '{$permissionName}' created/found");
    }

    $totalPermissions = count($permissions);
    $this->command->info('');
    $this->command->info("✓ Total {$totalPermissions} permissions created/verified");
    $this->command->info('');
    $this->command->warn('📌 SELANJUTNYA:');
    $this->command->warn('   Jalankan: php artisan db:seed --class=AdminWilayahRoleSeeder');
  }
}
