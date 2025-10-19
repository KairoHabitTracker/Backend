<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class ProfileController
{
    public function index(Request $request) {
        $user = $request->user()->load('info');
        return response()->json(['user' => $user], 200);
    }
}
