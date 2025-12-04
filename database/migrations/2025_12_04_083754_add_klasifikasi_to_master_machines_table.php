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
    Schema::table('master_machines', function (Blueprint $table) {
      // Cek jika kolom belum ada
      if (!Schema::hasColumn('master_machines', 'classification_id')) {
        $table->foreignId('classification_id')->nullable()->after('region_id')->constrained('master_classifications')->nullOnDelete();
      }
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::table('master_machines', function (Blueprint $table) {
      $table->dropForeign(['classification_id']);
      $table->dropColumn('classification_id');
    });
  }
};
