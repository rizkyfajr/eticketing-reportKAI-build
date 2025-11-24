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
        // Mengubah tabel 'maintenance_orders' yang SUDAH ADA
        Schema::table('maintenance_orders', function (Blueprint $table) {

            // Kolom untuk 'Machine Hours' dari flowchart
            // saya letakkan setelah 'action_plan' (sesuai screenshot Anda)
            $table->integer('machine_hours')->nullable()->after('action_plan');

            // Kunci asing untuk menghubungkan order ini ke pedoman checklist
            // Harus nullable, karena 'unplanned' tidak punya pedoman
            $table->foreignId('master_pedoman_id')
                  ->nullable()
                  ->after('machine_hours')
                  ->constrained('master_pedoman'); // mengacu ke tabel 'master_pedoman'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('maintenance_orders', function (Blueprint $table) {
            // Hapus foreign key dulu sebelum drop kolom
            $table->dropForeign(['master_pedoman_id']);

            $table->dropColumn('master_pedoman_id');
            $table->dropColumn('machine_hours');
        });
    }
};
