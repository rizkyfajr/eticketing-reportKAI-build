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
    Schema::create('warming_up', function (Blueprint $table) {
      $table->id();
      $table->foreignId('working_report_id')->constrained('working_reports')->cascadeOnDelete();
      $table->dateTime('tanggal')->nullable();
      $table->string('cuaca')->nullable();
      $table->string('jenis_kpjr')->nullable();
      $table->string('nomor_mesin')->nullable();
      $table->string('nomor_sarana')->nullable();

      $table->time('waktu_start_engine')->nullable();
      $table->string('jam_traveling_awal')->nullable();
      $table->time('jam_kerja_awal')->nullable();
      $table->string('jam_mesin_awal')->nullable();
      $table->string('jam_generator_awal')->nullable();
      $table->integer('counter_tamping_awal')->nullable();
      $table->integer('oddometer_awal')->nullable();
      $table->integer('hsd_awal_kerja')->nullable();
      $table->string('konsumsi_hsd')->nullable();
      
      $table->time('waktu_stop_engine')->nullable();
      $table->string('jam_traveling_akhir')->nullable();
      $table->time('jam_kerja_akhir')->nullable();
      $table->string('jam_mesin_akhir')->nullable();
      $table->string('jam_generator_akhir')->nullable();
      $table->integer('counter_tamping_akhir')->nullable();
      $table->integer('oddometer_akhir')->nullable();
      $table->integer('hsd_akhir_kerja')->nullable();

      $table->foreignId('operator_by1')->nullable()->constrained('users')->nullOnDelete();
      $table->dateTime('operator_at1')->nullable();
      $table->foreignId('operator_by2')->nullable()->constrained('users')->nullOnDelete();
      $table->dateTime('operator_at2')->nullable();
      $table->foreignId('operator_by3')->nullable()->constrained('users')->nullOnDelete();
      $table->dateTime('operator_at3')->nullable();
      $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
      $table->dateTime('approved_at')->nullable();
      $table->foreignId('approved_by1')->nullable()->constrained('users')->nullOnDelete();
      $table->dateTime('approved_at1')->nullable();
      $table->string('note')->nullable();

      $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
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
    Schema::dropIfExists('warming_up');
  }
};
