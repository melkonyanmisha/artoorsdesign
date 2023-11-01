@php
    /** @var Modules\Seller\Entities\SellerProduct $product */

use App\Services\CartService;
@endphp

@php
    $link = route('artoors.files',$product->id);

    $disabledAddToCartClass = "";
    if( CartService::isProductInCart(@$product->skus->first()->id)) {
      $disabledAddToCartClass = "disabled";
    }

@endphp

<a @auth
       class="{{ $disabledAddToCartClass }} addToCartFromThumnail add_catalog_btn"
   @else
       class="add_catalog_btn login_btn"
   @endauth
   tabindex="-1" data-producttype="{{ @$product->product->product_type }}"
   data-seller={{ $product->user_id }} data-product-sku={{ @$product->skus->first()->id }}
                           @if(@$product->hasDeal)
                                   data-base-price={{ selling_price(@$product->skus->first()->selling_price,@$product->hasDeal->discount_type,@$product->hasDeal->discount) }}
                           @else
                                @if(@$product->hasDiscount == 'yes')
                                   data-base-price={{ selling_price(@$product->skus->first()->selling_price,@$product->discount_type,@$product->discount) }}
                                @else
                                   data-base-price={{ @$product->skus->first()->selling_price }}
                                @endif
                           @endif
   data-shipping-method=0 data-product-id={{ $product->id }}
   data-stock_manage="{{$product->stock_manage}}"
   data-stock="{{@$product->skus->first()->product_stock}}"
   data-min_qty="{{$product->product->minimum_order_qty}}">
    +
    <svg width="21" height="18" viewBox="0 0 21 18" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M19.645 2.25H5.33643L5.02002 0.703125C4.94971 0.316406 4.59815 0 4.21143 0H0.695801C0.449707 0 0.273926 0.210938 0.273926 0.421875V1.26562C0.273926 1.51172 0.449707 1.6875 0.695801 1.6875H3.5083L5.93408 14.2031C5.54736 14.625 5.33643 15.1523 5.33643 15.75C5.33643 17.0156 6.3208 18 7.58643 18C8.8169 18 9.83643 17.0156 9.83643 15.75C9.83643 15.3633 9.6958 14.9766 9.52002 14.625H14.6177C14.4419 14.9766 14.3364 15.3633 14.3364 15.75C14.3364 17.0156 15.3208 18 16.5864 18C17.8169 18 18.8364 17.0156 18.8364 15.75C18.8364 15.1172 18.5552 14.5547 18.1333 14.1328L18.1685 13.9922C18.2739 13.4648 17.8872 12.9375 17.3247 12.9375H7.41065L7.09424 11.25H18.063C18.4849 11.25 18.8013 11.0039 18.9067 10.6172L20.4888 3.30469C20.5942 2.77734 20.2075 2.25 19.645 2.25ZM7.58643 16.5938C7.09424 16.5938 6.74268 16.2422 6.74268 15.75C6.74268 15.293 7.09424 14.9062 7.58643 14.9062C8.04346 14.9062 8.43018 15.293 8.43018 15.75C8.43018 16.2422 8.04346 16.5938 7.58643 16.5938ZM16.5864 16.5938C16.0942 16.5938 15.7427 16.2422 15.7427 15.75C15.7427 15.293 16.0942 14.9062 16.5864 14.9062C17.0435 14.9062 17.4302 15.293 17.4302 15.75C17.4302 16.2422 17.0435 16.5938 16.5864 16.5938ZM17.395 9.5625H6.74268L5.65283 3.9375H18.6255L17.395 9.5625Z"
              fill="white"/>
    </svg>
</a>