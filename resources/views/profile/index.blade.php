@extends('layouts.app')
@section('title', __('My Profile'))

@section('breadcrumb')
    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
    <span class="text-slate-900 dark:text-white font-medium">{{ __('My Profile') }}</span>
@endsection

@section('page-header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('My Profile') }}</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('Manage your account information and security') }}</p>
    </div>
</div>
@endsection

@section('content')

@php
    $avatarUrl = $user->avatar ? Storage::url($user->avatar) : null;
    $initials  = strtoupper(substr($user->name ?? 'U', 0, 1));
@endphp

{{-- ============================================================
     PROFILE HEADER CARD + AVATAR UPLOAD
============================================================ --}}
<div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden mb-6">
    <div class="p-6">

        @if (session('success'))
            <div class="mb-5 flex items-start gap-3 p-3 text-sm rounded-xl bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/30 text-green-800 dark:text-green-300">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any() && !$errors->has('current_password') && !$errors->has('new_password'))
            <div class="mb-5 flex items-start gap-3 p-3 text-sm rounded-xl bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30 text-red-800 dark:text-red-300">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">

            {{-- AVATAR with hover-to-upload overlay --}}
            <div class="relative flex-shrink-0 group">

                <form id="avatarForm" method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data" class="hidden">
                    @csrf
                    <input type="file" id="avatarInput" name="avatar" accept="image/png,image/jpeg,image/jpg,image/webp">
                </form>

                <div id="avatarPreviewWrapper"
                     class="relative w-24 h-24 rounded-2xl overflow-hidden bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-4xl shadow-lg shadow-blue-500/20">

                    @if ($avatarUrl)
                        <img id="avatarImage" src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="w-full h-full object-cover object-center">
                        <span id="avatarInitials" class="hidden">{{ $initials }}</span>
                    @else
                        <img id="avatarImage" src="" alt="" class="w-full h-full object-cover object-center hidden">
                        <span id="avatarInitials">{{ $initials }}</span>
                    @endif

                    <button type="button"
                            onclick="document.getElementById('avatarInput').click()"
                            class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 flex flex-col items-center justify-center text-white transition-opacity cursor-pointer">
                        <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="text-[10px] font-semibold uppercase tracking-wider">{{ __('Change') }}</span>
                    </button>
                </div>

                {{-- Upload spinner --}}
                <div id="avatarSpinner"
                     class="hidden absolute inset-0 rounded-2xl bg-black/60 flex items-center justify-center">
                    <svg class="animate-spin w-8 h-8 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                </div>
            </div>

            {{-- User Info --}}
            <div class="flex-1 min-w-0 text-center sm:text-left">
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $user->name }}</h2>

                <div class="flex items-center justify-center sm:justify-start gap-2 mt-2 flex-wrap">
                    <span class="inline-flex items-center gap-1 bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400 text-xs font-medium px-2.5 py-1 rounded-full">
                        {{ ucfirst($user->role) }}
                    </span>
                    @if ($user->status === 'active')
                        <span class="inline-flex items-center gap-1 bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400 text-xs font-medium px-2.5 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                            {{ __('Active') }}
                        </span>
                    @endif
                    @if ($user->email)
                        <span class="text-xs text-slate-500 dark:text-slate-400">{{ $user->email }}</span>
                    @endif
                </div>

                {{-- Avatar action buttons --}}
                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mt-4">
                    <button type="button"
                            onclick="document.getElementById('avatarInput').click()"
                            class="inline-flex items-center gap-2 text-xs font-semibold text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-500/10 hover:bg-blue-100 dark:hover:bg-blue-500/20 border border-blue-200 dark:border-blue-500/30 px-3 py-1.5 rounded-lg transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        {{ __('Upload Photo') }}
                    </button>

                    @if ($avatarUrl)
                        <form method="POST" action="{{ route('profile.avatar.remove') }}" class="inline" id="removeAvatarForm">
                            @csrf @method('DELETE')
                            <button type="button" id="removeAvatarBtn"
                                    class="inline-flex items-center gap-2 text-xs font-semibold text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-500/10 hover:bg-red-100 dark:hover:bg-red-500/20 border border-red-200 dark:border-red-500/30 px-3 py-1.5 rounded-lg transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                {{ __('Remove') }}
                            </button>
                        </form>
                    @endif
                </div>

                <p class="text-xs text-slate-400 dark:text-slate-500 mt-3">
                    {{ __('JPG, PNG, or WEBP. Max 2MB. Recommended 400×400px.') }}
                </p>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================
     FORMS GRID
