<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTagRequest;
// use Illuminate\Http\Request;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Tag;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::orderByDesc('created_at')->get();

        return view('tags.index', compact('tags'));
    }

    public function create()
    {
        return view('tags.create');
    }

    public function store(StoreTagRequest $request)
    {
        $data = $request->validated();
        Tag::create($data);

        return redirect()->route('admin.tags.index')->with('success', 'Tag creato.');
    }

    public function show(string $id)
    {
        $tag = Tag::with('articles')->findOrFail($id);

        return view('tags.show', compact('tag'));
    }

    public function edit(string $id)
    {
        $tag = Tag::findOrFail($id);

        return view('tags.edit', compact('tag'));
    }

    public function update(UpdateTagRequest $request, string $id)
    {
        $data = $request->validated();
        Tag::whereId($id)->update($data);

        return redirect()->route('admin.tags.index')->with('success', 'Tag aggiornato.');
    }

    public function destroy(string $id)
    {
        Tag::destroy($id);

        return redirect()->route('admin.tags.index')->with('success', 'Tag eliminato.');
    }
}
