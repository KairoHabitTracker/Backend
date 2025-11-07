<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class FriendController
{
    /**
     * List friends for the current user.
     */
    public function index(Request $request) {
        return UserResource::collection($request->user()->friends);
    }

    /**
     * Remove a friend
     */
    public function destroy(Request $request, $id) {
        $friend = $request->user()->friends()->findOrFail($id);
        $friend->delete();
    }
}
