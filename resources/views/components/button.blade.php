@props(['color' => 'primary', 'icon' => null, 'size' => 'sm'])

@php
    $bgColor = 'primary';
    $textColor = 'text-white';

    switch ($color) {
        case 'success':
            $bgColor = 'green-500';
            break;
        case 'error':
            $bgColor = 'red-500';
            break;
        case 'secondary':
            $bgColor = 'gray-50';
            $textColor = 'text-gray-600';
    }
@endphp

<button
    {{ $attributes->merge(['type' => 'button', 'class' => "inline-flex items-center px-4 py-2 bg-$bgColor border border-gray-300 rounded-md font-semibold text-$size $textColor tracking-widest shadow-sm hover:brightness-75 focus:outline-none focus:ring-2 focus:ring-$bgColor disabled:opacity-25 transition ease-in-out duration-150"]) }}>

    @isset($icon)
        <i class="{{ $icon }} text-{{ $size }} mr-2"></i>
    @endisset
    {{ $slot }}
</button>
