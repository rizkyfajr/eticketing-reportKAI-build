<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
    public function up()
    {
        Schema::table('maintenance_orders', function (Blueprint $table) {
            $table->string('lampiran_path')->nullable()->after('title');
        });
    }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
    public function down(): void
    {
        Schema::table('maintenance_orders', function (Blueprint $table) {
            $table->dropColumn('lampiran_path');
        });
    }
};
