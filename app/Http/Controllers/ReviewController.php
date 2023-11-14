<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\User;

class ReviewController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'user_id'          => 'required',
            'product_id'       => 'required',
            'text'             => 'required',
            'is_positive_like' => 'required',
            'product_url'      => 'required',
        ]);

        $review = Review::create($request->all());

        if ( ! $review) {
            return response()->json(['success' => false, 'message' => 'Failed to create review']);
        }

        // Check if the review was created successfully

        $superAdminUsers = User::where('is_active', 1)->whereHas('role', function ($query) {
            return $query->where('type', 'superadmin');
        })->get();

        foreach ($superAdminUsers as $currentUser) {
            $this->createSystemNotification($currentUser, 'Review', $request->get('product_url'));
        }

        // Return a success response
        return response()->json(['success' => true, 'message' => 'Review created successfully']);
    }
}
