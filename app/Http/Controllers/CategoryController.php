<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $categories = Category::mainCategory()->withCount('subCategories')->paginate();

        return view('categories.index', ['categories' => $categories]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): View
    {
        $subCategories = Category::subCategory()->ofCategory($category->id)->paginate();

        return view('categories.show', ['category' => $category, 'subCategories' => $subCategories]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                Rule::unique('categories')
                    ->whereNot('id', $category->id)
                    ->where('user_id', $category->user_id),
                'max:255',
            ],
            'type' => [
                'required',
                'in:I,O'
            ],
            'color' => [
                'required',
                'regex:/^#([a-f0-9]{6}|[a-f0-9]{3})$/i'
            ]
        ]);

        $category->fill($validated);

        $category->save();

        return redirect()->back()->with('success', 'Categoria atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        //
    }
}
