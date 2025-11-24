<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabel ini menyimpan DETAIL ITEM dari setiap pedoman
        Schema::create('master_pedoman_items', function (Blueprint $table) {
            $table->id();

            // Kunci asing yang menghubungkan item ini ke pedomannya
            $table->foreignId('master_pedoman_id')
                  ->constrained('master_pedoman') // mengacu ke tabel 'master_pedoman'
                  ->onDelete('cascade'); // Jika pedoman dihapus, itemnya ikut terhapus

            $table->string('deskripsi_pekerjaan');
            $table->string('standar')->nullable(); // "1050" atau "2-8" (pakai string)
            $table->string('satuan')->nullable(); // "Bar", "°C", dll.
            $table->integer('urutan')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_pedoman_items');
    }
};
