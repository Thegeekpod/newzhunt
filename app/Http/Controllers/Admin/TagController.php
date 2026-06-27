<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::withCount('articles')->get();
        return view('admin.tags.index', compact('tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_bn' => 'required|string|max:255|unique:tags,name_bn',
        ]);

        // Generate slug replacing spaces with dashes
        $slug = preg_replace('/[^\p{L}\p{N}\s\-]/u', '', mb_strtolower($request->name_bn));
        $slug = preg_replace('/[\s\-]+/u', '-', $slug);
        $slug = trim($slug, '-');

        if (Tag::where('slug', $slug)->exists()) {
            $slug = $slug . '-' . time();
        }

        Tag::create([
            'name_bn' => $request->name_bn,
            'slug' => $slug
        ]);

        return redirect()->route('admin.tags.index')->with('success', 'Tag added successfully.');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        return redirect()->route('admin.tags.index')->with('success', 'Tag deleted successfully.');
    }
}
