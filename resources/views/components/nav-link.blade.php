@props(['active', 'label', 'icon'])

@php
$classes = ($active ?? false)
? 'flex items-center gap-3 py-2 px-2 rounded-sm bg-violet-700 transition ease-in-out duration-200 focus:outline-none'
: 'flex items-center gap-3 py-2 px-2 rounded-sm hover:bg-violet-700 transition ease-in-out duration-200';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    <i :class='icon'></i>
    <span>{{$label}}</span>
</a>
