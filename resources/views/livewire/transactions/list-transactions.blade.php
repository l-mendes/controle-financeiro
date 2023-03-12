<div>
    <x-card title="Transações realizadas" icon="fa-solid fa-arrow-right-arrow-left">
        <x-button icon="fa-solid fa-plus" wire:click.prevent="openAddModal" class="mb-2">
            TRANSAÇÃO
        </x-button>

        <x-tables.table>
            <x-slot name="thead">
                <x-tables.th>Nome</x-tables.th>
                <x-tables.th>Tipo</x-tables.th>
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
                            <span class="text-[{{ $transaction->type->getTextColor() }}]">
                                {{ $transaction->type->getLabelText() }}
                            </span>
                        </x-tables.td>
                        <x-tables.td>
                            @datetime($transaction->performed_at)
                        </x-tables.td>
                        <x-tables.td>
                            @money($transaction->amount)
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
</div>
