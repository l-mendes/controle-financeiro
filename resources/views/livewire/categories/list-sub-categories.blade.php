<div>

    <div class="mb-2">
        <x-button icon="fa-solid fa-plus" wire:click.prevent="openAddModal">
            SUB-CATEGORIA
        </x-button>
    </div>

    <x-tables.table>
        <x-slot name="thead">
            <x-tables.th>Sub-categoria</x-tables.th>
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
                    <x-tables.td class="text-lg">
                        <a class="cursor-pointer mr-4 text-blue-500" wire:click.prevent="openEditModal({{$subCategory}})">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <a class="cursor-pointer text-red-500" wire:click.prevent="openDeleteModal({{$subCategory}})">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    </x-tables.td>
                </x-tables.tr>
            @endforeach
        </x-slot>
    </x-tables.table>

    <div class="mt-4">
        {{ $subCategories->links() }}
    </div>

    <x-modal name="edit-sub-category" focusable title="{{ $isEditMode ? 'Editar' : 'Adicionar'}} sub-categoria">
        <form method="post" class="p-6" wire:submit.prevent="{{ $isEditMode ? 'updateSubCategory' : 'createSubCategory' }}">
            <div>
                <x-forms.input-label for="edit-sub-category-name" value="Categoria" />

                <x-forms.text-input id="edit-sub-category-name" class="block mt-1 w-3/4" type="text"
                    name="edit-sub-category-name" required wire:model.defer="subCategory.name" />

                @error('subCategory.name')
                    <x-forms.input-error :messages="$message" class="mt-2" />
                @enderror
            </div>

            <div class="mt-6">
                <x-forms.input-label for="edit-sub-category-color" value="Cor" />

                <x-forms.text-input id="edit-sub-category-color" name="edit-sub-category-color"
                    class="cursor-pointer w-[30px] h-[30px] sm:w-[40px] sm:h-[40px] outline-none" type="color"
                    required wire:model.defer="subCategory.color" />

                    @error('subCategory.color')
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

    <x-modal name="delete-sub-category" focusable title="Remover sub-categoria">
        <div class="p-6 flex gap-5 flex-col">
            <div>
                <h3 class="text-gray-600 font-medium">Deseja realmente excluir a sub-categoria?</h3>
            </div>

            <div class="text-center">
                <x-button color="secondary" x-on:click="$dispatch('close')">
                    Cancelar
                </x-button>

                <x-button wire:click.prevent="deleteSubCategory">
                    Confirmar
                </x-button>
            </div>
        </div>
    </x-modal>
</div>