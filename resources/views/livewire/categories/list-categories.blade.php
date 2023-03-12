<div>
    <div class="mb-2">
        <x-button icon="fa-solid fa-plus" wire:click.prevent="openAddModal">
            CATEGORIA
        </x-button>
    </div>

    <x-tables.table>
        <x-slot name="thead">
            <x-tables.th>Categoria</x-tables.th>
            <x-tables.th class="text-center">Sub-categorias</x-tables.th>
            <x-tables.th>Tipo</x-tables.th>
            <x-tables.th>Cor</x-tables.th>
            <x-tables.th></x-tables.th>
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
                        <a class="cursor-pointer text-red-500" wire:click.prevent="openDeleteModal({{$category}})">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    </x-tables.td>
                </x-tables.tr>
            @endforeach
        </x-slot>
    </x-tables.table>

    <div class="mt-4">
        {{ $categories->links() }}
    </div>

    <x-modal name="add-category" focusable title="Adicionar categoria">
        <form method="post" class="p-6" wire:submit.prevent="create">
            <div>
                <x-forms.input-label for="name" value="Categoria" />

                <x-forms.text-input id="name" class="block mt-1 w-full sm:w-3/4" type="text"
                    name="name" required wire:model.defer="category.name" />

                @error('category.name')
                    <x-forms.input-error :messages="$message" class="mt-2" />
                @enderror
            </div>

            <div class="mt-6">
                <x-forms.input-label for="color" value="Cor" />

                <x-forms.text-input id="color" name="color"
                    class="cursor-pointer w-[30px] h-[30px] sm:w-[40px] sm:h-[40px] outline-none" type="color"
                    required wire:model.defer="category.color" />

                    @error('category.color')
                        <x-forms.input-error :messages="$message" class="mt-2" />
                    @enderror
            </div>

            <div class="mt-6">
                <x-forms.input-label for="type" :value="'Tipo'" />

                <x-forms.select id="type" name="type" class="block mt-1 w-full sm:w-3/4" wire:model.defer="category.type" required>
                    @foreach ($types as $type)
                        <option value="{{ $type->value }}">
                            {{ $type->getLabelText() }}
                        </option>
                    @endforeach
                </x-forms.select>

                @error('category.type')
                        <x-forms.input-error :messages="$message" class="mt-2" />
                    @enderror
            </div>

            <div class="mt-6">
                <x-button type="submit">
                    SALVAR
                </x-button>
            </div>
        </form>
    </x-modal>

    <x-modal name="delete-category" focusable title="Remover categoria">
        <div class="p-6 flex gap-5 flex-col">
            <div>
                <h3 class="text-gray-600 font-medium">Deseja realmente excluir a categoria?</h3>
            </div>

            <div class="text-center">
                <x-button color="secondary" x-on:click="$dispatch('close')">
                    Cancelar
                </x-button>

                <x-button wire:click.prevent="delete">
                    Confirmar
                </x-button>
            </div>
        </div>
    </x-modal>
</div>