<?php

namespace App\Http\Controllers;

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

        return view('industries.show', [
            'industry' => $industry,
            'others' => Industry::published()->where('id', '!=', $industry->id)->limit(6)->get(),
        ]);
    }
}
