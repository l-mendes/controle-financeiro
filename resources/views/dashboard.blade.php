<x-app-layout>
    <x-header>
        Dashboard
    </x-header>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            {{ Auth::user()->name }}
        </div>
    </div>
</x-app-layout>
