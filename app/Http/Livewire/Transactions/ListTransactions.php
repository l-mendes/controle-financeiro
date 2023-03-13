<?php

namespace App\Http\Livewire\Transactions;

use App\Enums\Type;
use App\Models\Category;
use App\Models\Transaction;
use App\Rules\ValidTypeRule;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;

class ListTransactions extends Component
{
    public Transaction $transaction;

    public Collection $categories;

    public Category $category;

    public Collection $subCategories;

    public bool $isEditMode = false;

    public function render(): View
    {
        return view('livewire.transactions.list-transactions', [
            'transactions' => Transaction::with(['subCategory.category'])->done()->paginate()
        ]);
    }

    public function openAddModal(): void
    {
        $this->resetErrorBag();

        $this->transaction = new Transaction(['amount' => 0]);

        $this->dispatchBrowserEvent('open-modal', 'add-transaction');
    }

    public function create(): void
    {
        sleep(100);
    }

    protected function rules(): array
    {
        return [
            'transaction.name' => 'required|max:255',
            'transaction.type' => ['required', new ValidTypeRule],
            'transaction.category_id' => [
                'required',
                Rule::exists('categories', 'id')
                    ->whereNotNull('category_id')
                    ->where('user_id', auth()->id())
                    ->where('type', $this->transaction->type)
                    ->whereNull('deleted_at')
            ],
            'transaction.amount' => 'required|integer|min:1',
            'transaction.performed_at' => 'required|date_format:Y-m-d H:i:s',
            'transaction.done' => 'required|boolean',
        ];
    }
}
