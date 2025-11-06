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
    Schema::create('master_machines', function (Blueprint $table) {
      $table->id();
      $table->foreignId('region_id')->constrained('master_regions')->cascadeOnDelete();
      $table->string('name');         
      $table->string('type')->nullable();
      $table->string('nomor')->unique();
      $table->integer('tahun_md')->nullable();
      $table->integer('umur')->nullable();
      $table->string('no_sarana')->unique();
      $table->string('keterangan')->nullable();
      $table->timestamps();
    });
  }
  
  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('master_machines');
  }
};
