@props([
    'name' => '?',
    'initials' => null,
    'size' => 'md',  // sm | md | lg | xl
    'color' => 'blue',
    'gradient' => true,
])

@php
$sizeMap = [
    'sm' => 'w-8 h-8 text-xs',
    'md' => 'w-10 h-10 text-sm',
    'lg' => 'w-14 h-14 text-lg',
    'xl' => 'w-16 h-16 text-2xl',
];

$colorMap = [
    'blue'   => 'from-blue-500 to-indigo-600',
    'green'  => 'from-green-500 to-emerald-600',
    'purple' => 'from-purple-500 to-pink-500',
    'orange' => 'from-orange-500 to-red-500',
    'slate'  => 'from-slate-500 to-slate-700',
];

$initials = $initials ?? strtoupper(substr($name, 0, 1));
$sizeClass = $sizeMap[$size] ?? $sizeMap['md'];
$colorClass = $colorMap[$color] ?? $colorMap['blue'];
@endphp

@if ($gradient)
    <div {{ $attributes->merge(['class' => "rounded-xl bg-gradient-to-br $colorClass flex items-center justify-center text-white font-bold flex-shrink-0 shadow-lg $sizeClass"]) }}>
        {{ $initials }}
    </div>
@else
    <div {{ $attributes->merge(['class' => "rounded-xl bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-slate-700 dark:text-slate-300 font-bold flex-shrink-0 $sizeClass"]) }}>
        {{ $initials }}
    </div>
@endif
