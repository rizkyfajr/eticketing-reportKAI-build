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
    Schema::create('verifications', function (Blueprint $table) {
      $table->id();
      $table->foreignId('report_id')
            ->nullable()
            ->default(null)
            ->constrained('reports')
            ->cascadeOnDelete();
      $table->date('tanggal')
            ->nullable()
            ->default(null);
      $table->string('catatan')
            ->nullable();
      $table->foreignId('created_by_id')
            ->nullable()
            ->default(null)
            ->constrained('users')
            ->cascadeOnDelete();
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
    Schema::dropIfExists('verifications');
  }
};
