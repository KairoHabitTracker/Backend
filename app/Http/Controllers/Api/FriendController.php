<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class FriendController
{
    /**
     * List friends for the current user.
     */
    public function index(Request $request) {
        return $request->user()->friends();
    }

    /**
     * Remove a friend
     */
    public function destroy(Request $request, $id) {
        $friend = $request->user()->friends()->findOrFail($id);
        $friend->delete();
    }
}
