<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Dealership;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function toggle(Request $request, Dealership $dealership)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        $favorite = Favorite::where('user_id', $user->id)
            ->where('dealership_id', $dealership->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $status = 'removed';
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'dealership_id' => $dealership->id
            ]);
            $status = 'added';
        }

        return response()->json([
            'status' => $status,
            'count' => $user->favorites()->count()
        ]);
    }
}
