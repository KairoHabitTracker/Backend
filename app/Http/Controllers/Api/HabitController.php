<?php

namespace App\Http\Controllers\Api;

use App\Enums\HabitCategory;
use App\Http\Resources\HabitResource;
use App\Models\Habit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HabitController
{
    /**
     * List default habits.
     */
    public function index()
    {
        return HabitResource::collection(Habit::query()->builtin()->get());
    }

    /**
     * List custom habits for the current user.
     */
    public function custom(Request $request)
    {
        return HabitResource::collection(Habit::query()->custom($request->user()->id)->get());
    }

    /**
     * Create a new custom habit
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'emoji' => 'required|string|max:255',
            'hex_color' => 'required|string|size:7',
            'category' => ['required', 'string', Rule::enum(HabitCategory::class)]
        ]);

        $habit = Habit::create([
            'user_id' => $request->user()->id,
            'name' => $request->input('name'),
            'emoji' => $request->input('emoji'),
            'hex_color' => $request->input('hex_color'),
            'category' => $request->input('category'),
        ]);

        return $habit->toResource();
    }

    /**
     * Update a custom habit
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'emoji' => 'sometimes|string|max:255',
            'hex_color' => 'sometimes|string|size:7',
            'category' => ['sometimes', 'string', Rule::enum(HabitCategory::class)]
        ]);

        $habit = Habit::query()->findOrFail($id);
        if ($habit->user_id !== $request->user()->id) {
            return response()->json(['message' => 'You do not have permission to update this habit'], 403);
        }

        $habit->update($request->only('name', 'emoji', 'hex_color', 'category'));
        return $habit->toResource();
    }

    /**
     * Delete a custom habit
     */
    public function destroy(Request $request, int $id) {
        $habit = Habit::query()->findOrFail($id);
        if ($habit->user_id !== $request->user()->id) {
            return response()->json(['message' => 'You do not have permission to delete this habit'], 403);
        }

        $habit->delete();
        return response()->json(['message' => 'Habit deleted successfully'], 200);
    }
}
