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
    Schema::table('feedbacks', function (Blueprint $table) {
      $table->foreignId('report_id')
            ->nullable()
            ->default(null)
            ->constrained('reports')
            ->cascadeOnDelete()
            ->after('kode');
      $table->string('balasan1')->after('balasan');
    });
  }
  
  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::table('feedbacks', function (Blueprint $table) {
      //
    });
  }
};
