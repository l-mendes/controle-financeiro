@props(['active', 'label', 'icon'])

@php
$classes = ($active ?? false)
? 'flex items-center gap-3 py-2 px-2 rounded-md bg-primary text-white transition ease-in-out duration-200 focus:outline-none'
: 'flex items-center gap-3 py-2 px-2 rounded-md hover:bg-primary hover:text-white transition ease-in-out duration-200 focus:outline-none';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    <i class="{{$icon}}"></i>
    {{ $label }}
</a>
