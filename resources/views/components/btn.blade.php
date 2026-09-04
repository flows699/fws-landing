@props([
    'variant' => 'dark',
    'href' => null,
])

@php
    $variants = [
        'dark' => 'border-ink bg-ink text-white hover:bg-body hover:border-body',
        'light' => 'border-white bg-white text-ink hover:bg-white/85 hover:border-white/85',
        'outline' => 'border-white/40 bg-transparent text-white hover:bg-white/10',
    ];

    $classes = 'inline-flex h-[43px] items-center justify-center border px-[19px] font-mono text-[12px] leading-[19.2px] tracking-[0.96px] transition-colors '
        . ($variants[$variant] ?? $variants['dark']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->class($classes) }}>{{ $slot }}</a>
@else
    <button type="{{ $attributes->get('type', 'button') }}" {{ $attributes->except('type')->class($classes) }}>{{ $slot }}</button>
@endif
