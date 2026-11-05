<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function create()
    {
        return view('pages.testimonial');
    }

    public function store(Request $request)
    {
        // "website" is a honeypot: people never see it, bots fill it in.
        if ($request->filled('website')) {
            return back()->with('sent', true);
        }

        $data = $request->validate([
            'name' => 'required|string|max:120',
            'role' => 'nullable|string|max:120',
            'contact' => 'nullable|string|max:160',
            'quote' => 'required|string|min:10|max:2000',
            'files' => 'nullable|array|max:3',
            'files.*' => 'file|mimes:jpg,jpeg,png,webp,mp4,webm,mov,mp3,m4a,wav,ogg|max:20480',
        ]);

        $locale = app()->getLocale();
        $paths = collect($request->file('files', []))->map(fn ($file) => $file->store('testimonials', 'public'))->all();

        // Nothing is shown until an admin approves it: the record starts unpublished.
        Testimonial::create([
            'name' => [$locale => $data['name']],
            'role' => filled($data['role'] ?? null) ? [$locale => $data['role']] : null,
            'quote' => [$locale => $data['quote']],
            'attachments' => $paths ?: null,
            'contact' => $data['contact'] ?? null,
            'source' => 'website',
            'ip' => $request->ip(),
            'is_published' => false,
            'sort' => 0,
        ]);

        return back()->with('sent', true);
    }
}
