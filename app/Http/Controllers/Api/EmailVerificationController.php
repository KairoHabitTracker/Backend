<?php

namespace App\Http\Controllers\Api;

use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class EmailVerificationController
{
    /**
     * Show the email verification status
     */
    public function index(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified'], 200);
        }

        return response()->json(['message' => 'Email not verified'], 200);
    }

    /**
     * Verify the user's email address
     */
    #[QueryParameter(name: 'signature', type: 'string')]
    #[QueryParameter(name: 'expires', type: 'string')]
    public function verify(Request $request)
    {
        if (!hash_equals((string) $request->user()->getKey(), (string) $request->route('id'))) {
            return response()->json(['message' => 'Invalid verification link'], 403);
        }

        if (!hash_equals(sha1($request->user()->getEmailForVerification()), (string) $request->route('hash'))) {
            return response()->json(['message' => 'Invalid verification link'], 403);
        }

        if (!$request->user()->hasVerifiedEmail()) {
            $request->user()->markEmailAsVerified();

            event(new Verified($request->user()));
        }

        return response()->json(['message' => 'Email verified successfully']);
    }

    /**
     * Send the email verification notification
     */
    public function send(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified'], 200);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification email sent'], 200);
    }
}
