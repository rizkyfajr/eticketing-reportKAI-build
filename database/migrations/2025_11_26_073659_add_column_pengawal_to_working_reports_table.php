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
    Schema::table('working_reports', function (Blueprint $table) {
      $table->string('nama_pengawal')->nullable();
      $table->integer('nipp')->nullable();
      $table->string('nama_pengawal1')->nullable();
      $table->integer('nipp1')->nullable();
    });
  }
  
  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::table('working_reports', function (Blueprint $table) {
      //
    });
  }
};
