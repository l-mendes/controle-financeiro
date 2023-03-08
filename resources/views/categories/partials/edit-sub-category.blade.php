<a icon="a-solid fa-pen-to-square" x-data=""
    x-on:click.prevent="$dispatch('open-modal', 'edit-sub-category-{{$subCategory->id}}')">
    <i class="fa-solid fa-pen-to-square"></i>
</a>

<x-modal name="edit-sub-category-{{$subCategory->id}}" :show="$errors->editSubCategory->isNotEmpty()" focusable title="Editar sub-categoria">
    <form method="post" action="{{ route('categories.sub-categories.store', $category->id) }}" class="p-6">
        @csrf
        <div>
            <x-forms.input-label for="edit-sub-category-name-{{$subCategory->id}}" value="Categoria" />

            <x-forms.text-input id="edit-sub-category-name-{{$subCategory->id}}" class="block mt-1 w-3/4" type="text"
                name="edit-sub-category-name" required value="{{ old('edit-sub-category-name', $subCategory->name) }}" />

            <x-forms.input-error :messages="$errors->editSubCategory->get('edit-sub-category-name')" class="mt-2" />
        </div>

        <div class="mt-6">
            <x-forms.input-label for="edit-sub-category-color-{{$subCategory->id}}" value="Cor" />

            <x-forms.text-input id="edit-sub-category-color-{{$subCategory->id}}" name="edit-sub-category-color"
                class="cursor-pointer w-[30px] h-[30px] sm:w-[40px] sm:h-[40px] outline-none" type="color"
                required value="{{ old('edit-sub-category-color', $subCategory->color) }}" />

            <x-forms.input-error :messages="$errors->editSubCategory->get('edit-sub-category-color')" class="mt-2" />
        </div>
        <div class="mt-6">
            <x-button type="submit">
                SALVAR
            </x-button>
        </div>
    </form>
</x-modal>