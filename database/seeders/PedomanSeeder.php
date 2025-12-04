<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PedomanSeeder extends Seeder
{
    protected $now;

    public function run(): void
    {
        $this->now = (new \DateTime())->format('Y-m-d H:i:s');

        // 1. BERSIHKAN DATA LAMA
        Schema::disableForeignKeyConstraints();
        DB::table('master_pedoman_items')->truncate();
        DB::table('master_pedoman_categories')->truncate();
        DB::table('master_pedoman')->truncate();
        Schema::enableForeignKeyConstraints();

        // ====================================================
        // A. GROUP CSM (CSM WITH GEN)
        // ====================================================
        $this->createP1CSM();
        $this->createP3CSM();

        // ====================================================
        // B. GROUP PBR (PBR U-RS)
        // ====================================================
        $this->createP1PBR();
        $this->createP3PBR();

        // ====================================================
        // C. GROUP UNIMAT
        // ====================================================
        $this->createP1Unimat(); // NEW: Ditambahkan
        $this->createP6Unimat(); // Data kompleks Roda

        // ====================================================
        // D. PLACEHOLDER (Sisanya)
        // ====================================================
        $placeholders = [
            'P3 UNIMAT' => 'Pedoman Perawatan 3 Bulanan UNIMAT',
            'P6 CSM' => 'Pedoman Perawatan 6 Bulanan CSM',
            'P6 CSM WITH GEN' => 'Pedoman Perawatan 6 Bulanan CSM with GEN',
            'P6 PBR U-RS' => 'Pedoman Perawatan 6 Bulanan PBR U-RS',
        ];

        foreach ($placeholders as $kode => $nama) {
            $this->createPlaceholder($kode, $nama);
        }
    }

    // =========================================================================
    // 1. P1 CSM WITH GEN (Full dari PDF)
    // =========================================================================
    private function createP1CSM()
    {
        $id = $this->insertPedoman('P1 CSM WITH GEN', 'Pedoman Perawatan 1 Bulanan CSM with Gen');

        $cat = $this->insertCategory($id, 'Engine', 1);
        $this->insertItems($cat, [
            ['E.1.', 'Bahan bakar mesin diesel', 'numeric', '1050', 'Lt'],
            ['E.2.', 'Pelumas mesin diesel deutz', 'numeric', '33', 'Lt'],
            ['E.3.', 'Tekanan pelumas mesin diesel deutz', 'numeric', '2-6', 'Bar'],
            ['E.4.', 'Suhu operasi mesin diesel deutz', 'numeric', '70-75', '°C'],
            ['E.5.', 'Bocoran pelumas mesin diesel', 'checkbox', 'Tidak ada', '-'],
            ['E.13.', 'Water coolant', 'numeric', '39', 'Lt'],
            ['E.14.', 'Filter pemisah air bahan bakar', 'checkbox', '2', 'Pcs'],
        ]);

        $cat = $this->insertCategory($id, 'Generator', 2);
        $this->insertItems($cat, [
            ['G.1.', 'Pelumas diesel generator', 'numeric', '3.3', 'Lt'],
            ['G.2.', 'Water coolant diesel generator', 'numeric', '2.2', 'Lt'],
            ['G.3.', 'Filter pemisah air bahan bakar', 'checkbox', '1', 'Pcs'],
            ['G.4.', 'Filter bahan bakar mesin diesel generator', 'checkbox', '1', 'Pcs'],
            ['G.5.', 'Filter udara mesin diesel generator', 'checkbox', '1', 'Pcs'],
        ]);

        $cat = $this->insertCategory($id, 'Mekanik', 3);
        $this->insertItems($cat, [
            ['M.1.', 'Pelumas gearpump', 'numeric', '3.8', 'Lt'],
            ['M.2.', 'Pelumas bush tamping arm', 'numeric', '3', 'Lt'],
            ['M.3.', 'Pelumas shaft vibration', 'numeric', '2.5', 'Lt'],
            ['M.4.', 'Pelumas unit vibrasi', 'numeric', '2', 'Kg'],
            ['M.5.', 'Tebal blok kampas rem', 'numeric', '3-6', 'Cm'],
            ['M.6.', 'Coupler', 'numeric', '68-78.5', 'Cm'],
            ['M.10.', 'Pelumas roller lining', 'checkbox', '1', 'Pcs'],
            ['M.11.', 'Pelumas rods & tensioning troli depan', 'checkbox', '2', 'Pcs'],
            ['M.12.', 'Pelumas guide bushes troli depan', 'checkbox', '4', 'Pcs'],
            ['M.14.', 'Pelumas sensing rods troli tengah', 'checkbox', '2', 'Pcs'],
            ['M.15.', 'Pelumas guide bushes troli tengah', 'checkbox', '4', 'Pcs'],
            ['M.16.', 'Pelumas sensing rods troli belakang', 'checkbox', '2', 'Pcs'],
            ['M.17.', 'Pelumas guide bushes troli belakang', 'checkbox', '4', 'Pcs'],
            ['M.25.', 'Filter interval central grease lubrican', 'checkbox', '1', 'Pcs'],
            ['M.26.', 'Pelumas axle gearbox 1 dan 2', 'numeric', '7.5', 'Lt'],
            ['M.27.', 'Pelumas axle gearbox 3', 'numeric', '5.5', 'Lt'],
            ['M.28.', 'Pelumas intermediate drive shaft', 'numeric', '0.35', 'Lt'],
            ['M.29.', 'Pelumas cardan shaft power devider', 'numeric', '2', 'Lt'],
            ['M.30.', 'Pelumas SAT drive gearbox', 'numeric', '0.5', 'Lt'],
            ['M.31.', 'Pelumas drive reduction gearbox', 'numeric', '1.9', 'Lt'],
            ['M.32.', 'Pelumas ZF power shift gear', 'numeric', '46', 'Lt'],
            ['M.33.', 'Pelumas shaft bearings consolidator', 'checkbox', '2', 'Pcs'],
            ['M.34.', 'Pelumas pivot bearing consolidator', 'checkbox', '2', 'Pcs'],
            ['M.35.', 'Pelumas sliding surface axle support', 'checkbox', '2', 'Pcs'],
            ['M.36.', 'Pelumas SAT sliding surfaces support', 'checkbox', '2', 'Pcs'],
            ['M.37.', 'Pelumas longitudinal adjustment', 'checkbox', '2', 'Pcs'],
            ['M.38.', 'Pelumas side adjustment', 'checkbox', '2', 'Pcs'],
        ]);

        $cat = $this->insertCategory($id, 'Pneumatik', 4);
        $this->insertItems($cat, [
            ['P.2.', 'Kuras air tangki receiver pneumatik', 'checkbox', '2', 'Pcs'],
            ['P.3.', 'Selang dan pipa-pipa distribusi', 'checkbox', 'Cek', '-'],
            ['P.4.', 'Pelumas pneumatic', 'numeric', '0.2', 'Lt'],
            ['P.5.', 'Tekanan udara tangki receiver', 'numeric', '9', 'Bar'],
            ['P.6.', 'Tekanan udara pengereman', 'numeric', '3.5-4.0', 'Bar'],
            ['P.7.', 'Tekanan udara kerja', 'numeric', '7-9', 'Bar'],
            ['P.11.', 'Tabung silinder preload troli tengah', 'checkbox', '2', 'Pcs'],
            ['P.12.', 'Tabung silinder lifting troli tengah', 'checkbox', '2', 'Pcs'],
            ['P.13.', 'Tabung silinder preload lining', 'checkbox', '2', 'Pcs'],
            ['P.14.', 'Tabung silinder lifting lining', 'checkbox', '2', 'Pcs'],
            ['P.15.', 'Tabung silinder preload troli belakang', 'checkbox', '2', 'Pcs'],
            ['P.16.', 'Tabung silinder lifting troli belakang', 'checkbox', '2', 'Pcs'],
            ['P.17.', 'Tekanan udara drive charge', 'numeric', '5', 'Bar'],
        ]);

        $cat = $this->insertCategory($id, 'Hydraulik', 5);
        $this->insertItems($cat, [
            ['H.1.', 'Pelumas hidrolik', 'numeric', '1380', 'Lt'],
            ['H.2.', 'Selang dan pipa-pipa distribusi', 'checkbox', 'Cek', '-'],
            ['H.5.', 'Tekanan akumulator hidrolis kerja', 'numeric', '120-150', 'Bar'],
            ['H.6.', 'Tekanan hidrolis motor vibrasi', 'numeric', '150', 'Bar'],
            ['H.7.', 'Tekanan hidrolis squeezing', 'numeric', '90-110', 'Bar'],
        ]);

        $cat = $this->insertCategory($id, 'Elektrik & Keselamatan', 6);
        $this->insertItems($cat, [
            ['L.1.', 'Main and Buffer Baterai', 'numeric', '24', 'Vdc'],
            ['L.2.', 'Tegangan alternator', 'numeric', '26-28', 'Vdc'],
            ['L.3.', 'Cycle counter tamping', 'checkbox', 'Cek', '-'],
            ['L.4.', 'Jam mesin operasi', 'checkbox', 'Cek', '-'],
            ['L.5.', 'Electric Horn', 'checkbox', '3', 'Pcs'],
            ['L.6.', 'Air Conditioner', 'checkbox', 'Cek', '-'],
            ['a.', 'Lampu kabin', 'checkbox', 'Berfungsi', '-'],
            ['b.', 'Lampu kerja', 'checkbox', 'Berfungsi/3', 'Pcs'],
            ['c.', 'Semboyan 28', 'checkbox', 'Ada/2', 'Pcs'],
            ['d.', 'Semboyan 3', 'checkbox', 'Ada', 'Pcs'],
            ['e.', 'Semboyan 20', 'checkbox', 'Lengkap/6', 'Pcs'],
            ['f.', 'Semboyan 21 (Siang & Malam)', 'checkbox', 'Lengkap/4', 'Pcs'],
            ['g.', 'Semboyan 35', 'checkbox', 'Ada', 'Pcs'],
            ['h.', 'Perangkat wiper', 'checkbox', 'Lengkap/3', 'Pcs'],
            ['k.', 'Stop block', 'checkbox', 'Lengkap/2', 'Pcs'],
            ['l.', 'Perangkat radio komunikasi', 'checkbox', 'Berfungsi/2', 'Pcs'],
            ['-', 'Speedometer', 'checkbox', 'Berfungsi/1', 'Pcs'],
            ['l.', 'Alat pemadam api ringan', 'checkbox', 'Ada/1', 'Pcs'],
            ['m.', 'Dongkrak mekanik 1.5 ton', 'checkbox', 'Ada', 'Pcs'],
            ['n.', 'Derailment utility', 'checkbox', 'Ada/2', 'Pcs'],
            ['o.', 'Balok kayu stable', 'checkbox', 'Ada/2', 'Pcs'],
        ]);
    }

    // =========================================================================
    // 2. P3 CSM WITH GEN (Full dari PDF)
    // =========================================================================
    private function createP3CSM()
    {
        $id = $this->insertPedoman('P3 CSM WITH GEN', 'Pedoman Perawatan 3 Bulanan CSM with Gen');

        $cat = $this->insertCategory($id, 'Engine (Maintenance)', 1);
        $this->insertItems($cat, [
            ['E.1.', 'Bahan bakar mesin diesel', 'numeric', '1050', 'Lt'],
            ['E.2.', 'Pelumas mesin diesel deutz (Ganti)', 'checkbox', 'Ganti', 'Lt'],
            ['E.6.', 'Filter pelumas mesin diesel deutz', 'checkbox', 'Ganti', 'Pcs'],
            ['E.8.', 'Karet v-belt', 'checkbox', 'Cek', '-'],
            ['E.9.', 'Filter bahan bakar mesin diesel deutz', 'checkbox', 'Ganti', 'Pcs'],
            ['E.10.', 'Filter udara mesin diesel deutz', 'checkbox', 'Ganti', 'Pcs'],
            ['E.14.', 'Filter pemisah air bahan bakar', 'checkbox', 'Ganti', 'Pcs'],
        ]);

        $cat = $this->insertCategory($id, 'Generator', 2);
        $this->insertItems($cat, [
            ['G.1.', 'Pelumas diesel generator (Ganti)', 'checkbox', 'Ganti', 'Lt'],
            ['G.3.', 'Filter pemisah air bahan bakar', 'checkbox', 'Ganti', 'Pcs'],
            ['G.4.', 'Filter bahan bakar mesin diesel generator', 'checkbox', 'Ganti', 'Pcs'],
            ['G.5.', 'Filter udara mesin diesel generator', 'checkbox', 'Ganti', 'Pcs'],
        ]);

        $cat = $this->insertCategory($id, 'Hydraulik (Filter)', 3);
        $this->insertItems($cat, [
            ['H.9.', 'Filter hidrolis suction working pump', 'checkbox', 'Ganti', 'Pcs'],
            ['H.12.', 'Filter hidrolis return', 'checkbox', 'Ganti', 'Pcs'],
            ['H.13.', 'Filter proporsional hidrolis squeezing', 'checkbox', 'Ganti', 'Pcs'],
            ['H.14.', 'Filter hidrolis servo squeezing', 'checkbox', 'Ganti', 'Pcs'],
            ['H.15.', 'Filter hidrolis servo lining lifting', 'checkbox', 'Ganti', 'Pcs'],
            ['H.16.', 'Filter aeration', 'checkbox', 'Ganti', 'Pcs'],
            ['H.19.', 'Filter proporsional hidrolis drive SAT', 'checkbox', 'Ganti', 'Pcs'],
        ]);

        $cat = $this->insertCategory($id, 'Mekanik & Lainnya', 4);
        $this->insertItems($cat, [
            ['M.8.', 'Brake linings & brake block play', 'numeric', '5-7', 'Mm'],
            ['M.42.', 'Laher pivot bogie 1 dan 2', 'checkbox', 'Grease', '-'],
            ['M.43.', 'Power shift Gear', 'checkbox', 'Cek', '-'],
            ['M.44.', 'Lifting unit roller clamp', 'checkbox', 'Cek', '-'],
            ['M.45.', 'Pelumas satellite unit lateral', 'checkbox', 'Grease', '-'],
            ['M.46.', 'Pelumas support roller lateral SAT', 'checkbox', 'Grease', '-'],
        ]);

        // MENAMBAHKAN BAGIAN ELEKTRIK & KESELAMATAN YG HILANG (Copy dari P1 CSM karena item cek harian/bulanan biasanya sama di P3)
        $cat = $this->insertCategory($id, 'Elektrik, Keselamatan & Peralatan', 5);
        $this->insertItems($cat, [
            ['L.5.', 'Electric Horn', 'checkbox', 'Berfungsi', '-'],
            ['a.', 'Lampu kabin', 'checkbox', 'Berfungsi', '-'],
            ['b.', 'Lampu kerja', 'checkbox', 'Berfungsi', '-'],
            ['e.', 'Semboyan 20', 'checkbox', 'Lengkap', '-'],
            ['f.', 'Semboyan 21', 'checkbox', 'Lengkap', '-'],
            ['h.', 'Perangkat wiper', 'checkbox', 'Berfungsi', '-'],
            ['k.', 'Stop block', 'checkbox', 'Lengkap', '-'],
            ['l.', 'Radio Komunikasi', 'checkbox', 'Berfungsi', '-'],
            ['-', 'Speedometer', 'checkbox', 'Berfungsi', '-'],
            ['-', 'APAR', 'checkbox', 'Ada', '-'],
            ['-', 'Dongkrak & Derailment', 'checkbox', 'Ada', '-'],
        ]);
    }

    // =========================================================================
    // 3. P1 PBR U-RS (Full dari PDF)
    // =========================================================================
    private function createP1PBR()
    {
        $id = $this->insertPedoman('P1 PBR U-RS', 'Pedoman Perawatan 1 Bulanan PBR U-RS');

        $cat = $this->insertCategory($id, 'Engine', 1);
        $this->insertItems($cat, [
            ['E.1.', 'Bahan bakar mesin diesel', 'numeric', '1100', 'Lt'],
            ['E.2.', 'Pelumas mesin diesel deutz', 'numeric', '33', 'Lt'],
            ['E.3.', 'Tekanan pelumas mesin diesel', 'numeric', '2-6', 'Bar'],
            ['E.4.', 'Suhu operasi mesin diesel', 'numeric', '70-75', '°C'],
            ['E.5.', 'Bocoran pelumas mesin diesel', 'checkbox', 'Tidak ada', '-'],
            ['E.13.', 'Water coolant', 'numeric', '39', 'Lt'],
            ['E.14.', 'Filter pemisah air bahan bakar', 'checkbox', '2', 'Pcs'],
        ]);

        $cat = $this->insertCategory($id, 'Mekanik & Unit Bajak', 2);
        $this->insertItems($cat, [
            ['M.1.', 'Pelumas gearpump', 'numeric', '3.8', 'Lt'],
            ['M.5.', 'Tebal blok kampas rem', 'numeric', '3-6', 'Cm'],
            ['M.6.', 'Coupler', 'numeric', '68-78.5', 'Cm'],
            ['M.8.', 'Brake linings & brake block play', 'numeric', '5-7', 'Mm'],
            ['M.20.', 'Pelumas locking device', 'checkbox', 'Cek', '-'],
            ['M.26.', 'Pelumas axle gearbox 1 dan 2', 'numeric', '7.5', 'Lt'],
            ['M.47.', 'Unit conveyor belt', 'checkbox', 'Cek', '-'],
            ['M.48.', 'Unit bajak ballast', 'checkbox', 'Cek', '-'],
            ['M.49.', 'Unit sikat ballast', 'checkbox', 'Cek', '-'],
            ['M.50.', 'Pelumas pin plough bajak tengah', 'checkbox', '4', 'Unit'],
            ['M.51.', 'Pelumas pin plough cyl lifting bajak tengah', 'checkbox', '4', 'Unit'],
            ['M.52.', 'Pelumas pin plough lengan bajak samping', 'checkbox', '4', 'Unit'],
            ['M.53.', 'Pelumas pin plough cyl lifting bajak samping', 'checkbox', '1', 'Unit'],
            ['M.54.', 'Pelumas pin plough pengatur bajak samping', 'checkbox', '1', 'Unit'],
            ['M.55.', 'Pelumas pin plough cyl pengatur bajak samping', 'checkbox', '1', 'Unit'],
            ['M.56.', 'Pelumas pin plough bajak pemandu', 'checkbox', '2', 'Unit'],
            ['M.57.', 'Pelumas pin plough cyl bajak pemandu', 'checkbox', '2', 'Unit'],
            ['M.58.', 'Pelumas pin plough ballast konveyor', 'checkbox', '4', 'Unit'],
            ['M.59.', 'Pelumas pin plough sikat ballast', 'checkbox', '2', 'Unit'],
            ['M.60.', 'Pelumas pin plough cyl lifting sikat ballast', 'checkbox', '4', 'Unit'],
            ['M.61.', 'Pelumas pin ballast attachment', 'checkbox', '1', 'Unit'],
            ['M.62.', 'Pelumas dirt repelling agent', 'checkbox', 'Cek', '-'],
            ['M.63.', 'Pelumas pin plough cyl lift conveyor drums', 'checkbox', 'Cek', '-'],
            ['M.64.', 'Wear dan stop plate', 'checkbox', '10', 'Pcs'],
        ]);

        $cat = $this->insertCategory($id, 'Pneumatik & Hydraulik', 3);
        $this->insertItems($cat, [
            ['P.2.', 'Kuras air tangki receiver pneumatik', 'checkbox', '2', 'Pcs'],
            ['P.4.', 'Pelumas pneumatic', 'numeric', '0.2', 'Lt'],
            ['P.5.', 'Tekanan udara tangki receiver', 'numeric', '9', 'Bar'],
            ['P.6.', 'Tekanan udara pengereman', 'numeric', '3.5-4.0', 'Bar'],
            ['P.7.', 'Tekanan udara kerja', 'numeric', '7-9', 'Bar'],
            ['P.8.', 'Kuras drip cup container pneumatik', 'checkbox', '2', 'Pcs'],
            ['P.13.', 'Tekanan udara drive charge', 'numeric', '5', 'Bar'],
            ['H.1.', 'Pelumas hidrolik', 'numeric', '425', 'Lt'],
            ['H.3.', 'Tekanan charge pompa hidrolik', 'numeric', '30', 'Bar'],
            ['H.4.', 'Tekanan hidrolik pompa jalan', 'numeric', '350-380', 'Bar'],
            ['H.5.', 'Tekanan akumulator hidrolik kerja', 'numeric', '120-150', 'Bar'],
        ]);

        $cat = $this->insertCategory($id, 'Elektrik, Keselamatan & Peralatan', 4);
        $this->insertItems($cat, [
            ['L.1.', 'Main and Buffer Baterai', 'numeric', '24', 'Vdc'],
            ['L.2.', 'Tegangan alternator', 'numeric', '26-28', 'Vdc'],
            ['L.5.', 'Electric Horn', 'checkbox', '3', 'Pcs'],
            ['L.6.', 'GPS Tracker', 'checkbox', 'Cek', '-'],
            ['L.7.', 'Air Conditioner', 'checkbox', 'Cek', '-'],
            ['L.8.', 'Lampu kabin', 'checkbox', '2', 'Pcs'],
            ['L.9.', 'Lampu kerja', 'checkbox', '3', 'Pcs'],
            ['e.', 'Semboyan 20', 'checkbox', '6', 'Pcs'],
            ['f.', 'Semboyan 21', 'checkbox', '4', 'Pcs'],
            ['g.', 'Semboyan 35', 'checkbox', '1', 'Pcs'],
            ['h.', 'Perangkat wiper', 'checkbox', '3', 'Pcs'],
            ['j.', 'Stop block', 'checkbox', '2', 'Pcs'],
            ['k.', 'Radio Komunikasi', 'checkbox', '2', 'Pcs'],
            ['l.', 'Alat pemadam api ringan', 'checkbox', '1', 'Pcs'],
            ['m.', 'Dongkrak mekanik 1.5t', 'checkbox', '1', 'Pcs'],
            ['n.', 'Derailment utility', 'checkbox', '2', 'Pcs'],
            ['o.', 'Balok kayu stable', 'checkbox', '2', 'Pcs'],
        ]);
    }

    // =========================================================================
    // 4. P3 PBR U-RS (LENGKAP - SESUAI PDF PAGE 1 & 2)
    // =========================================================================
    private function createP3PBR()
    {
        $id = $this->insertPedoman('P3 PBR U-RS', 'Pedoman Perawatan 3 Bulanan PBR U-RS');

        // ENGINE
        $cat = $this->insertCategory($id, 'Engine (Maintenance)', 1);
        $this->insertItems($cat, [
            ['E.1.', 'Bahan bakar mesin diesel', 'numeric', '1100', 'Lt'],
            ['E.2.', 'Pelumas mesin diesel deutz', 'numeric', '33', 'Lt'],
            ['E.3.', 'Tekanan pelumas mesin diesel deutz', 'numeric', '2-6', 'Bar'],
            ['E.4.', 'Suhu operasi mesin diesel deutz', 'numeric', '70-75', '°C'],
            ['E.5.', 'Bocoran pelumas mesin diesel', 'checkbox', 'Tidak ada', '-'],
            ['E.6.', 'Filter pelumas mesin diesel', 'checkbox', 'Ganti', 'Pcs'],
            ['E.8.', 'Karet v-belt', 'checkbox', 'Cek', '-'],
            ['E.9.', 'Filter bahan bakar mesin diesel', 'checkbox', 'Ganti', 'Pcs'],
            ['E.10.', 'Filter udara mesin diesel', 'checkbox', 'Ganti', 'Pcs'],
            ['E.13.', 'Water coolant', 'numeric', '39', 'Lt'],
            ['E.14.', 'Filter pemisah air bahan bakar', 'checkbox', 'Ganti', 'Pcs'],
        ]);

        // MEKANIK
        $cat = $this->insertCategory($id, 'Mekanik & Hydraulik', 2);
        $this->insertItems($cat, [
            ['M.1.', 'Pelumas gearpump', 'numeric', '3.8', 'Lt'],
            ['M.5.', 'Tebal blok kampas rem', 'numeric', '3-6', 'Cm'],
            ['M.6.', 'Coupler', 'numeric', '68-78.5', 'Cm'],
            ['M.8.', 'Brake linings & brake block play', 'numeric', '5-7', 'Mm'],
            ['M.20.', 'Pelumas locking device', 'checkbox', 'Cek', '-'],
            ['M.26.', 'Pelumas axle gearbox 1 dan 2', 'numeric', '7.5', 'Lt'],
            ['M.47.', 'Unit conveyor belt', 'checkbox', 'Cek', '-'],
            ['M.48.', 'Unit bajak ballast', 'checkbox', 'Cek', '-'],
            ['M.49.', 'Unit sikat ballast', 'checkbox', 'Cek', '-'],
            ['M.50-63.', 'Pelumas pin plough (Semua unit)', 'checkbox', 'Grease', 'Unit'],
            ['M.64.', 'Wear dan stop plate', 'checkbox', '10', 'Pcs'],
            ['H.6.', 'Filter hidrolis suction working pump', 'checkbox', 'Ganti', 'Pcs'],
            ['H.12.', 'Filter hidrolis return', 'checkbox', 'Ganti', 'Pcs'],
            ['H.16.', 'Filter aeration', 'checkbox', 'Ganti', 'Pcs'],
        ]);

        // PNEUMATIK
        $cat = $this->insertCategory($id, 'Pneumatik', 3);
        $this->insertItems($cat, [
            ['P.2.', 'Kuras air tangki receiver pneumatik', 'checkbox', '2', 'Pcs'],
            ['P.3-4.', 'Selang & Pelumas pneumatic', 'checkbox', 'Cek', '-'],
            ['P.5.', 'Tekanan udara tangki receiver', 'numeric', '9', 'Bar'],
            ['P.6.', 'Tekanan udara pengereman', 'numeric', '3.5-4.0', 'Bar'],
            ['P.8.', 'Kuras drip cup container pneumatik', 'checkbox', '2', 'Pcs'],
            ['P.13.', 'Tekanan udara drive charge', 'numeric', '5', 'Bar'],
        ]);

        // ELEKTRIK, KESELAMATAN & PERALATAN
        $cat = $this->insertCategory($id, 'Elektrik, Keselamatan & Peralatan', 4);
        $this->insertItems($cat, [
            ['L.1.', 'Main and Buffer Baterai', 'numeric', '24', 'Vdc'],
            ['L.2.', 'Tegangan alternator', 'numeric', '26-28', 'Vdc'],
            ['L.5.', 'Electric Horn', 'checkbox', '3', 'Pcs'],
            ['L.6.', 'GPS Tracker', 'checkbox', 'Cek', '-'],
            ['L.7.', 'Air Conditioner', 'checkbox', 'Cek', '-'],
            ['L.8-9.', 'Lampu kabin & Lampu kerja', 'checkbox', 'Berfungsi', '-'],
            ['c-d.', 'Semboyan 28 & 3', 'checkbox', 'Ada', '-'],
            ['e-g.', 'Semboyan 20, 21, 35', 'checkbox', 'Lengkap', '-'],
            ['h.', 'Perangkat wiper', 'checkbox', 'Lengkap', '-'],
            ['j.', 'Stop block', 'checkbox', 'Ada/2', 'Pcs'],
            ['k.', 'Radio Komunikasi', 'checkbox', 'Berfungsi', '-'],
            ['l.', 'Speedometer', 'checkbox', 'Berfungsi', '-'],
            ['l.', 'APAR', 'checkbox', 'Ada', '-'],
            ['m.', 'Dongkrak mekanik 1.5t', 'checkbox', 'Ada', '-'],
            ['n.', 'Derailment utility', 'checkbox', 'Ada', '-'],
            ['o.', 'Balok kayu stable', 'checkbox', 'Ada', '-'],
        ]);
    }

    // =========================================================================
    // 5. P1 UNIMAT (NEW - LENGKAP)
    // =========================================================================
    private function createP1Unimat()
    {
        $id = $this->insertPedoman('P1 UNIMAT', 'Pedoman Perawatan 1 Bulanan UNIMAT');

        $cat = $this->insertCategory($id, 'Engine', 1);
        $this->insertItems($cat, [
            ['E.1.', 'Bahan bakar mesin diesel', 'numeric', '1000', 'Lt'],
            ['E.2.', 'Pelumas mesin diesel', 'numeric', 'Cek', 'Lt'],
            ['E.3.', 'Tekanan pelumas mesin', 'numeric', '2-5', 'Bar'],
            ['E.4.', 'Suhu operasi mesin', 'numeric', '70-80', '°C'],
            ['E.13.', 'Water coolant', 'numeric', 'Cek', 'Lt'],
        ]);

        $cat = $this->insertCategory($id, 'Mekanik & Tamping', 2);
        $this->insertItems($cat, [
            ['M.1.', 'Pelumas gearpump', 'numeric', 'Cek', 'Lt'],
            ['M.7.', 'Tamping tine', 'checkbox', '12', 'Pcs'],
            ['M.33.', 'Pelumas shaft bearings', 'checkbox', 'Grease', '-'],
            ['M.34.', 'Pelumas pivot bearing', 'checkbox', 'Grease', '-'],
            ['M.35.', 'Pelumas sliding surface', 'checkbox', 'Grease', '-'],
        ]);

        $cat = $this->insertCategory($id, 'Hydraulik & Pneumatik', 3);
        $this->insertItems($cat, [
            ['P.5.', 'Tekanan udara tangki receiver', 'numeric', '8-9', 'Bar'],
            ['P.6.', 'Tekanan udara pengereman', 'numeric', '3.5-4.0', 'Bar'],
            ['H.1.', 'Pelumas hidrolik', 'numeric', 'Cek', 'Lt'],
            ['H.5.', 'Tekanan akumulator hidrolis', 'numeric', '100-150', 'Bar'],
        ]);

        $cat = $this->insertCategory($id, 'Elektrik & Keselamatan', 4);
        $this->insertItems($cat, [
            ['L.1.', 'Baterai', 'numeric', '24', 'Vdc'],
            ['L.5.', 'Electric Horn', 'checkbox', 'Berfungsi', '-'],
            ['-', 'Lampu kerja & Kabin', 'checkbox', 'Berfungsi', '-'],
            ['-', 'Semboyan 21 & 35', 'checkbox', 'Lengkap', '-'],
            ['-', 'Wiper', 'checkbox', 'Berfungsi', '-'],
            ['-', 'APAR & P3K', 'checkbox', 'Ada', '-'],
        ]);
    }

    // =========================================================================
    // 6. P6 UNIMAT (COMPLEX RODA)
    // =========================================================================
    private function createP6Unimat()
    {
        $id = $this->insertPedoman('P6 UNIMAT', 'Pedoman Perawatan 6 Bulanan UNIMAT');

        $cat = $this->insertCategory($id, 'Standarisasi Pengukuran', 1);

        DB::table('master_pedoman_items')->insert([
            'master_pedoman_category_id' => $cat,
            'nomor_poin' => null,
            'deskripsi' => 'Diagram Standar Pengukuran Roda',
            'tipe_input' => 'image',
            'gambar_referensi_path' => 'diagram-roda-default.png',
            'created_at' => $this->now, 'updated_at' => $this->now,
        ]);

        $config = json_encode([
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
            'master_pedoman_category_id' => $cat,
            'nomor_poin' => '1.',
            'deskripsi' => 'Tabel Pengukuran Profil Roda',
            'tipe_input' => 'table',
            'extra_config' => $config,
            'created_at' => $this->now, 'updated_at' => $this->now,
        ]);
    }

    // =========================================================================
    // HELPER FUNCTIONS
    // =========================================================================

    private function createPlaceholder($kode, $nama)
    {
        $id = $this->insertPedoman($kode, $nama);
        $cat = $this->insertCategory($id, 'Umum', 1);
        $this->insertItems($cat, [
            ['1.', 'Item ini belum diisi. Silakan edit di Menu Master Data.', 'checkbox', '', '']
        ]);
    }

    private function insertPedoman($kode, $nama)
    {
        return DB::table('master_pedoman')->insertGetId([
            'kode_pedoman' => $kode,
            'nama_pedoman' => $nama,
            'created_at' => $this->now, 'updated_at' => $this->now,
        ]);
    }

    private function insertCategory($pedomanId, $name, $order)
    {
        return DB::table('master_pedoman_categories')->insertGetId([
            'master_pedoman_id' => $pedomanId,
            'name' => $name,
            'order' => $order,
            'created_at' => $this->now, 'updated_at' => $this->now,
        ]);
    }

    private function insertItems($categoryId, $items)
    {
        foreach ($items as $i) {
            DB::table('master_pedoman_items')->insert([
                'master_pedoman_category_id' => $categoryId,
                'nomor_poin' => $i[0],
                'deskripsi' => $i[1],
                'tipe_input' => $i[2],
                'standar_nilai' => $i[3],
                'satuan' => $i[4],
                'created_at' => $this->now, 'updated_at' => $this->now,
            ]);
        }
    }
}
