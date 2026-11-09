<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show the profile page.
     */
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'total_transactions' => Transaction::where('user_id', $user->id)->count(),
            'total_incoming'     => Transaction::where('user_id', $user->id)->where('type', 'in')->sum('quantity'),
            'total_outgoing'     => Transaction::where('user_id', $user->id)->where('type', 'out')->sum('quantity'),
            'account_age_days'   => $user->created_at->diffInDays(now()),
        ];

        return view('profile.index', compact('user', 'stats'));
    }

    /**
     * Update profile info (name, username).
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
        ]);

        $user->forceFill($data)->save();

        return back()->with('success', __('Profile updated successfully.'));
    }

    /**
     * Update password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password'     => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user();

        // Verify current password
        if (!Hash::check($request->input('current_password'), $user->password)) {
            return back()->withErrors([
                'current_password' => __('Current password is incorrect.'),
            ]);
        }

        // Update password
        $user->forceFill([
            'password' => Hash::make($request->input('new_password')),
        ])->save();

        // Verify the hash was actually saved
        $user->refresh();

        if (!Hash::check($request->input('new_password'), $user->password)) {
            Log::error("Profile password update FAILED verification for user ID {$user->id}");
            return back()->withErrors([
                'new_password' => 'Password could not be saved. Please try again.',
            ]);
        }

        Log::info("Profile password update SUCCESS for user ID {$user->id} ({$user->email})");

        return back()->with('success', __('Password updated successfully.'));
    }

    /**
     * Upload or replace the authenticated user's avatar.
     *
     * Route: POST /profile/avatar
     * Name:  profile.avatar
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048', // 2MB max
        ]);

        $user = Auth::user();

        // Delete old avatar if exists
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Store new avatar under storage/app/public/avatars/
        $path = $request->file('avatar')->store('avatars', 'public');

        $user->forceFill(['avatar' => $path])->save();

        return response()->json([
            'success'    => true,
            'message'    => __('Profile picture updated successfully.'),
            'avatar_url' => Storage::url($path),
        ]);
    }

    /**
     * Remove the authenticated user's avatar.
     *
     * Route: DELETE /profile/avatar
     * Name:  profile.avatar.remove
     */
    public function removeAvatar(Request $request)
    {
        $user = Auth::user();

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->forceFill(['avatar' => null])->save();

        return back()->with('success', __('Profile picture removed.'));
    }
}