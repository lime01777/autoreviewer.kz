<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Dealership;
use App\Models\News;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = [];

        // Static Pages
        $urls[] = ['url' => route('home'), 'lastmod' => now()->toAtomString(), 'priority' => '1.0'];
        $urls[] = ['url' => route('dealerships.index'), 'lastmod' => now()->toAtomString(), 'priority' => '0.9'];
        $urls[] = ['url' => route('news.index'), 'lastmod' => now()->toAtomString(), 'priority' => '0.8'];

        // Dealerships
        $dealerships = Dealership::where('status', 'published')->get();
        foreach ($dealerships as $dealer) {
            $urls[] = [
                'url' => route('dealerships.show', $dealer->slug),
                'lastmod' => $dealer->updated_at->toAtomString(),
                'priority' => '0.8'
            ];
        }

        // News
        $news = News::where('status', 'published')->get();
        foreach ($news as $item) {
            $urls[] = [
                'url' => route('news.show', $item->slug),
                'lastmod' => $item->updated_at->toAtomString(),
                'priority' => '0.6'
            ];
        }

        $content = view('public.sitemap', compact('urls'))->render();

        return response($content, 200)->header('Content-Type', 'text/xml');
    }

    public function robots(): Response
    {
        $content = "User-agent: *\nAllow: /\n\nSitemap: " . route('home') . "/sitemap.xml";
        return response($content, 200)->header('Content-Type', 'text/plain');
    }
}
