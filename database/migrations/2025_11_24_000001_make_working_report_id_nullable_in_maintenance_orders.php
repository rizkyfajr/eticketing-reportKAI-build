<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Cara tanpa DBAL: drop constraint dulu, lalu modify dengan raw SQL
        DB::statement('ALTER TABLE maintenance_orders MODIFY working_report_id BIGINT UNSIGNED NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE maintenance_orders MODIFY working_report_id BIGINT UNSIGNED NOT NULL');
    }
};
