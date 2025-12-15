<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminWilayahCompleteSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * Seeder lengkap untuk setup Admin Wilayah
   * Menjalankan semua seeder dalam urutan yang benar:
   * 1. Permissions
   * 2. Role + Menu
   * 3. Demo Users (optional)
   *
   * CARA PAKAI:
   * php artisan db:seed --class=AdminWilayahCompleteSeeder
   *
   * @return void
   */
  public function run()
  {
    $this->command->info('');
    $this->command->info('═══════════════════════════════════════════════════════════');
    $this->command->info('  ADMIN WILAYAH COMPLETE SETUP');
    $this->command->info('═══════════════════════════════════════════════════════════');
    $this->command->info('');

    // Step 1: Buat permissions
    $this->command->warn('STEP 1/3: Membuat Permissions...');
    $this->command->info('─────────────────────────────────────────────────────────');
    $this->call(AdminWilayahPermissionSeeder::class);

    // Step 2: Buat role dan menu
    $this->command->warn('STEP 2/3: Membuat Role & Menu...');
    $this->command->info('─────────────────────────────────────────────────────────');
    $this->call(AdminWilayahRoleSeeder::class);

    // Step 3: Tanya apakah mau buat demo users
    $this->command->info('');
    $this->command->warn('STEP 3/3: Demo Users (Optional)');
    $this->command->info('─────────────────────────────────────────────────────────');

    if ($this->command->confirm('Apakah Anda ingin membuat demo users untuk Admin Wilayah?', true)) {
      $this->call(DemoAdminWilayahSeeder::class);
    } else {
      $this->command->info('Demo users skipped.');
    }

    // Summary
    $this->command->info('');
    $this->command->info('═══════════════════════════════════════════════════════════');
    $this->command->info('  ✓ SETUP ADMIN WILAYAH SELESAI!');
    $this->command->info('═══════════════════════════════════════════════════════════');
    $this->command->info('');
    $this->command->warn('📋 YANG SUDAH DIBUAT:');
    $this->command->warn('   ✓ Permissions untuk Admin Wilayah');
    $this->command->warn('   ✓ Role "admin-wilayah"');
    $this->command->warn('   ✓ Menu: Dashboard, Master Data, Working Order, Maintenance Order, Check Sheet');
    $this->command->info('');
    $this->command->warn('📌 CARA ASSIGN ADMIN WILAYAH:');
    $this->command->warn('   1. Login sebagai Super Admin');
    $this->command->warn('   2. Buka menu User Management');
    $this->command->warn('   3. Pilih user yang ingin dijadikan Admin Wilayah');
    $this->command->warn('   4. Set Role = "admin-wilayah"');
    $this->command->warn('   5. Set Wilayah = [pilih DAOP/Divre]');
    $this->command->warn('   6. Save');
    $this->command->info('');
    $this->command->warn('⚠ PENTING:');
    $this->command->warn('   Admin Wilayah HANYA bisa akses data di wilayahnya (berdasarkan region_id)');
    $this->command->warn('   Pastikan region_id di users terisi dengan benar!');
    $this->command->info('');
  }
}
