@extends('frontend.default.layouts.newApp')

@section('content')
    <main>
        @include('frontend.default.includes.mainInclude')
            <section class="wrapper">
                @section('breadcrumb')
                    {{__('defaultTheme.wishlist')}}
                @endsection
                @include('frontend.default.partials._breadcrumb')
                <div class="favorite_section">
                    <h1>{{__('defaultTheme.wishlist')}}</h1>
                    <div class="d_flex fl_end">
                        <div class="favorite_prods d_flex">
                            @php
                                if (auth()->user()->role->type != 'customer')
                                {
                                    $products = App\Models\Wishlist::with('user', 'seller', 'product', 'product.product')->whereHas('product', function($query){
                                        $query->where('status', 1)->whereHas('product', function($query){
                                            $query->where('status', 1);
                                        });
                                    })->where('user_id',auth()->user()->id)->paginate(12);
                                }else {
                                    $products = App\Models\Wishlist::with('user', 'seller', 'product', 'product.product')->whereHas('product', function($query){
                                        $query->where('status', 1)->whereHas('product', function($query){
                                            $query->where('status', 1);
                                        });
                                    })->where('user_id',auth()->user()->id)->paginate(request()->paginate_id??10);
                                }
                            @endphp
                            @foreach($products as $product)
                                <div class="model_product">
                                    <a href="{{singleProductURL(@$product->product->seller->slug, $product->product->slug, $product->product->product->categories[0]->slug)}}" target="_blank" >
                                        <div class="product_img_iner">
                                            <img @if (@$product->product->thum_img != null) src="{{showImage(@$product->product->thum_img)}}" @else src="{{showImage(@$product->product->product->thumbnail_image_source)}}" @endif alt="{{@$product->product->product->product_name}}" class="img-fluid" />
                                        </div>
                                    </a>
                                    @if($product->product->discount != 0 )
                                        <span class="sale_red"> -@if($product->product->discount_type != 0)
                                                $@endif {{$product->product->discount}} @if($product->product->discount_type == 0)
                                                %@endif</span>
                                    @endif
                                    <div class="add_to_fav add_to_wishlist"
                                         @if(!empty(\App\Models\Wishlist::where('user_id',auth()->id())->where('seller_product_id',$product->product->id)->first()->id))
                                         data-wish="{{\App\Models\Wishlist::where('user_id',auth()->id())->where('seller_product_id',$product->product->id)->first()->id}}"
                                         @endif
                                         data-product_id="{{$product->product->id}}" data-seller_id="{{$product->product->user_id}}">
                                        @guest
                                            <svg width="27" height="23" viewBox="0 0 27 23" fill="none" xmlns="http://www.w3.org/2000/svg" class="add_to_wishlistt">
                                                <path d="M23.4972 1.67509C20.524 -0.836734 15.9617 -0.477902 13.1423 2.44402C10.2716 -0.477902 5.70932 -0.836734 2.73614 1.67509C-1.1085 4.90459 -0.544619 10.1846 2.22352 13.004L11.1943 22.1798C11.7069 22.6924 12.3734 23 13.1423 23C13.8599 23 14.5263 22.6924 15.039 22.1798L24.061 13.004C26.7779 10.1846 27.3418 4.90459 23.4972 1.67509ZM22.2669 11.261L13.2961 20.4369C13.1935 20.5394 13.091 20.5394 12.9372 20.4369L3.96642 11.261C2.06973 9.36436 1.7109 5.77604 4.32525 3.57178C6.32446 1.88014 9.40017 2.13645 11.3481 4.0844L13.1423 5.92982L14.9364 4.0844C16.8331 2.13645 19.9088 1.88014 21.908 3.52052C24.5224 5.77604 24.1636 9.36436 22.2669 11.261Z" fill="#00AAAD"/>
                                            </svg>
                                        @endguest
                                        @auth
                                            @if(!empty(\App\Models\Wishlist::where('user_id',auth()->id())->where('seller_product_id',$product->product->id)->first()->id))
                                                <svg width="28" height="24" viewBox="0 0 28 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M24.2611 2C21.2879 -0.511827 16.7256 -0.152994 13.9062 2.76893C11.0355 -0.152994 6.47324 -0.511827 3.50005 2C-0.344584 5.22949 0.219297 10.5095 2.98743 13.3289L11.9583 22.5047C12.4709 23.0173 13.1373 23.3249 13.9062 23.3249C14.6239 23.3249 15.2903 23.0173 15.8029 22.5047L24.825 13.3289C27.5418 10.5095 28.1057 5.22949 24.2611 2Z" fill="#00AAAD"/>
                                                </svg>
                                            @else
                                                <svg width="27" height="23" viewBox="0 0 27 23" fill="none" xmlns="http://www.w3.org/2000/svg" class="add_to_wishlistt">
                                                    <path d="M23.4972 1.67509C20.524 -0.836734 15.9617 -0.477902 13.1423 2.44402C10.2716 -0.477902 5.70932 -0.836734 2.73614 1.67509C-1.1085 4.90459 -0.544619 10.1846 2.22352 13.004L11.1943 22.1798C11.7069 22.6924 12.3734 23 13.1423 23C13.8599 23 14.5263 22.6924 15.039 22.1798L24.061 13.004C26.7779 10.1846 27.3418 4.90459 23.4972 1.67509ZM22.2669 11.261L13.2961 20.4369C13.1935 20.5394 13.091 20.5394 12.9372 20.4369L3.96642 11.261C2.06973 9.36436 1.7109 5.77604 4.32525 3.57178C6.32446 1.88014 9.40017 2.13645 11.3481 4.0844L13.1423 5.92982L14.9364 4.0844C16.8331 2.13645 19.9088 1.88014 21.908 3.52052C24.5224 5.77604 24.1636 9.36436 22.2669 11.261Z" fill="#00AAAD"/>
                                                </svg>



                                            @endif
                                        @endauth

                                    </div>
                                    <div class="about_model sto_ d_flex">
                                        <div class="d_flex sto_ col_titles_mob">
                                            <a class='model_name' href="{{singleProductURL(@$product->product->seller->slug, $product->product->slug, $product->product->product->categories[0]->slug)}}" target="_blank">
                                                @if (@$product->product->product_name)
                                                    {{@$product->product->product_name}}
                                                     @else {{$product->product->product->product_name}}
                                                    @endif
                                            </a>

