<?php

namespace App\Http\Controllers;

use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use LogsActivity;

    /**
     * Show the unified login page with role tabs.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /**
     * Handle login attempt.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'role'     => 'nullable|string',
        ]);

        $attempt = [
            'username' => $credentials['username'],
            'password' => $credentials['password'],
            'status'   => 'active',
        ];

        if (!Auth::attempt($attempt)) {
            // ✅ Log failed login attempt (security audit)
            $this->logActivity(
                'login_failed',
                'Failed login attempt for username: ' . $credentials['username'],
                null,
                [
                    'user_id'    => null,
                    'ip_address' => $request->ip(),
                ]
            );

            return back()
                ->withErrors(['username' => __('Username or password is incorrect.')])
                ->withInput(['role' => $credentials['role'] ?? null])
                ->onlyInput('username');
        }

        $user = Auth::user();

        // Enforce role match if a role tab was selected
        if (!empty($credentials['role']) && $user->role !== $credentials['role']) {
            Auth::logout();

            $this->logActivity(
                'login_failed',
                "Role mismatch for {$user->username}: tried to login as {$credentials['role']}",
                $user,
                ['user_id' => $user->id]
            );

            return back()
                ->withErrors([
                    'username' => __('This account is not a :role. Please select the correct tab.', [
                        'role' => $credentials['role'],
                    ]),
                ])
                ->withInput(['role' => $credentials['role'] ?? null])
                ->onlyInput('username');
        }

        $request->session()->regenerate();

        // ✅ LOG SUCCESSFUL LOGIN
        $this->logActivity(
            'login',
            "{$user->name} ({$user->role}) logged in successfully",
            $user
        );

        return redirect()
            ->intended(route('dashboard'))
            ->with('success', __('Welcome, :name', ['name' => $user->name]));
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        $user = Auth::user();

        // ✅ LOG LOGOUT BEFORE clearing the session
        if ($user) {
            $this->logActivity(
                'logout',
                "{$user->name} ({$user->role}) logged out",
                $user
            );
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', __('You have been logged out successfully.'));
    }
}
