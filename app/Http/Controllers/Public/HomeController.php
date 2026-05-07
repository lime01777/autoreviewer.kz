<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Dealership;
use App\Models\News;
use App\Models\Review;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('dealerships')->get();
        
        $featuredDealerships = Dealership::where('status', 'published')
            ->where('is_featured', true)
            ->with(['categories'])
            ->latest()
            ->take(6)
            ->get();

        $latestReviews = Review::where('status', 'approved')
            ->with(['dealership', 'user'])
            ->latest()
            ->take(4)
            ->get();

        $latestNews = News::where('status', 'published')
            ->latest()
            ->take(3)
            ->get();

        $banners = Banner::where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', now());
            })
            ->get()
            ->groupBy('position');

        $popularDealerships = Dealership::where('status', 'published')
            ->withCount([
                'reviews as reviews_count' => function ($query) {
                    $query->where('status', 'approved');
                },
            ])
            ->withAvg([
                'reviews as rating_avg' => function ($query) {
                    $query->where('status', 'approved');
                },
            ], 'rating')
            ->with(['categories'])
            ->orderByDesc('reviews_count')
            ->orderByDesc('rating_avg')
            ->take(6)
            ->get();

        return view('public.index', compact(
            'categories',
            'featuredDealerships',
            'popularDealerships',
            'latestReviews',
            'latestNews',
            'banners'
        ));
    }
}
