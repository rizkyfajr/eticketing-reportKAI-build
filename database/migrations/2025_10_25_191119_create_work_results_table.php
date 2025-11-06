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
      $table->foreignId('machine_id')->constrained('master_machines')->cascadeOnDelete();
      $table->foreignId('region_id')->constrained('master_regions')->cascadeOnDelete();
      $table->dateTime('tanggal')->nullable();
      $table->string('cuaca')->nullable();
      $table->string('wilayah')->nullable();
      $table->string('petak_jalan')->nullable();
      $table->string('jalur')->nullable();
      $table->string('kelas_jalan')->nullable();
      $table->string('kecepatan_lintas')->nullable();
      $table->string('lokasi_awal1', 20)->nullable();
      $table->string('lokasi_akhir1', 20)->nullable();
      $table->decimal('jumlah1', 8, 3)->nullable();
      $table->string('lokasi_awal2', 20)->nullable();
      $table->string('lokasi_akhir2', 20)->nullable();
      $table->decimal('jumlah2', 8, 3)->nullable();
      $table->string('lokasi_awal3', 20)->nullable();
      $table->string('lokasi_akhir3', 20)->nullable();
      $table->decimal('jumlah3', 8, 3)->nullable();
      $table->decimal('total_distance', 8, 3)->nullable();
      $table->string('no_wesel1', 20)->nullable();
      $table->string('km_hm1', 20)->nullable();
      $table->integer('jumlah_wesel1')->nullable();
      $table->string('no_wesel2', 20)->nullable();
      $table->string('km_hm2', 20)->nullable();
      $table->integer('jumlah_wesel2')->nullable();
      $table->string('no_wesel3', 20)->nullable();
      $table->string('km_hm3', 20)->nullable();
      $table->integer('jumlah_wesel3')->nullable();
      $table->integer('total_wesel')->nullable();
      $table->dateTime('waktu_start_engine')->nullable();
      $table->time('jam_luncuran')->nullable();
      $table->time('jam_kerja')->nullable();
      $table->time('jam_mesin')->nullable();
      $table->time('jam_genset')->nullable();
      $table->dateTime('waktu_stop_engine')->nullable();
      $table->integer('counter_pecok')->nullable();
      $table->integer('oddometer')->nullable();
      $table->decimal('penggunaan_hsd', 10, 2)->nullable();
      $table->decimal('penggunaan_hsd1', 10, 2)->nullable();
      $table->decimal('hsd_tersedia', 10, 2)->nullable();
      $table->integer('oddometer_hsd')->nullable();
      $table->foreignId('pengawal_id')->nullable()->constrained('users')->nullOnDelete();
      $table->text('note')->nullable();
      $table->foreignId('operator_by1')->nullable()->constrained('users')->nullOnDelete();
      $table->dateTime('operator_at1')->nullable();
      $table->foreignId('operator_by2')->nullable()->constrained('users')->nullOnDelete();
      $table->dateTime('operator_at2')->nullable();
      $table->foreignId('operator_by3')->nullable()->constrained('users')->nullOnDelete();
      $table->dateTime('operator_at3')->nullable();
      $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
      $table->dateTime('approved_at')->nullable();
      $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
      $table->foreignId('updated_by_id')->nullable()->constrained('users')->nullOnDelete();
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
