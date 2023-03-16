<div>
    <x-card title="Transações realizadas" icon="fa-solid fa-arrow-right-arrow-left">
        <div class="shadow-lg rounded-lg mb-6 border-2 border-gray-200 p-4">
            <h1>Período:</h1>

            <div class="mt-1 w-full lg:w-3/4 flex gap-2 gap-y-3 sm:items-center flex-col sm:flex-row">
                <x-forms.text-input wire:model.defer="startDate" id="startDate" type="date" name="startDate" required />

                <span class="text-sm text-center">até</span>

                <x-forms.text-input wire:model.defer="endDate" id="endDate" type="date" name="endDate" required />

                <x-button icon="fa-solid fa-magnifying-glass" wire:click.prevent="applyFilter">
                    Buscar
                </x-button>
            </div>

            @error('startDate')
                <x-forms.input-error :messages="$message" class="mt-2" />
            @enderror

            @error('endDate')
                <x-forms.input-error :messages="$message" class="mt-2" />
            @enderror
        </div>

        <x-button icon="fa-solid fa-plus" wire:click.prevent="openAddModal" class="mb-2">
            TRANSAÇÃO
        </x-button>

        <x-tables.table>
            <x-slot name="thead">
                <x-tables.th>Nome</x-tables.th>
                <x-tables.th>Categoria</x-tables.th>
                <x-tables.th>Data</x-tables.th>
                <x-tables.th>Valor</x-tables.th>
                <x-tables.th></x-tables.th>
            </x-slot>

            <x-slot name="tbody">
                @forelse ($transactions as $transaction)
                    <x-tables.tr>
                        <x-tables.td>
                            {{ $transaction->name }}
                        </x-tables.td>
                        <x-tables.td>
                            {{ $transaction->subCategory->category->name }}
                        </x-tables.td>
                        <x-tables.td>
                            {{ $transaction->performed_at }}
                        </x-tables.td>
                        <x-tables.td>
                            <span class="text-[{{ $transaction->type->getTextColor() }}]">
                                @if ($transaction->type->isInbound())
                                    + @money($transaction->amount)
                                @else
                                    - @money($transaction->amount)
                                @endif
                            </span>
                        </x-tables.td>
                        <x-tables.td>
                            <a class="cursor-pointer text-red-500">
                                <i class="fa-solid fa-xmark"></i>
                            </a>
                        </x-tables.td>
                    </x-tables.tr>
                @empty
                    <x-tables.tr>
                        <x-tables.td colspan="5" class="text-center text-gray-5 00">
                            <span>Nenhuma transação encontrada.</span>
                        </x-tables.td>
                    </x-tables.tr>
                @endforelse
            </x-slot>
        </x-tables.table>

        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    </x-card>

    <x-modal name="add-transaction" focusable title="Adicionar transação">
        <form method="post" class="p-6" wire:submit.prevent="create">
            <div>
                <x-forms.input-label for="name" value="Descrição" />

                <x-forms.text-input id="name" class="block mt-1 w-full sm:w-3/4" type="text" name="name"
                    required wire:model.defer="transaction.name" />

                @error('transaction.name')
                    <x-forms.input-error :messages="$message" class="mt-2" />
                @enderror
            </div>

            <div class="mt-6">
                <x-forms.input-label for="type" value="Tipo" />

                <x-forms.select id="type" name="type" class="block mt-1 w-full sm:w-2/4"
                    wire:model="transaction.type" required>
                    <option value="">
                        Selecione uma opção
                    </option>
                    @foreach ($types as $type)
                        <option value="{{ $type->value }}">
                            {{ $type->getLabelText() }}
                        </option>
                    @endforeach
                </x-forms.select>

                @error('transaction.type')
                    <x-forms.input-error :messages="$message" class="mt-2" />
                @enderror
            </div>

            <div class="mt-6">
                <x-forms.input-label for="category" value="Categoria" />

                <x-forms.select id="category" name="category" class="block mt-1 w-full sm:w-2/4"
                    wire:model="categoryId" required>
                    <option value="0">
                        Selecione uma opção
                    </option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->name }}
                        </option>
                    @endforeach
                </x-forms.select>

                @error('category')
                    <x-forms.input-error :messages="$message" class="mt-2" />
                @enderror
            </div>

            <div class="mt-6">
                <x-forms.input-label for="category_id" value="Sub-categoria" />

                <x-forms.select id="category_id" name="category_id" class="block mt-1 w-full sm:w-2/4"
                    wire:model="transaction.category_id" required>
                    <option value="">
                        Selecione uma opção
                    </option>
                    @foreach ($subCategories as $subCategory)
                        <option value="{{ $subCategory->id }}">
                            {{ $subCategory->name }}
                        </option>
                    @endforeach
                </x-forms.select>

                @error('transaction.category_id')
                    <x-forms.input-error :messages="$message" class="mt-2" />
                @enderror

                @error('categoryId')
                    <x-forms.input-error :messages="$message" class="mt-2" />
                @enderror
            </div>

            <div class="mt-6">
                <x-forms.input-label for="amount" value="Valor" />


                <x-forms.currency-input wire:model.defer="transaction.amount" id="amount"
                    class="block mt-1 w-full sm:w-2/4" type="text" name="amount" />

                @error('transaction.amount')
                    <x-forms.input-error :messages="$message" class="mt-2" />
                @enderror
            </div>

            <div class="mt-6">
                <x-forms.input-label for="performed_at" value="Data da transação" />

                <x-forms.datetime-input id="performed_at" class="block mt-1 w-full sm:w-2/4" type="text"
                    name="performed_at" required wire:model.lazy="transaction.performed_at" />

                @error('transaction.performed_at')
                    <x-forms.input-error :messages="$message" class="mt-2" />
                @enderror
            </div>

            <div class="mt-6">

                <x-forms.checkbox-input id="done" name="done" wire:model="transaction.done"
                    label="Transação concluída?" />

                @error('transaction.done')
                    <x-forms.input-error :messages="$message" class="mt-2" />
                @enderror
            </div>

            <div class="mt-6">
                <x-button type="submit" wire:loading.attr="disabled" loader>
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
