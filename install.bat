@echo off
chcp 65001 >nul
cls

REM =============================================================================
REM ETICKETING KAI - FRESH INSTALLATION SCRIPT (WINDOWS)
REM =============================================================================
REM Script untuk fresh installation lengkap di Windows
REM Jalankan dengan: install.bat
REM =============================================================================

echo ═══════════════════════════════════════════════════════════
echo   ETICKETING KAI - FRESH INSTALLATION
echo ═══════════════════════════════════════════════════════════
echo.

REM STEP 1: Install Dependencies
echo STEP 1: Installing Dependencies...
echo ──────────────────────────────────────────────────────────
echo → Installing Composer dependencies...
call composer install --no-interaction --prefer-dist --optimize-autoloader
if errorlevel 1 (
    echo ✗ Composer install GAGAL!
    pause
    exit /b 1
)
echo ✓ Composer install

echo → Installing NPM dependencies...
call npm install
if errorlevel 1 (
    echo ✗ NPM install GAGAL!
    pause
    exit /b 1
)
echo ✓ NPM install
echo.

REM STEP 2: Setup Environment
echo STEP 2: Setup Environment...
echo ──────────────────────────────────────────────────────────
if not exist .env (
    copy .env.example .env
    echo ✓ Copy .env file
) else (
    echo ⚠ .env file already exists, skipping...
)

php artisan key:generate --force
if errorlevel 1 (
    echo ✗ Generate key GAGAL!
    pause
    exit /b 1
)
echo ✓ Generate application key
echo.

REM STEP 3: Database Migration
echo STEP 3: Database Migration...
echo ──────────────────────────────────────────────────────────
set /p db_ready="Apakah database sudah dibuat? (y/n): "
if /i not "%db_ready%"=="y" (
    echo ⚠ Buat database dulu, lalu jalankan script ini lagi!
    pause
    exit /b 1
)

php artisan migrate --force
if errorlevel 1 (
    echo ✗ Migration GAGAL!
    pause
    exit /b 1
)
echo ✓ Database migration
echo.

REM STEP 4: Seeding Database
echo STEP 4: Seeding Database...
echo ──────────────────────────────────────────────────────────

echo → Running InitialSeeder...
php artisan db:seed --class=InitialSeeder --force
if errorlevel 1 (
    echo ✗ InitialSeeder GAGAL!
    pause
    exit /b 1
)
echo ✓ InitialSeeder

echo → Running MenuSeeder...
php artisan db:seed --class=MenuSeeder --force
if errorlevel 1 (
    echo ✗ MenuSeeder GAGAL!
    pause
    exit /b 1
)
echo ✓ MenuSeeder

echo → Running MasterRegionSeeder...
php artisan db:seed --class=MasterRegionSeeder --force
if errorlevel 1 (
    echo ✗ MasterRegionSeeder GAGAL!
    pause
    exit /b 1
)
echo ✓ MasterRegionSeeder

echo → Running MasterClassificationSeeder...
php artisan db:seed --class=MasterClassificationSeeder --force
if errorlevel 1 (
    echo ✗ MasterClassificationSeeder GAGAL!
    pause
    exit /b 1
)
echo ✓ MasterClassificationSeeder

echo → Running MasterDataPermissionSeeder...
php artisan db:seed --class=MasterDataPermissionSeeder --force
if errorlevel 1 (
    echo ✗ MasterDataPermissionSeeder GAGAL!
    pause
    exit /b 1
)
echo ✓ MasterDataPermissionSeeder

echo → Running AdminWilayahCompleteSeeder...
php artisan db:seed --class=AdminWilayahCompleteSeeder --force
if errorlevel 1 (
    echo ✗ AdminWilayahCompleteSeeder GAGAL!
    pause
    exit /b 1
)
echo ✓ AdminWilayahCompleteSeeder
echo.

REM STEP 5: Clear Cache
echo STEP 5: Clearing Cache...
echo ──────────────────────────────────────────────────────────
php artisan cache:clear
echo ✓ Cache clear

php artisan config:clear
echo ✓ Config clear

php artisan route:clear
echo ✓ Route clear

php artisan view:clear
echo ✓ View clear
echo.

REM STEP 6: Build Assets
echo STEP 6: Building Assets...
echo ──────────────────────────────────────────────────────────
set /p build_prod="Build untuk production? (y/n) [n]: "
if /i "%build_prod%"=="y" (
    call npm run build
    if errorlevel 1 (
        echo ✗ NPM build GAGAL!
        pause
        exit /b 1
    )
    echo ✓ NPM build (production)
) else (
    echo Skipping build... Jalankan 'npm run dev' nanti untuk development
)
echo.

REM STEP 7: Final
echo ═══════════════════════════════════════════════════════════
echo   ✓ INSTALASI SELESAI!
echo ═══════════════════════════════════════════════════════════
echo.
echo 📋 INFORMASI LOGIN:
echo.
echo SUPER ADMIN:
echo   URL      : http://localhost:8000/login
echo   Username : su
echo   Password : password
echo.
echo ADMIN WILAYAH (Demo):
echo   URL      : http://localhost:8000/login
echo   Username : admin.daop1jakarta (atau daop2, daop3, dst)
echo   Password : password
echo.
echo ⚠ GANTI PASSWORD setelah login pertama kali!
echo.
echo 📌 CARA MENJALANKAN:
echo   Development : php artisan serve + npm run dev
echo   Production  : Setup web server (Apache/Nginx)
echo.
echo 📚 DOKUMENTASI:
echo   - SETUP_INSTALLATION_GUIDE.md
echo   - ADMIN_WILAYAH_GUIDE.md
echo   - ADMIN_WILAYAH_QUICKSTART.md
echo.
pause
