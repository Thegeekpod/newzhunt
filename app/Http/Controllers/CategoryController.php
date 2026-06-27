<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Article;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
        // Paginated articles
        $articles = Article::published()
            ->where('category_id', $category->id)
            ->orderBy('created_at', 'desc')
            ->paginate(6);

        return view('category', compact('category', 'articles'));
    }
}
