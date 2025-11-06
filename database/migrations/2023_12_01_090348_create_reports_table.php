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
    Schema::create('reports', function (Blueprint $table) {
      $table->id();
      $table->string('kode')
            ->nullable()
            ->default(null);
      $table->string('bagian_sistem');
      $table->date('tanggal')
            ->nullable()
            ->default(null);
      $table->string('bagian_pelapor');
      $table->string('kategori');
      $table->string('kendala');
      $table->string('dampak');
      $table->string('kontak');
      $table->string('url');
      $table->integer('status')
            ->default(1)
            ->nullable();
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
    Schema::dropIfExists('reports');
  }
};
