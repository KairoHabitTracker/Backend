<?php

namespace App\Http\Controllers\Api;

use App\Enums\FriendRequestStatus;
use App\Http\Resources\FriendRequestResource;
use App\Models\FriendRequest;
use Illuminate\Http\Request;

class FriendRequestController
{
    /**
     * List friend requests received by the current user.
     */
    public function received(Request $request)
    {
        return FriendRequestResource::collection(
            $request->user()->friendRequestsToMe()
        );
    }

    /**
     * List friend requests sent by the current user.
     */
    public function sent(Request $request)
    {
        return FriendRequestResource::collection(
            $request->user()->friendRequestsOfMine()
        );
    }

    /**
     * Send a friend request
     */
    public function store(Request $request)
    {
        $request->validate([
            'friend_id' => 'string|required|exists:users,id',
        ]);

        $existing = FriendRequest::query()
            ->where('sender_id', $request->user()->id)
            ->where('receiver_id', $request->friend_id)->first();

        if ($existing) {
            if ($existing->status === FriendRequestStatus::PENDING) {
                return response()->json(['message' => 'Friend request already sent'], 400);
            } else {
                if ($existing->responded_at && $existing->responded_at->diffInDays(now()) < 7) {
                    return response()->json(['message' => 'You can only resend a friend request after 7 days of rejection'], 400);
                } else {
                    $existing->delete();
                }
            }
        }

        $request->user()->friendRequestsOfMine()->create([
            'receiver_id' => $request->friend_id,
        ]);

        return response()->json(['message' => 'Friend request sent successfully']);
    }

    /**
     * Accept a friend request
     */
    public function accept(Request $request, $id)
    {
        $fr = $request->user()->friendRequestsToMe()->findOrFail($id);
        $fr->accept();

        return response()->json(['message' => 'Friend request accepted successfully']);
    }

    /**
     * Reject a friend request
     */
    public function reject(Request $request, $id)
    {
        $fr = $request->user()->friendRequestsToMe()->findOrFail($id);
        $fr->reject();

        return response()->json(['message' => 'Friend request rejected successfully']);
    }

    /**
     * Cancel a friend request
     */
    public function destroy(Request $request, $id)
    {
        $fr = $request->user()->friendRequestsOfMine()->findOrFail($id);
        $fr->delete();
    }
}
