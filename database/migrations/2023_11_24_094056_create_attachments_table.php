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
    Schema::create('attachments', function (Blueprint $table) {
      $table->id();
      $table->morphs('attachmentable');
      $table->foreignId('uploader_id')->constrained('users')->onDelete('cascade');
      $table->string('path')->nullable()->default(null);
      $table->string('filename')->nullable()->default(null);
      $table->unsignedBigInteger('size')->nullable()->default(null);
      $table->longText('description')->nullable()->default(null);
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
    Schema::dropIfExists('attachments');
  }
};
