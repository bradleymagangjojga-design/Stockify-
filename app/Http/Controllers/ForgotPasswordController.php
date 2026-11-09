<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /**
     * Show the "forgot password" form.
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle the submitted email — generate reset token.
     * Since mail isn't configured, show the reset URL on screen (dev mode).
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->input('email');
        $user  = User::where('email', $email)->first();

        if (!$user) {
            return back()
                ->withInput()
                ->withErrors(['email' => __('Email not found in our system.')]);
        }

        // Generate a secure, URL-safe token
        $token = Str::random(60);

        // Store the HASHED token in the database
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'email'      => $email,
                'token'      => Hash::make($token),
                'created_at' => now(),
            ]
        );

        // Build the reset URL with the RAW token
        $resetUrl = route('password.reset', [
            'token' => $token,
            'email' => $email,
        ]);

        Log::info("Password reset link generated for {$email}: {$resetUrl}");

        // Flash the raw URL to the session so it can be shown on screen (dev mode)
        return redirect()
            ->route('password.request')
            ->with('status', __('We have generated a password reset link.'))
            ->with('reset_url', $resetUrl);
    }
}
