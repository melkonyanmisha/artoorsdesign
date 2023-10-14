<?php
namespace App\Services;

use App\Repositories\CartRepository;
use App\Models\Cart;


class CartService{

    protected $cartRepository;

    private static $userCarts = null;

    public function __construct(CartRepository $cartRepository){
        $this->cartRepository = $cartRepository;
    }

    public function store($data){
        return $this->cartRepository->store($data);
    }
    public function update($data){
        return $this->cartRepository->update($data);
    }

    public function updateCartShippingInfo($data)
    {
        return $this->cartRepository->updateCartShippingInfo($data);
    }

    public function getCartData(){
        return $this->cartRepository->getCartData();
    }
    public function updateQty($data){
        return $this->cartRepository->updateQty($data);
    }
    public function selectAll($data){
        return $this->cartRepository->selectAll($data);
    }
    public function selectAllSeller($data){
        return $this->cartRepository->selectAllSeller($data);
    }
    public function selectItem($data){
        return $this->cartRepository->selectItem($data);
    }
    public function deleteCartProduct($data){
        return $this->cartRepository->deleteCartProduct($data);
    }
    public function deleteAll(){
        return $this->cartRepository->deleteAll();
    }
    public static function isProductInCart($skuId): bool {
      $carts = self::getUserCarts();
      $productExistsInCart = false;
      foreach ($carts as $cartOne) {
        if ($cartOne->product_id === $skuId) {
          $productExistsInCart = true;
          break;
        }
      }

      return $productExistsInCart;
    }
    public static function isProductPurchased($product): bool {
        $orders = \App\Models\Order::where('customer_id',auth()->id())->get();

        foreach ($orders as $order) {
            foreach ($order->packages[0]->products as $product_one) {
                if(empty($product_one->seller_product_sku)) {
                    continue;
                }
                if($product_one->seller_product_sku->product->product()->first()->id == $product->id)
                    return TRUE;
            }
        }

        return FALSE;
    }
    public static function getUserCarts() {
      if(is_null(self::$userCarts)) {
        self::$userCarts = CartRepository::getActiveUserCarts();
      }

      return self::$userCarts;
    }
}
