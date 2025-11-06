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
    Schema::create('check_sheet_masters', function (Blueprint $table) {
      $table->id();
      $table->string('group_name')->nullable();
      $table->string('komponen');
      $table->string('rujukan')->nullable();
      $table->string('satuan')->nullable();
      $table->integer('urutan')->default(0);
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
    Schema::dropIfExists('check_sheet_masters');
  }
};
