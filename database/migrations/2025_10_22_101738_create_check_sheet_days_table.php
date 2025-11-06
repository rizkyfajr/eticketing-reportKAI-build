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
    Schema::create('check_sheet_days', function (Blueprint $table) {
      $table->id();
      $table->foreignId('working_report_id')->constrained('working_reports')->cascadeOnDelete();
      $table->integer('no_seri')->nullable();
      $table->string('jenis')->nullable();
      $table->time('jam_mesin')->nullable();
      $table->integer('counter_pecok')->nullable();
      $table->integer('kilometer_mesin')->nullable();
      $table->date('tanggal')->nullable();
      $table->string('lokasi')->nullable();
      $table->string('wilayah')->nullable();
      $table->foreignId('region_id')->constrained('master_regions')->cascadeOnDelete();
      $table->text('note')->nullable();
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
    Schema::dropIfExists('check_sheet_days');
  }
};
