<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'active');

        $query = User::query();

        // Tab filtering
        match ($tab) {
            'active'   => $query->where('status', 'active'),
            'new'      => $query->where('created_at', '>=', now()->subDays(7)),
            'staff'    => $query->where('role', 'Staff Gudang'),
            'manager'  => $query->where('role', 'Manajer Gudang'),
            'admin'    => $query->where('role', 'Admin'),
            'inactive' => $query->where('status', '!=', 'active'),
            default    => $query->where('status', 'active'),
        };

        // Search
        $query->when($request->filled('search'), function ($q) use ($request) {
            $term = $request->search;
            $q->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('username', 'like', "%{$term}%")
                  ->orWhere('email', 'like', "%{$term}%");
            });
        });

        // Sort: Staff → Manager → Admin, then by name
        $query->orderByRaw("CASE role
            WHEN 'Staff Gudang'   THEN 1
            WHEN 'Manajer Gudang' THEN 2
            WHEN 'Admin'          THEN 3
            ELSE 4 END")
            ->orderBy('name');

        $users = $query->paginate(25)->withQueryString();

        // Tab counts
        $counts = [
            'active'   => User::where('status', 'active')->count(),
            'new'      => User::where('created_at', '>=', now()->subDays(7))->count(),
            'staff'    => User::where('role', 'Staff Gudang')->count(),
            'manager'  => User::where('role', 'Manajer Gudang')->count(),
            'admin'    => User::where('role', 'Admin')->count(),
            'inactive' => User::where('status', '!=', 'active')->count(),
        ];

        return view('users.index', compact('users', 'tab', 'counts'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('users', 'username')],
            'email'    => ['nullable', 'email', 'max:150', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:6'],
            'role'     => ['required', 'in:Admin,Manajer Gudang,Staff Gudang'],
            'status'   => ['required', 'in:active,inactive'],
        ]);

        $data['password'] = Hash::make($data['password']);
        User::create($data);

        return redirect()->route('users.index')->with('success', __('User created successfully.'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash',
                           Rule::unique('users', 'username')->ignore($user->id)],
            'email'    => ['nullable', 'email', 'max:150',
                           Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6'],
            'role'     => ['required', 'in:Admin,Manajer Gudang,Staff Gudang'],
            'status'   => ['required', 'in:active,inactive'],
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', __('User updated successfully.'));
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', __('You cannot delete your own account.'));
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', __('User deleted successfully.'));
    }
}
