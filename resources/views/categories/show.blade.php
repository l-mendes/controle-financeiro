<x-app-layout>
    <x-header>
        <x-slot name="backLink">
            <a href="{{ route('categories.index') }}" class="text-primary text-xl">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        </x-slot>
        Categorias - {{ $category->name }}
    </x-header>
    @if (session('success'))
        <x-alerts.alert-success>
            {{ session('success') }}
        </x-alerts.alert-success>
    @endif
    <x-card class="mb-5">
        <form action="{{ route('categories.update', $category->id) }}" method="POST" class="flex flex-col gap-4">
            @method('PUT')
            @csrf
            <div>
                <x-forms.input-label for="name" value="Categoria" />

                <x-forms.text-input id="name" class="block mt-1 w-full sm:w-[50%]" type="text" name="name"
                    required value="{{ old('name', $category->name) }}" />

                <x-forms.input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-forms.input-label for="color" value="Cor" />

                <x-forms.text-input id="color"
                    class="cursor-pointer w-[30px] h-[30px] sm:w-[40px] sm:h-[40px] outline-none" type="color"
                    name="color" required value="{{ old('color', $category->color) }}" />

                <x-forms.input-error :messages="$errors->get('color')" class="mt-2" />
            </div>

            <div>
                <x-forms.input-label for="type" :value="'Tipo'" />

                <x-forms.select id="type" name="type" class="block mt-1 w-full sm:w-[50%]">
                    @foreach ($categoryTypes as $type)
                        <option {{ old('type', $category->type->value) == $type->value ? 'selected' : '' }}
                            value="{{ $type->value }}">
                            {{ $type->getLabelText() }}
                        </option>
                    @endforeach
                </x-forms.select>

                <x-forms.input-error :messages="$errors->get('type')" class="mt-2" />
            </div>

            <div class="mt-2">
                <x-button type="submit">
                    SALVAR
                </x-button>
            </div>
        </form>
    </x-card>

    @include('categories.partials.add-sub-category')

    <div x-data="{ name: '', color: '#000000' }">
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
                        <x-tables.td>
                            @include('categories.partials.edit-sub-category', ['subCategory' => $subCategory])
                        </x-tables.td>
                    </x-tables.tr>
                @endforeach
            </x-slot>
        </x-tables.table>

        {{ $subCategories->onEachSide(1)->links() }}


    </div>
</x-app-layout>
