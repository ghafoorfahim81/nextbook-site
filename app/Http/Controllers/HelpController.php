<?php

namespace App\Http\Controllers;

use App\Models\HelpArticle;
use App\Models\HelpCategory;
use App\Support\SiteSearch;
use Illuminate\Http\Request;

class HelpController extends Controller
{
    public function index()
    {
        return view('help.index', [
            'categories' => HelpCategory::published()->withCount('articles')->get(),
        ]);
    }

    public function search(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $results = collect();

        if (mb_strlen($q) >= 2) {
            $results = app(SiteSearch::class)->helpArticles($q);
        }
        return view('help.search', ['q' => $q, 'results' => $results]);
    }

    public function category(HelpCategory $category)
    {
        abort_unless($category->is_published, 404);

        return view('help.category', ['category' => $category->load('articles')]);
    }

    public function article(HelpCategory $category, HelpArticle $article)
    {
        abort_unless($article->is_published && $article->help_category_id === $category->id, 404);

        return view('help.article', [
            'category' => $category,
            'article' => $article,
            'siblings' => $category->articles,
        ]);
    }

    public function feedback(Request $request, HelpArticle $article)
    {
        $request->validate(['helpful' => 'required|in:yes,no']);

        $article->increment($request->input('helpful') === 'yes' ? 'helpful_yes' : 'helpful_no');

        return back()->with('feedback', true);
    }
}
