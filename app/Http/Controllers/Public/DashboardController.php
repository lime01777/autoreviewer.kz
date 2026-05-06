<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $reviews = $user->reviews()->with('dealership')->latest()->take(5)->get();
        $favorites = $user->favoriteDealerships()->take(3)->get();

        return view('public.dashboard.index', compact('user', 'reviews', 'favorites'));
    }

    public function reviews()
    {
        $reviews = Auth::user()->reviews()->with('dealership')->latest()->paginate(10);
        return view('public.dashboard.reviews', compact('reviews'));
    }

    public function favorites()
    {
        $favorites = Auth::user()->favoriteDealerships()->paginate(12);
        return view('public.dashboard.favorites', compact('favorites'));
    }
    public function toggleFavorite(\App\Models\Dealership $dealership)
    {
        $user = Auth::user();
        $user->favoriteDealerships()->toggle($dealership->id);

        return back()->with('success', 'Список избранного обновлен');
    }
}
