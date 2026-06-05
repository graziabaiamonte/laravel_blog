<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
// use App\Traits\HandlesImageUpload; 

class CategoryController extends Controller
{
    // use HandlesImageUpload; // VECCHIO sistema

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

        unset($data['image']);

        $category = Category::create($data);

        if ($request->hasFile('image')) {
            $category->addMediaFromRequest('image')
                ->toMediaCollection(Category::MEDIA_IMAGE);
        }

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

        // 'image' e 'remove_image' non sono colonne: li togliamo prima di update().
        unset($data['image'], $data['remove_image']);

        $category->update($data);

        if ($request->hasFile('image')) {
            $category->addMediaFromRequest('image')
                ->toMediaCollection(Category::MEDIA_IMAGE);
        } elseif ($request->boolean('remove_image')) {
            $category->clearMediaCollection(Category::MEDIA_IMAGE);
        }

        return redirect()->route('admin.categories.index')->with('success', 'Categoria aggiornata.');
    }

    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Categoria eliminata.');
    }
}