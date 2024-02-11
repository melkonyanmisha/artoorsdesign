@php
    use App\Models\Wishlist;
    use App\Services\CartService;
@endphp

<div class="for_my_favorites">
    @php
        if (auth()->user()->role->type != 'customer')
        {
            $products = Wishlist::with('user', 'seller', 'product', 'product.product')->whereHas('product', function($query){
                $query->where('status', 1)->whereHas('product', function($query){
                    $query->where('status', 1);
                });
            })->where('user_id',auth()->user()->id)->paginate(12);
        }else {
            $products = Wishlist::with('user', 'seller', 'product', 'product.product')->whereHas('product', function($query){
                $query->where('status', 1)->whereHas('product', function($query){
                    $query->where('status', 1);
                });
            })->where('user_id',auth()->user()->id)->get();
        }
    @endphp
    <h4>My Favorites</h4>
    <div class="favorite_prods d_flex">
        @foreach($products as $product)
            <div class="model_product">
                <a href="{{singleProductURL(@$product->product->seller->slug, @$product->product->slug, @$product->product->product->categories[0]->slug)}}"
                   target="_blank">
                    <div class="product_img_iner">
                        <img @if (@$product->product->thum_img != null) src="{{showImage(@$product->product->thum_img)}}"
                             @else src="{{showImage(@$product->product->product->thumbnail_image_source)}}"
                             @endif alt="{{@$product->product->product->product_name}}" class="img-fluid"/>
                    </div>
                </a>
                @if($product->product->discount != 0 )
                    <span class="sale_red">
                        -
                        @if($product->product->discount_type != 0)
                            $
                        @endif
                        {{$product->product->discount}}
                        @if($product->product->discount_type == 0)
                            %
                        @endif
                    </span>
                @endif
                <div class="add_to_fav add_to_wishlist"
                     @if(!empty(Wishlist::where('user_id',auth()->id())->where('seller_product_id',$product->product->id)->first()->id))
                         data-wish="{{Wishlist::where('user_id',auth()->id())->where('seller_product_id',$product->product->id)->first()->id}}"
                     @endif
                     data-product_id="{{$product->product->id}}" data-seller_id="{{$product->product->user_id}}">
                    @guest
                        <svg width="27" height="23" viewBox="0 0 27 23" fill="none" xmlns="http://www.w3.org/2000/svg"
                             class="add_to_wishlistt">
                            <path d="M23.4972 1.67509C20.524 -0.836734 15.9617 -0.477902 13.1423 2.44402C10.2716 -0.477902 5.70932 -0.836734 2.73614 1.67509C-1.1085 4.90459 -0.544619 10.1846 2.22352 13.004L11.1943 22.1798C11.7069 22.6924 12.3734 23 13.1423 23C13.8599 23 14.5263 22.6924 15.039 22.1798L24.061 13.004C26.7779 10.1846 27.3418 4.90459 23.4972 1.67509ZM22.2669 11.261L13.2961 20.4369C13.1935 20.5394 13.091 20.5394 12.9372 20.4369L3.96642 11.261C2.06973 9.36436 1.7109 5.77604 4.32525 3.57178C6.32446 1.88014 9.40017 2.13645 11.3481 4.0844L13.1423 5.92982L14.9364 4.0844C16.8331 2.13645 19.9088 1.88014 21.908 3.52052C24.5224 5.77604 24.1636 9.36436 22.2669 11.261Z"
                                  fill="#00AAAD"/>
                        </svg>
                    @endguest
                    @auth
                        @if(!empty(Wishlist::where('user_id',auth()->id())->where('seller_product_id',$product->product->id)->first()->id))
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
                        <a href="{{singleProductURL(@$product->product->seller->slug, @$product->product->slug,  @$product->product->product->categories[0]->slug)}}"
                           target="_blank">
                            @if (@$product->product->product_name)
                                {{@$product->product->product_name}}
                            @else
                                {{$product->product->product->product_name}}
                            @endif</a>

                        <span class="data_of_model">{{$product->created_at->toDateString()}}</span>
                    </div>
                    <div class="d_flex sto_ for_sale_height">
                        @php
                            $reviews = @$product->product->reviews->where('status',1)->pluck('rating');
                            if(count($reviews)>0){
                                $value = 0;
                                $rating = 0;
                                foreach($reviews as $review){
                                    $value += $review;
                                }
                                $rating = $value/count($reviews);
                                $total_review = count($reviews);
                            }else{
                                $rating = 0;
                                $total_review = 0;
                            }
                        @endphp
                        <div class="d_flex eye_cool">
                            <div class="watched_">
                                <svg width="18" height="12" viewBox="0 0 18 12" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 2.5C8.65625 2.53125 8.3125 2.5625 8 2.65625C8.15625 2.90625 8.21875 3.21875 8.25 3.5C8.25 4.46875 7.4375 5.25 6.5 5.25C6.1875 5.25 5.875 5.1875 5.65625 5.03125C5.5625 5.34375 5.5 5.65625 5.5 6C5.5 7.9375 7.0625 9.5 9 9.5C10.9375 9.5 12.5 7.9375 12.5 6C12.5 4.09375 10.9375 2.53125 9 2.53125V2.5ZM17.875 5.5625C16.1875 2.25 12.8125 0 9 0C5.15625 0 1.78125 2.25 0.09375 5.5625C0.03125 5.6875 0 5.84375 0 6C0 6.1875 0.03125 6.34375 0.09375 6.46875C1.78125 9.78125 5.15625 12 9 12C12.8125 12 16.1875 9.78125 17.875 6.46875C17.9375 6.34375 17.9688 6.1875 17.9688 6.03125C17.9688 5.84375 17.9375 5.6875 17.875 5.5625ZM9 10.5C5.90625 10.5 3.0625 8.78125 1.5625 6C3.0625 3.21875 5.90625 1.5 9 1.5C12.0625 1.5 14.9062 3.21875 16.4062 6C14.9062 8.78125 12.0625 10.5 9 10.5Z"
                                          fill="#717171"></path>
                                </svg>
                                <span>{{$product->product->viewed}}</span>
                            </div>
                            {{--                            <a href="{{route('download',['filename' => $product->product->product->pdf])}}"--}}
                            {{--                               class='download_'--}}
                            {{--                               @if($product->product->skus->first()->selling_price != 0.0 || is_null($product->product->product->pdf)) style='display:none' @endif>--}}
                            {{--                                <svg width="18" height="16" viewBox="0 0 18 16" fill="none"--}}
                            {{--                                     xmlns="http://www.w3.org/2000/svg">--}}
                            {{--                                    <path d="M16.5 9H13.5938L15.0625 7.5625C16 6.625 15.3125 5 14 5H12V1.5C12 0.6875 11.3125 0 10.5 0H7.5C6.65625 0 6 0.6875 6 1.5V5H4C2.65625 5 1.96875 6.625 2.9375 7.5625L4.375 9H1.5C0.65625 9 0 9.6875 0 10.5V14.5C0 15.3438 0.65625 16 1.5 16H16.5C17.3125 16 18 15.3438 18 14.5V10.5C18 9.6875 17.3125 9 16.5 9ZM4 6.5H7.5V1.5H10.5V6.5H14L9 11.5L4 6.5ZM16.5 14.5H1.5V10.5H5.875L7.9375 12.5625C8.5 13.1562 9.46875 13.1562 10.0312 12.5625L12.0938 10.5H16.5V14.5ZM13.75 12.5C13.75 12.9375 14.0625 13.25 14.5 13.25C14.9062 13.25 15.25 12.9375 15.25 12.5C15.25 12.0938 14.9062 11.75 14.5 11.75C14.0625 11.75 13.75 12.0938 13.75 12.5Z"--}}
                            {{--                                          fill="#323232"/>--}}
                            {{--                                </svg>--}}
                            {{--                                <span> {{$product->product->product->downloads}}</span>--}}
                            {{--                            </a>--}}
                            {{--                            <a class="like_post" onclick="likes('{{$product->product->id}}')">--}}
                            {{--                                <svg width="15" height="16" viewBox="0 0 15 16" fill="none"--}}
                            {{--                                     xmlns="http://www.w3.org/2000/svg">--}}
                            {{--                                    <path d="M14.5625 8.96875C14.8438 8.5 15 8 15 7.40625C15 6.03125 13.8125 4.75 12.3125 4.75H11.1562C11.3125 4.34375 11.4375 3.875 11.4375 3.28125C11.4375 1 10.25 0 8.46875 0C6.53125 0 6.65625 2.96875 6.21875 3.40625C5.5 4.125 4.65625 5.5 4.0625 6H1C0.4375 6 0 6.46875 0 7V14.5C0 15.0625 0.4375 15.5 1 15.5H3C3.4375 15.5 3.84375 15.1875 3.9375 14.7812C5.34375 14.8125 6.3125 16 9.5 16C9.75 16 10 16 10.2188 16C12.625 16 13.6875 14.7812 13.7188 13.0312C14.1562 12.4688 14.375 11.6875 14.2812 10.9375C14.5938 10.375 14.6875 9.6875 14.5625 8.96875ZM12.625 10.6562C13.0312 11.3125 12.6562 12.1875 12.1875 12.4688C12.4375 13.9688 11.625 14.5 10.5312 14.5H9.34375C7.125 14.5 5.65625 13.3438 4 13.3438V7.5H4.3125C5.21875 7.5 6.4375 5.3125 7.28125 4.46875C8.15625 3.59375 7.875 2.09375 8.46875 1.5C9.9375 1.5 9.9375 2.53125 9.9375 3.28125C9.9375 4.5 9.0625 5.0625 9.0625 6.25H12.3125C12.9688 6.25 13.4688 6.84375 13.5 7.4375C13.5 8 13.0938 8.59375 12.7812 8.59375C13.2188 9.0625 13.3125 10.0312 12.625 10.6562ZM2.75 13.5C2.75 13.9375 2.40625 14.25 2 14.25C1.5625 14.25 1.25 13.9375 1.25 13.5C1.25 13.0938 1.5625 12.75 2 12.75C2.40625 12.75 2.75 13.0938 2.75 13.5Z"--}}
                            {{--                                          fill="#717171"/>--}}
                            {{--                                </svg>--}}
                            {{--                                <span class="likes{{$product->product->id}}">{{count(\App\Models\Like::where('product_id',$product->product->id)->get())}}</span>--}}
                            {{--                            </a>--}}
                        </div>
                        <div class='d_flex sale_price_col'>
                            @if(($product->product->hasDeal || $product->product->hasDiscount == 'yes') && single_price(@$product->product->skus->first()->selling_price) != '$ 0.00')
                                <span class="prev_price">{{$product->product->skus->max('selling_price')}}$</span>
                            @endif
                            <span class="price_of_prod">
                                        @if($product->product->hasDeal)

                                    {{single_price(selling_price(@$product->product->skus->first()->selling_price,$product->product->hasDeal->discount_type,$product->product->hasDeal->discount))}}
                                @else

                                    @if($product->product->hasDiscount == 'yes')
                                        {{single_price(selling_price(@$product->product->skus->first()->selling_price,$product->product->discount_type,$product->product->discount))}}
                                        @if($product->product->hasDeal || $product->product->hasDiscount == 'yes')
                                            <span class="prev_price">{{$product->product->skus->max('selling_price')}}$</span>
                                        @endif
                                    @else
                                        {{(@$product->product->skus->first()->selling_price == 0)?'Free':single_price(@$product->product->skus->first()->selling_price)}}
                                        {{--                                        {{single_price(@$product->skus->first()->selling_price)}}--}}
                                    @endif

                                @endif
                                    </span>
                        </div>
                    </div>

                    @if(CartService::isProductPurchased(@$product->product()->first()))

                        @include('product::products.download_product_partial', ['product' => $product->product()->first()])
                    @else
                        @php
                            $disabledAddToCartClass = "";
                            if(CartService::isProductInCart(@$product->product->skus->first()->id)) {
                              $disabledAddToCartClass = "disabled";
                            }
                        @endphp
                        <a @if(single_price($product->product->skus->max('selling_price')) == '$ 0.00')
                               href="{{$product->product->product->video_link}}"
                           class="add_catalog_btn"
                           @else
                               @auth class="{{ $disabledAddToCartClass }} addToCartFromThumnail add_catalog_btn"
                           @elseif(single_price($product->product->skus->max('selling_price')) == '$ 0.00') class="{{ $disabledAddToCartClass }} addToCartFromThumnail add_catalog_btn"
                           @else class="add_catalog_btn" @endauth
                           @endif data-producttype="{{ @$product->product->product->product_type }}" data-seller={{ @$product->product->user_id }} data-product-sku={{ @$product->product->skus->first()->id }}
                @if(@$product->product->hasDeal)
                    data-base-price={{ selling_price(@$product->product->skus->first()->selling_price,@$product->product->hasDeal->discount_type,$product->product->hasDeal->discount) }}
                @else
                  @if(@$product->product->hasDiscount == 'yes')
                    data-base-price={{ selling_price(@$product->product->skus->first()->selling_price,@$product->product->discount_type,@$product->product->discount) }}
                  @else
                    data-base-price={{ @$product->product->skus->first()->selling_price }}
                  @endif
                @endif
                data-shipping-method={{ @$product->product->product->shippingMethods->first()->shipping_method_id }}
                data-product-id={{ @$product->product->id }}
                data-stock_manage="{{$product->product->stock_manage}}"
                           data-stock="{{@$product->product->skus->first()->product_stock}}"
                           data-min_qty="{{$product->product->product->minimum_order_qty}}"
                        >
                            @if(single_price($product->product->skus->max('selling_price')) == '$ 0.00')
                                <svg width="18" height="16" viewBox="0 0 18 16" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_794_15016)">
                                        <path d="M16.5 9H13.5938L15.0625 7.5625C16 6.625 15.3125 5 14 5H12V1.5C12 0.6875 11.3125 0 10.5 0H7.5C6.65625 0 6 0.6875 6 1.5V5H4C2.65625 5 1.96875 6.625 2.9375 7.5625L4.375 9H1.5C0.65625 9 0 9.6875 0 10.5V14.5C0 15.3438 0.65625 16 1.5 16H16.5C17.3125 16 18 15.3438 18 14.5V10.5C18 9.6875 17.3125 9 16.5 9ZM4 6.5H7.5V1.5H10.5V6.5H14L9 11.5L4 6.5ZM16.5 14.5H1.5V10.5H5.875L7.9375 12.5625C8.5 13.1562 9.46875 13.1562 10.0312 12.5625L12.0938 10.5H16.5V14.5ZM13.75 12.5C13.75 12.9375 14.0625 13.25 14.5 13.25C14.9062 13.25 15.25 12.9375 15.25 12.5C15.25 12.0938 14.9062 11.75 14.5 11.75C14.0625 11.75 13.75 12.0938 13.75 12.5Z"
                                              fill="white"/>
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_794_15016">
                                            <rect width="18" height="16" fill="white"/>
                                        </clipPath>
                                    </defs>
                                </svg>

                            @else
                                +
                                <svg width="21" height="18" viewBox="0 0 21 18" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M19.645 2.25H5.33643L5.02002 0.703125C4.94971 0.316406 4.59815 0 4.21143 0H0.695801C0.449707 0 0.273926 0.210938 0.273926 0.421875V1.26562C0.273926 1.51172 0.449707 1.6875 0.695801 1.6875H3.5083L5.93408 14.2031C5.54736 14.625 5.33643 15.1523 5.33643 15.75C5.33643 17.0156 6.3208 18 7.58643 18C8.8169 18 9.83643 17.0156 9.83643 15.75C9.83643 15.3633 9.6958 14.9766 9.52002 14.625H14.6177C14.4419 14.9766 14.3364 15.3633 14.3364 15.75C14.3364 17.0156 15.3208 18 16.5864 18C17.8169 18 18.8364 17.0156 18.8364 15.75C18.8364 15.1172 18.5552 14.5547 18.1333 14.1328L18.1685 13.9922C18.2739 13.4648 17.8872 12.9375 17.3247 12.9375H7.41065L7.09424 11.25H18.063C18.4849 11.25 18.8013 11.0039 18.9067 10.6172L20.4888 3.30469C20.5942 2.77734 20.2075 2.25 19.645 2.25ZM7.58643 16.5938C7.09424 16.5938 6.74268 16.2422 6.74268 15.75C6.74268 15.293 7.09424 14.9062 7.58643 14.9062C8.04346 14.9062 8.43018 15.293 8.43018 15.75C8.43018 16.2422 8.04346 16.5938 7.58643 16.5938ZM16.5864 16.5938C16.0942 16.5938 15.7427 16.2422 15.7427 15.75C15.7427 15.293 16.0942 14.9062 16.5864 14.9062C17.0435 14.9062 17.4302 15.293 17.4302 15.75C17.4302 16.2422 17.0435 16.5938 16.5864 16.5938ZM17.395 9.5625H6.74268L5.65283 3.9375H18.6255L17.395 9.5625Z"
                                          fill="white"/>
                                </svg>
                            @endif
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>