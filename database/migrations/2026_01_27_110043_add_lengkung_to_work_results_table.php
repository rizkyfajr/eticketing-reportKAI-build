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
      $table->string('no_lengkung1', 20)->nullable()->after('total_wesel');
      $table->string('radius1', 20)->nullable()->after('no_lengkung1');
      $table->string('hu_hi7')->nullable()->after('radius1');
      $table->integer('jumlah_lengkung1')->nullable()->after('hu_hi7');
      
      $table->string('no_lengkung2', 20)->nullable()->after('jumlah_lengkung1');
      $table->string('radius2', 20)->nullable()->after('no_lengkung2');
      $table->string('hu_hi8')->nullable()->after('radius2');
      $table->integer('jumlah_lengkung2')->nullable()->after('hu_hi8');
      
      $table->string('no_lengkung3', 20)->nullable()->after('jumlah_lengkung2');
      $table->string('radius3', 20)->nullable()->after('no_lengkung3');
      $table->string('hu_hi9')->nullable()->after('radius3');
      $table->integer('jumlah_lengkung3')->nullable()->after('hu_hi9');
      
      $table->integer('total_lengkung')->nullable()->after('jumlah_lengkung3');
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
