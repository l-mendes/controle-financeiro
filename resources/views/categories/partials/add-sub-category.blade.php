<div class="mb-2">
    <x-button icon="fa-solid fa-plus" x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'add-sub-category')">
        SUB-CATEGORIA
    </x-button>

    <x-modal name="add-sub-category" :show="$errors->subCategory->isNotEmpty()" focusable title="Adicionar sub-categoria">
        <form method="post" action="{{ route('categories.sub-categories.store', $category->id) }}" class="p-6">
            @csrf
            <div>
                <x-forms.input-label for="sub-category-name" value="Categoria" />

                <x-forms.text-input id="sub-category-name" class="block mt-1 w-3/4" type="text"
                    name="sub-category-name" required value="{{ old('sub-category-name') }}" />

                <x-forms.input-error :messages="$errors->subCategory->get('sub-category-name')" class="mt-2" />
            </div>

            <div class="mt-6">
                <x-forms.input-label for="sub-category-color" value="Cor" />

                <x-forms.text-input id="sub-category-color" name="sub-category-color"
                    class="cursor-pointer w-[30px] h-[30px] sm:w-[40px] sm:h-[40px] outline-none" type="color"
                    required value="{{ old('sub-category-color', '#000000') }}" />

                <x-forms.input-error :messages="$errors->subCategory->get('sub-category-color')" class="mt-2" />
            </div>
            <div class="mt-6">
                <x-button type="submit">
                    SALVAR
                </x-button>
            </div>
        </form>
    </x-modal>
</div>
