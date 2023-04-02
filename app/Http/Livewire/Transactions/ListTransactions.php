<?php

namespace App\Http\Livewire\Transactions;

use App\Enums\Type;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use App\Rules\ValidTypeRule;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
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

    public $startDate;

    public $endDate;

    public bool $isDone = false;

    public string $type = '';

    public Transaction $currentTransaction;

    public function mount()
    {
        $user = auth()->user();

        $this->startDate = now()->timezone($user->timezone)->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->timezone($user->timezone)->format('Y-m-d');

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
        $timezone = auth()->user()->timezone;

        return view('livewire.transactions.list-transactions', [
            'transactions' => Transaction::with(['subCategory.category'])
                ->whereBetween(
                    DB::raw("DATE(CONVERT_TZ(performed_at, 'UTC', '$timezone'))"),
                    [
                        $this->startDate,
                        $this->endDate
                    ]
                )
                ->when($this->isDone, function ($q) {
                    return $q->done();
                })
                ->when($this->type !== '', function ($q) {
                    return $q->where('type', $this->type);
                })
                ->orderByDesc('performed_at')
                ->paginate()
        ]);
    }

    public function applyFilter()
    {
        $validator = Validator::make([
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'isDone' => $this->isDone
        ], [
            'startDate' => 'required|date|before:endDate',
            'endDate' => 'required|date|after:startDate',
            'isDone' => 'required|boolean'
        ]);

        $validator->validate();
    }

    public function openEditModal(Transaction $transaction): void
    {
        $this->resetErrorBag();

        $this->isEditMode = true;

        switch ($transaction->type) {
            case Type::INBOUND:
                $this->categories = $this->inboundCategories;
                break;
            case Type::OUTBOUND:
                $this->categories = $this->outboundCategories;
                break;
        }

        $this->transaction = $transaction->toArray();

        $this->transaction['performed_at'] = $transaction->performed_at->timezone(auth()->user()->timezone)->format('d/m/Y H:i');

        $this->categoryId = $transaction->subCategory->category_id;

        $this->subCategories = Category::subCategory()->ofCategory($this->categoryId)->get();

        $this->dispatchBrowserEvent('open-modal', 'add-transaction');
    }

    public function openAddModal(): void
    {
        $this->resetErrorBag();

        $this->isEditMode = false;

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

    public function update(): void
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

        $transaction = Transaction::findOrFail($this->transaction['id']);

        $transaction->fill($data['transaction']);

        $transaction->save();

        $this->dispatchBrowserEvent('close-modal', 'add-transaction');

        $this->dispatchBrowserEvent('alert', ['type' => 'success', 'message' => 'Transação atualizada com sucesso!']);
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

    public function openDeleteModal(Transaction $transaction): void
    {
        $this->currentTransaction = $transaction;

        $this->dispatchBrowserEvent('open-modal', 'delete-transaction');
    }

    public function delete()
    {
        if ($this->currentTransaction) {
            $this->currentTransaction->delete();

            $this->dispatchBrowserEvent('close-modal', 'delete-transaction');

            $this->dispatchBrowserEvent('alert', ['type' => 'success', 'message' => 'Transação excluída com sucesso!']);
        }
    }

    public function markAsDone(Transaction $transaction): void
    {
        if (!$transaction->done) {
            $transaction->done = true;
            $transaction->save();
        }
    }

    public function markAsUndone(Transaction $transaction): void
    {
        if ($transaction->done) {
            $transaction->done = false;
            $transaction->save();
        }
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
