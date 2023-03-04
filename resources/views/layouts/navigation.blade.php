<nav class="hidden md:block min-h-screen md:w-[260px] overflow-y-auto bg-[#fff] text-gray-600 py-3 px-4 shadow">
    <header class="flex flex-1 justify-center border-b border-b-gray-300 py-3">
        <h1 class="sm:text-lg md:text-xl font-semibold">
            <a href="{{route('dashboard')}}">
                Controle Financeiro
            </a>
        </h1>
    </header>

    <div class="mt-5 flex flex-col gap-1">
        @foreach(config('menu') as $menu)
        <x-nav-link :href="route($menu['route'])" :active="request()->routeIs($menu['route'])" :label="$menu['label']" :icon="$menu['icon']" />
        @endforeach
    </div>
</nav>

<div class="md:hidden relative" x-data="{ open: false }">
    <div class="md:hidden flex w-full bg-[#6515DD] px-4 shadow h-[60px] text-white">
        <button @click="open = true" type="button" class="px-2 rounded self-center focus:outline-none focus:ring-2 focus:ring-[#c4a2f6]">
            <i class="fa-solid fa-bars text-2xl"></i>
        </button>
    </div>
    <nav x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90" class="top-0 absolute z-50 h-screen w-screen bg-white overflow-y-auto text-gray-600 py-3 px-4 shadow">
        <header class="flex flex-1 border-b border-b-gray-300 py-6">
            <button type="button" @click="open = false">
                <i class="fa-solid fa-xmark text-2xl"></i>
            </button>
            <h1 class="text-xl font-semibold mx-auto">
                Controle Financeiro
            </h1>
        </header>

        <div class="mt-5 flex flex-col gap-1">
            @foreach(config('menu') as $menu)
            <x-nav-link :href="route($menu['route'])" :active="request()->routeIs($menu['route'])" :label="$menu['label']" :icon="$menu['icon']" />
            @endforeach
        </div>
    </nav>
</div>
