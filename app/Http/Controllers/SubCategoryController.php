<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubCategoryController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validateWithBag('subCategory', [
            'sub-category-name' => [
                'required',
                Rule::unique('categories', 'name')
                    ->whereNotNull('category_id')
                    ->where('category_id', $category->id)
                    ->where('user_id', $category->user_id),
                'max:255',
            ],
            'sub-category-color' => [
                'required',
                'regex:/^#([a-f0-9]{6}|[a-f0-9]{3})$/i'
            ]
        ]);

        $category->subCategories()->create([
            'name' => $validated['sub-category-name'],
            'color' => $validated['sub-category-color']
        ]);

        return redirect()->back()->with('success', 'Sub-categoria criada com sucesso!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        //
    }
}
