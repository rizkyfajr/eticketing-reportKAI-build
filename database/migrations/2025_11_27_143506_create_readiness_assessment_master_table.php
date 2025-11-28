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
    Schema::create('readiness_assessment_master', function (Blueprint $table) {
      $table->id();
      $table->string('group_name');
      $table->string('urutan')->default(0);
      $table->integer('nomor')->nullable(); 
      $table->string('komponen'); 
      $table->text('pertanyaan'); 
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
    Schema::dropIfExists('readiness_assessment_master');
  }
};
