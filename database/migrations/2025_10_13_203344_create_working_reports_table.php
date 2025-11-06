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
    Schema::create('working_reports', function (Blueprint $table) {
      $table->id();
      $table->foreignId('machine_id')->constrained('master_machines')->cascadeOnDelete();
      $table->foreignId('region_id')->constrained('master_regions')->cascadeOnDelete();
      $table->dateTime('date')->nullable();
      $table->boolean('has_trouble')->nullable();
      $table->enum('status', [
          'draft',
          'checksheet_done',
          'warming_up_done',
          'photo_uploaded',
          'work_done',
          'verification',
          'finished',
      ])->default('draft');
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
    Schema::dropIfExists('working_reports');
  }
};
