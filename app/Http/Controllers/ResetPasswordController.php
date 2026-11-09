<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ResetPasswordController extends Controller
{
    /**
     * Show the reset password form (arrived from reset link).
     */
    public function showResetForm(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    /**
     * Handle the password reset submission.
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token'    => 'required|string',
            'email'    => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        // Find the reset record
        $record = DB::table('password_reset_tokens')
            ->where('email', $request->input('email'))
            ->first();

        if (!$record) {
            Log::warning("Password reset failed: no token record for {$request->email}");
            return back()->withErrors([
                'email' => __('Reset token not found. Please request a new link.'),
            ]);
        }

        // Verify token
        if (!Hash::check($request->input('token'), $record->token)) {
            Log::warning("Password reset failed: token mismatch for {$request->email}");
            return back()->withErrors([
                'email' => __('Reset token is invalid or has expired.'),
            ]);
        }

        // Check expiry (60 minutes)
        if (now()->diffInMinutes($record->created_at) > 60) {
            DB::table('password_reset_tokens')->where('email', $record->email)->delete();
            Log::warning("Password reset failed: token expired for {$request->email}");
            return back()->withErrors([
                'email' => __('Reset token has expired. Please request a new link.'),
            ]);
        }

        // Find user
        $user = User::where('email', $request->input('email'))->first();

        if (!$user) {
            Log::warning("Password reset failed: user not found for {$request->email}");
            return back()->withErrors(['email' => __('User not found.')]);
        }

        // ---------- UPDATE PASSWORD ----------
        $newHashedPassword = Hash::make($request->input('password'));

        $user->forceFill([
            'password'       => $newHashedPassword,
            'remember_token' => null,
        ])->save();

        // Force-refresh from DB and verify the hash actually matches
        $user->refresh();

        if (!Hash::check($request->input('password'), $user->password)) {
            Log::error("Password reset FAILED verification for user ID {$user->id} ({$user->email})");
            return back()->withErrors([
                'email' => 'Password could not be saved. Please try again.',
            ]);
        }

        Log::info("Password reset SUCCESS for user ID {$user->id} ({$user->email})");

        // Delete used token
        DB::table('password_reset_tokens')->where('email', $record->email)->delete();

        return redirect()
            ->route('login')
            ->with('success', __('Password reset successfully! Please login with your new password.'));
    }
}
