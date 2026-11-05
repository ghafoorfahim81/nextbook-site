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

    public function robots(): Response
    {
        // Search results and the sign-in form are no use in a search engine; the admin is private.
        $lines = ['User-agent: *', 'Disallow: /admin', 'Disallow: /*/search', 'Disallow: /*/login', '', 'Sitemap: '.url('/sitemap.xml'), ''];

        return response(implode("\n", $lines), 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    public function sitemap(): Response
    {
        $locales = array_keys(config('site.locales'));

        // path => when the page last changed (null for the pages that are only code), so a search
        // engine knows what to fetch again.
        $pages = [];
        foreach (['', '/features', '/industries', '/pricing', '/faq', '/about', '/security', '/help', '/blog', '/contact', '/demo', '/login', '/terms', '/privacy'] as $path) {
            $pages[$path] = null;
        }

        foreach (Feature::published()->get() as $feature) {
            $pages['/features/'.$feature->slug] = $feature->updated_at;
        }
        foreach (Industry::published()->get() as $industry) {
            $pages['/industries/'.$industry->slug] = $industry->updated_at;
        }
        foreach (Post::live()->get() as $post) {
            $pages['/blog/'.$post->slug] = $post->updated_at;
        }
        foreach (HelpCategory::published()->get() as $category) {
            $pages['/help/'.$category->slug] = $category->updated_at;
        }
        foreach (HelpArticle::published()->with('category')->get() as $article) {
            $pages['/help/'.$article->category->slug.'/'.$article->slug] = $article->updated_at;
        }

        $default = config('site.default_locale');
        $xml = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">';
        foreach ($pages as $path => $changed) {
            foreach ($locales as $locale) {
                $xml .= '<url><loc>'.e(url("/{$locale}{$path}")).'</loc>';
                if ($changed) {
                    $xml .= '<lastmod>'.$changed->toAtomString().'</lastmod>';
                }
                foreach ($locales as $alt) {
                    $xml .= '<xhtml:link rel="alternate" hreflang="'.$alt.'" href="'.e(url("/{$alt}{$path}")).'"/>';
                }
                $xml .= '<xhtml:link rel="alternate" hreflang="x-default" href="'.e(url("/{$default}{$path}")).'"/>';
                $xml .= '</url>';
            }
        }
        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}