{{--                                            <span class="data_of_model">{{$product->product->created_at->toDateString()}}</span>--}}
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
                                            <span class="twenty_sp">
                                                @php
                                                    $productFileTypesTxts =\App\Services\ProductService::getProductFileTypes($product->product);
                                                    echo implode(", ", $productFileTypesTxts)
                                                @endphp
                                            </span>
                                            <div class='d_flex sale_price_col'>
                                                @if(($product->product->hasDeal || $product->product->hasDiscount == 'yes') && single_price(@$product->product->skus->first()->selling_price) != '$ 0.00')
                                                    <span class="prev_price">{{$product->product->skus->max('selling_price')}}$</span>
                                                @endif
                                                <span class="price_of_prod">
                                                @if($product->product->hasDeal)

                                                    {{single_price(selling_price(@$product->product->skus->first()->selling_price,$product->product->hasDeal->discount_type,$product->product->hasDeal->discount))}}
                                                @else

                                                    @if($product->product->hasDiscount == 'yes')
                                                            {{(@$product->product->skus->first()->selling_price == 0)? 'Free':single_price(selling_price(@$product->product->skus->first()->selling_price,$product->product->discount_type,$product->product->discount))}}

{{--                                                        @if($product->product->hasDeal || $product->product->hasDiscount == 'yes')--}}
{{--                                                            <span class="prev_price">{{$product->product->skus->max('selling_price')}}$</span>--}}
{{--                                                        @endif--}}
                                                    @else
                                                        {{(@$product->product->skus->first()->selling_price == 0 ) ? 'Free':single_price(@$product->product->skus->first()->selling_price)}}
                                                    @endif

                                                @endif
                                            </span>
                                            </div>
                                        </div>

                                        @if( \App\Services\CartService::isProductPurchased(@$product->product->product()->first()))
                                            @include('product::products.download_product_partial', ['product' => $product->product->product()->first()])
                                        @else
                                            @php
                                                $disabledAddToCartClass = "";
                                                if( \App\Services\CartService::isProductInCart($product->product->skus->first()->id)) {
                                                  $disabledAddToCartClass = "disabled";
                                                }
                                            @endphp
                                            <a  @if(single_price($product->product->skus->max('selling_price')) == '$ 0.00')
                                                    href="{{$product->product->product->video_link}}"
                                                class="add_catalog_btn"
                                                @else @auth class="{{ $disabledAddToCartClass }} addToCartFromThumnail add_catalog_btn"
                                               @elseif(single_price($product->product->skus->max('selling_price')) == '$ 0.00') class="{{ $disabledAddToCartClass }} addToCartFromThumnail add_catalog_btn"
                                               @else class="add_catalog_btn login_btn" @endauth
                                                @endif
                                                data-producttype="{{ @$product->product->product->product_type }}" data-seller={{ @$product->product->user_id }} data-product-sku={{ @$product->product->skus->first()->id }}
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
                                                    <svg width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <g clip-path="url(#clip0_794_15016)">
                                                            <path d="M16.5 9H13.5938L15.0625 7.5625C16 6.625 15.3125 5 14 5H12V1.5C12 0.6875 11.3125 0 10.5 0H7.5C6.65625 0 6 0.6875 6 1.5V5H4C2.65625 5 1.96875 6.625 2.9375 7.5625L4.375 9H1.5C0.65625 9 0 9.6875 0 10.5V14.5C0 15.3438 0.65625 16 1.5 16H16.5C17.3125 16 18 15.3438 18 14.5V10.5C18 9.6875 17.3125 9 16.5 9ZM4 6.5H7.5V1.5H10.5V6.5H14L9 11.5L4 6.5ZM16.5 14.5H1.5V10.5H5.875L7.9375 12.5625C8.5 13.1562 9.46875 13.1562 10.0312 12.5625L12.0938 10.5H16.5V14.5ZM13.75 12.5C13.75 12.9375 14.0625 13.25 14.5 13.25C14.9062 13.25 15.25 12.9375 15.25 12.5C15.25 12.0938 14.9062 11.75 14.5 11.75C14.0625 11.75 13.75 12.0938 13.75 12.5Z" fill="white"/>
                                                        </g>
                                                        <defs>
                                                            <clipPath id="clip0_794_15016">
                                                                <rect width="18" height="16" fill="white"/>
                                                            </clipPath>
                                                        </defs>
                                                    </svg>

                                                @else
                                                    +
                                                    <svg width="21" height="18" viewBox="0 0 21 18" fill="none" xmlns="http://www.w3.org/2000/svg">
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


                    @if($products->lastPage() > 1)
                            <div class="d_flex pagination">
                                <div class='choose_pagination_quantity d_flex'> <span> Per page: </span>
                                    <a href="{{route('frontend.my-wishlist',['paginate_id'=>12])}}">12</a>
                                    <a href="{{route('frontend.my-wishlist',['paginate_id'=>24])}}">24</a>
                                    <a href="{{route('frontend.my-wishlist',['paginate_id'=>36])}}">36</a>
                                    <a href="{{route('frontend.my-wishlist',['paginate_id'=>72])}}">72</a>
                                    <a href="{{route('frontend.my-wishlist',['paginate_id'=>144])}}">144</a>
                                </div>

                                <div class="d_flex pagination_block">
                                    <a href="{{ $products->previousPageUrl() }}" class="prev_next_page">
                                        <svg width="9" height="15" viewBox="0 0 9 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M8 1.25L2 7.25L8 13.25" stroke="#282828" stroke-width="2.25"/>
                                        </svg>
                                    </a>
                                    @if($products->lastPage() > 1)
                                        <a href="{{ $products->url(1) }}" class="pagination_sp @if($products->currentPage() == 1) pagination_sp_active @endif">1</a>
                                    @endif
                                    @if($products->lastPage() > 2)
                                        <a href="{{ $products->url(2) }}" class="pagination_sp @if($products->currentPage() == 2) pagination_sp_active @endif">2</a>
                                    @endif
                                    @if($products->lastPage() > 3)
                                        <a href="{{ $products->url(3) }}" class="pagination_sp @if($products->currentPage() == 3) pagination_sp_active @endif">3</a>
                                    @endif
                                    @if( $products->lastPage() > 4)
                                        <a>...</a>
                                    @endif
                                    <a href="{{ $products->url($products->lastPage()) }}" class="pagination_sp  @if($products->currentPage() == $products->lastPage()) pagination_sp_active @endif">{{ $products->lastPage() }}</a>
                                    <a href="{{ $products->nextPageUrl()  }}" class="prev_next_page">
                                        <svg width="9" height="15" viewBox="0 0 9 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M1.25 1.25L7.25 7.25L1.25 13.25" stroke="#282828" stroke-width="2.25"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        </main>
@endsection
