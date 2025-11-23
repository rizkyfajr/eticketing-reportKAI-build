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
        $table->string('cuaca')->nullable()->before('status');
        $table->string('jenis_kpjr')->nullable()->before('status');
        $table->string('nomor_mesin')->nullable()->before('status');
        $table->string('nomor_sarana')->nullable()->before('status');
        $table->time('waktu_start_engine')->nullable()->before('status');
        $table->string('jam_traveling_awal')->nullable()->before('status');
        $table->time('jam_kerja_awal')->nullable()->before('status');
        $table->string('jam_mesin_awal')->nullable()->before('status');
        $table->string('jam_generator_awal')->nullable()->before('status');
        $table->integer('counter_tamping_awal')->nullable()->before('status');
        $table->integer('oddometer_awal')->nullable()->before('status');
        $table->integer('hsd_awal_kerja')->nullable()->before('status');
        $table->foreignId('operator_by1')->nullable()->constrained('users')->nullOnDelete()->before('status');
        $table->dateTime('operator_at1')->nullable()->before('status');
        $table->foreignId('operator_by2')->nullable()->constrained('users')->nullOnDelete()->before('status');
        $table->dateTime('operator_at2')->nullable()->before('status');
        $table->foreignId('operator_by3')->nullable()->constrained('users')->nullOnDelete()->before('status');
        $table->dateTime('operator_at3')->nullable()->before('status');
        $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete()->before('status');
        $table->dateTime('approved_at')->nullable()->before('status');
        $table->foreignId('approved_by1')->nullable()->constrained('users')->nullOnDelete()->before('status');
        $table->dateTime('approved_at1')->nullable()->before('status');
        $table->string('note')->nullable()->before('status');
        $table->string('mode')->nullable()->before('status');
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
