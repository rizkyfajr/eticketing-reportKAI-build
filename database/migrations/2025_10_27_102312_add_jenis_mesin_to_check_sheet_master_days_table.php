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
    Schema::table('check_sheet_master_days', function (Blueprint $table) {
      $table->enum('jenis_mesin', ['MTT', 'PBR'])
                  ->nullable()
                  ->after('satuan');
    });
  }
  
  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::table('check_sheet_master_days', function (Blueprint $table) {
      //
    });
  }
};
