@props(['disabled' => false, 'label' => null, 'size' => 'sm', 'id'])

@php
    switch ($size) {
        case 'md':
            $classes = 'w-5 h-5';
            break;
        case 'lg':
            $classes = 'w-6 h-6';
            break;
        case 'xl':
            $classes = 'w-7 h-7';
            break;
        default:
            $classes = 'w-4 h-4';
            break;
    }
@endphp
<div class="flex items-center">
    <input id="{{ $id }}" type="checkbox" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
        'class' => "cursor-pointer border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm $classes",
    ]) !!}>
    <label for="{{ $id }}" class="cursor-pointer ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
        {{ $label }}
    </label>
</div>
