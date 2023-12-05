<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProductsReviewsController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        $full           = boolval(request('full'));
        $productReviews = $this->getReviews($full);

        return view('backEnd.products_reviews', ['productReviews' => $productReviews]);
    }

    /**
     * @param bool $full
     *
     * @return array
     */
    private function getReviews(bool $full): array
    {
        if ($full) {
            $reviews = Review::with('product')->latest()->get()->toArray();
        } else {
            $reviews = Review::with('product.categories')->latest()->paginate(100)->toArray()['data'] ?? [];
        }

        if ( ! empty($reviews)) {
            foreach ($reviews as &$currentReview) {
                $currentProductCatSlug              = $currentReview['product']['categories'][0]['slug'] ?? '';
                $currentReview['product_name_html'] = sprintf(
                    '<a href="%1$s" target="_blank">%2$s</a>',
                    singleProductURL('', $currentReview['product']['slug'], $currentProductCatSlug),
                    $currentReview['product']['product_name']
                );

                $currentReview['remove_form'] = sprintf(
                    '
                <form action="%1$s" method="post" onsubmit="return confirm(\'Are you sure you want to delete this review?\')">
                    <input type="hidden" name="_token" value="%2$s">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit">Remove</button>
                </form>',
                    route('admin.products_reviews.destroy', ['id' => $currentReview['id']]),
                    csrf_token()
                );
            }
        }

        return $reviews;
    }

    /**
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        // Find the review by ID
        $review = Review::find($id);

        // Check if the review exists before attempting to delete
        if ($review) {
            // Delete the review
            $review->delete();

            return redirect()->route('admin.products_reviews')->with('success', 'Review has been deleted.');
        } else {
            return redirect()->route('admin.products_reviews')->with('error', 'Review not found.');
        }
    }

}