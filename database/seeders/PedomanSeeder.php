<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema; // Tambahkan ini

class PedomanSeeder extends Seeder
{
    public function run(): void
    {
        // === 1. MEMBERSIHKAN DATA LAMA (PENTING) ===
        // Kita matikan pengecekan kunci asing sebentar agar bisa menghapus data induk
        Schema::disableForeignKeyConstraints();

        // Kosongkan tabel agar tidak duplicate entry
        DB::table('master_pedoman_items')->truncate();
        DB::table('master_pedoman_categories')->truncate();
        DB::table('master_pedoman')->truncate();

        Schema::enableForeignKeyConstraints();
        // ============================================

        $now = (new \DateTime())->format('Y-m-d H:i:s');

        // =================================================================
        // 1. PEDOMAN: P1 CSM WITH GEN (Tipe Standar / Checklist Biasa)
        // =================================================================

        $p1_csm_id = DB::table('master_pedoman')->insertGetId([
            'kode_pedoman' => 'P1 CSM WITH GEN',
            'nama_pedoman' => 'Pedoman Perawatan 1 Bulanan CSM with Gen',
            'created_at' => $now, 'updated_at' => $now,
        ]);

        // --- Kategori A: Engine (P1 CSM) ---
        $cat_p1_engine = DB::table('master_pedoman_categories')->insertGetId([
            'master_pedoman_id' => $p1_csm_id,
            'name' => 'Engine',
            'order' => 1,
            'created_at' => $now, 'updated_at' => $now,
        ]);

        // Item Engine P1
        $items_p1_engine = [
            ['nomor_poin' => 'E.1', 'deskripsi' => 'Saluran bahan bakar mesin diesel', 'tipe' => 'numeric', 'std' => '1050', 'sat' => 'Bar'],
            ['nomor_poin' => 'E.2', 'deskripsi' => 'Tekanan pelumas mesin diesel', 'tipe' => 'numeric', 'std' => '2-8', 'sat' => 'Bar'],
            ['nomor_poin' => 'E.3', 'deskripsi' => 'Suhu operasi mesin diesel', 'tipe' => 'numeric', 'std' => '70-75', 'sat' => '°C'],
            ['nomor_poin' => 'E.4', 'deskripsi' => 'Sistem pendingin mesin diesel', 'tipe' => 'numeric', 'std' => '39', 'sat' => 'Ltr'],
            ['nomor_poin' => 'E.5', 'deskripsi' => 'Bocoran pelumas mesin diesel', 'tipe' => 'checkbox', 'std' => 'Tidak ada', 'sat' => '-'],
        ];

        foreach ($items_p1_engine as $item) {
            DB::table('master_pedoman_items')->insert([
                'master_pedoman_category_id' => $cat_p1_engine,
                'nomor_poin' => $item['nomor_poin'],
                'deskripsi' => $item['deskripsi'],
                'tipe_input' => $item['tipe'], // numeric atau checkbox
                'standar_nilai' => $item['std'],
                'satuan' => $item['sat'],
                'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        // --- Kategori B: Generator (P1 CSM) ---
        $cat_p1_gen = DB::table('master_pedoman_categories')->insertGetId([
            'master_pedoman_id' => $p1_csm_id,
            'name' => 'Generator',
            'order' => 2,
            'created_at' => $now, 'updated_at' => $now,
        ]);

        $items_p1_gen = [
            ['nomor_poin' => 'G.1', 'deskripsi' => 'Filter diesel generator', 'tipe' => 'numeric', 'std' => '3,3', 'sat' => 'Ltr'],
            ['nomor_poin' => 'G.2', 'deskripsi' => 'Water coolant diesel generator', 'tipe' => 'numeric', 'std' => '2,2', 'sat' => 'Ltr'],
            ['nomor_poin' => 'G.3', 'deskripsi' => 'Filter pemisah air bahan bakar', 'tipe' => 'checkbox', 'std' => '1', 'sat' => 'Pcs'],
        ];

        foreach ($items_p1_gen as $item) {
            DB::table('master_pedoman_items')->insert([
                'master_pedoman_category_id' => $cat_p1_gen,
                'nomor_poin' => $item['nomor_poin'],
                'deskripsi' => $item['deskripsi'],
                'tipe_input' => $item['tipe'],
                'standar_nilai' => $item['std'],
                'satuan' => $item['sat'],
                'created_at' => $now, 'updated_at' => $now,
            ]);
        }


        // =================================================================
        // 2. PEDOMAN: P6 UNIMAT (Tipe Kompleks / Gambar & Tabel)
        // =================================================================

        $p6_unimat_id = DB::table('master_pedoman')->insertGetId([
            'kode_pedoman' => 'P6 UNIMAT',
            'nama_pedoman' => 'Pedoman Perawatan 6 Bulanan UNIMAT',
            'created_at' => $now, 'updated_at' => $now,
        ]);

        // --- Kategori A: Standarisasi Pengukuran (Ini yang kompleks) ---
        $cat_p6_ukur = DB::table('master_pedoman_categories')->insertGetId([
            'master_pedoman_id' => $p6_unimat_id,
            'name' => 'Standarisasi Pengukuran',
            'order' => 1,
            'created_at' => $now, 'updated_at' => $now,
        ]);

        // ITEM 1: Gambar Diagram (Hanya tampilan)
        DB::table('master_pedoman_items')->insert([
            'master_pedoman_category_id' => $cat_p6_ukur,
            'nomor_poin' => null,
            'deskripsi' => 'Diagram Standar Pengukuran Roda',
            'tipe_input' => 'image', // Tipe IMAGE
            'gambar_referensi_path' => 'diagram-roda-default.png', // Placeholder
            'created_at' => $now, 'updated_at' => $now,
        ]);

        // ITEM 2: Tabel Pengukuran (Input Dinamis)
        // Kita simpan struktur kolomnya dalam JSON di 'extra_config'
        $configTabelRoda = json_encode([
            'columns' => [
                ['name' => 'Diameter (d)', 'std' => '>640'],
                ['name' => 'Raakdrant (r)', 'std' => '0'],
                ['name' => 'Tinggi Flens (t)', 'std' => '>27'],
                ['name' => 'Tebal Flens (i)', 'std' => '>22'],
                ['name' => 'Jarak Keping (a)', 'std' => '>999']
            ],
            'rows_label' => ['Roda 1', 'Roda 2', 'Roda 3', 'Roda 4']
        ]);

        DB::table('master_pedoman_items')->insert([
            'master_pedoman_category_id' => $cat_p6_ukur,
            'nomor_poin' => '1.',
            'deskripsi' => 'Tabel Pengukuran Profil Roda',
            'tipe_input' => 'table', // Tipe TABLE
            'extra_config' => $configTabelRoda,
            'created_at' => $now, 'updated_at' => $now,
        ]);


        // =================================================================
        // 3. PEDOMAN LAINNYA (PLACEHOLDER)
        // =================================================================
        $others = [
            'P1 PBR U-RS', 'P3 PBR U-RS', 'P6 PBR U-RS',
            'P6 CSM WITH GEN', 'P3 CSM', 'P6 CSM',
            'P1 UNIMAT', 'P3 UNIMAT'
        ];

        foreach ($others as $code) {
            DB::table('master_pedoman')->insert([
                'kode_pedoman' => $code,
                'nama_pedoman' => 'Pedoman Perawatan ' . $code,
                'created_at' => $now, 'updated_at' => $now,
            ]);
        }
    }
}
