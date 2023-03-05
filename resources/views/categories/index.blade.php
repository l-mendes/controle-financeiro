<x-app-layout>
    <x-header>
        Categorias
    </x-header>
    <div class="mb-4">

        <x-tables.table>
            <x-slot name="thead">
                <x-tables.th>Categoria</x-tables.th>
                <x-tables.th class="text-center">Sub-categorias</x-tables.th>
                <x-tables.th>Tipo</x-tables.th>
                <x-tables.th>Cor</x-tables.th>
                <x-tables.th />
            </x-slot>

            <x-slot name="tbody">
                @foreach ($categories as $category)
                    <x-tables.tr>
                        <x-tables.td>
                            <a href="{{ route('categories.show', $category->id) }}" class="text-primary">
                                {{ $category->name }}
                            </a>
                        </x-tables.td>
                        <x-tables.td class="text-center">
                            <a href="{{ route('categories.show', $category->id) }}" class="text-primary text-center">
                                {{ $category->sub_categories_count }}
                            </a>
                        </x-tables.td>
                        <x-tables.td>
                            <span class="text-[{{ $category->type->getTextColor() }}]">
                                {{ $category->type->getLabelText() }}
                            </span>
                        </x-tables.td>
                        <x-tables.td>
                            <div class="w-5 h-5 rounded-full" style="background: {{ $category->color }}" />
                        </x-tables.td>
                        <x-tables.td>
                            <a href="{{ route('categories.edit', $category->id) }}">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        </x-tables.td>
                    </x-tables.tr>
                @endforeach
            </x-slot>
        </x-tables.table>
    </div>

    {{ $categories->onEachSide(1)->links() }}
</x-app-layout>
