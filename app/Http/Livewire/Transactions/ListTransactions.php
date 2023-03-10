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

    public Collection $subCategories;

    public int $categoryId;

    public Collection $inboundCategories;

    public Collection $outboundCategories;

    public bool $isEditMode = false;

    public function mount()
    {
        $this->transaction = new Transaction(['type' => Type::INBOUND, 'amount' => 0]);

        $this->inboundCategories = Category::mainCategory()
            ->with('subCategories')
            ->where('type', Type::INBOUND)
            ->get();

        $this->outboundCategories = Category::mainCategory()
            ->with('subCategories')
            ->where('type', Type::OUTBOUND)
            ->get();

        $this->categories = $this->inboundCategories;

        $this->subCategories = new Collection([]);
    }

    public function render(): View
    {
        return view('livewire.transactions.list-transactions', [
            'transactions' => Transaction::with(['subCategory.category'])->done()->paginate()
        ]);
    }

    public function openAddModal(): void
    {
        $this->resetErrorBag();

        $this->transaction = new Transaction(['type' => Type::INBOUND, 'amount' => 0]);

        $this->dispatchBrowserEvent('open-modal', 'add-transaction');
    }

    public function updatedTransactionType($value): void
    {
        if ($value) {
            switch ($value) {
                case Type::INBOUND->value:
                    $this->categories = $this->inboundCategories;
                    break;
                case Type::OUTBOUND->value:
                    $this->categories = $this->outboundCategories;
                    break;
            }
        } else {
            $this->categories = new Collection([]);
        }

        $this->transaction->category_id = '';
    }

    public function updatedCategoryId($value): void
    {
        if ($value) {
            $this->subCategories = Category::subCategory()->ofCategory($value)->get();
        } else {
            $this->subCategories = new Collection([]);
        }

        $this->transaction->category_id = '';
    }

    public function create(): void
    {
        dd($this->transaction);
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
