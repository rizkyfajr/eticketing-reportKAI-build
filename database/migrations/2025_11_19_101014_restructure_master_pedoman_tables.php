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
        // 1. Hapus tabel items lama (karena kita ganti struktur)
        // Kita gunakan foreign key check = 0 biar tidak error constraint
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('master_pedoman_items');
        Schema::dropIfExists('master_pedoman_categories');
        Schema::enableForeignKeyConstraints();

        // 2. Buat Tabel Kategori (Contoh: "Engine", "Mekanik", "Standarisasi Pengukuran")
        Schema::create('master_pedoman_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('master_pedoman_id')->constrained('master_pedoman')->onDelete('cascade');
            $table->string('name'); // Nama Kategori
            $table->integer('order')->default(0); // Urutan tampilan
            $table->timestamps();
        });

        // 3. Buat Tabel Items BARU (Support Gambar & Tipe Input Dinamis)
        Schema::create('master_pedoman_items', function (Blueprint $table) {
            $table->id();

            // Link ke Kategori, BUKAN langsung ke Pedoman
            $table->foreignId('master_pedoman_category_id')->constrained('master_pedoman_categories')->onDelete('cascade');

            $table->string('nomor_poin')->nullable(); // Contoh: "E.1", "M.5"
            $table->text('deskripsi'); // "Cek Tekanan Oli" atau "Diagram Roda"

            // TIPE INPUT (Ini kunci kedinamisannya)
            // 'checkbox' : Ceklis OK/NG (Default)
            // 'numeric'  : Input angka biasa
            // 'text'     : Input teks pendek
            // 'image'    : Hanya menampilkan gambar (seperti diagram roda), tidak ada input
            // 'table'    : Input tabel kompleks (row & column banyak)
            $table->string('tipe_input')->default('checkbox');

            $table->string('standar_nilai')->nullable(); // "890", "2-6"
            $table->string('satuan')->nullable(); // "Bar", "Lt"

            // Path untuk gambar referensi (Diagram Roda di Excel Anda)
            $table->string('gambar_referensi_path')->nullable();

            // JSON untuk konfigurasi kompleks (Misal: definisi kolom untuk tabel roda)
            $table->json('extra_config')->nullable();

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
        Schema::dropIfExists('master_pedoman_categories');
    }
};
