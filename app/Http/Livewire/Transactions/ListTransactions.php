<?php

namespace App\Http\Livewire\Transactions;

use App\Models\Transaction;
use Livewire\Component;

class ListTransactions extends Component
{
    public function render()
    {
        return view('livewire.transactions.list-transactions', [
            'transactions' => Transaction::with(['category.category'])->done()->paginate()
        ]);
    }
}
