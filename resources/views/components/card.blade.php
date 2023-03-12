@props(['title' => null, 'icon' => null])

<div {{ $attributes->merge(['class' => 'bg-white overflow-hidden shadow rounded-lg']) }}>
    @if($title)
        <div class="border-b-2 border-gray-300 p-4 text-gray-600 flex items-center gap-4">
            @if($icon)
                <i class="{{$icon}} text-lg"></i>
            @endif
            <h2 class="font-semibold text-lg truncate">
                {{ $title }}
            </h2>
        </div>
    @endif
    <div class="p-6">
        {{ $slot }}
    </div>
</div>
