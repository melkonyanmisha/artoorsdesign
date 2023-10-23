@if(isset($products) && count($products) > 0)
    <div class="categories_block d_flex">

        @foreach($products as $product)
            <div class="model_product">
                <a href="{{singleProductURL($product->seller->slug, $product->slug, $product->product->categories[0]->slug)}}">
                    <div class='model_img'>
                        <img @if ($product->thum_img != null) src="{{asset(asset_path($product->thum_img))}}"
                             @else src="{{asset(asset_path(@$product->product->thumbnail_image_source))}}"
                             @endif  alt="{{@$product->product_name?@$product->product_name:@$product->product->product_name}}">
                    </div>
                </a>
                @if($product->hasDiscount == 'yes')
                    @if($product->discount != 0 )
                        <span class="sale_red"> -
                            @if($product->discount_type != 0)
                                $
                            @endif
                            {{$product->discount}}
                            @if($product->discount_type == 0)
                                %
                            @endif
                        </span>
                    @endif
                @endif

                <div class="add_to_fav add_to_wishlist"
                     @if(!empty(\App\Models\Wishlist::where('user_id',auth()->id())->where('seller_product_id',$product->id)->first()->id))
                         data-wish="{{\App\Models\Wishlist::where('user_id',auth()->id())->where('seller_product_id',$product->id)->first()->id}}"
                     @endif
                     data-product_id="{{$product->id}}" data-seller_id="{{$product->user_id}}">
                    @guest
                        <svg width="27" height="23" viewBox="0 0 27 23" fill="none" xmlns="http://www.w3.org/2000/svg"
                             class="add_to_wishlistt">
                            <path d="M23.4972 1.67509C20.524 -0.836734 15.9617 -0.477902 13.1423 2.44402C10.2716 -0.477902 5.70932 -0.836734 2.73614 1.67509C-1.1085 4.90459 -0.544619 10.1846 2.22352 13.004L11.1943 22.1798C11.7069 22.6924 12.3734 23 13.1423 23C13.8599 23 14.5263 22.6924 15.039 22.1798L24.061 13.004C26.7779 10.1846 27.3418 4.90459 23.4972 1.67509ZM22.2669 11.261L13.2961 20.4369C13.1935 20.5394 13.091 20.5394 12.9372 20.4369L3.96642 11.261C2.06973 9.36436 1.7109 5.77604 4.32525 3.57178C6.32446 1.88014 9.40017 2.13645 11.3481 4.0844L13.1423 5.92982L14.9364 4.0844C16.8331 2.13645 19.9088 1.88014 21.908 3.52052C24.5224 5.77604 24.1636 9.36436 22.2669 11.261Z"
                                  fill="#00AAAD"/>
                        </svg>
                    @endguest
                    @auth
                        @if(!empty(\App\Models\Wishlist::where('user_id',auth()->id())->where('seller_product_id',$product->id)->first()->id))
                            <svg width="28" height="24" viewBox="0 0 28 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M24.2611 2C21.2879 -0.511827 16.7256 -0.152994 13.9062 2.76893C11.0355 -0.152994 6.47324 -0.511827 3.50005 2C-0.344584 5.22949 0.219297 10.5095 2.98743 13.3289L11.9583 22.5047C12.4709 23.0173 13.1373 23.3249 13.9062 23.3249C14.6239 23.3249 15.2903 23.0173 15.8029 22.5047L24.825 13.3289C27.5418 10.5095 28.1057 5.22949 24.2611 2Z"
                                      fill="#00AAAD"/>
                            </svg>
                        @else
                            <svg width="27" height="23" viewBox="0 0 27 23" fill="none"
                                 xmlns="http://www.w3.org/2000/svg" class="add_to_wishlistt">
                                <path d="M23.4972 1.67509C20.524 -0.836734 15.9617 -0.477902 13.1423 2.44402C10.2716 -0.477902 5.70932 -0.836734 2.73614 1.67509C-1.1085 4.90459 -0.544619 10.1846 2.22352 13.004L11.1943 22.1798C11.7069 22.6924 12.3734 23 13.1423 23C13.8599 23 14.5263 22.6924 15.039 22.1798L24.061 13.004C26.7779 10.1846 27.3418 4.90459 23.4972 1.67509ZM22.2669 11.261L13.2961 20.4369C13.1935 20.5394 13.091 20.5394 12.9372 20.4369L3.96642 11.261C2.06973 9.36436 1.7109 5.77604 4.32525 3.57178C6.32446 1.88014 9.40017 2.13645 11.3481 4.0844L13.1423 5.92982L14.9364 4.0844C16.8331 2.13645 19.9088 1.88014 21.908 3.52052C24.5224 5.77604 24.1636 9.36436 22.2669 11.261Z"
                                      fill="#00AAAD"/>
                            </svg>
                        @endif
                    @endauth
                </div>
                <div class="about_model sto_ d_flex">
                    <div class="d_flex sto_ col_titles_mob">
                        <a class="model_name"
                           href="{{singleProductURL($product->seller->slug, $product->slug, $product->product->categories[0]->slug)}}">
                            @if ($product->product_name)
                                {{$product->product_name}}
                            @else
                                {{$product->product->product_name}}
                            @endif
                        </a>
                    </div>
                    <div class="d_flex sto_ for_sale_height">
                        <span class="twenty_sp">
                            @php
                                $productFileTypesTxts =\App\Services\ProductService::getProductFileTypes($product);
                                echo implode(", ", $productFileTypesTxts)
                            @endphp
                        </span>
                        <div class='d_flex sale_price_col'>
                            @if(($product->hasDeal || $product->hasDiscount == 'yes') && single_price(@$product->skus->first()->selling_price) != '$ 0.00')
                                <span class="prev_price">{{$product->skus->max('selling_price')}}$</span>
                            @endif
                            <span class="price_of_prod">
                                @if($product->hasDeal)
                                    {{single_price(selling_price(@$product->skus->first()->selling_price,$product->hasDeal->discount_type,$product->hasDeal->discount))}}
                                @else
                                    @if($product->hasDiscount == 'yes')
                                        {{(single_price(@$product->skus->first()->selling_price) == '$ 0.00')?'Free':single_price(selling_price(@$product->skus->first()->selling_price,$product->discount_type,$product->discount))}}

                                    @else
                                        {{(single_price(@$product->skus->first()->selling_price) == '$ 0.00')?'Free':single_price(@$product->skus->first()->selling_price)}}
                                    @endif
                                @endif
                            </span>
                        </div>
                    </div>
                    @if( \App\Services\CartService::isProductPurchased(@$product->product()->first()) || single_price($product->skus->max('selling_price')) == '$ 0.00')
                        @include('product::products.download_product_partial', ['product' => $product->product()->first()])
                    @else
                        <a
                                @if(single_price($product->skus->max('selling_price')) == '$ 0.00')
                                    @auth
                                        href="{{$product->product->video_link}}"
                                class="add_catalog_btn"
                                @else
                                    class="add_catalog_btn login_btn"
                                @endauth
                                @else
                                    @php
                                        $disabledAddToCartClass = "";
                                        if( \App\Services\CartService::isProductInCart(@$product->skus->first()->id)) {
                                          $disabledAddToCartClass = "disabled";
                                        }
                                    @endphp
                                    @auth class="{{ $disabledAddToCartClass }} addToCartFromThumnail add_catalog_btn"
                                @elseif(single_price($product->skus->max('selling_price')) == '$ 0.00') class="{{$disabledAddToCartClass}} addToCartFromThumnail add_catalog_btn"
                                @else class="{{$disabledAddToCartClass}} add_catalog_btn login_btn"
                                @endauth
                                @endif
                                tabindex="-1"
                                data-producttype="{{ @$product->product->product_type }}"
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
                                data-min_qty="{{$product->product->minimum_order_qty}}"
                        >
                            +
                            <svg width="21" height="18" viewBox="0 0 21 18" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M19.645 2.25H5.33643L5.02002 0.703125C4.94971 0.316406 4.59815 0 4.21143 0H0.695801C0.449707 0 0.273926 0.210938 0.273926 0.421875V1.26562C0.273926 1.51172 0.449707 1.6875 0.695801 1.6875H3.5083L5.93408 14.2031C5.54736 14.625 5.33643 15.1523 5.33643 15.75C5.33643 17.0156 6.3208 18 7.58643 18C8.8169 18 9.83643 17.0156 9.83643 15.75C9.83643 15.3633 9.6958 14.9766 9.52002 14.625H14.6177C14.4419 14.9766 14.3364 15.3633 14.3364 15.75C14.3364 17.0156 15.3208 18 16.5864 18C17.8169 18 18.8364 17.0156 18.8364 15.75C18.8364 15.1172 18.5552 14.5547 18.1333 14.1328L18.1685 13.9922C18.2739 13.4648 17.8872 12.9375 17.3247 12.9375H7.41065L7.09424 11.25H18.063C18.4849 11.25 18.8013 11.0039 18.9067 10.6172L20.4888 3.30469C20.5942 2.77734 20.2075 2.25 19.645 2.25ZM7.58643 16.5938C7.09424 16.5938 6.74268 16.2422 6.74268 15.75C6.74268 15.293 7.09424 14.9062 7.58643 14.9062C8.04346 14.9062 8.43018 15.293 8.43018 15.75C8.43018 16.2422 8.04346 16.5938 7.58643 16.5938ZM16.5864 16.5938C16.0942 16.5938 15.7427 16.2422 15.7427 15.75C15.7427 15.293 16.0942 14.9062 16.5864 14.9062C17.0435 14.9062 17.4302 15.293 17.4302 15.75C17.4302 16.2422 17.0435 16.5938 16.5864 16.5938ZM17.395 9.5625H6.74268L5.65283 3.9375H18.6255L17.395 9.5625Z"
                                      fill="white"/>
                            </svg>
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    {{ $products->links('frontend.default.partials.paginate') }}
@else
    <div class="sto_ new_class">
        <img src="{{asset(asset_path('/new/img/product_not_found.png'))}}">
        <span class="about_error_sp">{{__("common.product_not_found")}}</span>
    </div>
@endif