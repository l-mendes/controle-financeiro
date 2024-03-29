<?php

namespace App\Livewire\Categories;

use App\Enums\Type;
use App\Models\Category;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class ListCategories extends Component
{
    use WithPagination;

    public Category $category;

    public function render()
    {
        return view('livewire.categories.list-categories', [
            'categories' => Category::mainCategory()->withCount('subCategories')->paginate()
        ]);
    }

    public function openAddModal(): void
    {
        $this->resetErrorBag();

        $this->category = new Category(['color' => '#cccccc', 'type' => 'I']);

        $this->dispatch('open-modal', 'add-category');
    }

    public function create(): void
    {
        $data = $this->validate();

        $this->category->subCategories()->create($data['category']);

        $this->dispatch('close-modal', 'add-category');

        $this->dispatch('alert', ['type' => 'success', 'message' => 'Categoria criada com sucesso!']);
    }

    public function openDeleteModal(Category $category): void
    {
        $this->category = $category;

        $this->dispatch('open-modal', 'delete-category');
    }

    public function delete()
    {
        if ($this->category) {
            $this->category->delete();

            $this->dispatch('close-modal', 'delete-category');

            $this->dispatch('alert', ['type' => 'success', 'message' => 'Categoria excluída com sucesso!']);
        }
    }

    protected function rules(): array
    {
        $types = implode(',', array_column(Type::cases(), 'value'));

        return [
            'category.name' => [
                'required',
                Rule::unique('categories', 'name')
                    ->whereNull('category_id')
                    ->where('user_id', auth()->id())
                    ->whereNull('deleted_at'),
                'max:255',
            ],
            'category.color' => [
                'required',
                'regex:/^#([a-f0-9]{6}|[a-f0-9]{3})$/i'
            ],
            'category.type' => [
                'required',
                "in:$types"
            ]
        ];
    }
}
