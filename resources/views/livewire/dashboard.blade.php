<div>
    <div class="flex flex-wrap gap-y-4 -mx-1 lg:-mx-4 px-1 lg:px-4">

        <div class="px-1 w-full md:w-1/2 lg:px-2 xl:w-1/4">
            <div class="p-4 sm:p-6 shadow-sm rounded-md bg-green-500 border-gray-300 border">
                <div class="flex w-full justify-between items-center">
                    <span class="text-gray-100 text-lg font-semibold">Carteira</span>

                    <i class="text-3xl fa-solid fa-sack-dollar text-gray-100"></i>
                </div>
                <div class="mt-2 truncate">
                    <span class="text-3xl font-semibold text-gray-100">@money($wallet)</span>
                </div>
            </div>
        </div>

        <div class="px-1 w-full md:w-1/2 lg:px-2 xl:w-1/4">
            <div class="p-4 sm:p-6 shadow-sm rounded-md bg-white border-gray-300 border">
                <div class="flex w-full justify-between items-center">
                    <span class="text-gray-400 text-lg font-semibold">Saldo (mês)</span>

                    <i class="text-3xl fa-solid fa-scale-balanced text-blue-500"></i>
                </div>
                <div class="mt-2 truncate">
                    <span class="text-3xl font-semibold text-blue-500">@money($balance)</span>
                </div>
            </div>
        </div>

        <div class="px-1 w-full md:w-1/2 lg:px-2 xl:w-1/4">
            <div class="p-4 sm:p-6 shadow-sm rounded-md bg-white border-gray-300 border">
                <div class="flex w-full justify-between items-center">
                    <span class="text-gray-400 text-lg font-semibold">Entradas (mês)</span>

                    <i class="text-3xl fa-regular fa-circle-up text-green-500"></i>
                </div>
                <div class="mt-2 truncate">
                    <span class="text-green-500 text-3xl font-semibold">
                        @money($inboundAmount)
                    </span>
                </div>
            </div>
        </div>

        <div class="px-1 w-full md:w-1/2 lg:px-2 xl:w-1/4">
            <div class="p-4 sm:p-6 shadow-sm rounded-md bg-white border-gray-300 border">
                <div class="flex w-full justify-between items-center">
                    <span class="text-gray-400 text-lg font-semibold">Saídas (mês)</span>

                    <i class="text-3xl fa-regular fa-circle-down text-red-500"></i>
                </div>
                <div class="mt-2 truncate">
                    <span class="text-3xl font-semibold text-red-500">-@money($outboundAmount)</span>
                </div>
            </div>
        </div>

        <div class="px-1 w-full md:w-1/2 lg:px-2">
            <div class="py-2 px-3 shadow-sm rounded-md bg-white border-gray-300 border">
                <h1 class="text-gray-600 font-semibold">
                    Despesas por Categoria
                </h1>
                <div class="h-[320px] w-full">
                    <livewire:charts.livewire-currency-donut-chart
                        key="{{ $expensesByCategoryPieChart->reactiveKey() }}" :pie-chart-model="$expensesByCategoryPieChart" />
                </div>
            </div>
        </div>

        <div class="px-1 w-full md:w-1/2 lg:px-2">
            <div class="py-2 px-3 shadow-sm rounded-md bg-white border-gray-300 border">
                <h1 class="text-gray-600 font-semibold">
                    Despesas por Sub-categoria
                </h1>
                <div class="h-[320px] w-full">
                    <livewire:charts.livewire-currency-donut-chart
                        key="{{ $expensesByCategoryPieChart->reactiveKey() }}" :pie-chart-model="$expensesBySubCategoryPieChart" />
                </div>
            </div>
        </div>

        <div class="px-1 lg:px-2 w-full">
            <div class="py-2 px-3 shadow-sm rounded-md bg-white border-gray-300 border flex flex-col gap-3">
                <h1 class="text-gray-600 font-semibold">
                    Últimas transações
                </h1>
                <x-tables.table>
                    <x-slot name="thead">
                        <x-tables.th class="text-xs sm:text-sm">Nome</x-tables.th>
                        <x-tables.th class="hidden sm:table-cell text-xs sm:text-sm">Categoria</x-tables.th>
                        <x-tables.th class="hidden sm:table-cell text-xs sm:text-sm">Sub-categoria</x-tables.th>
                        <x-tables.th class="text-xs sm:text-sm">Data</x-tables.th>
                        <x-tables.th class="text-xs sm:text-sm">Valor</x-tables.th>
                    </x-slot>

                    <x-slot name="tbody">
                        @forelse ($transactions as $transaction)
                            <x-tables.tr class="text-xs sm:text-sm">
                                <x-tables.td>
                                    {{ $transaction->name }}
                                </x-tables.td>
                                <x-tables.td class="hidden sm:table-cell whitespace-nowrap">
                                    {{ $transaction->subCategory->category->name }}
                                </x-tables.td>
                                <x-tables.td class="hidden sm:table-cell whitespace-nowrap">
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

                <div class="self-center">
                    <x-link href="{{ route('transactions.index') }}" icon="fa-solid fa-plus">
                        Ver mais
                    </x-link>
                </div>
            </div>
        </div>
    </div>
</div>
