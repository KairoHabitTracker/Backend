<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class ProfileController
{
    public function index(Request $request): UserResource {
        return $request->user()->toResource();
    }
}
