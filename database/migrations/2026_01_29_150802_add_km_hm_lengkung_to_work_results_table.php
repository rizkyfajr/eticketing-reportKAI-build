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
      $table->string('km_hm_lengkung1', 20)->nullable()->after('radius1');
      $table->string('km_hm_lengkung2', 20)->nullable()->after('radius2');
      $table->string('km_hm_lengkung3', 20)->nullable()->after('radius3');
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
