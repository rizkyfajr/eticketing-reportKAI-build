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
    public function up(): void
    {
        Schema::table('maintenance_order_results', function (Blueprint $table) {
            $table->unique(['maintenance_order_id', 'master_pedoman_item_id'], 'order_item_unique');
        });
    }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
    public function down(): void
    {
        Schema::table('maintenance_order_results', function (Blueprint $table) {
            $table->dropUnique('order_item_unique');
        });
    }
};
