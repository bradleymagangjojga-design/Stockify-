<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect to Google's OAuth consent screen.
     *
     * - If ?popup=1, marks the session so the callback renders a
     *   self-closing view instead of a full-page redirect.
     * - Falls back to demo mode if Google credentials aren't configured.
     */
    public function redirect(Request $request)
    {
        // Demo mode — no Google credentials
        if (! config('services.google.client_id')) {
            return $this->demoLogin($request->boolean('popup'));
        }

        // Remember popup mode for the callback
        if ($request->boolean('popup')) {
            session(['google_popup' => true]);
        }

        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the callback from Google.
     */
    public function callback(Request $request)
    {
        $popup = (bool) session()->pull('google_popup', false);

        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Throwable $e) {
            return $this->failure($popup, 'Login Google gagal: ' . $e->getMessage());
        }

        // Find user by google_id OR email
        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($user) {
            if (! $user->google_id) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar'    => $googleUser->getAvatar(),
                ]);
            }
        } else {
            // Create a new user
            $user = User::create([
                'name'      => $googleUser->getName() ?: 'Google User',
                'username'  => $this->generateUsername($googleUser->getEmail()),
                'email'     => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar'    => $googleUser->getAvatar(),
                'password'  => null,
                'role'      => 'Staff Gudang',
                'status'    => 'active',
            ]);
        }

        if ($user->status !== 'active') {
            return $this->failure($popup, 'Akun Anda belum aktif. Hubungi Administrator.');
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        $welcome = 'Selamat datang, ' . $user->name . '!';

        // Popup flow → render self-closing view
        if ($popup) {
            return view('auth.google-popup-close', [
                'status'   => 'success',
                'message'  => null,
                'redirect' => route('dashboard'),
            ]);
        }

        // Full-page flow
        return redirect()->route('dashboard')->with('success', $welcome);
    }

    /**
     * Demo mode — log in as the first Admin if Google isn't configured.
     * Works in both popup and full-page modes.
     */
    protected function demoLogin(bool $popup = false)
    {
        $user = User::where('role', 'Admin')->first();

        if (! $user) {
            return $this->failure(
                $popup,
                'Tidak ada user Admin. Google OAuth belum dikonfigurasi.'
            );
        }

        Auth::login($user, true);

        $welcome = 'Demo mode: Login sebagai ' . $user->name;

        if ($popup) {
            return view('auth.google-popup-close', [
                'status'   => 'success',
                'message'  => $welcome,
                'redirect' => route('dashboard'),
            ]);
        }

        return redirect()->route('dashboard')->with('success', $welcome);
    }

    /**
     * Handle failure in either mode.
     */
    protected function failure(bool $popup, string $message)
    {
        if ($popup) {
            return view('auth.google-popup-close', [
                'status'   => 'error',
                'message'  => $message,
                'redirect' => null,
            ]);
        }

        return redirect()->route('login')->withErrors(['username' => $message]);
    }

    /**
     * Generate a unique username from an email.
     */
    protected function generateUsername(string $email): string
    {
        $base     = Str::slug(explode('@', $email)[0], '.');
        $username = $base;
        $counter  = 1;

        while (User::where('username', $username)->exists()) {
            $username = $base . $counter;
            $counter++;
        }

        return $username;
    }
}