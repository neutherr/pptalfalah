<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(Request $request): View
    {
        $categories = ArticleCategory::orderBy('order')->withCount('articles')->get();

        $articlesQuery = Article::published()->with('category', 'author');

        if ($request->filled('category')) {
            $articlesQuery->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $articlesQuery->where(fn ($q) => $q
                ->where('title', 'like', "%$search%")
                ->orWhere('excerpt', 'like', "%$search%")
            );
        }

        $articles = $articlesQuery->orderByDesc('published_at')->paginate(9);
        $settings = SiteSetting::pluck('value', 'key');

        return view('pages.articles.index', compact('articles', 'categories', 'settings'));
    }

    public function show(string $slug): View
    {
        $article = Article::published()->where('slug', $slug)->with('category', 'author')->firstOrFail();
        
        // Increment view count
        $article->increment('views');

        $related = Article::published()
            ->where('id', '!=', $article->id)
            ->where('article_category_id', $article->article_category_id)
            ->with('category')
            ->limit(3)
            ->get();

        $settings = SiteSetting::pluck('value', 'key');

        return view('pages.articles.show', compact('article', 'related', 'settings'));
    }
}
