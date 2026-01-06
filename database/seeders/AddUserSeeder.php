<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;

class AddUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // === PARAMETER KONFIGURASI UMUM ===
        $defaultPassword = 'password';
        $hashedPassword = Hash::make($defaultPassword);
        
        $defaultRegionId = null; 
        
        // ID POSISI:
        $kepalaUptPositionId = 1;       // Kepala UPT Mekanik
        $kepalaOperatorPositionId = 2; // Kepala Operator KPJR
        $operatorPositionId = 3;       // Operator KPJR
        $calonOperatorKpjrPositionId = 4; // CALON Operator KPJR 
        $calonOperatorKpjrbPositionId = 5; // CALON Operator KPJRB 
        
        // ID ROLE:
        $adminRoleId = 1;     // ASUMSI: ID Role untuk Admin/Kepala
        $operatorRoleId = 2; // ASUMSI: ID Role untuk 'Operator'
        // ==================================
        
        // Fungsi pembantu untuk membuat format email
        $createEmail = function ($name) {
            // Ubah ke huruf kecil, hilangkan spasi/tanda baca lain yang tidak perlu, dan tambahkan domain
            $sluggedName = strtolower(preg_replace('/[^a-z0-9]/i', '', $name));
            return $sluggedName . '@gmail.com';
        };

        // --- DATA SET 1: UPT Mekanik JJ Koridor 1 (division_id = 1) - Operator KPJR (pos_id=3) ---
        $division1Id = 1;
        $usersData1 = [
            ['name' => 'Agus Rachmat Gumilar', 'username' => '48770'],
            ['name' => 'Sunaji', 'username' => '48880'],
            ['name' => 'Eko Suharyanto', 'username' => '48931'],
            ['name' => 'Muhammad Kurniawan', 'username' => '52623'],
            ['name' => 'Dwi Adi Wisasono', 'username' => '54646'],
            ['name' => 'Hadi Warsito', 'username' => '54665'],
            ['name' => 'Roni Wahyudi', 'username' => '54707'],
            ['name' => 'Tri Wahyu Bagio Utomo', 'username' => '58359'],
            ['name' => 'Agus Supriatno', 'username' => '58370'],
            ['name' => 'Nuur Rochman', 'username' => '58729'],
            ['name' => 'Fendi Nugroho', 'username' => '62393'],
            ['name' => 'Satriya Agung Renaldi', 'username' => '64569'],
            ['name' => 'ARI YUNIANT0', 'username' => '76862'],
            ['name' => 'HENGKI PRATAMA', 'username' => '75670'],
            ['name' => 'HEDI WARSITO', 'username' => '75669'],
            ['name' => 'CAKRA ARDIANSYAH', 'username' => '74358'],
            ['name' => 'RAIHAN RIZKI NUSANTARA', 'username' => '76871'],
            ['name' => 'MUHAMMAD ADIAN ILHAM', 'username' => '75680'],
        ];

        // --- DATA SET 2: UPT Mekanik JJ Koridor 2 (division_id = 3) - Operator KPJR (pos_id=3) ---
        $division3Id = 3;
        $usersData2 = [
            ['name' => 'Hendry Baskoro', 'username' => '54419'], 
            ['name' => 'Oman', 'username' => '54422'],
            ['name' => 'Deni Harfino', 'username' => '58353'],
            ['name' => 'Febrianhal', 'username' => '62189'],
            ['name' => 'Yohantino', 'username' => '66187'],
            ['name' => 'Wahyu Sasonojati', 'username' => '66189'],
            ['name' => 'Chaelani', 'username' => '66190'],
            ['name' => 'Muhammad Fadh Rizky Multazam', 'username' => '67969'],
            ['name' => 'BAGUS ANGGARA SAPUTRA', 'username' => '73290'],
            ['name' => 'FEBRIANSYAH SAPUTRA', 'username' => '71094'],
        ];

        // --- DATA SET 3: Daop 2 Bandung (division_id = 4) - Operator KPJR (pos_id=3) ---
        $division4Id = 4;
        $usersData3 = [
            ['name' => 'AKHMAD GIANTO', 'username' => '58798'],
            ['name' => 'ANDRIE MUNANDAR P.', 'username' => '58799'],
            ['name' => 'AGUS ROHIM', 'username' => '58808'],
            ['name' => 'UUS NOPIANSYAH', 'username' => '60949'],
            ['name' => 'AGUNG ANANTA', 'username' => '61841'],
            ['name' => 'FUJI KURNIAWAN', 'username' => '61269'],
            ['name' => 'ERIK SENJAYA', 'username' => '61276'],
            ['name' => 'DIAN RUSDIAN', 'username' => '61285'],
            ['name' => 'YODI SOMANTRI', 'username' => '63897'],
            ['name' => 'BAYU YUSWANTORO CAHYADI', 'username' => '66193'],
            ['name' => 'DIDIK TRIAWAN', 'username' => '66195'],
            ['name' => 'DANANG AJI PANGESTU', 'username' => '66196'],
            ['name' => 'SANDY WILANTARA', 'username' => '66198'],
            ['name' => 'MUHAMAD HANAPI', 'username' => '71932'],
            ['name' => 'ARIONDRA', 'username' => '75083'],
        ];

        // --- DATA SET 4: Daop 2 Bandung (division_id = 4) - Calon Operator KPJR (pos_id=4) ---
        $division4Id_Calon = 4;
        $usersData4 = [
            ['name' => 'DERYL SYAWAL LIANSYAH', 'username' => '75707'],
            ['name' => 'FARIDLO YUANA SATRIA', 'username' => '75710'],
            ['name' => 'GABRIEL YUDHA RIVEN YUN', 'username' => '75713'],
            ['name' => 'NAUFAL ADI PRAMUDITA', 'username' => '75720'],
            ['name' => 'PAMBUDIA WASISTA', 'username' => '75721'],
            ['name' => 'RAZAQ ILHAM SUPARMAN', 'username' => '75723'],
            ['name' => 'FERDIANSYAH SAPUTRA', 'username' => '75712'],
            ['name' => 'ARIF ZAENAL MUHTAROM', 'username' => '76114'],
            ['name' => 'AGIS', 'username' => '77785'],
        ];

        // --- DATA SET 5: Daop 3 Cirebon (division_id = 5) - Kepala UPT Mekanik (pos_id=1) ---
        $division5Id = 5;
        $usersData5 = [
            ['name' => 'MOMO DASMA', 'username' => '64539'],
        ];

        // --- DATA SET 6: Daop 3 Cirebon (division_id = 5) - Kepala Operator KPJR (pos_id=2) ---
        $usersData6 = [
            ['name' => 'Risdiawan Hartanto', 'username' => '64527'],
        ];
        
        // --- DATA SET 7: Daop 3 Cirebon (division_id = 5) - Operator KPJR (pos_id=3) ---
        $usersData7 = [
            ['name' => 'Abdul Goparudin', 'username' => '62269'],
            ['name' => 'Andria Pradana Aditya', 'username' => '64511'],
            ['name' => 'Mulyadi', 'username' => '48895'],
            ['name' => 'Sutarwad', 'username' => '58856'],
            ['name' => 'Adris', 'username' => '50667'],
            ['name' => 'Eric Sanjaya', 'username' => '55183'],
            ['name' => 'Hasanudin', 'username' => '55109'],
            ['name' => 'Danu Permadi', 'username' => '55100'],
            ['name' => 'Mulyo Slamet', 'username' => '58861'],
            ['name' => 'Bagus Ryan Amandita', 'username' => '55094'],
            ['name' => 'Lili Mauludin', 'username' => '55116'],
            ['name' => 'Mohamad Sholeh Rifa\'i', 'username' => '55120'],
            ['name' => 'Agus Daryadi', 'username' => '55082'],
            ['name' => 'Saphan Muliawan', 'username' => '58858'],
            ['name' => 'Yuni Siswanto', 'username' => '55168'],
            ['name' => 'Ghea Khrisna Hararegar', 'username' => '55108'],
            ['name' => 'Deni Kusmawan', 'username' => '58857'],
            ['name' => 'Jaja Nurjaman', 'username' => '55111'],
            ['name' => 'Mahadi', 'username' => '55118'],
            ['name' => 'SUHENDRA', 'username' => '58860'],
            ['name' => 'Sucipto', 'username' => '55087'],
            ['name' => 'Andri Kusnadi', 'username' => '55089'],
            ['name' => 'Dadi', 'username' => '48088'],
            ['name' => 'Soimin', 'username' => '66199'],
            ['name' => 'Imam Agus Faisal', 'username' => '66201'],
            ['name' => 'Agus Triana', 'username' => '52670'],
            ['name' => 'Agung Tri Jayanto Iskandar', 'username' => '66200'],
            ['name' => 'Aditya Imam Rachmatullah', 'username' => '66203'],
            ['name' => 'Amanda Kautsar', 'username' => '66202'],
            ['name' => 'Subani', 'username' => '50677'],
        ];

        // --- DATA SET 8: Daop 3 Cirebon (division_id = 5) - Calon Operator KPJRB (pos_id=5) ---
        $usersData8 = [
            ['name' => 'HANUNG ADI LAKSONO', 'username' => '76891'],
        ];

        // --- DATA SET 9: Daop 4 Semarang (division_id = 6) - CAMPURAN POSISI (Kepala UPT, Kepala Operator, Operator) ---
        $division6Id = 6;
        $usersData9_All = [
            ['name' => 'FAIQ WILDAN HANIF', 'username' => '64365'], // Pos 1 (Kepala UPT Mekanik)
            ['name' => 'SIGIT ISNAENI', 'username' => '59163'], // Pos 2 (Kepala Operator KPJR)
            ['name' => 'AGUS PRASETYO', 'username' => '55334'], // Pos 3 (Operator KPJR)
            ['name' => 'KHOTIB ASHOBRI', 'username' => '48840'], 
            ['name' => 'SUSILO', 'username' => '59137'], 
            ['name' => 'YOGI AJI PRAKOSA', 'username' => '55459'], 
            ['name' => 'RAJIKAN', 'username' => '55431'], 
            ['name' => 'HENGGAR SURYA PRADIPTA', 'username' => '63956'], 
            ['name' => 'M TAUFIK', 'username' => '55417'], 
            ['name' => 'MEINAR CATUR SETIANTO', 'username' => '55406'], 
            ['name' => 'ANDIKA PRISMA FEBRUAN', 'username' => '66208'], 
            ['name' => 'KARUNIA BAHTIAR YUSUF', 'username' => '66209'], 
            ['name' => 'GIRI RAHENDRA', 'username' => '66207'], 
            ['name' => 'RIYAN DWI UTOMO', 'username' => '58956'], 
            ['name' => 'MARWAN WICAKSONO', 'username' => '55271'], 
            ['name' => 'ISMOYO HADI SETIAWAN', 'username' => '55395'], 
            ['name' => 'KUS HARIYANTO', 'username' => '55401'], 
            ['name' => 'BUDI NUGROHO', 'username' => '53530'], 
            ['name' => 'ANDI GIRI PRABOWO', 'username' => '55350'], 
            ['name' => 'SISWANTORO', 'username' => '64487'], 
            ['name' => 'M HERMAWAN', 'username' => '55414'], 
            ['name' => 'SATRIYA ADHI HANGGORO', 'username' => '64528'], 
            ['name' => 'DWI TANTO', 'username' => '63935'], 
            ['name' => 'SURYO IBROHIM', 'username' => '55447'], 
            ['name' => 'M JAYIDU SYAFI\'I', 'username' => '66204'], 
            ['name' => 'GALUH ARDIYANTO', 'username' => '66395'], 
            ['name' => 'AGUS MUGIYANTO', 'username' => '63946'], 
            ['name' => 'FEBRI EKO PRIYANTO', 'username' => '63952'], 
            ['name' => 'DONI FATAH', 'username' => '55296'], 
            ['name' => 'SETIAJI PRAMUDIBYO', 'username' => '49704'], 
            ['name' => 'IRAWAN', 'username' => '63938'], 
            ['name' => 'BAGUS NUGROHO', 'username' => '53529'], 
            ['name' => 'DONY SUKMA TRI H', 'username' => '55369'], 
            ['name' => 'TRI AGUS PUJI YANTO', 'username' => '53513'], 
            ['name' => 'HERU KURNIAWAN', 'username' => '53536'], 
            ['name' => 'MUCH ASEP YUSUF', 'username' => '59044'], 
            ['name' => 'PRISTIANDIKA TRESNANSAH', 'username' => '55314'], 
            ['name' => 'MOCHAMAD YANUAR NUR ADI', 'username' => '64540'], 
        ];
        
        // --- DATA SET 10: Daop 4 Semarang (division_id = 6) - Calon Operator KPJR (pos_id=4) ---
        $usersData10 = [
            ['name' => 'MUHAMMAD ALIF AKBAR', 'username' => '75752'],
        ];

        // --- DATA SET 11: Daop 5 Purwokerto (division_id = 7) - CAMPURAN POSISI (Kepala UPT, Kepala Operator, Operator) ---
        $division7Id = 7;
        $usersData11_All = [
            ['name' => 'FAHMI HILMANSYAH', 'username' => '64366'], // Kepala UPT Mekanik
            ['name' => 'SUKARSO', 'username' => '48672'], // Kepala Operator KPJR
            ['name' => 'SUPRIYATNO', 'username' => '48892'], // Operator KPJR
            ['name' => 'IMAM MAKSUM', 'username' => '48927'],
            ['name' => 'TEGUH PURWOKO', 'username' => '49028'],
            ['name' => 'BUDI RAHMAD MULYONO', 'username' => '51243'],
            ['name' => 'ASEP SARIFUDIN', 'username' => '51265'],
            ['name' => 'FURY DWI SANTOSA', 'username' => '53239'],
            ['name' => 'WAHIDIN', 'username' => '55543'],
            ['name' => 'ANDRI SUSANTO', 'username' => '55646'],
            ['name' => 'CATUR WAHYUDI', 'username' => '55656'],
            ['name' => 'EKO RIYONO SAALAM', 'username' => '55666'],
            ['name' => 'HENDRAWAN RINDA AGASTIAN', 'username' => '55686'],
            ['name' => 'MAKSUM', 'username' => '59158'],
            ['name' => 'JOKO RIYANTO', 'username' => '59159'],
            ['name' => 'A AN SUGIANTO', 'username' => '59160'],
            ['name' => 'RONI PUJIYONO', 'username' => '59161'],
            ['name' => 'HARI APRIJADI', 'username' => '59162'],
            ['name' => 'AGUS NURWANTORO', 'username' => '59164'],
            ['name' => 'WAHYU PRIYA UTAMA', 'username' => '59182'],
            ['name' => 'ROMIDI', 'username' => '59183'],
            ['name' => 'RIANTO', 'username' => '59187'],
            ['name' => 'HENDI ARIFIANTO', 'username' => '59211'],
            ['name' => 'DARYONO', 'username' => '59220'],
            ['name' => 'GANJAR PUNGKAS SUPARBOWO', 'username' => '59222'],
            ['name' => 'LUTFI BUDIANTO', 'username' => '61352'],
            ['name' => 'SAEFUL ARIFIN', 'username' => '61353'],
            ['name' => 'MUNA PUTRA', 'username' => '64477'],
            ['name' => 'GUNAWAN', 'username' => '64518'],
            ['name' => 'LUCKY IRAWAN', 'username' => '64519'],
            ['name' => 'HEPSA INU KERTOPATI', 'username' => '66210'],
            ['name' => 'DADANG SOBARUDIN', 'username' => '67666'],
            ['name' => 'IRFAN BUDIANTO', 'username' => '68275'],
            ['name' => 'SOFIYANTO', 'username' => '68332'],
            ['name' => 'RISMANTO', 'username' => '68348'],
            ['name' => 'PURNOMO DWI CAHYONO', 'username' => '69420'],
        ];

        // --- DATA SET 12: Daop 6 Yogyakarta (division_id = 8) - CAMPURAN POSISI (Kepala UPT, Kepala Operator, Operator) ---
        $division8Id = 8;
        $usersData12_All = [
            ['name' => 'YOTO SUTARJO', 'username' => '46440'], // Pos 1 (Kepala UPT Mekanik)
            ['name' => 'AGUNG RAHMADI', 'username' => '48779'], // Pos 2 (Kepala Operator KPJR)
            ['name' => 'RACHMAT RAGIL HENDRAWAN', 'username' => '69063'], // Pos 3 (Operator KPJR)
            ['name' => 'TAUFIK ISKANDAR', 'username' => '68049'], 
            ['name' => 'MOCHAMAD ERFIN IMANSYAH', 'username' => '66219'],
            ['name' => 'HASAN SITO SAHO', 'username' => '66218'],
            ['name' => 'NANANG WAHONO', 'username' => '55931'],
            ['name' => 'TRIYANTO', 'username' => '51994'],
            ['name' => 'AGUNG PURNOMO', 'username' => '50164'],
            ['name' => 'WAHYU ARI WIBOWO', 'username' => '55866'],
            ['name' => 'MUHAMMAD KHOIRU AL MALIK', 'username' => '55929'],
            ['name' => 'ASEP SUKINDRA', 'username' => '55827'],
            ['name' => 'ERWIN SETIAWAN', 'username' => '50318'],
            ['name' => 'ERDIYAN SUJARWADI', 'username' => '48420'],
            ['name' => 'ADITYA ODY SAPUTRA', 'username' => '53464'],
            ['name' => 'TEGUH TRI WIBOWO', 'username' => '59378'],
            ['name' => 'ARI MURWANTO', 'username' => '59376'],
            ['name' => 'MARLIANUS HARJOMO', 'username' => '59375'],
            ['name' => 'ACHMAD ZAINUDIN', 'username' => '59352'],
            ['name' => 'ABDUL MUNASIR', 'username' => '59351'],
            ['name' => 'CONY SEPTRIATWAN', 'username' => '59348'],
            ['name' => 'NURYANTO', 'username' => '59346'],
            ['name' => 'HENDRO WINOTO', 'username' => '59322'],
            ['name' => 'BAMBANG AGUS MARGONO', 'username' => '59314'],
            ['name' => 'EFFENDI', 'username' => '59292'],
            ['name' => 'AMINTO', 'username' => '59255'],
            ['name' => 'ARIS APRIYANTO', 'username' => '59254'],
            ['name' => 'ANDRI RAHMAT HIDAYAT', 'username' => '59253'],
            ['name' => 'SUYAT', 'username' => '59252'],
        ];

        // --- DATA SET 13: Daop 7 Madiun (division_id = 9) - CAMPURAN POSISI (Kepala UPT, Kepala Operator, Operator) ---
        $division9Id = 9;
        $usersData13_All = [
            ['name' => 'AHID ABDUL RAHMAN', 'username' => '64539'],     // Index 0 -> Pos 1 (Kepala UPT Mekanik)
            ['name' => 'MUH WACHID ARYANTO', 'username' => '59251'],   // Index 1 -> Pos 2 (Kepala Operator KPJR)
            ['name' => 'Sukadi', 'username' => '43458'],               // Index 2 -> Pos 3 (Operator KPJR)
            ['name' => 'Edwin Yulinata', 'username' => '50497'],
            ['name' => 'Wahyu Widodo', 'username' => '53074'],
            ['name' => 'Frendy Atama Sukamdono', 'username' => '53082'],
            ['name' => 'Topan Wasono', 'username' => '49653'],
            ['name' => 'Eko Yulianto', 'username' => '56041'],
            ['name' => 'Dedi Yuliono', 'username' => '50218'],
            ['name' => 'Bintar Sidiq', 'username' => '50287'],
            ['name' => 'Dani Trisnanto', 'username' => '53129'],
            ['name' => 'Hadi Rusnanto', 'username' => '59507'],
            ['name' => 'Septyan Aji Santoso', 'username' => '62301'],
            ['name' => 'Suraji', 'username' => '51155'],
            ['name' => 'Gondo Prastiyo', 'username' => '53102'],
            ['name' => 'Dhany Indra Maharadhi', 'username' => '59573'],
            ['name' => 'Annang Wulan Doko', 'username' => '59506'],
            ['name' => 'Puryanto', 'username' => '59542'],
            ['name' => 'Fauzi Andre Irawan', 'username' => '66229'],
            ['name' => 'M. Bahari Dirgantara Putera', 'username' => '66227'],
            ['name' => 'Ridwan Sholihin', 'username' => '66228'],
            ['name' => 'Whendo Aswin Biyantara', 'username' => '66223'],
            ['name' => 'Dika Riswanda', 'username' => '70838'],
            ['name' => 'Erik Tri Susilo', 'username' => '66224'],
            ['name' => 'Yanuar Purnama Kusuma', 'username' => '66225'],
            ['name' => 'Novi Prasetyo', 'username' => '58800'],
        ];

        // --- DATA SET 14: Daop 7 Madiun (division_id = 9) - Calon Operator KPJR (pos_id=4) ---
        $division9Id_Calon = 9;
        $usersData14 = [
            ['name' => 'Ahmad Haikal Musthofa', 'username' => '75791'],
            ['name' => 'Alvian Bayu Prastiyo', 'username' => '75792'],
            ['name' => 'Awie Hanapi', 'username' => '75793'],
            ['name' => 'Azis Pebrisa Budiono', 'username' => '75794'],
            ['name' => 'Dimas Maulana Firmansyah', 'username' => '75795'],
        ];

        // === DATA SET 15: Daop 8 Surabaya (division_id = 10) ===
        $division10Id = 10;
        
        // Data Kepala UPT Mekanik (Position ID = 1) - Dari image_7fc1c3
        $usersData15_KepalaUpt = [
            ['name' => 'SUPRYADI', 'username' => '48800'], 
        ];
        
        // Data Kepala Operator KPJR (Position ID = 2) - Dari image_7fc1c3
        $usersData16_KepalaOp = [
            ['name' => 'DENNY SURYA NUGROHO', 'username' => '53421'], 
        ];

        // Data Operator KPJR (Position ID = 3) - Dari image_7fc1c3
        $usersData17_Operator = [
            ['name' => 'ROY SAPUTRO', 'username' => '69681'],
            ['name' => 'SISWOYO', 'username' => '48979'],
            ['name' => 'MOCHAMAD KARIM', 'username' => '48847'],
            ['name' => 'SUTRISNO', 'username' => '48776'],
            ['name' => 'BENY RUBIANTORO', 'username' => '66235'],
            ['name' => 'YUSUF ROMANSYAH', 'username' => '59679'],
            ['name' => 'MUHAMMAD MIFTAH', 'username' => '64476'],
            ['name' => 'DIDIK MARDI SANTOSO', 'username' => '49746'],
            ['name' => 'OKTARI PUJI CRISTYAWAN', 'username' => '53840'],
            ['name' => 'SUISMANTO', 'username' => '64488'],
            ['name' => 'MOCH. ZAENAL MUSTOFA', 'username' => '55193'],
            ['name' => 'NUR KAHFI', 'username' => '55197'],
            ['name' => 'ANDRYAN GUSTI CAHYA PUTRA', 'username' => '61476'],
            ['name' => 'NURCHOLIS MADJID', 'username' => '53708'],
            ['name' => 'YANGGA SOFIANTO', 'username' => '66230'],
            ['name' => 'FIRMANSAH PRIYO LAKSONO', 'username' => '50327'],
            ['name' => 'MUHAMAD ROFIQ', 'username' => '50226'],
            ['name' => 'MUHAMMAD RIZA AZIZI', 'username' => '59732'],
            ['name' => 'ADI SUYITNO', 'username' => '48440'],
            ['name' => 'TOTOK SRI WINARKO', 'username' => '48419'],
            ['name' => 'MUKHAMAD RIDWAN', 'username' => '53908'],
            ['name' => 'SUDARMANTO', 'username' => '50151'],
            ['name' => 'FIRMAN WICAKSONO', 'username' => '50412'],
            ['name' => 'EFYS SUPRIYADI', 'username' => '59685'],
            ['name' => 'FAJAR SETIYO WAHYU', 'username' => '66234'],
            ['name' => 'ACHMAD JAINURI', 'username' => '66231'],
            ['name' => 'SUMADJI', 'username' => '40594'],
            ['name' => 'MOCHAMAD ROZI', 'username' => '48991'],
            ['name' => 'JUWAERI', 'username' => '49593'],
            ['name' => 'SUGIANTORO', 'username' => '69988'],
            ['name' => 'JOKO PRAMONO', 'username' => '56230'],
            ['name' => 'ADI SUHENDRO', 'username' => '56262'],
            ['name' => 'AGUNG PRASETIYO', 'username' => '62238'],
            ['name' => 'OKTARI PUJI CRISTYAWAN (Duplicate)', 'username' => '53840'], // Ditemukan duplikat username/nama, ditambahkan (Duplicate) agar unik
            ['name' => 'IWAN KURNIAWADI', 'username' => '53784'],
            ['name' => 'LEONARDO YOGA ANUGRAH', 'username' => '69689'],
            ['name' => 'GHIYATS FAQIHUDDIN FIL ANGRDH', 'username' => '76914'],
            ['name' => 'WAHYU ABDUL AZIS', 'username' => '76916'],
            ['name' => 'MUHAMMAD MIFTACHUL ARIF', 'username' => '53675'],
        ];
        
        // === DATA SET BARU YANG DIMINTA: Daop 9 Jember (division_id = 11) ===
        $division11Id = 11; 
        $usersData18_All = [
            ['name' => 'ENDEP DINY KURNIADIN', 'username' => '62469'], // Pos 1 (Kepala UPT Mekanik)
            ['name' => 'KASMAN', 'username' => '48328'], // Pos 3 (Operator KPJR)
            ['name' => 'HARIADI', 'username' => '48351'], // Pos 2 (Kepala Operator KPJR)
            ['name' => 'DODI HARTANTO', 'username' => '53605'],
            ['name' => 'SULIH WIRIANTO', 'username' => '56595'],
            ['name' => 'OKKY GIAN AFRISTA', 'username' => '56635'],
            ['name' => 'ACHMAD ZAINUL ABIDIN', 'username' => '56485'],
            ['name' => 'MOHAMMAD IRVAN PRAYUGO', 'username' => '64111'],
            ['name' => 'BAYU WARDANA PUTRA', 'username' => '53658'],
            ['name' => 'DESIS SETYAWAN', 'username' => '66239'],
            ['name' => 'FIAN KURNIAWAN', 'username' => '66241'],
            ['name' => 'DONY HADI ARIYANTO', 'username' => '56490'],
            ['name' => 'RONALD ALEXANDER', 'username' => '56582'],
            ['name' => 'M.ABDUL GHOFAR', 'username' => '56566'],
            ['name' => 'ISBANDI SUJATMIKO', 'username' => '56556'],
            ['name' => 'JAENAL ABIDIN', 'username' => '64110'],
            ['name' => 'HARI HARSONO', 'username' => '49147'],
            ['name' => 'NEZAR KOMARIL', 'username' => '69340'],
            ['name' => 'MUHAMMAD HOLIL', 'username' => '69906'],
            ['name' => 'SUYITMAN', 'username' => '59775'],
            ['name' => 'DIDIK MUSTARI', 'username' => '59768'],
            ['name' => 'TIGARIS ALIFANDI', 'username' => '69335'],
            ['name' => 'SEPTIAN AGUNG NUR AVIANTO', 'username' => '66238'],
            ['name' => 'SENDIKA NATA MARFIYANSYH', 'username' => '56590'],
            ['name' => 'AJI KRESNAWAN KUSRIYADI', 'username' => '66240'],
        ];

        // Gabungkan dan Proses Data
        $allUsersData = [
            'div_1_op' => ['data' => $usersData1, 'division_id' => $division1Id, 'position_id' => $operatorPositionId, 'role_id' => $operatorRoleId],
            'div_3_op' => ['data' => $usersData2, 'division_id' => $division3Id, 'position_id' => $operatorPositionId, 'role_id' => $operatorRoleId],
            'div_4_op' => ['data' => $usersData3, 'division_id' => $division4Id, 'position_id' => $operatorPositionId, 'role_id' => $operatorRoleId],
            'div_4_calon_op' => ['data' => $usersData4, 'division_id' => $division4Id_Calon, 'position_id' => $calonOperatorKpjrPositionId, 'role_id' => $operatorRoleId],
            'div_5_kepala_upt' => ['data' => $usersData5, 'division_id' => $division5Id, 'position_id' => $kepalaUptPositionId, 'role_id' => $adminRoleId],
            'div_5_kepala_op' => ['data' => $usersData6, 'division_id' => $division5Id, 'position_id' => $kepalaOperatorPositionId, 'role_id' => $adminRoleId],
            'div_5_op' => ['data' => $usersData7, 'division_id' => $division5Id, 'position_id' => $operatorPositionId, 'role_id' => $operatorRoleId],
            'div_5_calon_kpjrb' => ['data' => $usersData8, 'division_id' => $division5Id, 'position_id' => $calonOperatorKpjrPositionId, 'role_id' => $operatorRoleId], 
            
            // SET DATA DAOP 4 SEMARANG
            'div_6_all' => ['data' => $usersData9_All, 'division_id' => $division6Id, 'position_id_map' => [
                0 => $kepalaUptPositionId, 
                1 => $kepalaOperatorPositionId,
                'default' => $operatorPositionId 
            ], 'role_id' => $operatorRoleId],
            'div_6_calon_op' => ['data' => $usersData10, 'division_id' => $division6Id, 'position_id' => $calonOperatorKpjrPositionId, 'role_id' => $operatorRoleId],

            // SET DATA DAOP 5 PURWOKERTO
            'div_7_all' => ['data' => $usersData11_All, 'division_id' => $division7Id, 'position_id_map' => [
                0 => $kepalaUptPositionId, 
                1 => $kepalaOperatorPositionId,
                'default' => $operatorPositionId 
            ], 'role_id' => $operatorRoleId],
            
            // SET DATA DAOP 6 YOGYAKARTA (division_id = 8)
            'div_8_all' => ['data' => $usersData12_All, 'division_id' => $division8Id, 'position_id_map' => [
                0 => $kepalaUptPositionId, 
                1 => $kepalaOperatorPositionId,
                'default' => $operatorPositionId 
            ], 'role_id' => $operatorRoleId],

            // SET DATA DAOP 7 MADIUN (division_id = 9) - CAMPURAN POSISI
            'div_9_all' => ['data' => $usersData13_All, 'division_id' => $division9Id, 'position_id_map' => [
                0 => $kepalaUptPositionId, 
                1 => $kepalaOperatorPositionId,
                'default' => $operatorPositionId 
            ], 'role_id' => $operatorRoleId],
            'div_9_calon_op' => ['data' => $usersData14, 'division_id' => $division9Id_Calon, 'position_id' => $calonOperatorKpjrPositionId, 'role_id' => $operatorRoleId],
            
            // === SET DATA DIVISI 10 (DAOP 8 SB) ===
            'div_10_kepala_upt' => ['data' => $usersData15_KepalaUpt, 'division_id' => $division10Id, 'position_id' => $kepalaUptPositionId, 'role_id' => $adminRoleId],
            'div_10_kepala_op' => ['data' => $usersData16_KepalaOp, 'division_id' => $division10Id, 'position_id' => $kepalaOperatorPositionId, 'role_id' => $adminRoleId],
            'div_10_op' => ['data' => $usersData17_Operator, 'division_id' => $division10Id, 'position_id' => $operatorPositionId, 'role_id' => $operatorRoleId],

            // === SET DATA BARU DIVISI 11 (DAOP 9 JEMBER) ===
            'div_11_all' => ['data' => $usersData18_All, 'division_id' => $division11Id, 'position_id_map' => [
                0 => $kepalaUptPositionId,       // ENDEP DINY KURNIADIN (Baris 1)
                1 => $kepalaOperatorPositionId,  // HARIADI (Baris 2)
                'default' => $operatorPositionId // Baris ke-3 dan seterusnya
            ], 'role_id' => $operatorRoleId],
        ];

        foreach ($allUsersData as $key => $set) {
          // Ambil base info
          $divisionId = $set['division_id'];
          $baseRoleId = $set['role_id'];

          foreach ($set['data'] as $index => $userData) {
              // Cek apakah user sudah ada berdasarkan username
              $existingUser = User::where('username', $userData['username'])->first();

              if (!$existingUser) {
                  // Tentukan Position ID
                  if (isset($set['position_id_map'])) {
                      $positionId = $set['position_id_map'][$index] ?? $set['position_id_map']['default'];
                  } else {
                      $positionId = $set['position_id'];
                  }

                  // Tentukan Role ID secara otomatis
                  // Admin (1) jika posisinya Kepala UPT atau Kepala Operator
                  $roleId = in_array($positionId, [$kepalaUptPositionId, $kepalaOperatorPositionId]) 
                            ? $adminRoleId 
                            : $baseRoleId;

                  $user = User::create([
                      'name'              => $userData['name'],
                      'username'          => $userData['username'],
                      'email'             => $createEmail($userData['name']),
                      'password'          => $hashedPassword,
                      'region_id'         => $defaultRegionId,
                      'division_id'       => $divisionId,
                      'position_id'       => $positionId,
                      'email_verified_at' => now(),
                  ]);

                  if ($roleId) {
                      $user->roles()->sync([$roleId]);
                  }
              }
          }
      }
    }
}