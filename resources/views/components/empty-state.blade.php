@props([
    'icon' => '📭',
    'title' => 'Belum ada data',
    'description' => 'Data akan muncul di sini setelah ditambahkan',
    'actionLabel' => null,
    'actionUrl' => null,
])

<div {{ $attributes->merge(['class' => 'px-5 py-16 text-center']) }}>
    <div class="inline-flex flex-col items-center max-w-sm">
        <div class="w-20 h-20 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-4xl mb-4">
            {{ $icon }}
        </div>
        <p class="font-semibold text-slate-900 dark:text-white text-lg">{{ $title }}</p>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $description }}</p>

        @if ($actionLabel && $actionUrl)
            <a href="{{ $actionUrl }}"
               class="inline-flex items-center gap-2 mt-5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 px-5 py-2.5 rounded-lg transition shadow-lg shadow-blue-500/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                {{ $actionLabel }}
            </a>
        @endif

        {{ $slot }}
    </div>
</div>
