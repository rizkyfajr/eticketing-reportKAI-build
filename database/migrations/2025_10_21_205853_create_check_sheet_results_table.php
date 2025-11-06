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
    Schema::create('check_sheet_results', function (Blueprint $table) {
      $table->id();
      $table->foreignId('check_sheet_id')->constrained('check_sheets')->cascadeOnDelete();
      $table->foreignId('check_sheet_master_id')->constrained('check_sheet_masters')->cascadeOnDelete();
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
    Schema::dropIfExists('check_sheet_results');
  }
};
