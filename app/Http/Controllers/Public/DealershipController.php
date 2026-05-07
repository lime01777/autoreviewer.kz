<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Dealership;
use Illuminate\Http\Request;

class DealershipController extends Controller
{
    public function index(Request $request)
    {
        $query = Dealership::where('status', 'published')
            ->withCount([
                'reviews as reviews_count' => function ($q) {
                    $q->where('status', 'approved');
                },
            ])
            ->withAvg([
                'reviews as rating_avg' => function ($q) {
                    $q->where('status', 'approved');
                },
            ], 'rating');

        // Search
        $search = $request->input('search') ?? $request->input('q');
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('city', 'like', '%' . $search . '%')
                  ->orWhere('address', 'like', '%' . $search . '%');
            });
        }

        // Category Filter
        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        // Brand Filter
        if ($request->filled('brand')) {
            $query->whereHas('brands', function ($q) use ($request) {
                $q->where('brands.id', $request->brand);
            });
        }

        // Type Filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
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
            case 'alphabetical':
                $query->orderBy('title');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        $dealerships = $query->with(['categories', 'brands'])->paginate(12)->withQueryString();
        
        $categories = Category::withCount('dealerships')->get();
        $brands = Brand::withCount('dealerships')->get();
        $cities = Dealership::where('status', 'published')->distinct()->pluck('city');
        $types = [
            'official_dealer' => 'Официальный дилер',
            'dealership' => 'Автосалон',
            'shop' => 'Автомагазин',
            'used' => 'Авто с пробегом',
            'service' => 'Сервис',
            'parts' => 'Запчасти',
        ];

        return view('public.dealerships.index', compact('dealerships', 'categories', 'brands', 'cities', 'types'));
    }

    public function show(Dealership $dealership)
    {
        $dealership->loadCount([
            'reviews as reviews_count' => function ($q) {
                $q->where('status', 'approved');
            },
        ])->loadAvg([
            'reviews as rating_avg' => function ($q) {
                $q->where('status', 'approved');
            },
        ], 'rating');

        $dealership->load(['categories', 'reviews' => function ($q) {
            $q->where('status', 'approved')->latest();
        }]);

        $similarDealerships = Dealership::where('status', 'published')
            ->where('id', '!=', $dealership->id)
            ->whereHas('categories', function ($q) use ($dealership) {
                $q->whereIn('categories.id', $dealership->categories->pluck('id'));
            })
            ->withCount([
                'reviews as reviews_count' => function ($q) {
                    $q->where('status', 'approved');
                },
            ])
            ->withAvg([
                'reviews as rating_avg' => function ($q) {
                    $q->where('status', 'approved');
                },
            ], 'rating')
            ->take(3)
            ->get();

        return view('public.dealerships.show', compact('dealership', 'similarDealerships'));
    }
}
