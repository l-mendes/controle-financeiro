<x-slot name="header">
    <div class="inline-flex gap-4">
        @if (isset($backLink))
            {{ $backLink }}
        @endif
        <h2 class="font-semibold text-xl text-gray-600">
            {{ $slot }}
        </h2>
    </div>
</x-slot>
