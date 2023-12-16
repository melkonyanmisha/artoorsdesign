@extends('frontend.default.layouts.newApp')

@section('content')
    <main>
        @include('frontend.default.includes.mainInclude')
        @php
            $carts = collect();
        $compares = 0;
        $wishlists = 0;
        if(auth()->check()){
            $carts = \App\Models\Cart::with('product.product.product','giftCard','product.product_variations.attribute', 'product.product_variations.attribute_value.color')->where('user_id',auth()->user()->id)->where('product_type', 'product')->whereHas('product',function($query){
                return $query->where('status', 1)->whereHas('product', function($q){
                    return $q->activeSeller();
                });
            })->orWhere('product_type', 'gift_card')->where('user_id',auth()->user()->id)->whereHas('giftCard', function($query){
                return $query->where('status', 1);
            })->get();
            $compares = count(\App\Models\Compare::with('sellerProductSKU.product')->where('customer_id', auth()->user()->id)->whereHas('sellerProductSKU', function($query){
                return $query->where('status',1)->whereHas('product', function($q){
                    return $q->activeSeller();
                });
            })->pluck('id'));
            $wishlists = count(\App\Models\Wishlist::where('user_id', auth()->user()->id)->pluck('id'));
        }else {
            $carts = \App\Models\Cart::with('product.product.product','giftCard','product.product_variations.attribute', 'product.product_variations.attribute_value.color')->where('session_id',session()->getId())->where('product_type', 'product')->whereHas('product',function($query){
                return $query->where('status', 1)->whereHas('product', function($q){
                    return $q->activeSeller();
                });
            })->orWhere('product_type', 'gift_card')->where('session_id', session()->getId())->whereHas('giftCard', function($query){
                return $query->where('status', 1);
            })->get();

            if(\Session::has('compare')){
                $dataList = Session::get('compare');
                $collcets =  collect($dataList);

                $collcets =  $collcets->sortByDesc('product_type');
                $products = [];
                foreach($collcets as $collcet){
                    $product = \Modules\Seller\Entities\SellerProductSKU::with('product')->where('id',$collcet['product_sku_id'])->whereHas('product', function($query){
                        return $query->activeSeller();
                    })->pluck('id');
                    if($product){
                        $products[] = $product;
                    }
                }
                $compares = count($products);
            }

        }
        $all_select_count = 0;
        $subtotal = 0;
        $discount = 0;
        $actual_price = 0;
        $shipping_cost = 0;
        $sellect_seller = 0;
        $selected_product_check  = 0;

        foreach ($cartData as $key => $items) {
            $all_select_count += count($items);
            $sellect_seller  = $key;
            $p = 0;
            foreach ($items as $key => $data) {
                if ($data->is_select == 1) {
                    $all_select_count = $all_select_count - 1;
                    $selected_product_check ++;
                    $p = 1;
                }
            }
            if($p == 1){
                $shipping_cost += 20;

            }
        }

        $items = 0;
        foreach($carts as $cart){
            if(auth()->check()){
                $items += $cart->qty;
            }else{
                $items += $cart['qty'];
            }
        }
        @endphp
        <section class="wrapper">
            @section('breadcrumb')
                Shopping Cart
            @endsection
            @include('frontend.default.partials._breadcrumb')
{{--            <div class="d_flex from_to">--}}
{{--                <a class="from_this" href="{{url('/')}}">Home</a>--}}
{{--                <span class="slashes">/</span>--}}
{{--                <span class="this_page">Shopping Cart</span>--}}
{{--            </div>--}}
            <div class="d_flex shopping_section">
                <div class="d_flex cart_checkout sto_">
                    <div class="shop_carts_block d_flex">
                        <div class="title_num d_flex">
                            <h1>Shopping Cart</h1>
                            <span class="how_much_shopp d_flex">{{$items}}</span>
                        </div>
                            @foreach($cartData as $admin_id => $items)
                                @foreach($items as $key => $cart)
                                        @if($cart['is_select'] == 1)
                                            @php
                                                $subtotal += $cart->product->selling_price * $cart->qty;
                                            @endphp
                                        @endif
                                    <div class="d_flex shop_cart_prod">
                                <a href="{{singleProductURL(@$cart->seller->slug, @$cart->product->product->slug, @$cart->product->product->product->categories[0]->slug)}}" class="d_flex img_name_prod">
                                    <div class="prod_img">
                                        <img src="@if(@$cart->product->product->product->product_type == 1)
                                        {{asset(asset_path(@$cart->product->product->product->thumbnail_image_source))}}
                                        @else
                                        {{asset(asset_path(@$cart->product->sku->variant_image?@$cart->product->sku->variant_image:@$cart->product->product->product->thumbnail_image_source))}}
                                        @endif" alt="">
                                    </div>
                                    <span class="shops_name">{{ $cart->product->product->product_name}}</span>
                                </a>

                                <div class="d_flex sales_remove">
                                    <div class="d_flex price_prod">
                                        @if($cart->product->product->hasDeal)
                                            @if($cart->product->product->hasDeal->discount > 0)
                                                @if($cart->product->product->hasDeal->discount_type == 0)
                                                    <span class="this_moment_price">{{single_price($cart->total_price)}}</span>
                                                    <span class="sale_for">-{{$cart->product->product->hasDeal->discount}}%</span>
                                                    <span class="prev_price">{{single_price($cart->product->selling_price)}}</span>
                                                @else
                                                    <span class="this_moment_price">{{single_price($cart->total_price)}}</span>
                                                    <span class="sale_for">-{{single_price($cart->product->product->hasDeal->discount)}}</span>
{{--                                                    <span class="sale_for">-{{$cart->product->product->discount/$cart->product->selling_price*100}}%</span>--}}
                                                    <span class="prev_price">{{single_price($cart->product->selling_price)}}</span>
                                                @endif
                                            @else
                                                <span class="this_moment_price">{{single_price($cart->product->selling_price)}}</span>
                                            @endif
                                        @else
                                            @if(@$cart->product->product->hasDiscount == 'yes')
                                                @if($cart->product->product->discount_type == 0)
                                                    <span class="this_moment_price">{{single_price($cart->total_price)}}</span>
                                                    <span class="sale_for">-{{$cart->product->product->discount}}%</span>
                                                    <span class="prev_price">{{single_price($cart->product->selling_price)}}</span>
                                                @else
                                                    <span class="this_moment_price">{{single_price($cart->total_price)}}</span>
                                                    <span class="sale_for">-{{single_price($cart->product->product->discount)}}</span>
{{--                                                    <span class="sale_for">-{{$cart->product->product->discount/$cart->product->selling_price*100}}%</span>--}}
                                                    <span class="prev_price">{{single_price($cart->product->selling_price)}}</span>
                                                @endif
                                            @else
                                                <span class="this_moment_price">{{single_price($cart->product->selling_price)}}</span>
                                            @endif

                                        @endif

                                        @if($cart->product->product->tax)
                                            <span class="tax">
                                                {{sprintf('+ %1$s %2$s%%', strtoupper(__('product.tax')),  $cart->product->product->tax)}}
                                            </span>
                                        @endif

                                    </div>
                                    <div class=" remove_shop_cart delete_catal cart_item_delete_btn" data-id="{{$cart->id}}"
                                         data-product_id="{{$cart->product_id}}"
                                         data-unique_id="#delete_item_{{$cart->id}}" id="delete_item_{{$cart->id}}">
                                        <svg width="17" height="17" viewBox="0 0 17 17" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path d="M1 1L16 16M16 1L1 16" stroke="#717171"/>
                                        </svg>
                                    </div>
{{--                                    <div class="remove_shop_cart">--}}
{{--                                        <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">--}}
{{--                                            <path d="M1 1L16 16M16 1L1 16" stroke="#717171"/>--}}
{{--                                        </svg>--}}
{{--                                    </div>--}}
                                </div>
                            </div>
                                @if($cart->is_select == 1)
                                    @php
                                        $actual_price += $cart->total_price;
                                    @endphp
                                @endif
                                @endforeach
                            @endforeach


                    </div>
                    @php
                        $total = 0;
                        $subtotal = 0;
                        $additional_shipping = 0;
                        $tax = 0;
                        $sameStateTaxes = \Modules\GST\Entities\GstTax::whereIn('id', app('gst_config')['within_a_single_state'])->get();
                        $diffStateTaxes = \Modules\GST\Entities\GstTax::whereIn('id', app('gst_config')['between_two_different_states_or_a_state_and_a_Union_Territory'])->get();
                        $flatTax = \Modules\GST\Entities\GstTax::where('id', app('gst_config')['flat_tax_id'])->first();
                    @endphp
                    @foreach ($carts as $key => $cart)
                        @php
                            $subtotal += $cart->price * $cart->qty;
                        @endphp
                        @if (file_exists(base_path().'/Modules/GST/') && $cart->product->product->product->is_physical == 1)
                            @if (isset($address) && app('gst_config')['enable_gst'] == "gst")
                                @if (\app\Traits\PickupLocation::pickupPointAddress(1)->state_id == $address->state)
                                    @if($cart->product->product->product->gstGroup)
                                        @php
                                            $sameStateTaxesGroup = json_decode($cart->product->product->product->gstGroup->same_state_gst);
                                            $sameStateTaxesGroup = (array) $sameStateTaxesGroup;
                                        @endphp
                                        @foreach ($sameStateTaxesGroup as $key => $sameStateTax)
                                            @php
                                                $gstAmount = $cart->total_price * $sameStateTax / 100;
                                                $tax += $gstAmount;
                                            @endphp
                                        @endforeach
                                    @else
                                        @foreach ($sameStateTaxes as $key => $sameStateTax)
                                            @php
                                                $gstAmount = $cart->total_price * $sameStateTax->tax_percentage / 100;
                                                $tax += $gstAmount;
                                            @endphp
                                        @endforeach
                                    @endif
                                @else
                                    @if($cart->product->product->product->gstGroup)
                                        @php
                                            $diffStateTaxesGroup = json_decode($cart->product->product->product->gstGroup->outsite_state_gst);
                                            $diffStateTaxesGroup = (array) $diffStateTaxesGroup;
                                        @endphp
                                        @foreach ($diffStateTaxesGroup as $key => $diffStateTax)
                                            @php
                                                $gstAmount = $cart->total_price * $diffStateTax / 100;
                                                $tax += $gstAmount;
                                            @endphp
                                        @endforeach
                                    @else
                                        @foreach ($diffStateTaxes as $key => $diffStateTax)
                                            @php
                                                $gstAmount = $cart->total_price * $diffStateTax->tax_percentage / 100;
                                                $tax += $gstAmount;
                                            @endphp
                                        @endforeach
                                    @endif
                                @endif

                            @else
                                @if($cart->product->product->product->gstGroup)
                                    @php
                                        $flatTaxGroup = json_decode($cart->product->product->product->gstGroup->same_state_gst);
                                        $flatTaxGroup = (array) $flatTaxGroup;
                                    @endphp
                                    @foreach($flatTaxGroup as $sameStateTax)
                                        @php
                                            $gstAmount = $cart->total_price * $sameStateTax / 100;
                                            $tax += $gstAmount;
                                        @endphp
                                    @endforeach
                                @else
                                    @php
                                        $gstAmount = $cart->total_price * $flatTax->tax_percentage / 100;
                                        $tax += $gstAmount;
                                    @endphp
                                @endif

                            @endif

                        @else

                            @if($cart->product->product->product->gstGroup)
                                @php
                                    $sameStateTaxesGroup = json_decode($cart->product->product->product->gstGroup->same_state_gst);
                                    $sameStateTaxesGroup = (array) $sameStateTaxesGroup;
                                @endphp
                                @foreach ($sameStateTaxesGroup as $key => $sameStateTax)
                                    @php
                                        $gstAmount = ($cart->total_price * $sameStateTax) / 100;
                                        $tax += $gstAmount;
                                    @endphp
                                @endforeach
                            @else
                                @foreach ($sameStateTaxes as $key => $sameStateTax)
                                    @php
                                        $gstAmount = ($cart->total_price * $sameStateTax->tax_percentage) / 100;
                                        $tax += $gstAmount;
                                    @endphp
                                @endforeach
                            @endif

                        @endif
                    @endforeach
                    @php
                        $total = $subtotal + $tax ;
                    @endphp
                    @if(isModuleActive('MultiVendor'))
                        @php
                            $total = $total_amount;
                        @endphp
                    @endif
                    @php
                        $coupon = 0;
                        $coupon_id = null;
                        $total_for_coupon = $actual_price;
                    @endphp
                    @if(count($cartData) > 0)

                    @else
                        @php
                            $subtotal = 0;
                        @endphp
                    @endif
                    @php
                        $grand_total = $actual_price;
                        $discount = $subtotal - $actual_price;

                    @endphp
                    @auth
                        @php
                            if(\Session::has('coupon_type')&&\Session::has('coupon_discount')){
                                $coupon_type = \Session::get('coupon_type');
                                $coupon_discount = \Session::get('coupon_discount');
                                $coupon_discount_type = \Session::get('coupon_discount_type');
                                $coupon_id = \Session::get('coupon_id');

                                if($coupon_type == 1){
                                    $couponProducts = \Session::get('coupon_products');
                                    if($coupon_discount_type == 0){

                                        foreach($couponProducts as  $key => $item){
                                            $cart = \App\Models\Cart::where('user_id',auth()->user()->id)->where('is_select',1)->where('product_type', 'product')->whereHas('product',function($query) use($item){
                                                $query->whereHas('product', function($q) use($item){
                                                    $q->where('id', $item);
                                                });
                                            })->first();
                                            $coupon += ($cart->total_price/100)* $coupon_discount;
                                        }
                                    }else{
                                        if($total_for_coupon > $coupon_discount){
                                            $coupon = $coupon_discount;
                                        }else {
                                            $coupon = $total_for_coupon;
                                        }
                                    }

                                }
                                elseif($coupon_type == 2){

                                    if($coupon_discount_type == 0){

                                        $maximum_discount = \Session::get('maximum_discount');
                                        $coupon = ($total_for_coupon/100)* $coupon_discount;

                                        if($coupon > $maximum_discount && $maximum_discount > 0){
                                            $coupon = $maximum_discount;
                                        }
                                    }else{
                                        $coupon = $coupon_discount;
                                    }
                                }
                                elseif($coupon_type == 3){
                                    $maximum_discount = \Session::get('maximum_discount');
                                    $coupon = $shippingtotal;

                                    if($coupon > $maximum_discount && $maximum_discount > 0){
                                        $coupon = $maximum_discount;
                                    }

                                }

                            }
                            if ($coupon > 0 ){
                            $discount += $coupon;
                            $total = $grand_total-$coupon;
                        }
                        @endphp
                    @endauth
                    <div class="goto_checkout_block d_flex">
                        <div class="total_check d_flex sto_">
                            <div class="d_flex sto_ total_padd">
                                <span class="sub_sale">Total:</span>
                                <span class="price_total">{{single_price($total)}}</span>
                            </div>
                            <div class="total_padd sto_">
                                <a href="{{route('frontend.checkout')}}" class="goto_check_btn sto_ d_flex">{{__('defaultTheme.proceed_to_checkout')}}</a>
                            </div>
                        </div>
                        @if($subtotal > 0)
                        <div  class="d_flex promocode_form sto_">
                            <input class="promo_code coupon_code" type="text" placeholder="Promocode" data-total="{{$total}}">
                            <button class="apply_promo d_flex coupon_apply_btn" onclick="couponApply1('{{$total}}')" data-total="{{$total}}">Apply</button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
