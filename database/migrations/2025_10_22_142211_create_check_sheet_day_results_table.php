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
    Schema::create('check_sheet_day_results', function (Blueprint $table) {
      $table->id();
      $table->foreignId('check_sheet_day_id')->constrained('check_sheet_days')->cascadeOnDelete();
      $table->foreignId('check_sheet_master_day_id')->constrained('check_sheet_master_days')->cascadeOnDelete();
      $table->tinyInteger('cek')->default(0);
      $table->tinyInteger('tambahan')->default(0);
      $table->tinyInteger('ganti')->default(0);
      $table->string('kiri_depan')->nullable();
      $table->string('kanan_depan')->nullable();
      $table->tinyInteger('hasil')->default(0);
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
    Schema::dropIfExists('check_sheet_day_results');
  }
};
