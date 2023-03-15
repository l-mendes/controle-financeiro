@props(['disabled' => false])

<div x-data="" x-init="window.Inputmask('currency', {
    radixPoint: ',',
    prefix: 'R$ ',
    numericInput: true,
    rightAlign: false,
    autoUnmask: true,
    unmaskAsNumber: true,
    allowMinus: false,
    onBeforeMask: function(value, opts) {
        if (null === value) {
            value = '0.00'
        }
        return value;
    }
}).mask($refs.inputCurrency);">

    <input x-ref="inputCurrency" x-on:change="$dispatch('inputCurrency', $refs.inputCurrency.value)"
        onfocus="this.select();" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
            'class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm',
        ]) !!}>

</div>
