<div>
    <x-card title="Transações realizadas" icon="fa-solid fa-arrow-right-arrow-left">
        <div class="shadow-lg rounded-lg mb-6 border-2 border-gray-200 p-4">
            <h1>Período:</h1>

            <div class="mt-1 w-full flex flex-col sm:flex-row sm:items-center gap-2 gap-y-3">
                <x-forms.text-input wire:model="startDate" id="startDate" type="date" name="startDate"
                    class="w-full sm:w-auto" required />

                <span class="text-sm text-center">até</span>

                <x-forms.text-input wire:model="endDate" id="endDate" type="date" name="endDate"
                    class="w-full sm:w-auto" required />
            </div>

            @error('startDate')
                <x-forms.input-error :messages="$message" class="mt-2" />
            @enderror

            @error('endDate')
                <x-forms.input-error :messages="$message" class="mt-2" />
            @enderror

            <div class="mt-4">
                <label for="typeFilter" class="block">Tipo:</label>

                <x-forms.select id="typeFilter" name="typeFilter" wire:model="type" class="w-full sm:w-[200px]">
                    <option value="">
                        Todos
                    </option>
                    @foreach ($types as $type)
                        <option value="{{ $type->value }}">
                            {{ $type->getLabelText() }}
                        </option>
                    @endforeach
                </x-forms.select>
            </div>

            @error('type')
                <x-forms.input-error :messages="$message" class="mt-2" />
            @enderror

            <div class="mt-4">
                <x-forms.checkbox-input id="is-done" name="is-done" wire:model="isDone"
                    label="Somente transações concluídas?" />
            </div>

            @error('isDone')
                <x-forms.input-error :messages="$message" class="mt-2" />
            @enderror

            <x-button icon="fa-solid fa-magnifying-glass" class="mt-4 w-full sm:w-auto"
                wire:click.prevent="applyFilter">
                Buscar
            </x-button>
        </div>

        <x-button icon="fa-solid fa-plus" wire:click.prevent="openAddModal" class="mb-2">
            TRANSAÇÃO
        </x-button>

        <x-tables.table>
            <x-slot name="thead">
                <x-tables.th>Nome</x-tables.th>
                <x-tables.th>Categoria</x-tables.th>
                <x-tables.th>Sub-categoria</x-tables.th>
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
                        <x-tables.td class="whitespace-nowrap">
                            {{ $transaction->subCategory->category->name }}
                        </x-tables.td>
                        <x-tables.td class="whitespace-nowrap">
                            {{ $transaction->subCategory->name }}
                        </x-tables.td>
                        <x-tables.td class="whitespace-nowrap">
                            {{ $transaction->performed_at }}
                        </x-tables.td>
                        <x-tables.td class="whitespace-nowrap">
                            <span class="text-[{{ $transaction->type->getTextColor() }}]">
                                @if ($transaction->type->isInbound())
                                    + @money($transaction->amount)
                                @else
                                    - @money($transaction->amount)
                                @endif
                            </span>
                        </x-tables.td>
                        <x-tables.td class="whitespace-nowrap">
                            @if (!$transaction->done)
                                <a class="cursor-pointer text-green-500 mr-2" title="Marcar como concluída"
                                    wire:click.prevent="markAsDone({{ $transaction }})">
                                    <i class="fa-solid fa-check"></i>
                                </a>
                            @else
                                <a class="cursor-pointer text-yellow-500 mr-2" title="Marcar como não concluída"
                                    wire:click.prevent="markAsUndone({{ $transaction }})">
                                    <i class="fa-sharp fa-solid fa-rotate-left"></i>
                                </a>
                            @endif
                            <a class="cursor-pointer text-blue-500 mr-2"
                                wire:click.prevent="openEditModal({{ $transaction }})">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <a class="cursor-pointer text-red-500"
                                wire:click.prevent="openDeleteModal({{ $transaction }})">
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

    <x-modal name="add-transaction" focusable title="{{ $isEditMode ? 'Editar' : 'Adicionar' }} transação">
        <form method="post" class="p-6" wire:submit={{ $isEditMode ? 'update' : 'create' }}>
            <div>
                <x-forms.input-label for="name" value="Descrição" />

                <x-forms.text-input id="name" class="block mt-1 w-full sm:w-3/4" type="text" name="name"
                    required wire:model="transaction.name" />

                @error('transaction.name')
                    <x-forms.input-error :messages="$message" class="mt-2" />
                @enderror
            </div>

            <div class="mt-6">
                <x-forms.input-label for="type" value="Tipo" />

                <x-forms.select id="type" name="type" class="block mt-1 w-full sm:w-2/4"
                    wire:model.live="transaction.type" required>
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
                    wire:model.live="categoryId" required>
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
                    wire:model.live="transaction.category_id" required>
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


                <x-forms.currency-input wire:model="transaction.amount" id="amount"
                    class="block mt-1 w-full sm:w-2/4" type="text" name="amount" />

                @error('transaction.amount')
                    <x-forms.input-error :messages="$message" class="mt-2" />
                @enderror
            </div>

            <div class="mt-6">
                <x-forms.input-label for="performed_at" value="Data da transação" />

                <x-forms.datetime-input id="performed_at" class="block mt-1 w-full sm:w-2/4" type="text"
                    name="performed_at" required wire:model.blur="transaction.performed_at" />

                @error('transaction.performed_at')
                    <x-forms.input-error :messages="$message" class="mt-2" />
                @enderror
            </div>

            <div class="mt-6">

                <x-forms.checkbox-input id="done" name="done" wire:model.live="transaction.done"
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

    <x-modal name="delete-transaction" focusable title="Remover transação">
        <div class="p-6 flex gap-5 flex-col">
            <div>
                <h3 class="text-gray-600 font-medium">Deseja realmente excluir a transação?</h3>
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
