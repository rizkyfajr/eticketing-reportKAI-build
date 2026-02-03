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
    Schema::table('warming_up', function (Blueprint $table) {
      $table->string('satuan')->nullable()->after('hsd_akhir_kerja');
    });
  }
  
  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::table('warming_up', function (Blueprint $table) {
      //
    });
  }
};
