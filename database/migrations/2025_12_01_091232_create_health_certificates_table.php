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
    Schema::create('health_certificates', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
      $table->date('upload_date'); // Tanggal upload (H-1 sebelum dinas)
      $table->date('valid_from'); // Tanggal mulai berlaku
      $table->date('valid_until'); // Tanggal akhir berlaku (valid_from + 3 hari = 4 hari total)
      $table->string('file_path'); // Path file surat keterangan sehat
      $table->enum('status', ['active', 'expired'])->default('active');
      $table->text('notes')->nullable(); // Catatan tambahan
      $table->timestamps();

      // Index untuk performa query
      $table->index(['user_id', 'valid_until']);
      $table->index(['user_id', 'status']);
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('health_certificates');
  }
};
