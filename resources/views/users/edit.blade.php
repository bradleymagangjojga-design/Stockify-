@extends('layouts.app')
@section('title', __('Edit User'))

@section('breadcrumb')
    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
    <a href="{{ route('users.index') }}" class="text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition">{{ __('User') }}</a>
    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
    <span class="text-slate-900 dark:text-white font-medium">{{ __('Edit') }}</span>
@endsection

@section('page-header')
<div>
    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Edit User') }}</h1>
    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('Update user account information') }}</p>
</div>
@endsection

@section('content')

<div class="max-w-3xl mx-auto">

    {{-- Preview Card --}}
    <div class="relative bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 mb-6 flex items-center gap-4 overflow-hidden">
        <div class="absolute -top-16 -right-16 w-48 h-48 bg-blue-500/5 dark:bg-blue-500/10 rounded-full blur-3xl"></div>

        <div class="relative w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-2xl flex-shrink-0 shadow-lg shadow-blue-500/20 ring-4 ring-white dark:ring-slate-900">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>

        <div class="relative flex-1 min-w-0">
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-widest text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-500/20 px-2 py-0.5 rounded">
                    {{ __('Editing') }}
                </span>
            </div>
            <h3 class="text-lg font-bold text-slate-900 dark:text-white truncate">{{ $user->name }}</h3>
            <div class="flex items-center gap-2 mt-1 flex-wrap">
                @php
                    $roleClass = match($user->role) {
                        'Admin'          => 'purple',
                        'Manajer Gudang' => 'blue',
                        default          => 'green',
                    };
                    $roleLabel = match($user->role) {
                        'Admin'          => 'Admin',
                        'Manajer Gudang' => __('Warehouse Manager'),
                        default          => __('Warehouse Staff'),
                    };
                @endphp
                <span class="inline-flex items-center text-xs font-semibold px-2.5 py-0.5 rounded-full bg-{{ $roleClass }}-100 dark:bg-{{ $roleClass }}-500/20 text-{{ $roleClass }}-700 dark:text-{{ $roleClass }}-400">
                    {{ $roleLabel }}
                </span>

                @if ($user->status === 'active')
                    <span class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-0.5 rounded-full bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                        {{ __('Active') }}
                    </span>
                @endif

                @if ($user->id === auth()->id())
                    <span class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-0.5 rounded-full bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400">
                        {{ __('Your Account') }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 rounded-2xl bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30 flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl bg-red-100 dark:bg-red-500/20 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm font-semibold text-red-800 dark:text-red-300">{{ __('Validation Failed') }}</p>
                <ul class="list-disc list-inside text-xs text-red-700 dark:text-red-400 space-y-0.5 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">

        {{-- Card Header --}}
        <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-xl flex-shrink-0 shadow-lg shadow-blue-500/20">
                ✏️
            </div>
            <div>
                <h2 class="font-semibold text-slate-900 dark:text-white">{{ __('Edit Information') }}</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ __('Update account data') }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('users.update', $user) }}">
            @csrf @method('PUT')

            <div class="p-6 space-y-5">
                <div>
                    <label for="username" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2.5">
                        {{ __('Username') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" required
                           class="w-full px-4 py-3 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 focus:bg-white dark:focus:bg-slate-800 transition">
                    @error('username')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2.5">
                        {{ __('Full Name') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-4 py-3 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 focus:bg-white dark:focus:bg-slate-800 transition">
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ✅ Password with Eye Toggle moved inside --}}
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2.5">
                        {{ __('New Password') }}
                        <span class="text-xs font-normal text-slate-500 dark:text-slate-400 ml-1">({{ __('leave empty to keep unchanged') }})</span>
                    </label>
                    <div class="relative">
                        <input type="password" id="password" name="password"
                               placeholder="••••••••"
                               class="w-full px-4 py-3 pr-12 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 focus:bg-white dark:focus:bg-slate-800 transition">

                        <button type="button" onclick="togglePassword('password', 'eyeIcon-password', 'eyeOffIcon-password')"
                                class="absolute inset-y-0 right-4 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition">
                            <svg id="eyeIcon-password" class="w-5 h-5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="eyeOffIcon-password" class="w-5 h-5 hidden pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                    <div>
                        <label for="role" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2.5">
                            {{ __('Role') }} <span class="text-red-500">*</span>
                        </label>
                        <select id="role" name="role" required
                                class="w-full px-4 py-3 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 focus:bg-white dark:focus:bg-slate-800 transition">
                            @foreach (['Admin' => 'Admin', 'Manajer Gudang' => __('Warehouse Manager'), 'Staff Gudang' => __('Warehouse Staff')] as $value => $label)
                                <option value="{{ $value }}" @selected(old('role', $user->role) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2.5">
                            {{ __('Status') }} <span class="text-red-500">*</span>
                        </label>
                        <select id="status" name="status" required
                                class="w-full px-4 py-3 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 focus:bg-white dark:focus:bg-slate-800 transition">
                            <option value="active" @selected(old('status', $user->status) === 'active')>{{ __('Active') }}</option>
                            <option value="inactive" @selected(old('status', $user->status) === 'inactive')>{{ __('Inactive') }}</option>
                        </select>
                    </div>
                </div>

                {{-- Did You Know --}}
                <div class="flex items-start gap-3 p-4 rounded-xl bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/30">
                    <div class="w-9 h-9 rounded-lg bg-amber-100 dark:bg-amber-500/20 flex items-center justify-center flex-shrink-0 text-lg">
                        💡
                    </div>
                    <div class="flex-1">
                        <p class="text-xs font-semibold text-amber-900 dark:text-amber-300">{{ __('Did you know?') }}</p>
                        <p class="text-xs text-amber-700 dark:text-amber-400 mt-0.5 leading-relaxed">
                            {{ __('Changing this user\'s role will immediately affect what pages and features they can access. Make sure to inform them about the change.') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Card Footer --}}
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/30 flex items-center justify-end gap-3">
                <a href="{{ route('users.index') }}"
                   class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 px-5 py-3 rounded-xl transition">
                    {{ __('Cancel') }}
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 px-5 py-3 rounded-xl transition shadow-lg shadow-blue-500/30 hover:shadow-xl hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ __('Update User') }}
                </button>
            </div>

        </form>

    </div>
</div>

@push('scripts')
<script>
    function togglePassword(inputId, eyeIconId, eyeOffIconId) {
        const input = document.getElementById(inputId);
        const eyeIcon = document.getElementById(eyeIconId);
        const eyeOffIcon = document.getElementById(eyeOffIconId);
        if (!input) return;

        if (input.type === 'password') {
            input.type = 'text';
            eyeIcon?.classList.add('hidden');
            eyeOffIcon?.classList.remove('hidden');
        } else {
            input.type = 'password';
            eyeIcon?.classList.remove('hidden');
            eyeOffIcon?.classList.add('hidden');
        }
    }
</script>
@endpush

@endsection
