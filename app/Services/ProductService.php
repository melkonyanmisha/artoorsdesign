<?php
namespace App\Services;

use App\Repositories\ProductRepository;
use Modules\Seller\Entities\SellerProduct;


class ProductService{

    protected $productRepository;

    public function __construct(ProductRepository $productRepository){
        $this->productRepository = $productRepository;
    }

    public static function getProductFileTypes(SellerProduct $product): ?array {
        $productFileTypesTxts = [];
        if ($product->file_type) {
            $productFileTypes = explode(',', $product->file_type);
            foreach ($productFileTypes as $productFileTypeOne) {
                switch ((int)$productFileTypeOne) {
                    case 1:
                        $productFileTypesTxts[] = '3DM';
                        break;
                    case 2:
                        $productFileTypesTxts[] = 'STL';
                        break;
                    case 3:
                        $productFileTypesTxts[] = 'Obj';
                        break;
                }
            }
        }

        return $productFileTypesTxts;
    }

    public function getProductBySlug($slug)
    {
        return $this->productRepository->getProductBySlug($slug);
    }

    public function getActiveSellerProductBySlug($slug, $seller_slug = null)
    {
        return $this->productRepository->getActiveSellerProductBySlug($slug, $seller_slug);
    }

    public function getProductByID($id){
        return $this->productRepository->getProductByID($id);
    }

    public function recentViewIncrease($id){
        return $this->productRepository->recentViewIncrease($id);
    }

    public function recentViewStore($seller_product_id)
    {
        return $this->productRepository->recentViewStore($seller_product_id);
    }

    public function lastRecentViewinfo()
    {
        return $this->productRepository->lastRecentViewinfo();
    }

    public function getReviewByPage($data){
        return $this->productRepository->getReviewByPage($data);
    }

}
