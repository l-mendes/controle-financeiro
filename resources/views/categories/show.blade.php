<x-app-layout>
    <x-header>
        <x-slot name="backLink">
            <a href="{{ route('categories.index') }}" class="text-primary text-xl">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        </x-slot>
        Categorias - {{ $category->name }}
    </x-header>
    <div class="mb-4 flex flex-col gap-6">

        <div class="bg-white overflow-hidden shadow rounded-lg p-6 flex flex-col gap-6">
            <h1 class="text-gray-400 font-semibold">Detalhes</h1>
            <div>
                <div class="text-gray-500">
                    <span>Categoria:</span>
                </div>

                <div class="text-gray-500 font-semibold text-xl">
                    <span>{{ $category->name }}</span>
                </div>
            </div>

            <div>
                <div class="text-gray-500">
                    <span>Tipo:</span>
                </div>

                <div class="text-xl">
                    <span class="text-[{{ $category->type->getTextColor() }}]">
                        {{ $category->type->getLabelText() }}
                    </span>
                </div>
            </div>

            <div>
                <div class="text-gray-500">
                    <span>Cor:</span>
                </div>

                <div class="text-gray-600 font-semibold text-xl">
                    <div class="w-5 h-5 rounded-full" style="background: {{ $category->color }}"></div>
                </div>
            </div>
        </div>

        <x-tables.table>
            <x-slot name="thead">
                <x-tables.th>Subcategoria</x-tables.th>
                <x-tables.th>Cor</x-tables.th>
                <x-tables.th />
            </x-slot>

            <x-slot name="tbody">
                @foreach ($subCategories as $subCategory)
                    <x-tables.tr>
                        <x-tables.td>
                            {{ $subCategory->name }}
                        </x-tables.td>
                        <x-tables.td>
                            <div class="w-5 h-5 rounded-full" style="background: {{ $subCategory->color }}" />
                        </x-tables.td>
                        <x-tables.td>
                            <a href="{{ route('categories.edit', $subCategory->id) }}">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        </x-tables.td>
                    </x-tables.tr>
                @endforeach
            </x-slot>
        </x-tables.table>
    </div>

    {{ $subCategories->onEachSide(1)->links() }}
</x-app-layout>
