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
        Schema::table('master_machines', function (Blueprint $table) {
            $table->string('hierarchy_code')->nullable()->after('type');
        });
    }

    public function down(): void
    {
        Schema::table('master_machines', function (Blueprint $table) {
            $table->dropColumn('hierarchy_code');
        });
    }
};
