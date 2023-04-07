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

    </div>
</div>
