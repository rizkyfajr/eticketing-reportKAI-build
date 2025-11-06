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
    Schema::table('reports', function (Blueprint $table) {
      $table->foreignId('bagian_id')
            ->nullable()
            ->default(null)
            ->constrained('system_section')
            ->cascadeOnDelete()
            ->after('tanggal');
    });
  }
  
  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::table('reports', function (Blueprint $table) {
      //
    });
  }
};
