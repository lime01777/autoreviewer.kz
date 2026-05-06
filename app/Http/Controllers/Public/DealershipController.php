<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Dealership;
use Illuminate\Http\Request;

class DealershipController extends Controller
{
    public function index(Request $request)
    {
        $query = Dealership::where('status', 'published');

        // Search
        $search = $request->input('search') ?? $request->input('q');
        if ($search) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        // Category Filter
        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        // City Filter
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        // Sorting
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'rating':
                $query->orderByDesc('rating_avg');
                break;
            case 'reviews':
                $query->orderByDesc('reviews_count');
                break;
            case 'recommended':
                $query->orderByDesc('is_featured');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        $dealerships = $query->with('categories')->paginate(12)->withQueryString();
        
        $categories = Category::withCount('dealerships')->get();
        $cities = Dealership::where('status', 'published')->distinct()->pluck('city');

        return view('public.dealerships.index', compact('dealerships', 'categories', 'cities'));
    }

    public function show(Dealership $dealership)
    {
        $dealership->load(['categories', 'reviews' => function ($q) {
            $q->where('status', 'approved')->latest();
        }]);

        $similarDealerships = Dealership::where('status', 'published')
            ->where('id', '!=', $dealership->id)
            ->whereHas('categories', function ($q) use ($dealership) {
                $q->whereIn('categories.id', $dealership->categories->pluck('id'));
            })
            ->take(3)
            ->get();

        return view('public.dealerships.show', compact('dealership', 'similarDealerships'));
    }
}
