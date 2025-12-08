<?php

namespace App\Http\Resources;

use App\Models\UserHabit;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin UserHabit
 */
class UserHabitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            'last_completed_at' => $this->last_completed_at,
        ]);
    }
}
