<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Article;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function show($slug)
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();
        
        // Paginated articles sharing this tag
        $articles = Article::published()
            ->whereHas('tags', function ($query) use ($tag) {
                $query->where('tag_id', $tag->id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(6);

        return view('category', [
            'category' => (object) [
                'name_bn' => 'ট্যাগ: ' . $tag->name_bn,
                'description' => 'ট্যাগ সম্পর্কিত সমস্ত খবরের তালিকা দেখুন।'
            ],
            'articles' => $articles
        ]);
    }
}
