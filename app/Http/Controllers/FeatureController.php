<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use App\Models\Industry;

class FeatureController extends Controller
{
    public function index()
    {
        return view('features.index', ['features' => Feature::published()->get()]);
    }

    public function show(Feature $feature)
    {
        abort_unless($feature->is_published, 404);

        return view('features.show', [
            'feature' => $feature,
            'others' => Feature::published()->where('id', '!=', $feature->id)->get(),
            // Trades that list this module among the ones they rely on.
            'industries' => Industry::published()->get()->filter(
                fn (Industry $industry) => in_array($feature->slug, $industry->feature_slugs ?? [], true)
            ),
        ]);
    }
}
