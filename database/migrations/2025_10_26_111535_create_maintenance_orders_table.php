<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('maintenance_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('working_report_id')->constrained('working_reports')->cascadeOnDelete();
            $table->foreignId('master_machine_id')->constrained('master_machines')->cascadeOnDelete();
            $table->enum('category', ['planned','unplanned'])->default('unplanned');
            $table->string('title');
            $table->text('problem_note')->nullable();
            $table->string('component_lv1')->nullable();
            $table->string('component_lv2')->nullable();
            $table->string('location')->nullable();
            $table->dateTime('trouble_at')->nullable();
            $table->string('assigned_to')->nullable();
            $table->dateTime('plan_start_at')->nullable();
            $table->text('action_plan')->nullable();
            $table->dateTime('repair_start_at')->nullable();
            $table->text('repair_action')->nullable();
            $table->dateTime('repair_done_at')->nullable();
            $table->text('repair_result')->nullable();
            $table->string('attachment_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_orders');
    }
};
