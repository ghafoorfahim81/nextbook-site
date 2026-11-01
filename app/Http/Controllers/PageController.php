<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Feature;
use App\Models\HelpArticle;
use App\Models\HelpCategory;
use App\Models\Industry;
use App\Models\Post;
use App\Models\PricingAddon;
use App\Models\PricingPlan;
use Illuminate\Http\Response;

class PageController extends Controller
{
    public function home()
    {
        return view('pages.home', [
            'features' => Feature::published()->where('status', 'available')->get(),
            'industries' => Industry::published()->get(),
            'faqs' => Faq::published()->limit(5)->get(),
            // Real figures from the content itself, so they cannot drift from the site.
            'stats' => [
                'features' => Feature::published()->where('status', 'available')->count(),
                'industries' => Industry::published()->count(),
                'guides' => HelpArticle::published()->count(),
            ],
        ]);
    }

    public function pricing()
    {
        return view('pages.pricing', [
            'plans' => PricingPlan::published()->get(),
            'addons' => PricingAddon::published()->get(),
            'faqs' => Faq::published()->get(),
        ]);
    }

    public function faq()
    {
        return view('pages.faq', ['faqs' => Faq::published()->get()]);
    }

    public function about()
    {
        return view('pages.about');
    }

    public function security()
    {
        return view('pages.security');
    }

    public function sitemap(): Response
    {
        $locales = array_keys(config('site.locales'));
        $paths = ['', '/features', '/industries', '/pricing', '/faq', '/about', '/security', '/help', '/blog', '/contact', '/demo', '/login', '/terms', '/privacy'];

        foreach (Feature::published()->get() as $feature) {
            $paths[] = '/features/'.$feature->slug;
        }
        foreach (Industry::published()->get() as $industry) {
            $paths[] = '/industries/'.$industry->slug;
        }
        foreach (Post::live()->get() as $post) {
            $paths[] = '/blog/'.$post->slug;
        }
        foreach (HelpCategory::published()->get() as $category) {
            $paths[] = '/help/'.$category->slug;
        }
        foreach (HelpArticle::published()->with('category')->get() as $article) {
            $paths[] = '/help/'.$article->category->slug.'/'.$article->slug;
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">';
        foreach ($paths as $path) {
            foreach ($locales as $locale) {
                $xml .= '<url><loc>'.e(url("/{$locale}{$path}")).'</loc>';
                foreach ($locales as $alt) {
                    $xml .= '<xhtml:link rel="alternate" hreflang="'.$alt.'" href="'.e(url("/{$alt}{$path}")).'"/>';
                }
                $xml .= '</url>';
            }
        }
        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}
