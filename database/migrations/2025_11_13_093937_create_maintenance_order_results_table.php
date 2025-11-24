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
    Schema::create('maintenance_order_results', function (Blueprint $table) {
        $table->id();

        // Menghubungkan ke Order mana
        $table->foreignId('maintenance_order_id')->constrained('maintenance_orders')->onDelete('cascade');
        // Menghubungkan ke Pertanyaan checklist yang mana
        $table->foreignId('master_pedoman_item_id')->constrained('master_pedoman_items')->onDelete('cascade');
        // Jawaban dari teknisi
        $table->string('realisasi')->nullable(); // con: "1040"
        $table->string('status')->nullable(); // con: "OK" atau "NG" (Not Good)
        $table->text('catatan')->nullable(); // Catatan jika ada temuan

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
    Schema::dropIfExists('maintenance_order_results');
  }
};
