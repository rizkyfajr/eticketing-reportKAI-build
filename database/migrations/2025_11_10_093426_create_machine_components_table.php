<?php
// database/migrations/2025_10_27_000000_create_machine_components_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('machine_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('master_machine_id')->nullable()
                  ->constrained('master_machines')->nullOnDelete();

            $table->string('machine_type')->nullable();

            $table->foreignId('parent_id')->nullable()
                  ->constrained('machine_components')->cascadeOnDelete();

            $table->string('code')->nullable();
            $table->string('name');
            $table->unsignedTinyInteger('level')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('machine_components');
    }
};
