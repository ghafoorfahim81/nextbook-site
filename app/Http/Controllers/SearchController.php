<?php

namespace App\Http\Controllers;

use App\Support\SiteSearch;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request, SiteSearch $search)
    {
        $query = trim((string) $request->query('q', ''));

        // The search box in the header asks for JSON and shows a few results per group;
        // the page shows more and works without JavaScript.
        if ($request->wantsJson()) {
            return response()->json(['groups' => $search->search($query, 4)]);
        }

        return view('pages.search', [
            'q' => $query,
            'groups' => $search->search($query, 20),
            'tooShort' => $query !== '' && mb_strlen($query) < SiteSearch::MIN_LENGTH,
        ]);
    }
}
