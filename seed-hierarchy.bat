@echo off
echo ================================
echo Machine Hierarchy Seeder Script
echo ================================
echo.

echo [INFO] Script ini akan mengisi data hierarki mesin ke database
echo.

:MENU
echo Pilih opsi:
echo 1. Jalankan Seeder Standar (MachineHierarchySeeder)
echo 2. Jalankan Seeder Extended (MachineHierarchyExtendedSeeder)
echo 3. Lihat jumlah data yang sudah ada
echo 4. Truncate tabel machine_components
echo 5. Test Seeder (Run validation tests)
echo 6. Display Hierarchy Tree
echo 7. Exit
echo.

set /p choice="Masukkan pilihan (1-7): "

if "%choice%"=="1" goto SEED_STANDARD
if "%choice%"=="2" goto SEED_EXTENDED
if "%choice%"=="3" goto COUNT_DATA
if "%choice%"=="4" goto TRUNCATE_TABLE
if "%choice%"=="5" goto TEST_SEEDER
if "%choice%"=="6" goto DISPLAY_TREE
if "%choice%"=="7" goto END
goto MENU

:SEED_STANDARD
echo.
echo [INFO] Menjalankan MachineHierarchySeeder...
php artisan db:seed --class=MachineHierarchySeeder
echo.
echo [SUCCESS] Seeder berhasil dijalankan!
echo.
pause
goto MENU

:SEED_EXTENDED
echo.
echo [INFO] Menjalankan MachineHierarchyExtendedSeeder...
echo [INFO] Ini akan membuat hierarki untuk semua tipe mesin yang ada
php artisan db:seed --class=MachineHierarchyExtendedSeeder
echo.
echo [SUCCESS] Seeder berhasil dijalankan!
echo.
pause
goto MENU

:COUNT_DATA
echo.
echo [INFO] Menghitung jumlah data...
php artisan tinker --execute="echo 'Total machine components: ' . App\Models\MachineComponent::count() . PHP_EOL; echo 'By level:' . PHP_EOL; App\Models\MachineComponent::selectRaw('level, count(*) as total')->groupBy('level')->orderBy('level')->get()->each(function($item) { echo '  Level ' . $item->level . ': ' . $item->total . PHP_EOL; });"
echo.
pause
goto MENU

:TRUNCATE_TABLE
echo.
echo [WARNING] Ini akan menghapus SEMUA data di tabel machine_components!
set /p confirm="Apakah Anda yakin? (Y/N): "
if /i "%confirm%"=="Y" (
    echo [INFO] Menghapus data...
    php artisan tinker --execute="DB::statement('SET FOREIGN_KEY_CHECKS=0'); App\Models\MachineComponent::truncate(); DB::statement('SET FOREIGN_KEY_CHECKS=1'); echo 'Data berhasil dihapus!' . PHP_EOL;"
    echo.
    echo [SUCCESS] Tabel berhasil di-truncate!
) else (
    echo [INFO] Operasi dibatalkan.
)
echo.
pause
goto MENU

:TEST_SEEDER
echo.
echo [INFO] Menjalankan Test Seeder...
echo [INFO] Akan melakukan validasi terhadap data hierarchy
php artisan db:seed --class=TestMachineHierarchySeeder
echo.
pause
goto MENU

:DISPLAY_TREE
echo.
echo [INFO] Display Hierarchy Tree
echo.
set /p machine="Masukkan machine type (default: MTT 07-16 G): "
if "%machine%"=="" set machine=MTT 07-16 G
echo [INFO] Menampilkan tree untuk: %machine%
php artisan db:seed --class=DisplayHierarchyTreeSeeder -- --machine_type="%machine%"
echo.
pause
goto MENU

:END
echo.
echo Terima kasih!
exit
