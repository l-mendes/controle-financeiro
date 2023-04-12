@props(['disabled' => false, 'name'])

@php
    $classes = '';
    
    if ($errors->has($name)) {
        $classes = 'border border-red-500';
    }
@endphp

<input {{ $disabled ? 'disabled' : '' }} name="{{ $name }}" {!! $attributes->merge([
    'class' => "border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm $classes",
]) !!}>
