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
        Schema::create('maintenance_order_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('maintenance_order_id')->constrained('maintenance_orders')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('type'); // 'input_failure', 'follow_up_plan', 'start_repair', 'repair_complete'
            $table->string('title');
            $table->text('message');
            $table->string('status')->default('BARU'); // BARU, DIPROSES, DIKERJAKAN, SELESAI
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'read_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('maintenance_order_notifications');
    }
};
