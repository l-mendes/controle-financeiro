<nav class="hidden md:block min-h-screen md:w-[260px] overflow-y-auto bg-[#fff] text-gray-600 py-3 px-4">
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
