<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderByDesc('created_at')->get();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function show(string $id)
    {
        $category = Category::with('articles')->findOrFail($id);
        return view('categories.show', compact('category'));
    }

    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();
        Category::create($data);
        return redirect()->route('admin.categories.index')->with('success', 'Categoria creata.');
    }

    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, string $id)
    {
        $data = $request->validated();

        $category = Category::findOrFail($id);
        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Categoria aggiornata.');
    }

    public function destroy(string $id)
    {
        Category::destroy($id);
        return redirect()->route('admin.categories.index')->with('success', 'Categoria eliminata.');
    }
}