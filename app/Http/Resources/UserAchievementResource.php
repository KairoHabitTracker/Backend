<?php

namespace App\Http\Resources;

use App\Models\UserAchievement;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
* @mixin UserAchievement
**/
class UserAchievementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
