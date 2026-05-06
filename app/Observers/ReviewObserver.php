<?php

namespace App\Observers;

use App\Models\Review;

class ReviewObserver
{
    public function saved(Review $review): void
    {
        $this->updateDealershipRating($review);
    }

    public function deleted(Review $review): void
    {
        $this->updateDealershipRating($review);
    }

    protected function updateDealershipRating(Review $review): void
    {
        $dealership = $review->dealership;
        
        if ($dealership) {
            $dealership->rating_avg = $dealership->reviews()
                ->where('status', 'approved')
                ->avg('rating') ?: 0;
                
            $dealership->reviews_count = $dealership->reviews()
                ->where('status', 'approved')
                ->count();
                
            $dealership->save();
        }
    }
}
