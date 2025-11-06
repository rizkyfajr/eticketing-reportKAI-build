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
    Schema::create('check_sheets', function (Blueprint $table) {
      $table->id();
      $table->foreignId('working_report_id')->constrained('working_reports')->cascadeOnDelete();
      $table->string('upt_resor')->nullable();
      $table->date('tanggal')->nullable();
      $table->time('waktu')->nullable();
      $table->foreignId('region_id')->constrained('master_regions')->cascadeOnDelete();
      $table->string('cuaca')->nullable();
      $table->string('tipe_kpjr')->nullable();
      $table->string('nomor_seri')->nullable();
      $table->time('jam_mesin')->nullable();
      $table->integer('kilometer_mesin')->nullable();
      $table->integer('counter_tamping')->nullable();
      $table->text('note')->nullable();
      $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
      $table->dateTime('approved_at')->nullable();
      $table->foreignId('approved_by1')->nullable()->constrained('users')->nullOnDelete();
      $table->dateTime('approved_at1')->nullable();
      $table->foreignId('approved_by2')->nullable()->constrained('users')->nullOnDelete();
      $table->dateTime('approved_at2')->nullable();
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
    Schema::dropIfExists('check_sheets');
  }
};
