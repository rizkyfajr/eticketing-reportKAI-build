<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Menu;

class AdminWilayahRoleSeeder extends Seeder
{
  /**
  * Run the database seeds.
  *
  * Seeder ini membuat:
  * 1. Role "admin-wilayah"
  * 2. Assign permissions yang sesuai untuk Admin Wilayah
  * 3. Membuat menu untuk Admin Wilayah
  *
  * @return void
  */
  public function run()
  {
    // Buat role Admin Wilayah jika belum ada
    $role = Role::firstOrCreate(
      ['name' => 'admin-wilayah'],
      ['guard_name' => 'web']
    );

    $this->command->info('✓ Role "admin-wilayah" berhasil dibuat/ditemukan');

    // Daftar permissions untuk Admin Wilayah
    // Admin Wilayah bisa CRUD data master terkait wilayahnya
    $permissionsToAssign = [
      // Master Data Permissions
      'read machine',
      'create machine',
      'update machine',
      'delete machine',

      'read classification',
      'create classification',
      'update classification',
      'delete classification',

      // Working Reports
      'read working report',
      'create working report',
      'update working report',
      'delete working report',

      // Maintenance Orders
      'read maintenance order',
      'create maintenance order',
      'update maintenance order',
      'delete maintenance order',

      // Check Sheets
      'read checksheet',
      'create checksheet',
      'update checksheet',
      'delete checksheet',

      // Work Results
      'read workresult',
      'create workresult',
      'update workresult',
      'delete workresult',

      // Warming Up
      'read warmingup',
      'create warmingup',
      'update warmingup',
      'delete warmingup',

      // Reports
      'read report',
      'create report',
      'update report',

      // Verifikasi
      'read verifikasi',

      // Dashboard & Analytics
      'read dashboard',

      // TIDAK PUNYA akses ke:
      // - User management (read user, create user, dll)
      // - Role & Permission management
      // - Region management (CRUD master regions)
      // - Menu management
    ];

    // Tambahkan permissions jika belum ada
    foreach ($permissionsToAssign as $permissionName) {
      $permission = Permission::firstOrCreate(
        ['name' => $permissionName],
        ['guard_name' => 'web']
      );

      // Assign permission ke role
      if (!$role->hasPermissionTo($permission)) {
        $role->givePermissionTo($permission);
        $this->command->info("  → Permission '{$permissionName}' assigned");
      }
    }

    $this->command->info('');
    $this->command->info('✓ Total ' . count($permissionsToAssign) . ' permissions assigned ke role "admin-wilayah"');

    // ==========================================
    // BUAT MENU UNTUK ADMIN WILAYAH
    // ==========================================
    $this->command->info('');
    $this->command->info('Membuat menu untuk Admin Wilayah...');

    // 1. Menu Dashboard (sudah ada, hanya perlu diassign permission)
    $dashboardMenu = Menu::where('name', 'dashboard')->first();
    if ($dashboardMenu) {
      $dashboardPerm = Permission::where('name', 'read dashboard')->first();
      if ($dashboardPerm && !$dashboardMenu->permissions->contains($dashboardPerm->id)) {
        $dashboardMenu->permissions()->attach($dashboardPerm->id);
        $this->command->info('  → Menu Dashboard linked to permission');
      }
    }

    // 2. Menu Master Data (Parent)
    $masterDataMenu = Menu::firstOrCreate(
      ['name' => 'Master Data'],
      [
        'icon' => 'database',
        'position' => 2,
        'deleteable' => false,
      ]
    );

    // Attach permissions untuk Master Data Menu
    $masterDataMenu->permissions()->sync(
      Permission::whereIn('name', [
        'read machine',
        'read classification',
      ])->get()->pluck('id')
    );

    // 2.1 Sub Menu - Master Mesin
    $masterMesinMenu = $masterDataMenu->childs()->firstOrCreate(
      ['name' => 'Master Mesin'],
      [
        'route_or_url' => 'master-machines.index',
        'icon' => 'cogs',
        'position' => 1,
        'deleteable' => false,
        'actives' => ['master-machines.*'],
      ]
    );

    $masterMesinMenu->permissions()->sync(
      Permission::whereIn('name', [
        'create machine', 'read machine', 'update machine', 'delete machine',
      ])->get()->pluck('id')
    );

    // 2.2 Sub Menu - Master Klasifikasi
    $masterKlasifikasiMenu = $masterDataMenu->childs()->firstOrCreate(
      ['name' => 'Master Klasifikasi'],
      [
        'route_or_url' => 'master-classifications.index',
        'icon' => 'tags',
        'position' => 2,
        'deleteable' => false,
        'actives' => ['master-classifications.*'],
      ]
    );

    $masterKlasifikasiMenu->permissions()->sync(
      Permission::whereIn('name', [
        'create classification', 'read classification', 'update classification', 'delete classification',
      ])->get()->pluck('id')
    );

    $this->command->info('  → Menu Master Data created');

    // 3. Menu Working Order (Parent)
    $workingOrderMenu = Menu::firstOrCreate(
      ['name' => 'Working Order'],
      [
        'icon' => 'briefcase',
        'position' => 3,
        'deleteable' => false,
      ]
    );

    $workingOrderMenu->permissions()->sync(
      Permission::whereIn('name', [
        'read working report',
      ])->get()->pluck('id')
    );

    // 3.1 Sub Menu - Laporan Kerja
    $workingReportMenu = $workingOrderMenu->childs()->firstOrCreate(
      ['name' => 'Laporan Kerja'],
      [
        'route_or_url' => 'working-reports.index',
        'icon' => 'file-alt',
        'position' => 1,
        'deleteable' => false,
        'actives' => ['working-reports.*'],
      ]
    );

    $workingReportMenu->permissions()->sync(
      Permission::whereIn('name', [
        'create working report', 'read working report', 'update working report', 'delete working report',
      ])->get()->pluck('id')
    );

    // 3.2 Sub Menu - Warming Up
    $warmingUpMenu = $workingOrderMenu->childs()->firstOrCreate(
      ['name' => 'Warming Up'],
      [
        'route_or_url' => 'warming-up.index',
        'icon' => 'fire',
        'position' => 2,
        'deleteable' => false,
        'actives' => ['warming-up.*'],
      ]
    );

    $warmingUpMenu->permissions()->sync(
      Permission::whereIn('name', [
        'create warmingup', 'read warmingup', 'update warmingup', 'delete warmingup',
      ])->get()->pluck('id')
    );

    // 3.3 Sub Menu - Work Result
    $workResultMenu = $workingOrderMenu->childs()->firstOrCreate(
      ['name' => 'Hasil Kerja'],
      [
        'route_or_url' => 'work-results.index',
        'icon' => 'check-circle',
        'position' => 3,
        'deleteable' => false,
        'actives' => ['work-results.*'],
      ]
    );

    $workResultMenu->permissions()->sync(
      Permission::whereIn('name', [
        'create workresult', 'read workresult', 'update workresult', 'delete workresult',
      ])->get()->pluck('id')
    );

    $this->command->info('  → Menu Working Order created');

    // 4. Menu Maintenance Order
    $maintenanceOrderMenu = Menu::firstOrCreate(
      ['name' => 'Maintenance Order'],
      [
        'icon' => 'tools',
        'route_or_url' => 'maintenance-orders.index',
        'position' => 4,
        'deleteable' => false,
        'actives' => ['maintenance-orders.*'],
      ]
    );

    $maintenanceOrderMenu->permissions()->sync(
      Permission::whereIn('name', [
        'create maintenance order', 'read maintenance order', 'update maintenance order', 'delete maintenance order',
      ])->get()->pluck('id')
    );

    $this->command->info('  → Menu Maintenance Order created');

    // 5. Menu Check Sheet (Parent)
    $checkSheetMenu = Menu::firstOrCreate(
      ['name' => 'Check Sheet'],
      [
        'icon' => 'clipboard-check',
        'position' => 5,
        'deleteable' => false,
      ]
    );

    $checkSheetMenu->permissions()->sync(
      Permission::whereIn('name', [
        'read checksheet',
      ])->get()->pluck('id')
    );

    // 5.1 Sub Menu - Check Sheet
    $checkSheetFormMenu = $checkSheetMenu->childs()->firstOrCreate(
      ['name' => 'Form Check Sheet'],
      [
        'route_or_url' => 'check-sheet.index',
        'icon' => 'clipboard-list',
        'position' => 1,
        'deleteable' => false,
        'actives' => ['check-sheet.*'],
      ]
    );

    $checkSheetFormMenu->permissions()->sync(
      Permission::whereIn('name', [
        'create checksheet', 'read checksheet', 'update checksheet', 'delete checksheet',
      ])->get()->pluck('id')
    );

    // 5.2 Sub Menu - Check Sheet Day
    $checkSheetDayMenu = $checkSheetMenu->childs()->firstOrCreate(
      ['name' => 'Check Sheet Harian'],
      [
        'route_or_url' => 'check-sheet-day.index',
        'icon' => 'calendar-check',
        'position' => 2,
        'deleteable' => false,
        'actives' => ['check-sheet-day.*'],
      ]
    );

    $checkSheetDayMenu->permissions()->sync(
      Permission::whereIn('name', [
        'create checksheet', 'read checksheet', 'update checksheet', 'delete checksheet',
      ])->get()->pluck('id')
    );

    $this->command->info('  → Menu Check Sheet created');

    $this->command->info('');
    $this->command->info('✓ Semua menu untuk Admin Wilayah berhasil dibuat!');
    $this->command->info('');
    $this->command->warn('📌 CATATAN PENTING:');
    $this->command->warn('   Admin Wilayah akan melihat data HANYA dari region yang ditugaskan');
    $this->command->warn('   Set region_id di tabel users untuk membatasi akses');
  }
}
