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
    Schema::create('check_sheet_work_results', function (Blueprint $table) {
      $table->id();
      $table->foreignId('working_report_id')->constrained('working_reports')->cascadeOnDelete();
      $table->foreignId('check_sheet_day_id')->constrained('check_sheet_days')->cascadeOnDelete();
      $table->text('catatan_gangguan')->nullable();
      $table->string('lokasi_dan_jam1')->nullable();
      $table->string('hu_hi_1')->nullable();
      $table->string('jumlah_1')->nullable();

      $table->string('lokasi_dan_jam2')->nullable();
      $table->string('hu_hi_2')->nullable();
      $table->string('jumlah_2')->nullable();

      $table->string('lokasi_dan_jam3')->nullable();
      $table->string('hu_hi_3')->nullable();
      $table->string('jumlah_3')->nullable();

      $table->foreignId('operator_by1')->nullable()->constrained('users')->nullOnDelete();
      $table->dateTime('operator_at1')->nullable();
      $table->string('validasi1')->nullable();
      $table->foreignId('operator_by2')->nullable()->constrained('users')->nullOnDelete();
      $table->dateTime('operator_at2')->nullable();
      $table->string('validasi2')->nullable();
      $table->foreignId('operator_by3')->nullable()->constrained('users')->nullOnDelete();
      $table->dateTime('operator_at3')->nullable();
      $table->string('validasi3')->nullable();
      $table->foreignId('operator_by4')->nullable()->constrained('users')->nullOnDelete();
      $table->dateTime('operator_at4')->nullable();
      $table->string('validasi4')->nullable();
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
    Schema::dropIfExists('check_sheet_work_results');
  }
};
