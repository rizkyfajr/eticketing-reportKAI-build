<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::table('users', function (Blueprint $table) {
      // Tambahkan kolom region_id untuk Admin Wilayah
      // NULL = Super Admin (akses semua region)
      // Terisi = Admin Wilayah (hanya akses region tertentu)
      $table->foreignId('region_id')->nullable()->after('division_id')->constrained('master_regions')->onDelete('set null');
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::table('users', function (Blueprint $table) {
      $table->dropForeign(['region_id']);
      $table->dropColumn('region_id');
    });
  }
};
