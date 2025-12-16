<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CheckSheetMasterDay;

class CheckSheetMasterDaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['group_name' => 'Mekanik', 'urutan' =>'M.5.', 'komponen' => 'Tebal blok kampas rem', 'rujukan' => null, 'nilai_rujukan' => '3 - 6', 'satuan' => 'Cm', 'jenis_mesin' => 'PBR'],
            ['group_name' => 'Mekanik', 'urutan' =>'M.6.', 'komponen' => 'Coupler', 'rujukan' => null, 'nilai_rujukan' => '68 - 70,5', 'satuan' => 'Cm', 'jenis_mesin' => 'PBR'],
            ['group_name' => 'Mekanik', 'urutan' =>'M.47.', 'komponen' => 'Unit conveyor belt', 'rujukan' => null, 'nilai_rujukan' => null, 'satuan' => null, 'jenis_mesin' => 'PBR'],
            ['group_name' => 'Mekanik', 'urutan' => 'M.48.', 'komponen' => 'Unit bajak tengah ballast', 'rujukan' => null, 'nilai_rujukan' => null, 'satuan' => null, 'jenis_mesin' => 'PBR'],
            ['group_name' => 'Mekanik', 'urutan' => 'M.49.', 'komponen' => 'Unit bajak samping ballast', 'rujukan' => null, 'nilai_rujukan' => null, 'satuan' => null, 'jenis_mesin' => 'PBR'],
            ['group_name' => 'Mekanik', 'urutan' => 'M.50.', 'komponen' => 'Unit sikat ballast', 'rujukan' => null, 'nilai_rujukan' => null, 'satuan' => null, 'jenis_mesin' => 'PBR'],
            ['group_name' => 'Mekanik', 'urutan' => 'M.51.', 'komponen' => 'Pengunci pengaman (bajak tengah, bajak samping, sikat, dan conveyor)', 'rujukan' => 'Ada / Lengkap', 'nilai_rujukan' => null, 'satuan' => null, 'jenis_mesin' => 'PBR'],

            ['group_name' => 'Pneumatik', 'urutan' =>'P.2.', 'komponen' => 'Kuras air tangki receiver pneumatik', 'rujukan' => null, 'nilai_rujukan' => '2', 'satuan' => 'Pcs', 'jenis_mesin' => 'PBR'],
            ['group_name' => 'Pneumatik', 'urutan' =>'P.3.', 'komponen' => 'Selang dan pipa-pipa distribusi', 'rujukan' => null, 'nilai_rujukan' => null, 'satuan' => null, 'jenis_mesin' => 'PBR'],
            ['group_name' => 'Pneumatik', 'urutan' =>'P.4.', 'komponen' => 'Pelumas pneumatic', 'rujukan' => 'Tellus S3 V 46', 'nilai_rujukan' => '0,2', 'satuan' => 'Lt', 'jenis_mesin' => 'PBR'],
            ['group_name' => 'Pneumatik', 'urutan' => 'P.5.', 'komponen' => 'Tekanan udara tangki receiver', 'rujukan' => null, 'nilai_rujukan' => '6,5 - 9', 'satuan' => 'Bar', 'jenis_mesin' => 'PBR'],
            ['group_name' => 'Pneumatik', 'urutan' => 'P.6.', 'komponen' => 'Tekanan udara pengereman', 'rujukan' => null, 'nilai_rujukan' => '3,5 - 4,0', 'satuan' => 'Bar', 'jenis_mesin' => 'PBR'],
            ['group_name' => 'Pneumatik', 'urutan' => 'P.7.', 'komponen' => 'Tekanan udara kerja', 'rujukan' => null, 'nilai_rujukan' => '7 - 9', 'satuan' => 'Bar', 'jenis_mesin' => 'PBR'],
            ['group_name' => 'Pneumatik', 'urutan' => 'P.13.', 'komponen' => 'Tekanan udara drive charge', 'rujukan' => null, 'nilai_rujukan' => '5', 'satuan' => 'Bar', 'jenis_mesin' => 'PBR'],
            
            ['group_name' => 'Hydraulik', 'urutan' =>'H.1.', 'komponen' => 'minyak pelumas hidrolik', 'rujukan' => 'Tellus S3 V 46', 'nilai_rujukan' => '425', 'satuan' => 'Lt', 'jenis_mesin' => 'PBR'],
            ['group_name' => 'Hydraulik', 'urutan' =>'H.2.', 'komponen' => 'Selang dan pipa-pipa distribusi', 'rujukan' => null, 'nilai_rujukan' => null, 'satuan' => null, 'jenis_mesin' => 'PBR'],
            ['group_name' => 'Hydraulik', 'urutan' =>'H.3.', 'komponen' => 'Tekanan charge pompa hidrolis', 'rujukan' => 'Tellus S3 V 46', 'nilai_rujukan' => '30', 'satuan' => 'Bar', 'jenis_mesin' => 'PBR'],
            ['group_name' => 'Hydraulik', 'urutan' => 'H.4.', 'komponen' => 'Tekanan hidrolis pompa jalan', 'rujukan' => null, 'nilai_rujukan' => '350 - 380', 'satuan' => 'Bar', 'jenis_mesin' => 'PBR'],
            ['group_name' => 'Hydraulik', 'urutan' => 'H.5.', 'komponen' => 'Tekanan akumulator hidrolis kerja', 'rujukan' => null, 'nilai_rujukan' => '120 - 170', 'satuan' => 'Bar', 'jenis_mesin' => 'PBR'],
            ['group_name' => 'Hydraulik', 'urutan' => 'H.6.', 'komponen' => 'Suhu pelumas hidrolik kerja', 'rujukan' => null, 'nilai_rujukan' => '20 - 85', 'satuan' => '°C', 'jenis_mesin' => 'PBR'],
            ['group_name' => 'Hydraulik', 'urutan' => 'H.7.', 'komponen' => 'Sistem penggerak jalan traveling dan working', 'rujukan' => 'Berfungsi / Lengkap', 'nilai_rujukan' => null, 'satuan' => null, 'jenis_mesin' => 'PBR'],
            
            ['group_name' => 'Elektrik', 'urutan' =>'L.1.', 'komponen' => 'Tegangan baterai', 'rujukan' => null, 'nilai_rujukan' => '24', 'satuan' => 'Vdc', 'jenis_mesin' => 'PBR'],
            ['group_name' => 'Elektrik', 'urutan' =>'L.2.', 'komponen' => 'Tegangan alternator', 'rujukan' => null, 'nilai_rujukan' => '26 - 28', 'satuan' => 'Vdc', 'jenis_mesin' => 'PBR'],
            ['group_name' => 'Elektrik', 'urutan' =>'L.4.', 'komponen' => 'Jam mesin operasi', 'rujukan' => null, 'nilai_rujukan' => null, 'satuan' => null, 'jenis_mesin' => 'PBR'],
            ['group_name' => 'Elektrik', 'urutan' => 'L.5.', 'komponen' => 'Electric Horn', 'rujukan' => null, 'nilai_rujukan' => '3', 'satuan' => 'Pcs', 'jenis_mesin' => 'PBR'],
            ['group_name' => 'Elektrik', 'urutan' => 'L.6.', 'komponen' => 'Gps tracker', 'rujukan' => null, 'nilai_rujukan' => '1', 'satuan' => 'Pcs', 'jenis_mesin' => 'PBR'],
            
            ['group_name' => 'Peralatan Keselamatan', 'urutan' => 'a.', 'komponen' => 'Lampu kabin', 'rujukan' => 'Berfungsi / Lengkap', 'nilai_rujukan' => '2', 'satuan' => 'Pcs', 'jenis_mesin' => 'PBR'],
            ['group_name' => 'Peralatan Keselamatan', 'urutan' => 'b.', 'komponen' => 'Lampu kerja', 'rujukan' => 'Berfungsi / Lengkap', 'nilai_rujukan' => '3', 'satuan' => 'Pcs', 'jenis_mesin' => 'PBR'],
            ['group_name' => 'Peralatan Keselamatan', 'urutan' => 'c.', 'komponen' => 'Perangkat wiper', 'rujukan' => 'Ada / Berfungsi / Lengkap', 'nilai_rujukan' => '2', 'satuan' => 'Pcs', 'jenis_mesin' => 'PBR'],
            ['group_name' => 'Peralatan Keselamatan', 'urutan' => 'd.', 'komponen' => 'Perangkat radio komunikasi', 'rujukan' => 'Ada / Berfungsi', 'nilai_rujukan' => '1', 'satuan' => 'Pcs', 'jenis_mesin' => 'PBR'],
            ['group_name' => 'Peralatan Keselamatan', 'urutan' => 'e.', 'komponen' => 'Alat pelindung diri', 'rujukan' => 'Ada', 'nilai_rujukan' => '6', 'satuan' => 'Pcs', 'jenis_mesin' => 'PBR'],
            ['group_name' => 'Peralatan Keselamatan', 'urutan' => 'f.', 'komponen' => 'Semboyan 2B (Bendera kuning)', 'rujukan' => 'Ada / Lengkap', 'nilai_rujukan' => '4', 'satuan' => 'Pcs', 'jenis_mesin' => 'PBR'],
            ['group_name' => 'Peralatan Keselamatan', 'urutan' => 'g.', 'komponen' => 'Semboyan 3 (Bendera merah)', 'rujukan' => 'Ada / Lengkap', 'nilai_rujukan' => '1', 'satuan' => 'Pcs', 'jenis_mesin' => 'PBR'],
            ['group_name' => 'Peralatan Keselamatan', 'urutan' => 'h.', 'komponen' => 'Semboyan 20 (Lampu sorot jalan)', 'rujukan' => 'Berfungsi / Lengkap', 'nilai_rujukan' => '3', 'satuan' => 'Pcs', 'jenis_mesin' => 'PBR'],
            ['group_name' => 'Peralatan Keselamatan', 'urutan' => 'i.', 'komponen' => 'Semboyan 21 (Siang & malam)', 'rujukan' => 'Ada / Berfungsi / Lengkap', 'nilai_rujukan' => '2', 'satuan' => 'Pcs', 'jenis_mesin' => 'PBR'],
            ['group_name' => 'Peralatan Keselamatan', 'urutan' => 'j.', 'komponen' => 'Semboyan 35', 'rujukan' => 'Ada / Berfungsi', 'nilai_rujukan' => '2', 'satuan' => 'Pcs', 'jenis_mesin' => 'PBR'],
            ['group_name' => 'Peralatan Keselamatan', 'urutan' => 'k.', 'komponen' => 'Alat pemadam api ringan', 'rujukan' => 'Ada / Belum kadaluarsa', 'nilai_rujukan' => '1', 'satuan' => 'Pcs', 'jenis_mesin' => 'PBR'],
            ['group_name' => 'Peralatan Keselamatan', 'urutan' => 'l.', 'komponen' => 'Stop block', 'rujukan' => 'Ada / Lengkap', 'nilai_rujukan' => '4', 'satuan' => 'Pcs', 'jenis_mesin' => 'PBR'],
            ['group_name' => 'Peralatan Keselamatan', 'urutan' => 'm.', 'komponen' => 'Dongkrak mekanik', 'rujukan' => 'Ada / Berfungsi', 'nilai_rujukan' => '1', 'satuan' => 'Pcs', 'jenis_mesin' => 'PBR'],
            ['group_name' => 'Peralatan Keselamatan', 'urutan' => 'n.', 'komponen' => 'Rerailment utility (Untuk KPJR diatas tahun 2020)', 'rujukan' => 'Ada / Berfungsi', 'nilai_rujukan' => '2', 'satuan' => 'Pcs', 'jenis_mesin' => 'PBR'],
            ['group_name' => 'Peralatan Keselamatan', 'urutan' => 'o.', 'komponen' => 'Balok kayu stable', 'rujukan' => 'Ada / Lengkap', 'nilai_rujukan' => '4', 'satuan' => 'Pcs', 'jenis_mesin' => 'PBR'],
            
            
        ];

        foreach ($data as $item) {
            CheckSheetMasterDay::create($item);
        }
    }
}