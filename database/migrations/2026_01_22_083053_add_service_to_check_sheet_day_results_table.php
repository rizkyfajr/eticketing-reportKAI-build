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
    Schema::table('check_sheet_day_results', function (Blueprint $table) {
      $table->tinyInteger('service')->default(0)->after('tambahan');
    });
  }
  
  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::table('check_sheet_day_results', function (Blueprint $table) {
      //
    });
  }
};
