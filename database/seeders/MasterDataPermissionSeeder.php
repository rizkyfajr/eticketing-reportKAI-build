<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class MasterDataPermissionSeeder extends Seeder
{
  /**
  * Run the database seeds.
  *
  * @return void
  */
  public function run()
  {
    collect(['machine', 'classification', 'region'])->each(function ($name) {
      collect(['create', 'read', 'update', 'delete'])->each(function ($ability) use ($name) {
        Permission::firstOrCreate([
          'name' => sprintf('%s %s', $ability, $name),
          'guard_name' => 'web',
        ]);
      });
    });
  }
}
