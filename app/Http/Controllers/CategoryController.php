<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Traits\HandlesImageUpload;

class CategoryController extends Controller
{
    use HandlesImageUpload;

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

        $data['image'] = $this->storeImage($request->file('image'), 'categories');

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
        $category = Category::findOrFail($id);

        $data = $request->validated();

        $data['image'] = $this->resolveImageUpload($request, $category->image, 'categories');

        // remove_image è solo un flag del form, non una colonna: lo togliamo.
        unset($data['remove_image']);

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Categoria aggiornata.');
    }

    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);

        // Prima cancelliamo il file dal disco, poi il record dal DB.
        $this->deleteImage($category->image);
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Categoria eliminata.');
    }
}