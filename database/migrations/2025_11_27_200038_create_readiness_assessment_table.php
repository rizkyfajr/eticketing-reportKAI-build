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
    Schema::create('readiness_assessment', function (Blueprint $table) {
      $table->id();
      $table->foreignId('assessment_master_id')->constrained('readiness_assessment_master')->cascadeOnDelete();
      $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
      $table->date('assessment_date');
      $table->tinyInteger('ya')->default(0);
      $table->tinyInteger('tidak')->default(0);
      $table->string('note')->nullable();
      $table->timestamps();
      
      // Constraint: Setiap pertanyaan (master) hanya boleh diisi 1x per hari oleh 1 user.
      $table->unique(['user_id', 'assessment_master_id', 'assessment_date'], 'unique_daily_assessment');
    });
  }
  
  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('readiness_assessment');
  }
};
