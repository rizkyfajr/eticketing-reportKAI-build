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
        // Tabel ini menyimpan NAMA pedoman/checklist
        Schema::create('master_pedoman', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pedoman')->unique(); // "P1 CSM WITH GEN", "P6 UNIMAT", dll.
            $table->string('nama_pedoman'); // "Perawatan 1 Bulanan CSM", dll.
            $table->text('deskripsi')->nullable(); // Opsional jika perlu
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_pedoman');
    }
};
