@props(['active'])

@php
$classes = ($active ?? false)
            ? 'group flex gap-x-3 rounded-md bg-slate-800 p-2 text-sm font-semibold leading-6 text-white shadow-sm ring-1 ring-white/10'
            : 'group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-slate-400 hover:text-white hover:bg-slate-800 transition-all duration-200';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
