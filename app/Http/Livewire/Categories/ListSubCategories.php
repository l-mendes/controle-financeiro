<?php

namespace App\Http\Livewire\Categories;

use App\Models\Category;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class ListSubCategories extends Component
{
    use WithPagination;

    public Category $category;

    public Category $subCategory;

    public bool $isEditMode = false;

    public function mount(Category $category)
    {
        $this->category = $category;
    }

    public function render()
    {
        return view('livewire.categories.list-sub-categories', [
            'subCategories' => $this->category->subCategories()->paginate()
        ]);
    }

    public function openEditModal(Category $subCategory): void
    {
        $this->resetErrorBag();

        $this->isEditMode = true;

        $this->subCategory = $subCategory;

        $this->dispatchBrowserEvent('open-modal', 'edit-sub-category');
    }

    public function openAddModal(): void
    {
        $this->resetErrorBag();

        $this->isEditMode = false;

        $this->subCategory = new Category(['color' => '#cccccc']);

        $this->dispatchBrowserEvent('open-modal', 'edit-sub-category');
    }

    public function createSubCategory(): void
    {
        $data = $this->validate();

        $this->isEditMode = false;

        $this->category->subCategories()->create($data['subCategory']);

        $this->dispatchBrowserEvent('close-modal', 'edit-sub-category');

        $this->dispatchBrowserEvent('alert', ['type' => 'success', 'message' => 'Sub-categoria criada com sucesso!']);
    }

    public function updateSubCategory(): void
    {
        $data = $this->validate();

        $this->subCategory->fill($data['subCategory']);

        $this->subCategory->save();

        $this->dispatchBrowserEvent('close-modal', 'edit-sub-category');

        $this->dispatchBrowserEvent('alert', ['type' => 'success', 'message' => 'Sub-categoria atualizada com sucesso!']);
    }

    protected function rules(): array
    {
        if ($this->isEditMode) {
            return [
                'subCategory.name' => [
                    'required',
                    Rule::unique('categories', 'name')
                        ->whereNotNull('category_id')
                        ->where('category_id', $this->category->id)
                        ->where('user_id', $this->subCategory->user_id)
                        ->whereNot('id', $this->subCategory->id),
                    'max:255',
                ],
                'subCategory.color' => [
                    'required',
                    'regex:/^#([a-f0-9]{6}|[a-f0-9]{3})$/i'
                ]
            ];
        }

        return [
            'subCategory.name' => [
                'required',
                Rule::unique('categories', 'name')
                    ->whereNotNull('category_id')
                    ->where('category_id', $this->category->id)
                    ->where('user_id', auth()->id()),
                'max:255',
            ],
            'subCategory.color' => [
                'required',
                'regex:/^#([a-f0-9]{6}|[a-f0-9]{3})$/i'
            ]
        ];
    }
}
