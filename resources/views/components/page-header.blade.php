@props([
    'title',
    'description' => null,
    'actions' => null,
])

<div {{ $attributes->merge(['class' => 'flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4']) }}>
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $title }}</h1>
        @if ($description)
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $description }}</p>
        @endif
    </div>

    @if ($actions)
        <div class="flex items-center gap-2 flex-wrap">
            {{ $actions }}
        </div>
    @endif

    {{ $slot }}
</div>
