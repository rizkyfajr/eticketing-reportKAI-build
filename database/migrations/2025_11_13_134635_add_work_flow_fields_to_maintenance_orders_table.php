<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
    public function up(): void
    {
        // Modify status column tanpa ->change() (tanpa DBAL)
        DB::statement("ALTER TABLE maintenance_orders MODIFY status VARCHAR(255) NOT NULL DEFAULT 'BARU' COMMENT 'BARU, DIPROSES, DIKERJAKAN, SELESAI'");

        Schema::table('maintenance_orders', function (Blueprint $table) {
            $table->foreignId('follow_up_by_id')->nullable()->constrained('users');
            $table->timestamp('follow_up_at')->nullable();
            $table->text('follow_up_plan')->nullable();
            $table->timestamp('follow_up_estimate_at')->nullable();

            $table->foreignId('start_repair_by_id')->nullable()->constrained('users');
            $table->timestamp('start_repair_at')->nullable();
            $table->text('start_repair_notes')->nullable();
            $table->string('start_repair_photo')->nullable();

            // 4. Kolom untuk 'repair complete'
            $table->foreignId('complete_repair_by_id')->nullable()->constrained('users');
            $table->timestamp('complete_repair_at')->nullable();
            $table->text('complete_repair_notes')->nullable();
            $table->string('complete_repair_photo')->nullable();
        });
    }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::table('maintenance_orders', function (Blueprint $table) {
      //
    });
  }
};
