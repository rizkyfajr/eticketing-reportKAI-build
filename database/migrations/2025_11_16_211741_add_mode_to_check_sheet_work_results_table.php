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
    Schema::table('check_sheet_work_results', function (Blueprint $table) {
      $table->string('mode')->nullable()->after('validasi4');
    });
  }
  
  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::table('check_sheet_work_results', function (Blueprint $table) {
      //
    });
  }
};
