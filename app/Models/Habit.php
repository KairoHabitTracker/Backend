<?php

namespace App\Models;

use App\Enums\HabitCategory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class Habit extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'emoji',
        'hex_color',
        'category',
    ];

    protected $casts = [
        'category' => HabitCategory::class,
    ];

    #[Scope]
    protected function custom(Builder $query, $userId) {
        $query->where('user_id', $userId);
    }

    #[Scope]
    protected function builtin(Builder $query) {
        $query->whereNull('user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
