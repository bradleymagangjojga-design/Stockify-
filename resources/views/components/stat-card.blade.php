@props([
    'label',
    'value',
    'icon' => '📦',
    'color' => 'blue',
    'trend' => null,
    'subtitle' => null,
    'gradient' => false,
])

@php
$colorMap = [
    'blue'   => ['bg' => 'bg-blue-100 dark:bg-blue-500/10',    'text' => 'text-blue-600 dark:text-blue-400'],
    'green'  => ['bg' => 'bg-green-100 dark:bg-green-500/10',  'text' => 'text-green-600 dark:text-green-400'],
    'orange' => ['bg' => 'bg-orange-100 dark:bg-orange-500/10','text' => 'text-orange-600 dark:text-orange-400'],
    'purple' => ['bg' => 'bg-purple-100 dark:bg-purple-500/10','text' => 'text-purple-600 dark:text-purple-400'],
    'red'    => ['bg' => 'bg-red-100 dark:bg-red-500/10',      'text' => 'text-red-600 dark:text-red-400'],
    'slate'  => ['bg' => 'bg-slate-100 dark:bg-slate-500/10',  'text' => 'text-slate-600 dark:text-slate-400'],
];
$c = $colorMap[$color] ?? $colorMap['blue'];
$isGradient = $gradient || $color === 'gradient';
@endphp

@if ($isGradient)
    {{-- Gradient version (for the primary stat) --}}
    <div {{ $attributes->merge(['class' => 'bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl p-5 text-white shadow-lg shadow-blue-500/20 hover:shadow-xl hover:-translate-y-0.5 transition-all']) }}>
        <div class="flex items-start justify-between mb-3">
            <div class="w-11 h-11 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-xl">
                {{ $icon }}
            </div>
            @if ($trend)
                <span class="inline-flex items-center gap-1 text-xs font-medium text-blue-100">
                    {{ $trend }}
                </span>
            @endif
        </div>
        <p class="text-xs font-medium text-blue-100 uppercase tracking-wider mb-1">{{ $label }}</p>
        <p class="text-2xl font-bold">{{ $value }}</p>
        @if ($subtitle)
            <p class="text-xs text-blue-100 mt-1">{{ $subtitle }}</p>
        @endif
    </div>
@else
    {{-- Standard version --}}
    <div {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-5 hover:shadow-lg transition']) }}>
        <div class="flex items-start justify-between mb-3">
            <div class="w-11 h-11 rounded-xl {{ $c['bg'] }} flex items-center justify-center text-xl">
                {{ $icon }}
            </div>
            @if ($trend)
                @php
                    $isPositive = str_starts_with($trend, '+');
                @endphp
                <span class="inline-flex items-center gap-1 text-xs font-medium {{ $isPositive ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if ($isPositive)
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                        @endif
                    </svg>
                    {{ $trend }}
                </span>
            @endif
        </div>
        <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">{{ $label }}</p>
        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $value }}</p>
        @if ($subtitle)
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ $subtitle }}</p>
        @endif
    </div>
@endif
