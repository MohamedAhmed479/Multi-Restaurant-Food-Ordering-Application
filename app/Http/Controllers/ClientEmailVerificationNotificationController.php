<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ClientEmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user('client')->hasVerifiedEmail()) {
            return redirect()->intended(route('clinet.dashboard', absolute: false));
        }

        $request->user('client')->sendEmailVerificationNotification();

        return back();
    }
}
