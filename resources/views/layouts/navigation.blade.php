<nav class="hidden md:block min-h-screen md:w-[260px] overflow-y-auto bg-violet-900 text-gray-100 py-3 px-4">
    <header class="flex flex-1 justify-center border-b border-b-gray-500 py-3">
        <h1 class="sm:text-lg md:text-xl font-medium">
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
