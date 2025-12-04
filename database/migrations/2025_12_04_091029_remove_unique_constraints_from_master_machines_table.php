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
    Schema::table('master_machines', function (Blueprint $table) {
      // Drop unique constraints
      $table->dropUnique(['nomor']);
      $table->dropUnique(['no_sarana']);
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::table('master_machines', function (Blueprint $table) {
      // Re-add unique constraints if rollback
      $table->unique('nomor');
      $table->unique('no_sarana');
    });
  }
};
