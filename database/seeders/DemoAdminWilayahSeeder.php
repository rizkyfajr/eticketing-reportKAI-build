<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\MasterRegion;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DemoAdminWilayahSeeder extends Seeder
{
  /**
  * Run the database seeds.
  *
  * Seeder ini membuat demo user Admin Wilayah untuk setiap DAOP
  * Username: admin.daop1, admin.daop2, dst
  * Password: password (untuk semua)
  *
  * @return void
  */
  public function run()
  {
    // Pastikan role admin-wilayah sudah ada
    $roleAdminWilayah = Role::where('name', 'admin-wilayah')->first();

    if (!$roleAdminWilayah) {
      $this->command->error('❌ Role "admin-wilayah" belum ada!');
      $this->command->warn('   Jalankan dulu: php artisan db:seed --class=AdminWilayahRoleSeeder');
      return;
    }

    // Ambil semua region dari database
    $regions = MasterRegion::all();

    if ($regions->isEmpty()) {
      $this->command->error('❌ Belum ada data master_regions!');
      $this->command->warn('   Pastikan tabel master_regions sudah di-seed terlebih dahulu');
      return;
    }

    $this->command->info('Membuat Admin Wilayah untuk setiap DAOP...');
    $this->command->info('');

    $createdCount = 0;

    foreach ($regions as $region) {
      // Generate username dari nama region
      // Contoh: "DAOP 1 Jakarta" -> "admin.daop1"
      $username = 'admin.' . strtolower(str_replace(' ', '', preg_replace('/[^a-zA-Z0-9\s]/', '', $region->name)));

      // Cek apakah user sudah ada
      $existingUser = User::where('username', $username)->first();

      if ($existingUser) {
        $this->command->warn("⚠ User '{$username}' sudah ada, skip...");
        continue;
      }

      // Buat user baru
      $user = User::create([
        'name' => "Admin {$region->name}",
        'username' => $username,
        'email' => "{$username}@kai.co.id",
        'password' => Hash::make('password'), // Default password
        'region_id' => $region->id, // Assign ke region
        'email_verified_at' => now(),
      ]);

      // Assign role admin-wilayah
      $user->assignRole($roleAdminWilayah);

      $createdCount++;

      $this->command->info("✓ Admin Wilayah dibuat:");
      $this->command->line("  → Nama     : {$user->name}");
      $this->command->line("  → Username : {$user->username}");
      $this->command->line("  → Email    : {$user->email}");
      $this->command->line("  → Password : password");
      $this->command->line("  → Region   : {$region->name}");
      $this->command->info('');
    }

    $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    $this->command->info("✓ Selesai! {$createdCount} Admin Wilayah berhasil dibuat");
    $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    $this->command->info('');
    $this->command->warn('📌 INFORMASI LOGIN:');
    $this->command->warn('   Username: admin.daop[nomor] (contoh: admin.daop1)');
    $this->command->warn('   Password: password (untuk semua akun)');
    $this->command->info('');
    $this->command->warn('⚠ KEAMANAN:');
    $this->command->warn('   Ganti password default setelah login pertama kali!');
  }
}
