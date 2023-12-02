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

        $cart = new \App\Models\Cart();
    $cartService = new \App\Repositories\CartRepository($cart);
    $Data = $cartService->getCartData();
            $cartDat = $Data['cartData'];

        foreach ($cartDat as $key => $items) {
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
                    Payment
                @endsection
                @include('frontend.default.partials._breadcrumb')
                <div class="d_flex cart_checkout payment_  sto_">
                    <div class="d_flex payment_section">
                        <h1>Payment</h1>
                        @auth
                        <div class="d_flex sto_ billing_adress">
                            <span class="bill_addr_sp">Billing adress</span>
                            <span class="add_addr" data-billing="0" @if(!auth()->user()->customerAddresses->where('is_billing_default',1)->first()) style="display: none"  @endif>Add new</span>
                        </div>
                        <div class="add_edit_address" @if(auth()->user()->customerAddresses->where('is_billing_default',1)->first())@else style="display: flex" @endif>
                            <input type="file" style="display: none" id="file">
                            <div class="d_flex sto_ inp_cols">
                                <div class="d_flex inps_labs">
                                    <label for="name">{{__('common.first_name')}}</label>
                                    <input autocomplete="off" type="text" id="name"  placeholder="{{__('common.first_name')}}" value="" name="first_name">
                                </div>
                                <div class="d_flex inps_labs">
                                    <label for="lastname">{{__('common.last_name')}}</label>
                                    <input autocomplete="off" type="text" id="lastname"  placeholder="{{__('common.last_name')}}" value="" name="last_name">
                                </div>
                            </div>
                            <div class="d_flex sto_ inp_cols">
                                <div class="d_flex inps_labs">
                                    <label for="country">Country</label>
                                    <input type="text" autocomplete="off" id="country" name="country" value="">
                                </div>
                                <div class="d_flex inps_labs">
                                    <label for="email">Email</label>
                                    <input type="email" autocomplete="off" id="email" name="email" placeholder="{{__('common.email_address')}}" value="">
                                </div>
                            </div>
                            <div class="d_flex sto_ inp_cols">
                                <button class="save_edits_btn d_flex" id="payment_btn_triggerr" type="submit">Save</button>
                            </div>
                        </div>

                        <div class="d_flex sto_ approved_addrs">
                            <div class="approved_data d_flex">
                                @php
                                    $addres = \Modules\Customer\Entities\CustomerAddress::where('customer_id', auth()->id())->where('is_billing_default', 1)->first()
                                @endphp
                                @if($addres)
                                <span>{{$addres->name}}</span>
                                <span>{{$addres->email}}</span>
                                <span>{{$addres->state}}</span>
                                <span>{{$addres->country}}</span>
                                @endif
                            </div>

                            @if($ag = auth()->user()->customerAddresses->where('is_billing_default',1)->first())
                                <span class="edit_addr" data-ag="{{$ag->id}}">Edit</span>
                            @endif
                        </div>
                        @else
                            @php
                            $addres = session()->get('shipping_address')?(object) session()->get('shipping_address'):null;
                            @endphp
                            <div class="add_edit_address"  style="display: flex">
                                <input type="file" autocomplete="off" style="display: none" id="file">
                                <div class="d_flex sto_ inp_cols">
                                    <div class="d_flex inps_labs">
                                        <label for="name">{{__('common.first_name')}}</label>
                                        <input autocomplete="off" type="text" id="name"  placeholder="{{__('common.first_name')}}" value="{{$addres?$addres->name:''}}" name="first_name">
                                    </div>
                                    <div class="d_flex inps_labs">
                                        <label for="lastname">{{__('common.last_name')}}</label>
                                        <input autocomplete="off" type="text" id="lastname"  placeholder="{{__('common.last_name')}}" value="{{$addres?$addres->lastname:''}}" name="last_name">
                                    </div>
                                </div>
                                <div class="d_flex sto_ inp_cols">
                                    <div class="d_flex inps_labs">
                                        <label for="country">Country</label>
                                        <input type="text" autocomplete="off" id="country" name="country" value="{{$addres?$addres->country:''}}">
                                    </div>
                                    <div class="d_flex inps_labs">
                                        <label for="email">Email</label>
                                        <input type="email" autocomplete="off" id="email" name="email" placeholder="{{__('common.email_address')}}" value="{{$addres?$addres->email:''}}">
                                    </div>
                                </div>
                                <div class="d_flex sto_ inp_cols">
                                    <button class="save_edits_btn d_flex" id="payment_btn_triggerr" type="submit">go</button>
                                </div>
                            </div>
                        @endauth

                        <div class="shop_carts_block d_flex">
                            <div class="d_flex sto_">
                                <span class="payment_shop">Shopping Cart</span>
                                <a href="{{route('frontend.cart')}}" class="goto_shop">Edit</a>
                            </div>
@php
$i = 0
@endphp
                            @foreach($cartData as $admin_id => $cart)

                            @php
                            $i++;
                            $ids[] = [];
                            $ids[$i] = $cart->product->product->id;
                            @endphp

                                    @if($cart->is_select)
                                        @php
                                            $subtotal += $cart->product->selling_price * $cart->qty;
                                        @endphp
                                    @endif
                                    <div class="d_flex shop_cart_prod" >
                                        <a href="{{singleProductURL(@$cart->product->seller->slug, @$cart->product->product->product->slug, @$cart->product->product->product->categories[0]->slug)}}" class="d_flex img_name_prod">
                                            <div class="prod_img">
                                                <img src="@if(@$cart->product->product->product->product_type == 1)
                                                {{asset(asset_path(@$cart->product->product->product->thumbnail_image_source))}}
                                                @else
                                                {{asset(asset_path(@$cart->product->sku->variant_image?@$cart->product->sku->variant_image:@$cart->product->product->product->thumbnail_image_source))}}
                                                @endif" alt="">
                                            </div>
                                            <span class="shops_name">{{$cart->product->product->product_name}}</span>
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
                                                            <span class="sale_for">-{{$cart->product->product->discount}}$</span>

{{--                                                            <span class="sale_for">-{{single_price($cart->product->product->hasDeal->discount)}}</span>--}}
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
{{--                                                            <span class="sale_for">-{{single_price($cart->product->product->discount)}}</span>--}}
                                                            <span class="sale_for">-{{$cart->product->product->discount}}$</span>

                                                            <span class="prev_price">{{single_price($cart->product->selling_price)}}</span>
                                                        @endif
                                                    @else
                                                        <span class="this_moment_price">{{single_price($cart->product->selling_price)}}</span>
                                                    @endif

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
                                        </div>
                                    </div>
                                @if($cart->is_select == 1)
                                    @php
                                        $actual_price += $cart->total_price;
                                    @endphp
                                @endif
                                @endforeach
{{--                            @endforeach--}}

                        </div>
                        <div class="payment_option d_flex">
                            <span class="choose_option">Choose the payment option</span>
                            <div class="d_flex pay_online_">

                                <div class="payment_option_block">
                                    <div class="d_flex payment_fl_online">
                                        <img src="{{asset('public/new/img/misamaster.jpg')}}" alt="">
                                    </div>
                                    <span class="pay_sp">Card</span>
                                    <div class="chosen_area">
                                        <div class="chosen_svg">
                                            <svg width="12" height="9" viewBox="0 0 12 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M1.41992 3.14844L4.89746 6.62634L10.7468 0.776978" stroke="white" stroke-width="2"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    @php
                    $ids = json_encode($ids);
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
                            <span class="total_title">Total</span>
                            <div class="d_flex subtotal_sale sto_">
                                <div class="d_flex sto_">
                                    <span class="sub_sale">{{ __('common.subtotal') }}:</span>
                                    <span class="price_of">{{ $subtotal }}$</span>
                                </div>
                            </div>
                            <div class="d_flex sto_ total_padd">
                                <span class="sub_sale">Total:</span>
                                <span class="price_total">{{$total}}$</span>
                            </div>
                            <div class="total_padd sto_ goto_check_btnn" id="payment_btn_trigger" data-total="{{$total}}" data-ids="{{$ids}}" >
                                <a   class="goto_check_btn sto_ d_flex" >Go to checkout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

    </main>
@endsection
@section('js')
    <script>
        $('.goto_check_btnn').click(function (){
            @auth
            @if(auth()->user()->customerAddresses->where('is_billing_default',1)->first())
            //payment

                let data = {'amount': $(this).attr('data-total')};

                if($(this).attr('data-total') > 0){
                    $.ajax({
                        url: '{{route('arca.step1')}}',
                        type: 'post',
                        data: data,
                        dataType:'JSON',
                        success: function (response) {
                            console.log(response)
                            $('.price_total').append(response.form);
                            $('.price_total').find('#arca_form').submit();
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            console.log(XMLHttpRequest)
                        }
                    });
                }


            else {
                    let data = {
                        'step':'select_payment',
                        'shipping_method' : 1,
                        'payment_id' : 1,
                        'gateway_id' : 1,
                    }
                    $.ajax({
                        url: '{{route('frontend.checkout')}}',
                        type: "GET",
                        data,
                        success: function (data) {
                            $.post('/parol/reset1').then(function (){
                                window.location.href = '{{route('frontend.customer_profile',['a' => 'purchases'])}}'
                            })

                        },
                        error: function (res){
                            console.log(res.responseJSON.errors)
                        }
                    });
                }



            @else
            if(!$('#name').val()){
                $('#name').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
            }
            if(!$('#lastname').val()){
                $('#lastname').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
            }
            if(!$('#street').val()){
                $('#street').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
            }
            if(!$('#email').val()){
                $('#email').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
            }
            if(!$('#phone').val()){
                $('#phone').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
            }
            if(!$('#country').val()){
                $('#country').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
            }
            if(!$('#state').val()){
                $('#state').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
            }
            if(!$('#city').val()){
                $('#city').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
            }
            if(!$('#zip_code').val()){
                $('#zip_code').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
            }
            @endif
            @else

            @php
                $checkoutRepo = new \App\Repositories\CheckoutRepository();
                $chek = $checkoutRepo->activeShippingAddress();
            @endphp

            @if($chek)
            let data = {
                'step':'select_payment',
                'shipping_method' : 1,
                'payment_id' : 1,
                'gateway_id' : 1,
            }
            $.ajax({
                url: '{{route('frontend.checkout')}}',
                type: "GET",
                data,
                success: function (data) {
                    console.log(data)
                    $.post('/parol/reset1')
                        .then(function (res){
                        window.location.href = '/'
                    })
                    // $('#dataWithPaginate').html(data);
                    // $('#product_short_list').niceSelect();
                    // $('#paginate_by').niceSelect();
                    // $('#pre-loader').hide();
                },
                error: function (res){
                    console.log(res.responseJSON.errors)
                }
            });
            @else
                console.log($('#name').val())
        if(!$('#name').val()){
                $('#name').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
            }
            if(!$('#lastname').val()){
                $('#lastname').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
            }
            if(!$('#street').val()){
                $('#street').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
            }
            if(!$('#email').val()){
                $('#email').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
            }
            if(!$('#phone').val()){
                $('#phone').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
            }
            if(!$('#country').val()){
                $('#country').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
            }
            if(!$('#state').val()){
                $('#state').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
            }
            if(!$('#city').val()){
                $('#city').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
            }
            if(!$('#zip_code').val()){
                $('#zip_code').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
            }
            @endif
            @endauth
        })

    </script>

@endsection
