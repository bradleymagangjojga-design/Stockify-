@props([
    'color' => 'slate',
    'icon' => null,
    'dot' => false,
    'size' => 'md',
])

@php
$colorMap = [
    'blue'   => 'bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400',
    'green'  => 'bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400',
    'red'    => 'bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400',
    'orange' => 'bg-orange-100 dark:bg-orange-500/20 text-orange-700 dark:text-orange-400',
    'yellow' => 'bg-yellow-100 dark:bg-yellow-500/20 text-yellow-700 dark:text-yellow-400',
    'purple' => 'bg-purple-100 dark:bg-purple-500/20 text-purple-700 dark:text-purple-400',
    'pink'   => 'bg-pink-100 dark:bg-pink-500/20 text-pink-700 dark:text-pink-400',
    'slate'  => 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400',
    'indigo' => 'bg-indigo-100 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-400',
];

$dotMap = [
    'blue' => 'bg-blue-500', 'green' => 'bg-green-500', 'red' => 'bg-red-500',
    'orange' => 'bg-orange-500', 'yellow' => 'bg-yellow-500', 'purple' => 'bg-purple-500',
    'pink' => 'bg-pink-500', 'slate' => 'bg-slate-400', 'indigo' => 'bg-indigo-500',
];

$sizeMap = [
    'sm' => 'text-[10px] px-2 py-0.5',
    'md' => 'text-xs px-2.5 py-1',
    'lg' => 'text-sm px-3 py-1.5',
];

$colorClass = $colorMap[$color] ?? $colorMap['slate'];
$dotClass   = $dotMap[$color] ?? $dotMap['slate'];
$sizeClass  = $sizeMap[$size] ?? $sizeMap['md'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 font-medium rounded-full $colorClass $sizeClass"]) }}>
    @if ($dot)
        <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }}"></span>
    @endif
    @if ($icon)
        <span>{{ $icon }}</span>
    @endif
    {{ $slot }}
</span>