============================================================ --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Profile Info Form --}}
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden">
        <div class="p-5 border-b border-slate-200 dark:border-slate-800">
            <h3 class="font-semibold text-slate-900 dark:text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                {{ __('Profile Information') }}
            </h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ __('Update your name and username') }}</p>
        </div>

        <form method="POST" action="{{ route('profile.update') }}" class="p-5 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    {{ __('Full Name') }}
                </label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                       class="w-full px-4 py-2.5 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition">
                @error('name')
                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="username" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    {{ __('Username') }}
                </label>
                <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" required
                       class="w-full px-4 py-2.5 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition">
                @error('username')
                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    {{ __('Role') }}
                </label>
                <input type="text" value="{{ ucfirst($user->role) }}" disabled
                       class="w-full px-4 py-2.5 text-sm bg-slate-100 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-500 dark:text-slate-500 cursor-not-allowed">
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                    {{ __('Role can only be changed by Administrator') }}
                </p>
            </div>

            <div class="pt-2">
                <button type="submit"
                        class="inline-flex items-center gap-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 px-5 py-2.5 rounded-lg transition shadow-lg shadow-blue-500/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ __('Save Profile') }}
                </button>
            </div>
        </form>
    </div>

    {{-- Password Form --}}
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden">
        <div class="p-5 border-b border-slate-200 dark:border-slate-800">
            <h3 class="font-semibold text-slate-900 dark:text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                {{ __('Change Password') }}
            </h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ __('Change your account password') }}</p>
        </div>

        <form method="POST" action="{{ route('profile.password') }}" class="p-5 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="current_password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    {{ __('Current Password') }}
                </label>
                <input type="password" id="current_password" name="current_password" required
                       placeholder="{{ __('Enter old password') }}"
                       class="w-full px-4 py-2.5 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition">
                @error('current_password')
                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="new_password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    {{ __('New Password') }}
                </label>
                <input type="password" id="new_password" name="new_password" required
                       placeholder="{{ __('Minimum 6 characters') }}"
                       class="w-full px-4 py-2.5 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition">
                @error('new_password')
                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="new_password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    {{ __('Confirm New Password') }}
                </label>
                <input type="password" id="new_password_confirmation" name="new_password_confirmation" required
                       placeholder="{{ __('Repeat new password') }}"
                       class="w-full px-4 py-2.5 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition">
            </div>

            <div class="pt-2">
                <button type="submit"
                        class="inline-flex items-center gap-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 px-5 py-2.5 rounded-lg transition shadow-lg shadow-red-500/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    {{ __('Change Password') }}
                </button>
            </div>
        </form>
    </div>

</div>

{{-- ============================================================
     CUSTOM DELETE CONFIRMATION MODAL
============================================================ --}}
<div id="deleteModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-800 w-full max-w-md overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="deleteModalContent">
        <div class="p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-500/20 flex items-center justify-center flex-shrink-0 text-red-600 dark:text-red-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Remove Profile Picture') }}</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Are you sure?') }}</p>
                </div>
            </div>

            <p class="text-sm text-slate-600 dark:text-slate-400 mb-6 leading-relaxed">
                {{ __('This will permanently delete your profile picture. You can always upload a new one later.') }}
            </p>

            <div class="flex items-center justify-end gap-3">
                <button type="button" id="cancelDeleteBtn" class="px-5 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl transition">
                    {{ __('Cancel') }}
                </button>
                <button type="button" id="confirmDeleteBtn" class="px-5 py-2.5 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl transition shadow-lg shadow-red-500/30">
                    {{ __('Delete') }}
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================
     AVATAR UPLOAD & DELETE MODAL SCRIPTS
