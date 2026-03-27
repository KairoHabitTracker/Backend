<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_health_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('user_id')->constrained('users')->onDelete('cascade');
            $table->date('date');
            $table->integer('steps')->default(0);
            $table->integer('sleep_minutes')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_health_logs');
    }
};
