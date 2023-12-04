<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use Illuminate\View\View;
use App\Models\Paymant_products;

class ProductsReviewsController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        $totalSales = $this->getReviews();

        return view('backEnd.products_reviews', ['totalSales' => $totalSales]);
    }


    private function getReviews(): array
    {
        $reviews = Review::latest()->paginate(1)->toArray();

        return $reviews['data'];
    }
}