<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\SubscriptionResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SubscriptionController
{
    /**
     * Get the current user's subscription details.
     */
    public function index(Request $request): SubscriptionResource {
        $subscription = $request->user()->subscription;

        if (!$subscription) {
            throw new NotFoundHttpException('Subscription not found.');
        }

        return new SubscriptionResource($subscription);
    }
}
