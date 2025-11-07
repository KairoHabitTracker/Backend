<?php

use App\Enums\FriendRequestStatus;
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
        Schema::create('friend_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignUlid('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignUlid('receiver_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', FriendRequestStatus::cases())->default(FriendRequestStatus::PENDING);
            $table->timestamp('responded_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('friend_requests');
    }
};