============================================================ --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ============================================================
    // 1. AVATAR UPLOAD — AJAX (no page reload required)
    // ============================================================
    const input    = document.getElementById('avatarInput');
    const spinner  = document.getElementById('avatarSpinner');
    const img      = document.getElementById('avatarImage');
    const initials = document.getElementById('avatarInitials');
    const form     = document.getElementById('avatarForm');
    const CSRF     = document.querySelector('meta[name="csrf-token"]')?.content || '';

    if (input) {
        input.addEventListener('change', function () {
            const file = this.files?.[0];
            if (!file) return;

            // Client-side validation
            if (file.size > 2 * 1024 * 1024) {
                alert('{{ __('File is too large. Maximum size is 2MB.') }}');
                this.value = '';
                return;
            }

            const allowed = ['image/png', 'image/jpeg', 'image/jpg', 'image/webp'];
            if (!allowed.includes(file.type)) {
                alert('{{ __('Invalid file type. Only JPG, PNG, or WEBP allowed.') }}');
                this.value = '';
                return;
            }

            // Optimistic preview
            const reader = new FileReader();
            reader.onload = (e) => {
                img.src = e.target.result;
                img.classList.remove('hidden');
                if (initials) initials.classList.add('hidden');
            };
            reader.readAsDataURL(file);

            // Show spinner
            spinner.classList.remove('hidden');

            // Upload via AJAX
            const fd = new FormData();
            fd.append('avatar', file);

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: fd,
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    if (data.avatar_url) {
                        img.src = data.avatar_url + '?t=' + Date.now();
                    }
                    if (typeof showToast === 'function') {
                        showToast(data.message || '{{ __('Profile picture updated') }}', 'success');
                    }
                    // Reload after short delay so navbar avatar updates
                    setTimeout(() => location.reload(), 800);
                } else {
                    alert(data.message || '{{ __('Upload failed') }}');
                    location.reload();
                }
            })
            .catch(err => {
                console.error('Avatar upload failed:', err);
                alert('{{ __('Failed to upload picture. Please try again.') }}');
                location.reload();
            })
            .finally(() => {
                spinner.classList.add('hidden');
                input.value = '';
            });
        });
    }

    // ============================================================
    // 2. CUSTOM DELETE CONFIRMATION MODAL
    // ============================================================
    const removeBtn = document.getElementById('removeAvatarBtn');
    const deleteModal = document.getElementById('deleteModal');
    const deleteModalContent = document.getElementById('deleteModalContent');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const removeAvatarForm = document.getElementById('removeAvatarForm');

    if (removeBtn && deleteModal) {
        // Open modal
        removeBtn.addEventListener('click', () => {
            deleteModal.classList.remove('hidden');
            deleteModal.classList.add('flex');
            // Trigger animation
            setTimeout(() => {
                deleteModalContent.classList.remove('scale-95', 'opacity-0');
                deleteModalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
        });

        // Close modal function
        const closeModal = () => {
            deleteModalContent.classList.remove('scale-100', 'opacity-100');
            deleteModalContent.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                deleteModal.classList.add('hidden');
                deleteModal.classList.remove('flex');
            }, 200);
        };

        // Cancel button
        cancelDeleteBtn.addEventListener('click', closeModal);

        // Confirm delete button
        confirmDeleteBtn.addEventListener('click', () => {
            removeAvatarForm.submit();
        });

        // Close on click outside the modal content
        deleteModal.addEventListener('click', (e) => {
            if (e.target === deleteModal) {
                closeModal();
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !deleteModal.classList.contains('hidden')) {
                closeModal();
            }
        });
    }
});
</script>

@endsection
