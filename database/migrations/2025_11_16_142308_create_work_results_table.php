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
    Schema::create('work_results', function (Blueprint $table) {
      $table->id();      
      $table->foreignId('working_report_id')->constrained('working_reports')->cascadeOnDelete();
      $table->string('wilayah')->nullable();
      $table->string('petak_jalan')->nullable();
      $table->string('kelas_jalan')->nullable();
      $table->string('lokasi_stabling_awal')->nullable();
      $table->string('lokasi_stabling_akhir')->nullable();
      $table->string('lokasi_awal1')->nullable();
      $table->string('lokasi_akhir1')->nullable();
      $table->integer('jumlah1')->nullable();
      $table->string('lokasi_awal2')->nullable();
      $table->string('lokasi_akhir2')->nullable();
      $table->integer('jumlah2')->nullable();
      $table->string('lokasi_awal3')->nullable();
      $table->string('lokasi_akhir3')->nullable();
      $table->integer('jumlah3')->nullable();
      $table->integer('total_distance')->nullable();
      $table->string('no_wesel1')->nullable();
      $table->string('km_hm1')->nullable();
      $table->integer('jumlah_wesel1')->nullable();
      $table->string('no_wesel2')->nullable();
      $table->string('km_hm2')->nullable();
      $table->integer('jumlah_wesel2')->nullable();
      $table->string('no_wesel3')->nullable();
      $table->string('km_hm3')->nullable();
      $table->integer('jumlah_wesel3')->nullable();
      $table->integer('total_wesel')->nullable();
      $table->time('waktu_stop_engine')->nullable();
      $table->string('jam_traveling_akhir')->nullable();
      $table->time('jam_kerja_akhir')->nullable();
      $table->string('jam_mesin_akhir')->nullable();
      $table->string('jam_generator_akhir')->nullable();
      $table->integer('counter_tamping_akhir')->nullable();
      $table->integer('oddometer_akhir')->nullable();
      $table->integer('hsd_akhir_kerja')->nullable();
      $table->string('konsumsi_hsd')->nullable();
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
    Schema::dropIfExists('work_results');
  }
};
