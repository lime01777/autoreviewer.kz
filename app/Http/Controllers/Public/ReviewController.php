<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Dealership;
use App\Models\Review;
use App\Http\Requests\StoreReviewRequest;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(StoreReviewRequest $request, Dealership $dealership)
    {
        // Simple Honeypot check
        if ($request->filled('website_url')) {
            return back()->with('error', 'Spam detected.');
        }

        $validated = $request->validated();

        $review = new Review($validated);
        $review->dealership_id = $dealership->id;
        $review->user_id = Auth::id();
        $review->status = 'pending';
        $review->ip_address = $request->ip();
        $review->user_agent = $request->userAgent();
        $review->save();

        return back()->with('success', 'Спасибо, ваш отзыв отправлен на модерацию и появится на сайте после проверки.');
    }
}
