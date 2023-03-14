@props(['disabled' => false])

<div x-data="" x-init="window.Inputmask({
    alias: 'datetime',
    autoUnmask: true,
    removeMaskOnSubmit: true,
    inputFormat: 'dd/mm/yyyy HH:MM',
    outputFormat: 'yyyy-mm-dd HH:MM',
    placeholder: '__/__/____ __:__',
    clearIncomplete: true,
    onBeforeMask: function(value, opts) {
        if (null === value) {
            value = '__/__/____ __:__'
        }
        return value;
    }
}).mask($refs.inputDatetime);">
    <input x-ref="inputDatetime" x-on:change="$dispatch('inputDatetime', $refs.inputDatetime.value)"
        {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
            'class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm',
        ]) !!}>
</div>
