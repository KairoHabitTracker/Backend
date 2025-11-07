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
        Schema::create('user_habits', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('habit_id')->constrained('habits')->onDelete('cascade');
            $table->integer('streak')->default(0);
            $table->string('notification_time')->nullable();
            $table->json('days_of_week')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamp('last_completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_habits');
    }
};
