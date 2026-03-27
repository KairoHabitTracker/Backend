<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class UserHealthLogController
{
    /**
     * Create a new health log
     */
    public function store(Request $request) {
        $request->validate([
            'date' => 'required|date',
            'steps' => 'required|integer|min:0',
            'sleep_minutes' => 'required|integer|min:0'
        ]);

        $request->user()->healthLogs()->create($request->only('date', 'steps', 'sleep_minutes'));

        return response()->json([
            'message' => "Health log created successfully"
        ]);
    }
}
