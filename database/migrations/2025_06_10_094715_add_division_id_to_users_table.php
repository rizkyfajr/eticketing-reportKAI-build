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
      Schema::table('users', function (Blueprint $table) {
          $table->unsignedBigInteger('division_id')->nullable()->after('position_id');
          $table->foreign('division_id')->references('id')->on('division')->onDelete('set null');
      });
  }

  public function down()
  {
      Schema::table('users', function (Blueprint $table) {
          $table->dropForeign(['division_id']);
          $table->dropColumn('division_id');
      });
  }
};
