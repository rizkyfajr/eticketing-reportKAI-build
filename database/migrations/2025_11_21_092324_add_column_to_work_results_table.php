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
    Schema::table('work_results', function (Blueprint $table) {
      $table->string('hu_hi1')->nullable()->after('lokasi_akhir1');
      $table->string('hu_hi2')->nullable()->after('lokasi_akhir2');
      $table->string('hu_hi3')->nullable()->after('lokasi_akhir3');
      $table->string('hu_hi4')->nullable()->after('km_hm1');
      $table->string('hu_hi5')->nullable()->after('km_hm2');
      $table->string('hu_hi6')->nullable()->after('km_hm3');
    });
  }
  
  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::table('work_results', function (Blueprint $table) {
      //
    });
  }
};
