#!/bin/bash

# =============================================================================
# ETICKETING KAI - FRESH INSTALLATION SCRIPT
# =============================================================================
# Script untuk fresh installation lengkap
# Jalankan dengan: bash install.sh
# =============================================================================

echo "═══════════════════════════════════════════════════════════"
echo "  ETICKETING KAI - FRESH INSTALLATION"
echo "═══════════════════════════════════════════════════════════"
echo ""

# Fungsi untuk mengecek apakah perintah berhasil
check_status() {
    if [ $? -eq 0 ]; then
        echo "✓ $1"
    else
        echo "✗ $1 GAGAL!"
        exit 1
    fi
}

# STEP 1: Install Dependencies
echo "STEP 1: Installing Dependencies..."
echo "──────────────────────────────────────────────────────────"
composer install --no-interaction --prefer-dist --optimize-autoloader
check_status "Composer install"

npm install
check_status "NPM install"
echo ""

# STEP 2: Setup Environment
echo "STEP 2: Setup Environment..."
echo "──────────────────────────────────────────────────────────"
if [ ! -f .env ]; then
    cp .env.example .env
    check_status "Copy .env file"
else
    echo "⚠ .env file already exists, skipping..."
fi

php artisan key:generate --force
check_status "Generate application key"
echo ""

# STEP 3: Database Migration
echo "STEP 3: Database Migration..."
echo "──────────────────────────────────────────────────────────"
read -p "Apakah database sudah dibuat? (y/n): " db_ready
if [ "$db_ready" != "y" ]; then
    echo "⚠ Buat database dulu, lalu jalankan script ini lagi!"
    exit 1
fi

php artisan migrate --force
check_status "Database migration"
echo ""

# STEP 4: Seeding Database
echo "STEP 4: Seeding Database..."
echo "──────────────────────────────────────────────────────────"

echo "→ Running InitialSeeder..."
php artisan db:seed --class=InitialSeeder --force
check_status "InitialSeeder"

echo "→ Running MenuSeeder..."
php artisan db:seed --class=MenuSeeder --force
check_status "MenuSeeder"

echo "→ Running MasterRegionSeeder..."
php artisan db:seed --class=MasterRegionSeeder --force
check_status "MasterRegionSeeder"

echo "→ Running MasterClassificationSeeder..."
php artisan db:seed --class=MasterClassificationSeeder --force
check_status "MasterClassificationSeeder"

echo "→ Running MasterDataPermissionSeeder..."
php artisan db:seed --class=MasterDataPermissionSeeder --force
check_status "MasterDataPermissionSeeder"

echo "→ Running AdminWilayahCompleteSeeder..."
php artisan db:seed --class=AdminWilayahCompleteSeeder --force
check_status "AdminWilayahCompleteSeeder"
echo ""

# STEP 5: Clear Cache
echo "STEP 5: Clearing Cache..."
echo "──────────────────────────────────────────────────────────"
php artisan cache:clear
check_status "Cache clear"

php artisan config:clear
check_status "Config clear"

php artisan route:clear
check_status "Route clear"

php artisan view:clear
check_status "View clear"
echo ""

# STEP 6: Build Assets
echo "STEP 6: Building Assets..."
echo "──────────────────────────────────────────────────────────"
read -p "Build untuk production? (y/n) [n]: " build_prod
if [ "$build_prod" = "y" ]; then
    npm run build
    check_status "NPM build (production)"
else
    echo "Skipping build... Jalankan 'npm run dev' nanti untuk development"
fi
echo ""

# STEP 7: Final
echo "═══════════════════════════════════════════════════════════"
echo "  ✓ INSTALASI SELESAI!"
echo "═══════════════════════════════════════════════════════════"
echo ""
echo "📋 INFORMASI LOGIN:"
echo ""
echo "SUPER ADMIN:"
echo "  URL      : http://localhost:8000/login"
echo "  Username : su"
echo "  Password : password"
echo ""
echo "ADMIN WILAYAH (Demo):"
echo "  URL      : http://localhost:8000/login"
echo "  Username : admin.daop1jakarta (atau daop2, daop3, dst)"
echo "  Password : password"
echo ""
echo "⚠ GANTI PASSWORD setelah login pertama kali!"
echo ""
echo "📌 CARA MENJALANKAN:"
echo "  Development : php artisan serve + npm run dev"
echo "  Production  : Setup web server (Apache/Nginx)"
echo ""
echo "📚 DOKUMENTASI:"
echo "  - SETUP_INSTALLATION_GUIDE.md"
echo "  - ADMIN_WILAYAH_GUIDE.md"
echo "  - ADMIN_WILAYAH_QUICKSTART.md"
echo ""
