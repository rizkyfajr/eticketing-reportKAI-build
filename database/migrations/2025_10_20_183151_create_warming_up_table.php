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
      $table->foreignId('machine_id')->constrained('master_machines')->cascadeOnDelete();
      $table->dateTime('waktu_start_engine')->nullable();
      $table->time('jam_kerja')->nullable();
      $table->time('jam_mesin')->nullable();
      $table->time('jam_genset')->nullable();
      $table->integer('counter_pecok')->nullable();
      $table->integer('oddometer')->nullable();
      $table->dateTime('waktu_stop_engine')->nullable();
      $table->decimal('penggunaan_hsd', 10, 2)->nullable();
      $table->decimal('hsd_tersedia', 10, 2)->nullable();
      $table->text('note')->nullable();
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
    Schema::dropIfExists('warming_up');
  }
};
