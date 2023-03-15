<?php

namespace App\Http\Livewire\Transactions;

use App\Enums\Type;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use App\Rules\ValidTypeRule;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;

class ListTransactions extends Component
{
    public array $transaction;

    public Collection $categories;

    public Collection $subCategories;

    public int $categoryId;

    public Collection $inboundCategories;

    public Collection $outboundCategories;

    public bool $isEditMode = false;

    public function mount()
    {
        $this->resetTransactionData();

        $this->inboundCategories = Category::mainCategory()
            ->with('subCategories')
            ->where('type', Type::INBOUND)
            ->get();

        $this->outboundCategories = Category::mainCategory()
            ->with('subCategories')
            ->where('type', Type::OUTBOUND)
            ->get();

        $this->categories = new Collection([]);

        $this->subCategories = new Collection([]);
    }

    public function render(): View
    {
        return view('livewire.transactions.list-transactions', [
            'transactions' => Transaction::with(['subCategory.category'])->done()->orderByDesc('performed_at')->paginate()
        ]);
    }

    public function openAddModal(): void
    {
        $this->resetErrorBag();

        $this->resetTransactionData();

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

        $this->transaction['category_id'] = '';
    }

    public function updatedCategoryId($value): void
    {
        if ($value) {
            $this->subCategories = Category::subCategory()->ofCategory($value)->get();
        } else {
            $this->subCategories = new Collection([]);
        }

        $this->transaction['category_id'] = '';
    }

    public function updatedTransactionAmount($value): void
    {
        if ($value) {
            $this->transaction['amount'] *= 100;
        }
    }

    public function create(): void
    {
        $data = $this->validate();

        /**
         * @var User $user
         */
        $user = auth()->user();

        $data['transaction']['performed_at'] = Carbon::createFromFormat(
            'd/m/Y H:i',
            $data['transaction']['performed_at'],
            $user->timezone
        )
            ->setTimezone(config('app.timezone'))
            ->format('Y-m-d H:i:s');

        $user->transactions()->create($data['transaction']);

        $this->dispatchBrowserEvent('close-modal', 'add-transaction');

        $this->dispatchBrowserEvent('alert', ['type' => 'success', 'message' => 'Transação criada com sucesso!']);
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
                    ->whereNull('deleted_at'),
            ],
            'categoryId' => [
                'required',
                Rule::exists('categories', 'id')
                    ->whereNull('category_id')
                    ->where('user_id', auth()->id())
                    ->where('type', $this->transaction['type'])
                    ->whereNull('deleted_at'),
            ],
            'transaction.amount' => 'required|decimal:0,2|min:1',
            'transaction.performed_at' => 'required|date_format:d/m/Y H:i',
            'transaction.done' => 'required|boolean',
        ];
    }

    private function resetTransactionData()
    {
        $this->transaction = [
            'name' => '',
            'type' => '',
            'category_id' => null,
            'amount' => 0,
            'performed_at' => now()->timezone(auth()->user()->timezone)->format('d/m/Y H:i'),
            'done' => true,
        ];
    }
}
