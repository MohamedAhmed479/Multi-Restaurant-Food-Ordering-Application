<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientEmailVerificationRequest;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class ClientVerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(ClientEmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user('client')->hasVerifiedEmail()) {
            return redirect()->intended(route('client.dashboard', absolute: false).'?verified=1');
        }

        if ($request->user('client')->markEmailAsVerified()) {
            event(new Verified($request->user('client')));
        }

        return redirect()->intended(route('client.dashboard', absolute: false).'?verified=1');
    }
}
