<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::where('status', 'published')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->paginate(12);

        return view('public.news.index', compact('news'));
    }

    public function show(News $news)
    {
        if ($news->status !== 'published' || $news->published_at > now()) {
            abort(404);
        }

        $recentNews = News::where('status', 'published')
            ->where('id', '!=', $news->id)
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('public.news.show', compact('news', 'recentNews'));
    }
}
