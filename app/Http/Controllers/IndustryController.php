<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use App\Models\Industry;

class IndustryController extends Controller
{
    public function index()
    {
        return view('industries.index', ['industries' => Industry::published()->get()]);
    }

    public function show(Industry $industry)
    {
        abort_unless($industry->is_published, 404);

        $slugs = $industry->feature_slugs ?? [];

        return view('industries.show', [
            'industry' => $industry,
            'others' => Industry::published()->where('id', '!=', $industry->id)->limit(8)->get(),
            // In the order the trade lists them, not the order of the site menu.
            'features' => Feature::published()->whereIn('slug', $slugs)->get()
                ->sortBy(fn (Feature $feature) => array_search($feature->slug, $slugs, true))->values(),
        ]);
    }
}
