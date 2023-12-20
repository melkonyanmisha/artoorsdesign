@php

    $adminColor = Modules\Appearance\Entities\AdminColor::where('is_active',1)->first();

    $popupContent = \Modules\FrontendCMS\Entities\SubscribeContent::findOrFail(2);

    $modal = false;
    if(Session::get('ip') == NULL){
    Session::put('ip',request()->ip());
    $modal = true;
    }
    $langs = app('langs');
    $currencies = app('currencies');

    $locale = app('general_setting')->language_code;
    $currency_code = app('general_setting')->currency_code;
    $ship = app('general_setting')->country_name;
    if(\Session::has('locale')){
        $locale = \Session::get('locale');
    }
    if(\Session::has('currency')){
        $currency = \Modules\GeneralSetting\Entities\Currency::where('id', session()->get('currency'))->first();
        $currency_code = $currency->code;
    }

    if(auth()->check()){
        $currency_code = auth()->user()->currency_code;
        $locale = auth()->user()->lang_code;
    }

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

    $regular_menus = Modules\Menu\Entities\Menu::with('elements.page','elements.childs','elements.childs.page')->where('menu_type', 'normal_menu')->where('menu_position','top_navbar')->whereIn('id',[1,2])->orderBy('id')->where('status', 1)->get();
    $topnavbar_right_menu = $regular_menus[1];
    $topnavbar_left_menu = $regular_menus[0];

    $top_bar = Modules\FrontendCMS\Entities\HomePageSection::where('section_name','top_bar')->first();
//--------------------------------------------------------------------------------------------
    $notifications =
Modules\OrderManage\Entities\CustomerNotification::where('customer_id',Auth::id())->where('read_status',0)->latest()->take(4)->get();

    //carts
    $cart = new \App\Models\Cart();
    $cartService = new \App\Repositories\CartRepository($cart);
    $Data = $cartService->getCartData();
            $cartData = $Data['cartData'];

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

@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-WD9MKPT');</script>
    <!-- End Google Tag Manager -->

    <meta name="facebook-domain-verification" content="a8c0xdcq1t1lk9lfjje9td2fly7pkk" />


    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{asset(asset_path(app('general_setting')->favicon))}}" type="image/png" />
    <link rel="canonical" href="{{request()->url()}}" />
    <title>@if(trim($__env->yieldContent('title'))) @yield('title') | {{app('general_setting')->site_title}} @else {{app('general_setting')->site_title}} @endif</title>
    @section('share_meta')
    @show
    @laravelPWA

    <meta name="csrf-token" content="{{csrf_token()}}">
    <link rel="stylesheet" href="{{asset(asset_path('new/css/style.css'))}}">
    <link rel="stylesheet" href="{{asset(asset_path('new/css/media.css'))}}">
{{--    <link rel="stylesheet" href="/new/css/style.css">--}}
{{--    <link rel="stylesheet" href="/new/css/media.css">--}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.css">
    <style>
        :root {
            --padd:  @if(Modules\Appearance\Entities\Header::find(1)->ka == 'true') 260px @else 188px @endif;
        }
    </style>
    @yield('styles')
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

{{--    <div id="fb-root"></div>--}}

    <script>(function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>

    <!-- Your share button code -->

    <script>
        $.ajaxSetup({
            headers:
                { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        function fill_message_block(id){
            $.post( "{{ route('find_user') }}",{ id }, function( data ) {
                $( ".message_block_" ).html( data );
            });
        }

        function aabb(id){
            $.post( "{{ route('aabb') }}",{ id }, function( data ) {
                $( "#sms_area" ).html( data );
            });
        }

    </script>
    <script>
        function sendMessage(fromUserId, toUserId) {
            const messege_text = $('.messege_text');

            let message = $(messege_text).val().trim();
            $(messege_text).val('')

            if (!message) {
                return false;
            }

            const urlParams = new URLSearchParams(window.location.search);
            const insteadOfAdminValue = parseInt(urlParams.get("instead_of_admin"));
            const data = new FormData();
            const files = $('#file')[0].files[0] ?? null;

            data.append('toUserId', toUserId);
            data.append('message', message);

            // The case when superadmin sends a message instead of Admin
            if (insteadOfAdminValue) {
                data.append('insteadOfAdmin', insteadOfAdminValue);
                data.append('fromUserId', fromUserId);
            }

            if (files) {
                data.append('file', files);
            }

            $.ajax({
                url: "{{ route('message.send') }}",
                type: 'post',
                data: data,
                contentType: false,
                processData: false,
                success: function (response) {
                    $("#sms_area").html(response);
                    $(".messege_text").val('');
                    $('.file_svg').html(`
                    <input type="file" id="file">
                                <svg width="16" height="18" viewBox="0 0 16 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2.08691 15.6562C3.89941 17.4688 6.83691 17.4688 8.64941 15.6562C10.7119 13.5938 12.7119 11.5938 14.7744 9.53125C14.9307 9.375 14.9307 9.15625 14.7744 9L14.2432 8.46875C14.0869 8.3125 13.8682 8.3125 13.7119 8.46875C11.6494 10.5312 9.64941 12.5312 7.58691 14.5938C6.36816 15.8125 4.36816 15.8125 3.14941 14.5938C1.93066 13.375 1.93066 11.375 3.14941 10.1562L10.1807 3.125C10.9932 2.3125 12.3057 2.3125 13.1182 3.125C13.9307 3.9375 13.9307 5.25 13.1182 6.0625L7.21191 11.9688C6.80566 12.375 6.14941 12.375 5.74316 11.9688C5.36816 11.5938 5.36816 10.9375 5.74316 10.5312L10.7744 5.53125C10.9307 5.375 10.9307 5.15625 10.7744 5L10.2432 4.46875C10.0869 4.3125 9.86816 4.3125 9.71191 4.46875L4.71191 9.46875C3.71191 10.4688 3.71191 12.0625 4.71191 13.0312C5.68066 14.0312 7.27441 14.0312 8.24316 13.0312L14.1807 7.125C15.5869 5.71875 15.5869 3.46875 14.1807 2.0625C12.7744 0.65625 10.5244 0.65625 9.11816 2.0625L2.08691 9.09375C0.274414 10.9062 0.274414 13.8438 2.08691 15.6562Z" fill="#717171"/>
                                </svg>
                    `)
                },
            })
        }
    </script>
    <script>

        var pusher = new Pusher('3292ff4512a604d12624', {
            cluster: 'eu'
        });

        var channel = pusher.subscribe('user.{{ auth()->id() }}');
        channel.bind('server', function(data) {
            aabb(data.user)
        });

    </script>

        <!-- Meta Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '544924167631452');
    fbq('track', 'PageView');
    fbq('track', 'ViewContent');
    fbq('track', 'Purchase');
    fbq('track', 'AddToCart');
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id=544924167631452&ev=PageView&noscript=1"
    /></noscript>
    <!-- End Meta Pixel Code -->

</head>
<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WD9MKPT"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<section class="container">
{{--    <script>--}}
{{--        console.log(JSON.token (ice_drive_cookie))--}}
{{--    </script>--}}
    <header>
        @if(Modules\Appearance\Entities\Header::find(1)->ka == 'true')
        <div class='header_first_blue'>
            @php
            $area = \Modules\Appearance\Entities\Header::find(1);
            @endphp
                {!! html_entity_decode($area->text) !!}
                <p> -{{$area->tokos }}%</p>
                <a href="{{$area->link}}" class='shop_now_btn_head'>Shop Now</a>
            @if(Modules\Appearance\Entities\Header::find(1)->time != '0000-00-00 00:00:00')
                  <span class="time" id="demo"></span>


                <script>
                    {{--var countDownDate = new Date(localStorage.getItem('time')??"{{$area->time}}").getTime();--}}
                    var countDownDate = new Date("{{$area->time}}").getTime();



                    var x = setInterval(function() {

                        // Get today's date and time
                        var now = new Date().getTime();

                        // Find the distance between now and the count down date
                        var distance = countDownDate - now;

                        // Time calculations for days, hours, minutes and seconds
                        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        var seconds = Math.floor((distance % (1000 * 60)) / 1000);


                        // Display the result in the element with id="demo"
                        document.getElementById("demo").innerHTML = days + "d " + hours + "h "
                            + minutes + "m " + seconds + "s ";

                        localStorage.setItem('time',distance)

                        // If the count down is finished, write some text
                        if(hours%10 === 0){

                        }
                        if (distance < 0) {
                            clearInterval(x);
                            $.get('/change/time')
                            $('.header_first_blue').css('display','none')
                            // document.getElementById("demo").innerHTML = "EXPIRED";
                        }
                    }, 1000);

                </script>
            @endif


        </div>

@endif

<div class="wrapper">
    <div class="d_flex head_soc">
        <a href="https://www.pinterest.com/Areve3d/" class="soc_svgs" target="_blank">
            <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
                <path opacity="0.7"
                      d="M15.5 8.53046C15.5 7.15546 15.125 5.84296 14.4375 4.65546C13.75 3.46796 12.8125 2.53046 11.625 1.84296C10.4375 1.15546 9.125 0.780457 7.75 0.780457C6.34375 0.780457 5.0625 1.15546 3.875 1.84296C2.6875 2.53046 1.71875 3.46796 1.03125 4.65546C0.34375 5.84296 0 7.15546 0 8.53046C0 10.1555 0.4375 11.6242 1.34375 12.9367C2.25 14.2492 3.4375 15.1867 4.9375 15.7492C4.8125 14.8117 4.84375 14.0617 4.96875 13.5305L5.84375 9.68671C5.6875 9.37421 5.625 8.99921 5.625 8.53046C5.625 7.99921 5.75 7.56171 6.03125 7.18671C6.28125 6.84296 6.625 6.65546 7.03125 6.65546C7.34375 6.65546 7.5625 6.78046 7.75 6.96796C7.90625 7.15546 8 7.40546 8 7.71796C8 8.03046 7.875 8.56171 7.65625 9.31171C7.5 9.74921 7.40625 10.093 7.375 10.3117C7.28125 10.6867 7.34375 11.0305 7.59375 11.3117C7.8125 11.593 8.125 11.718 8.53125 11.718C9.21875 11.718 9.78125 11.4055 10.25 10.718C10.7188 10.0617 10.9688 9.21796 10.9688 8.15546C10.9688 7.24921 10.6562 6.49921 10.0625 5.90546C9.4375 5.31171 8.65625 4.99921 7.71875 4.99921C7 4.99921 6.375 5.18671 5.84375 5.49921C5.3125 5.81171 4.90625 6.24921 4.625 6.74921C4.34375 7.24921 4.21875 7.81171 4.21875 8.37421C4.21875 8.71796 4.25 9.06171 4.375 9.37421C4.46875 9.68671 4.59375 9.96796 4.78125 10.1555C4.84375 10.218 4.84375 10.3117 4.84375 10.3742L4.625 11.2492C4.59375 11.3742 4.5 11.4055 4.375 11.343C3.90625 11.1555 3.53125 10.7492 3.21875 10.1555C2.90625 9.56171 2.78125 8.96796 2.78125 8.34296C2.78125 7.53046 2.96875 6.74921 3.375 6.03046C3.78125 5.31171 4.34375 4.74921 5.09375 4.31171C5.90625 3.87421 6.84375 3.65546 7.90625 3.65546C8.78125 3.65546 9.59375 3.87421 10.3438 4.24921C11.0625 4.62421 11.625 5.15546 12.0625 5.84296C12.4688 6.53046 12.6875 7.31171 12.6875 8.18671C12.6875 9.06171 12.5 9.84296 12.1562 10.5617C11.8125 11.3117 11.3438 11.8742 10.7188 12.3117C10.0938 12.7492 9.4375 12.9367 8.6875 12.9367C8.28125 12.9367 7.90625 12.8742 7.59375 12.6867C7.25 12.5305 7.03125 12.3117 6.90625 12.0617L6.40625 13.9055C6.28125 14.4367 5.96875 15.1242 5.46875 15.9367C6.1875 16.1867 6.9375 16.2805 7.75 16.2805C9.125 16.2805 10.4375 15.9367 11.625 15.2492C12.8125 14.5617 13.75 13.593 14.4375 12.4055C15.125 11.218 15.5 9.93671 15.5 8.53046Z"
                      fill="#393939"/>
            </svg>
        </a>
        <a href="https://www.instagram.com/areve3d" class="soc_svgs" target="_blank">
            <svg width="15" height="15" viewBox="0 0 15 15" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
                <path opacity="0.7"
                      d="M7.75 3.93671C8.375 3.93671 8.96875 4.12421 9.53125 4.43671C10.0938 4.74921 10.5312 5.18671 10.8438 5.74921C11.1562 6.31171 11.3438 6.90546 11.3438 7.53046C11.3438 8.18671 11.1562 8.78046 10.8438 9.34296C10.5312 9.90546 10.0938 10.343 9.53125 10.6555C8.96875 10.968 8.375 11.1242 7.75 11.1242C7.09375 11.1242 6.5 10.968 5.9375 10.6555C5.375 10.343 4.9375 9.90546 4.625 9.34296C4.3125 8.78046 4.15625 8.18671 4.15625 7.53046C4.15625 6.90546 4.3125 6.31171 4.625 5.74921C4.9375 5.18671 5.375 4.74921 5.9375 4.43671C6.5 4.12421 7.09375 3.93671 7.75 3.93671ZM7.75 9.87421C8.375 9.87421 8.9375 9.65546 9.40625 9.18671C9.84375 8.74921 10.0938 8.18671 10.0938 7.53046C10.0938 6.90546 9.84375 6.34296 9.40625 5.87421C8.9375 5.43671 8.375 5.18671 7.75 5.18671C7.09375 5.18671 6.53125 5.43671 6.09375 5.87421C5.625 6.34296 5.40625 6.90546 5.40625 7.53046C5.40625 8.18671 5.625 8.74921 6.09375 9.18671C6.53125 9.65546 7.09375 9.87421 7.75 9.87421ZM12.3438 3.78046C12.3438 3.56171 12.25 3.37421 12.0938 3.18671C11.9062 3.03046 11.7188 2.93671 11.5 2.93671C11.25 2.93671 11.0625 3.03046 10.9062 3.18671C10.7188 3.37421 10.6562 3.56171 10.6562 3.78046C10.6562 4.03046 10.7188 4.21796 10.9062 4.37421C11.0625 4.56171 11.25 4.62421 11.5 4.62421C11.7188 4.62421 11.9062 4.56171 12.0625 4.37421C12.2188 4.21796 12.3125 4.03046 12.3438 3.78046ZM14.7188 4.62421C14.7188 5.21796 14.75 6.18671 14.75 7.53046C14.75 8.90546 14.7188 9.87421 14.6875 10.468C14.6562 11.0617 14.5625 11.5617 14.4375 11.9992C14.25 12.5305 13.9375 12.9992 13.5625 13.3742C13.1875 13.7492 12.7188 14.0305 12.2188 14.218C11.7812 14.3742 11.25 14.468 10.6562 14.4992C10.0625 14.5305 9.09375 14.5305 7.75 14.5305C6.375 14.5305 5.40625 14.5305 4.8125 14.4992C4.21875 14.468 3.71875 14.3742 3.28125 14.1867C2.75 14.0305 2.28125 13.7492 1.90625 13.3742C1.53125 12.9992 1.25 12.5305 1.0625 11.9992C0.90625 11.5617 0.8125 11.0617 0.78125 10.468C0.75 9.87421 0.75 8.90546 0.75 7.53046C0.75 6.18671 0.75 5.21796 0.78125 4.62421C0.8125 4.03046 0.90625 3.49921 1.0625 3.06171C1.25 2.56171 1.53125 2.09296 1.90625 1.71796C2.28125 1.34296 2.75 1.03046 3.28125 0.842957C3.71875 0.717957 4.21875 0.624207 4.8125 0.592957C5.40625 0.561707 6.375 0.530457 7.75 0.530457C9.09375 0.530457 10.0625 0.561707 10.6562 0.592957C11.25 0.624207 11.7812 0.717957 12.2188 0.842957C12.7188 1.03046 13.1875 1.34296 13.5625 1.71796C13.9375 2.09296 14.25 2.56171 14.4375 3.06171C14.5625 3.49921 14.6562 4.03046 14.7188 4.62421ZM13.2188 11.6555C13.3438 11.3117 13.4062 10.7492 13.4688 9.96796C13.4688 9.53046 13.5 8.87421 13.5 8.03046V7.03046C13.5 6.18671 13.4688 5.53046 13.4688 5.09296C13.4062 4.31171 13.3438 3.74921 13.2188 3.40546C12.9688 2.78046 12.5 2.31171 11.875 2.06171C11.5312 1.93671 10.9688 1.87421 10.1875 1.81171C9.71875 1.81171 9.0625 1.78046 8.25 1.78046H7.25C6.40625 1.78046 5.75 1.81171 5.3125 1.81171C4.53125 1.87421 3.96875 1.93671 3.625 2.06171C2.96875 2.31171 2.53125 2.78046 2.28125 3.40546C2.15625 3.74921 2.0625 4.31171 2.03125 5.09296C2 5.56171 2 6.21796 2 7.03046V8.03046C2 8.87421 2 9.53046 2.03125 9.96796C2.0625 10.7492 2.15625 11.3117 2.28125 11.6555C2.53125 12.3117 3 12.7492 3.625 12.9992C3.96875 13.1242 4.53125 13.218 5.3125 13.2492C5.75 13.2805 6.40625 13.2805 7.25 13.2805H8.25C9.09375 13.2805 9.75 13.2805 10.1875 13.2492C10.9688 13.218 11.5312 13.1242 11.875 12.9992C12.5 12.7492 12.9688 12.2805 13.2188 11.6555Z"
                      fill="#393939"/>
            </svg>
        </a>
        <a href="https://www.facebook.com/profile.php?id=100086396290429" class="soc_svgs" target="_blank">
            <svg width="10" height="17" viewBox="0 0 10 17" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
                <path opacity="0.7"
                      d="M8.46875 9.53046L8.90625 6.65546H6.125V4.78046C6.125 3.96796 6.5 3.21796 7.75 3.21796H9.03125V0.749207C9.03125 0.749207 7.875 0.530457 6.78125 0.530457C4.5 0.530457 3 1.93671 3 4.43671V6.65546H0.4375V9.53046H3V16.5305H6.125V9.53046H8.46875Z"
                      fill="#393939"/>
            </svg>
        </a>
        <a href="https://www.linkedin.com/in/areve3d" class="soc_svgs" target="_blank">
            <svg width="15" height="15" viewBox="0 0 15 15" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
                <path opacity="0.7"
                      d="M3.875 14.5305V5.18671H0.96875V14.5305H3.875ZM2.4375 3.90546C2.875 3.90546 3.28125 3.74921 3.625 3.40546C3.9375 3.09296 4.125 2.68671 4.125 2.21796C4.125 1.78046 3.9375 1.37421 3.625 1.03046C3.28125 0.717957 2.875 0.530457 2.4375 0.530457C1.96875 0.530457 1.5625 0.717957 1.25 1.03046C0.90625 1.37421 0.75 1.78046 0.75 2.21796C0.75 2.68671 0.90625 3.09296 1.25 3.40546C1.5625 3.74921 1.96875 3.90546 2.4375 3.90546ZM14.75 14.5305V9.40546C14.75 7.96796 14.5312 6.90546 14.125 6.21796C13.5625 5.37421 12.625 4.93671 11.2812 4.93671C10.5938 4.93671 10.0312 5.12421 9.53125 5.43671C9.0625 5.71796 8.71875 6.06171 8.53125 6.46796H8.5V5.18671H5.71875V14.5305H8.59375V9.90546C8.59375 9.18671 8.6875 8.62421 8.90625 8.24921C9.15625 7.74921 9.625 7.49921 10.3125 7.49921C10.9688 7.49921 11.4062 7.78046 11.6562 8.34296C11.7812 8.68671 11.8438 9.21796 11.8438 9.96796V14.5305H14.75Z"
                      fill="#393939"/>
            </svg>
        </a>
    </div>
    <div class="header_first d_flex">
        <div class="head_social_lang"></div>
        <a class="head_logo" @if(auth()->check() && (auth()->user()->role->type == 'admin' || auth()->user()->role->type == 'superadmin')) href="{{url('/admin-dashboard')}}" @else href="{{url('/')}}" @endif>
            <img src="{{showImage(app('general_setting')->logo)}}" alt="">
        </a>
        <div class="head_social_lang head_lang_section">
            <div class="lang_drpdn" style="display:none;">
                <div class="d_flex chosen_lang">
                    <a class="eng_am_rus">{{strtoupper($locale)}}  </a>
                    <span class="drop_svg">
                            <svg width="12" height="8" viewBox="0 0 11 7" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                        d="M4.74219 6.38983C4.89844 6.54608 5.11719 6.54608 5.27344 6.38983L9.89844 1.79608C10.0547 1.67108 10.0547 1.42108 9.89844 1.26483L9.27344 0.671082C9.14844 0.514832 8.89844 0.514832 8.74219 0.671082L5.02344 4.35858L1.27344 0.671082C1.11719 0.514832 0.898438 0.514832 0.742188 0.671082L0.117188 1.26483C-0.0390625 1.42108 -0.0390625 1.67108 0.117188 1.79608L4.74219 6.38983Z"
                                        fill="#393939"/>
                           </svg>
                        </span>
                </div>
            </div>
        </div>
    </div>
    <nav class="header_second">
    <div class="d_flex space_betw">
        <div class="catalog_cl">
            <div class="menu_catalog d_flex">
                <svg width="19" height="11" viewBox="0 0 19 11" fill="none"
                     xmlns="http://www.w3.org/2000/svg">
                    <rect x="0.632812" y="4.79956" width="17.4108" height="1.5" fill="white"/>
                    <rect x="0.632812" y="0.289307" width="17.4108" height="1.5" fill="white"/>
                    <rect x="0.632812" y="9.30957" width="17.4108" height="1.5" fill="white"/>
                </svg>
                {{ __('defaultTheme.all_categories') }}
            </div>
            <div class="catalog_dropd">
                @foreach (Modules\Product\Entities\Category::where('status', 1)->where('searchable', 1)->get() as $key => $category)
                    @if($category->name == 'All designs')
                    <a href="{{route('frontend.category_slug',['slug' => 'all-products'])}}"
                            data-id="parent_cat"
                       data-value="{{ $category->id }}" href="#" class="{{$category->slug}}  catalog_a d_flex">
                        {{ $category->name }}
                        <svg width="7" height="12" viewBox="0 0 7 12" fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <path d="M1 1L5.96659 5.96652L1 10.9331" stroke="#717171"/>
                        </svg>
                    </a>
                    @else
                        <a href="{{route('frontend.category_slug',['slug'=>$category->slug])}}"
                           data-id="parent_cat"
                           data-value="{{ $category->id }}" href="#" class="{{$category->slug}}  catalog_a d_flex">
                            {{ $category->name }}
                            <svg width="7" height="12" viewBox="0 0 7 12" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 1L5.96659 5.96652L1 10.9331" stroke="#717171"/>
                            </svg>
                        </a>
                    @endif
                @endforeach

            </div>
        </div>
        <div class="menu_search">
            <input class="category_box_input" type="text" readonly="readonly"
                  placeholder="{{ __('defaultTheme.search_your_item') }}">
            <button class="search_svg" id="search_form">
                <svg width="23" height="23" viewBox="0 0 23 23" fill="none"
                     xmlns="http://www.w3.org/2000/svg">
                    <path opacity="0.7"
                          d="M22.8319 21.0375L17.4043 15.6099C17.2697 15.5202 17.1351 15.4305 17.0006 15.4305H16.4174C17.808 13.8157 18.7051 11.6626 18.7051 9.33008C18.7051 4.21648 14.4886 0 9.375 0C4.21655 0 0.0449219 4.21648 0.0449219 9.33008C0.0449219 14.4885 4.21655 18.6602 9.375 18.6602C11.7075 18.6602 13.8158 17.8079 15.4754 16.4174V17.0005C15.4754 17.1351 15.5203 17.2696 15.61 17.4042L21.0376 22.8318C21.2619 23.0561 21.6207 23.0561 21.8002 22.8318L22.8319 21.8001C23.0561 21.6207 23.0561 21.2618 22.8319 21.0375ZM9.375 16.5071C5.38281 16.5071 2.19802 13.3223 2.19802 9.33008C2.19802 5.38274 5.38281 2.1531 9.375 2.1531C13.3223 2.1531 16.552 5.38274 16.552 9.33008C16.552 13.3223 13.3223 16.5071 9.375 16.5071Z"
                          fill="#717171"/>
                </svg>
            </button>
        </div>
        @guest
            <a class="login_btn head_icons">
                <svg class="head_icons_svg" width="23" height="26" viewBox="0 0 23 26" fill="none"
                     xmlns="http://www.w3.org/2000/svg">
                    <path
                            d="M16.0692 15.4375C14.5804 15.4375 13.9129 16.25 11.5 16.25C9.03571 16.25 8.3683 15.4375 6.87946 15.4375C3.08036 15.4375 0 18.5352 0 22.293V23.5625C0 24.9336 1.07812 26 2.46429 26H20.5357C21.8705 26 23 24.9336 23 23.5625V22.293C23 18.5352 19.8683 15.4375 16.0692 15.4375ZM20.5357 23.5625H2.46429V22.293C2.46429 19.8555 4.41518 17.875 6.87946 17.875C7.64955 17.875 8.83036 18.6875 11.5 18.6875C14.1183 18.6875 15.2991 17.875 16.0692 17.875C18.5335 17.875 20.5357 19.8555 20.5357 22.293V23.5625ZM11.5 14.625C15.5558 14.625 18.8929 11.375 18.8929 7.3125C18.8929 3.30078 15.5558 0 11.5 0C7.39286 0 4.10714 3.30078 4.10714 7.3125C4.10714 11.375 7.39286 14.625 11.5 14.625ZM11.5 2.4375C14.1696 2.4375 16.4286 4.67188 16.4286 7.3125C16.4286 10.0039 14.1696 12.1875 11.5 12.1875C8.77902 12.1875 6.57143 10.0039 6.57143 7.3125C6.57143 4.67188 8.77902 2.4375 11.5 2.4375Z"
                            fill="#717171" fill-opacity="0.7"/>
                </svg>
            </a>

        @else
            <div class="head_icons profile_svg d_flex" style="display: flex">
                <!-- for profile  add display:flex; -->
{{--                            <svg class="head_icons_svg" width="23" height="27" viewBox="0 0 23 27" fill="none"--}}
{{--                                 xmlns="http://www.w3.org/2000/svg">--}}
{{--                                <path--}}
{{--                                        d="M16.0692 15.9746C14.5804 15.9746 13.9129 16.7871 11.5 16.7871C9.03571 16.7871 8.3683 15.9746 6.87946 15.9746C3.08036 15.9746 0 19.0723 0 22.8301V24.0996C0 25.4707 1.07812 26.5371 2.46429 26.5371H20.5357C21.8705 26.5371 23 25.4707 23 24.0996V22.8301C23 19.0723 19.8683 15.9746 16.0692 15.9746ZM20.5357 24.0996H2.46429V22.8301C2.46429 20.3926 4.41518 18.4121 6.87946 18.4121C7.64955 18.4121 8.83036 19.2246 11.5 19.2246C14.1183 19.2246 15.2991 18.4121 16.0692 18.4121C18.5335 18.4121 20.5357 20.3926 20.5357 22.8301V24.0996ZM11.5 15.1621C15.5558 15.1621 18.8929 11.9121 18.8929 7.84961C18.8929 3.83789 15.5558 0.537109 11.5 0.537109C7.39286 0.537109 4.10714 3.83789 4.10714 7.84961C4.10714 11.9121 7.39286 15.1621 11.5 15.1621ZM11.5 2.97461C14.1696 2.97461 16.4286 5.20898 16.4286 7.84961C16.4286 10.541 14.1696 12.7246 11.5 12.7246C8.77902 12.7246 6.57143 10.541 6.57143 7.84961C6.57143 5.20898 8.77902 2.97461 11.5 2.97461Z"--}}
{{--                                        fill="#717171" fill-opacity="0.7"/>--}}
{{--                            </svg>--}}

                <img src="{{auth()->user()->avatar?showImage(auth()->user()->avatar):showImage('frontend/default/img/avatar.jpg')}}" >

                <div class="dropdwn_for_profile">
                    <div class="triangle_"></div>
                    <a class="my_prof my_a" href="{{route('frontend.customer_profile')}}">Edit my
                        profile</a>
                    <a class="my_purch my_a"
                       href="{{route('frontend.customer_profile',['a'=>'purchases'])}}">My purchases</a>
                    <a class="my_com my_a" href="{{route('frontend.customer_profile',['a'=>'comments'])}}">My
                        comments</a>
                    <a class="my_fav my_a" href="{{route('frontend.customer_profile',['a'=>'favorites'])}}">{{ __('customer_panel.my_wishlist') }}</a>
                    <a class="my_sett my_a" href="{{route('frontend.customer_profile',['a'=>'Settings'])}}">Settings</a>

                    <form action="{{route('logout')}}" method="post">
                        @csrf
                        <button class="log_out_my my_a" style="border: none;background-color: white">Log out</button>
                    </form>

                </div>
            </div>
        @endguest
        @auth
        <a href="{{ route('frontend.my-wishlist') }}" class="head_fav head_icons">
            <svg class="head_icons_svg" width="31" height="27" viewBox="0 0 31 27" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
                <path d="M27.0356 2.46378C23.6147 -0.426299 18.3653 -0.0134306 15.1214 3.3485C11.8184 -0.0134306 6.56908 -0.426299 3.14817 2.46378C-1.27543 6.1796 -0.626633 12.2547 2.55835 15.4986L12.8801 26.0563C13.4699 26.6461 14.2366 27 15.1214 27C15.9471 27 16.7139 26.6461 17.3037 26.0563L27.6844 15.4986C30.8104 12.2547 31.4592 6.1796 27.0356 2.46378ZM25.62 13.4933L15.2983 24.0509C15.1803 24.1689 15.0624 24.1689 14.8854 24.0509L4.56372 13.4933C2.38141 11.311 1.96854 7.18228 4.97659 4.64609C7.27685 2.69971 10.8157 2.99461 13.057 5.2359L15.1214 7.35923L17.1857 5.2359C19.368 2.99461 22.9069 2.69971 25.2072 4.58711C28.2152 7.18228 27.8023 11.311 25.62 13.4933Z"
                      fill="#717171" fill-opacity="0.7"/>
            </svg>
            @if($wishlists)
               <span class="red_o d_flex">{{$wishlists}}</span>
            @endif
        </a>
        @else
            <a  class="login_btn head_fav head_icons">
                <svg class="head_icons_svg" width="31" height="27" viewBox="0 0 31 27" fill="none"
                     xmlns="http://www.w3.org/2000/svg">
                    <path d="M27.0356 2.46378C23.6147 -0.426299 18.3653 -0.0134306 15.1214 3.3485C11.8184 -0.0134306 6.56908 -0.426299 3.14817 2.46378C-1.27543 6.1796 -0.626633 12.2547 2.55835 15.4986L12.8801 26.0563C13.4699 26.6461 14.2366 27 15.1214 27C15.9471 27 16.7139 26.6461 17.3037 26.0563L27.6844 15.4986C30.8104 12.2547 31.4592 6.1796 27.0356 2.46378ZM25.62 13.4933L15.2983 24.0509C15.1803 24.1689 15.0624 24.1689 14.8854 24.0509L4.56372 13.4933C2.38141 11.311 1.96854 7.18228 4.97659 4.64609C7.27685 2.69971 10.8157 2.99461 13.057 5.2359L15.1214 7.35923L17.1857 5.2359C19.368 2.99461 22.9069 2.69971 25.2072 4.58711C28.2152 7.18228 27.8023 11.311 25.62 13.4933Z"
                          fill="#717171" fill-opacity="0.7"/>
                </svg>
            </a>
        @endauth
        @auth
            <div class="notif_fav head_icons">
                <svg class="head_icons_svg" width="24" height="27" viewBox="0 0 24 27" fill="none"
                     xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.7002 19.2987C21.7182 18.2133 19.8058 16.611 19.8058 11.2873C19.8058 7.30738 17.0147 4.10281 13.1899 3.27583V2.19041C13.1899 1.31174 12.4663 0.536438 11.5876 0.536438C10.6572 0.536438 9.93362 1.31174 9.93362 2.19041V3.27583C6.10881 4.10281 3.31774 7.30738 3.31774 11.2873C3.31774 16.611 1.40533 18.2133 0.423288 19.2987C0.113168 19.6088 -0.041892 20.0223 0.00979463 20.3841C0.00979463 21.2628 0.630034 22.0381 1.66377 22.0381H21.4597C22.4935 22.0381 23.1137 21.2628 23.1654 20.3841C23.1654 20.0223 23.0103 19.6088 22.7002 19.2987ZM3.4728 19.5571C4.55822 18.1616 5.74701 15.7323 5.7987 11.3389C5.7987 11.3389 5.7987 11.3389 5.7987 11.2873C5.7987 8.13437 8.38303 5.49835 11.5876 5.49835C14.7405 5.49835 17.3765 8.13437 17.3765 11.2873C17.3765 11.3389 17.3248 11.3389 17.3248 11.3389C17.3765 15.7323 18.5653 18.1616 19.6507 19.5571H3.4728ZM11.5876 27C13.3966 27 14.8439 25.5528 14.8439 23.692H8.27965C8.27965 25.5528 9.72688 27 11.5876 27Z"
                          fill="#717171" fill-opacity="0.7"/>
                </svg>
                @if(count($notifications))
                    <span class="red_o d_flex">{{count($notifications)}}</span>
                @endif
                <div class="for_notifications">
                    <div class="triangle_"></div>
                    <span class="notif_title">{{ __('common.notification') }}</span>
                    @foreach ($notifications as $notification)
                        <div class="notif_info_block sto_">
                            <div class="d_flex">
                                <a  class="d_flex notif_from">
                                    @php
                                        $admin = \App\Models\User::find(1);
                                    @endphp
                                    <img class="from_user d_flex" src="{{showImage($admin->avatar !=null?$admin->avatar:'backend/img/avatar.png')}}">
                                    {{--                                            <span class="from_user d_flex">S</span>--}}
                                    <div class="who_what_notif">
                                        <span class="notif_title_from">
                                            {{$notification->description}}
                                            {{--                                                Sveta Ganashiram <span class="notif_title_green">Commented</span> a Post--}}
                                        </span>
                                        <p class="what_notif">
                                            {{$notification->title}}
                                            {{--                                            Lorem Ipsum is simply dummy text of--}}
                                            {{--                                            the printing and typesetting industry. Lorem Ipsu....--}}
                                        </p>
                                    </div>
                                </a>
                                <span class="d_flex notif_clock">
                                    <svg width="13" height="14" viewBox="0 0 13 14" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M6.66699 0.83077C3.27767 0.83077 0.531575 3.57686 0.531575 6.96619C0.531575 10.3555 3.27767 13.1016 6.66699 13.1016C10.0563 13.1016 12.8024 10.3555 12.8024 6.96619C12.8024 3.57686 10.0563 0.83077 6.66699 0.83077ZM6.66699 11.9141C3.9209 11.9141 1.71908 9.71228 1.71908 6.96619C1.71908 4.24483 3.9209 2.01827 6.66699 2.01827C9.38835 2.01827 11.6149 4.24483 11.6149 6.96619C11.6149 9.71228 9.38835 11.9141 6.66699 11.9141ZM8.17611 9.34119C8.32454 9.44014 8.49772 9.41541 8.59668 9.26697L9.06673 8.64848C9.16569 8.50004 9.14095 8.32686 8.99251 8.22791L7.3597 7.01567V3.50264C7.3597 3.35421 7.21126 3.20577 7.06283 3.20577H6.27116C6.09798 3.20577 5.97428 3.35421 5.97428 3.50264V7.58468C5.97428 7.65889 5.99902 7.75785 6.07324 7.80733L8.17611 9.34119Z"
                                                fill="#717171"/>
                                    </svg>
                                    <span>{{$notification->created_at->toDateString()}}</span>
                                </span>
                                <span class="delete_notif" onclick="delete_notif('{{$notification->id}}')">
                                    <svg width="18" height="21" viewBox="0 0 18 21" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10.6172 16.2995H11.5547C11.7891 16.2995 12.0234 16.1042 12.0234 15.8307V7.39325C12.0234 7.15887 11.7891 6.9245 11.5547 6.9245H10.6172C10.3438 6.9245 10.1484 7.15887 10.1484 7.39325V15.8307C10.1484 16.1042 10.3438 16.2995 10.6172 16.2995ZM17.0234 3.1745H13.7812L12.4531 0.987C12.1406 0.440125 11.5156 0.0494995 10.8516 0.0494995H6.90625C6.24219 0.0494995 5.61719 0.440125 5.30469 0.987L3.97656 3.1745H0.773438C0.421875 3.1745 0.148438 3.487 0.148438 3.7995V4.4245C0.148438 4.77606 0.421875 5.0495 0.773438 5.0495H1.39844V18.1745C1.39844 19.2292 2.21875 20.0495 3.27344 20.0495H14.5234C15.5391 20.0495 16.3984 19.2292 16.3984 18.1745V5.0495H17.0234C17.3359 5.0495 17.6484 4.77606 17.6484 4.4245V3.7995C17.6484 3.487 17.3359 3.1745 17.0234 3.1745ZM6.82812 2.04169C6.86719 2.00262 6.94531 1.9245 7.02344 1.9245C7.02344 1.9245 7.02344 1.9245 7.0625 1.9245H10.7344C10.8125 1.9245 10.8906 2.00262 10.9297 2.04169L11.5938 3.1745H6.16406L6.82812 2.04169ZM14.5234 18.1745H3.27344V5.0495H14.5234V18.1745ZM6.24219 16.2995H7.17969C7.41406 16.2995 7.64844 16.1042 7.64844 15.8307V7.39325C7.64844 7.15887 7.41406 6.9245 7.17969 6.9245H6.24219C5.96875 6.9245 5.77344 7.15887 5.77344 7.39325V15.8307C5.77344 16.1042 5.96875 16.2995 6.24219 16.2995Z"
                                                fill="#717171"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    @endforeach
                    <div class="see_notifs-section">
                        <a class="see_notifs" href="{{route('frontend.customer_profile',['a'=>'comments'])}}">See all notifications</a>
                    </div>
                </div>
            </div>
        @else
            <div class="login_btn notif_fav head_icons">
                <svg class="head_icons_svg" width="24" height="27" viewBox="0 0 24 27" fill="none"
                     xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.7002 19.2987C21.7182 18.2133 19.8058 16.611 19.8058 11.2873C19.8058 7.30738 17.0147 4.10281 13.1899 3.27583V2.19041C13.1899 1.31174 12.4663 0.536438 11.5876 0.536438C10.6572 0.536438 9.93362 1.31174 9.93362 2.19041V3.27583C6.10881 4.10281 3.31774 7.30738 3.31774 11.2873C3.31774 16.611 1.40533 18.2133 0.423288 19.2987C0.113168 19.6088 -0.041892 20.0223 0.00979463 20.3841C0.00979463 21.2628 0.630034 22.0381 1.66377 22.0381H21.4597C22.4935 22.0381 23.1137 21.2628 23.1654 20.3841C23.1654 20.0223 23.0103 19.6088 22.7002 19.2987ZM3.4728 19.5571C4.55822 18.1616 5.74701 15.7323 5.7987 11.3389C5.7987 11.3389 5.7987 11.3389 5.7987 11.2873C5.7987 8.13437 8.38303 5.49835 11.5876 5.49835C14.7405 5.49835 17.3765 8.13437 17.3765 11.2873C17.3765 11.3389 17.3248 11.3389 17.3248 11.3389C17.3765 15.7323 18.5653 18.1616 19.6507 19.5571H3.4728ZM11.5876 27C13.3966 27 14.8439 25.5528 14.8439 23.692H8.27965C8.27965 25.5528 9.72688 27 11.5876 27Z"
                          fill="#717171" fill-opacity="0.7"/>
                </svg>
            </div>
        @endauth
        @auth

            <a href="{{route('message.index')}}" class="head_sms head_icons">
                <svg class="head_icons_svg" width="29" height="22" viewBox="0 0 29 22" fill="none"
                     xmlns="http://www.w3.org/2000/svg">
                    <path d="M25.8228 0.0829468H2.67132C1.1687 0.0829468 0 1.3073 0 2.75427V18.7822C0 20.2848 1.1687 21.4535 2.67132 21.4535H25.8228C27.2698 21.4535 28.4941 20.2848 28.4941 18.7822V2.75427C28.4941 1.3073 27.2698 0.0829468 25.8228 0.0829468ZM25.8228 2.75427V5.03603C24.5428 6.09342 22.5393 7.6517 18.3097 10.9909C17.3636 11.7143 15.5271 13.4952 14.2471 13.4396C12.9114 13.4952 11.0749 11.7143 10.1288 10.9909C5.89917 7.6517 3.89568 6.09342 2.67132 5.03603V2.75427H25.8228ZM2.67132 18.7822V8.48648C3.89568 9.48823 5.73221 10.9352 8.45919 13.1056C9.68355 14.0517 11.854 16.1665 14.2471 16.1109C16.5845 16.1665 18.6993 14.0517 19.9793 13.1056C22.7062 10.9352 24.5428 9.48823 25.8228 8.48648V18.7822H2.67132Z"
                          fill="#717171" fill-opacity="0.7"/>
                </svg>
                @php
                    $x = count(\App\Models\Message::where('to_id',auth()->id())->where('view','0')
                    ->where([['messages' ,'!=', '']])
                    ->get());
                @endphp
                @if($x)
                    <span class="red_o d_flex messageneri_tiv">{{ $x }}</span>
                @endif
            </a>
        @else
            <a class="login_btn head_sms head_icons">
                <svg class="head_icons_svg" width="29" height="22" viewBox="0 0 29 22" fill="none"
                     xmlns="http://www.w3.org/2000/svg">
                    <path d="M25.8228 0.0829468H2.67132C1.1687 0.0829468 0 1.3073 0 2.75427V18.7822C0 20.2848 1.1687 21.4535 2.67132 21.4535H25.8228C27.2698 21.4535 28.4941 20.2848 28.4941 18.7822V2.75427C28.4941 1.3073 27.2698 0.0829468 25.8228 0.0829468ZM25.8228 2.75427V5.03603C24.5428 6.09342 22.5393 7.6517 18.3097 10.9909C17.3636 11.7143 15.5271 13.4952 14.2471 13.4396C12.9114 13.4952 11.0749 11.7143 10.1288 10.9909C5.89917 7.6517 3.89568 6.09342 2.67132 5.03603V2.75427H25.8228ZM2.67132 18.7822V8.48648C3.89568 9.48823 5.73221 10.9352 8.45919 13.1056C9.68355 14.0517 11.854 16.1665 14.2471 16.1109C16.5845 16.1665 18.6993 14.0517 19.9793 13.1056C22.7062 10.9352 24.5428 9.48823 25.8228 8.48648V18.7822H2.67132Z"
                          fill="#717171" fill-opacity="0.7"/>
                </svg>
            </a>

        @endauth


        <div class="head_catal head_icons">
            <svg class="head_icons_svg" width="23" height="26" viewBox="0 0 23 26" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
                <path d="M18.0714 6.5C18.0714 2.94531 15.0938 0 11.5 0C7.85491 0 4.92857 2.94531 4.92857 6.5H0V21.9375C0 24.2227 1.79688 26 4.10714 26H18.8929C21.1518 26 23 24.2227 23 21.9375V6.5H18.0714ZM11.5 2.4375C13.7589 2.4375 15.6071 4.26562 15.6071 6.5H7.39286C7.39286 4.26562 9.18973 2.4375 11.5 2.4375ZM20.5357 21.9375C20.5357 22.8516 19.7656 23.5625 18.8929 23.5625H4.10714C3.18304 23.5625 2.46429 22.8516 2.46429 21.9375V8.9375H4.92857V10.9688C4.92857 11.6797 5.44196 12.1875 6.16071 12.1875C6.82812 12.1875 7.39286 11.6797 7.39286 10.9688V8.9375H15.6071V10.9688C15.6071 11.6797 16.1205 12.1875 16.8393 12.1875C17.5067 12.1875 18.0714 11.6797 18.0714 10.9688V8.9375H20.5357V21.9375Z"
                      fill="#717171" fill-opacity="0.7"/>
            </svg>

            @php
                $items = 0;
                $actualtotal = 0;
                foreach($carts as $cart){
                    if(auth()->check()){
                        $items += $cart->qty;
                        $actualtotal += $cart->total_price;
                    }else{
                        $items += $cart['qty'];
                        $actualtotal += $cart['total_price'];
                    }
                }

                $base_url = url('/');
                $current_url = url()->current();
                $just_path = trim(str_replace($base_url,'',$current_url));

            @endphp
            @php
                $subtotal = 0;
            @endphp
            @if($items)
                <span class="red_o d_flex">{{$items}}</span>
            @endif
            <div class="for_catal">
                <div class="triangle_"></div>
                <span class="notif_title d_flex shopping_fl">
{{--                                    SHOPPING CART--}}
                    {{ __('common.cart') }}
                        <span class="shop_cart_sp d_flex">{{$items}}</span>
                </span>
                @php
                    $total = 0;
                    $subtotal = 0;
                    $additional_shipping = 0;
                    $tax = 0;
                    $sameStateTaxes = \Modules\GST\Entities\GstTax::whereIn('id', app('gst_config')['within_a_single_state'])->get();
                    $diffStateTaxes = \Modules\GST\Entities\GstTax::whereIn('id', app('gst_config')['between_two_different_states_or_a_state_and_a_Union_Territory'])->get();
                    $flatTax = \Modules\GST\Entities\GstTax::where('id', app('gst_config')['flat_tax_id'])->first();
                @endphp
                @if(isset($carts[0]))
                <div class='scroll_block'>
                @foreach ($carts as $key => $cart)
                    @php
                        $subtotal += $cart->price * $cart->qty;
                    @endphp
                    <div class="catal_info_block sto_">
                        <div class="d_flex start_mob">
                            <a href="{{singleProductURL(@$cart->product->product->seller->slug, @$cart->product->product->slug, @$cart->product->product->product->categories[0]->slug)}}"
                               class="d_flex catal_about_">
                                <div class="img_for_catal">
                                    <img src="
                                     @if(@$cart->product->product->product->product_type == 1)
                                    {{asset(asset_path(@$cart->product->product->product->thumbnail_image_source))}}
                                    @else
                                    {{asset(asset_path(@$cart->product->sku->variant_image?@$cart->product->sku->variant_image:@$cart->product->product->product->thumbnail_image_source))}}
                                    @endif
                                            " alt="">
                                </div>
                                <div class="d_flex name_and_prie_catal">
                                    <span class="name_catal">{{$cart->product->product->product->product_name}}</span>
                                    <div class="d_flex price_prod">
{{--                                                    <span class="this_moment_price">{{single_price($cart->price)}}$</span>--}}
                                        @if($cart->product->product->hasDeal)
                                            @if($cart->product->product->hasDeal->discount > 0)
                                                @if($cart->product->product->hasDeal->discount_type == 0)
{{--                                                                <span class="this_moment_price">${{$cart->product->product->skus->first()->selling_price - $cart->product->product->discount}}</span>--}}
{{--                                                                <span class="sale_for d_flex">-{{$cart->product->product->discount/$cart->product->product->skus->first()->selling_price*100}}%</span>--}}

                                                    <span class="this_moment_price">{{single_price($cart->total_price)}}</span>
                                                    <span class="sale_for">-{{single_price($cart->product->product->hasDeal->discount)}}</span>
                                                    <span class="prev_price">{{single_price($cart->product->selling_price)}}</span>
                                                @else
{{--                                                                <span class="this_moment_price">${{$cart->product->product->skus->first()->selling_price - $cart->product->product->discount}}</span>--}}
{{--                                                                <span class="sale_for">-{{$cart->product->product->discount/$cart->product->product->skus->first()->selling_price*100}}%</span>--}}

                                                    <span class="this_moment_price">{{single_price($cart->total_price)}}</span>
                                                    <span class="sale_for">-{{single_price($cart->product->product->hasDeal->discount)}}</span>
                                                    <span class="prev_price">{{single_price($cart->product->selling_price)}}</span>
                                                @endif
                                            @else
                                                <span class="this_moment_price">{{single_price($cart->product->selling_price)}}</span>
                                            @endif
                                        @else
                                            @if(@$cart->product->product->hasDiscount == 'yes')
                                                @if($cart->product->product->discount_type == 0)
{{--                                                                <span class="this_moment_price">${{$cart->product->product->skus->first()->selling_price - $cart->product->product->discount}}</span>--}}
{{--                                                                <span class="sale_for">-{{$cart->product->product->discount/$cart->product->product->skus->first()->selling_price*100}}%</span>--}}

                                                    <span class="this_moment_price">{{single_price($cart->total_price)}}</span>
                                                    <span class="sale_for">-{{$cart->product->product->discount}}%</span>
                                                    <span class="prev_price">{{single_price($cart->product->selling_price)}}</span>
                                                @else
{{--                                                                <span class="this_moment_price">${{$cart->product->product->skus->first()->selling_price - $cart->product->product->discount}}</span>--}}
{{--                                                                <span class="sale_for">-{{$cart->product->product->discount/$cart->product->product->skus->first()->selling_price*100}}%</span>--}}

                                                    <span class="this_moment_price">{{single_price($cart->total_price)}}</span>
                                                    <span class="sale_for">-{{single_price($cart->product->product->discount)}}</span>
                                                    <span class="prev_price">{{single_price($cart->product->selling_price)}}</span>
                                                @endif
                                            @else
                                                <span class="this_moment_price">{{single_price($cart->product->selling_price)}}</span>
                                            @endif

                                            @if($cart->product->product->tax)
                                                <span title="Tax applied at the point of sale to the end buyer" class="tax">
                                                    {{sprintf('+ %1$s%%',  $cart->product->product->tax)}}
                                                </span>
                                            @endif

                                        @endif
                                    </div>
                                </div>
                            </a>

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

                            <div class="delete_catal cart_item_delete_btn" data-id="{{$cart->id}}"
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
                </div>
                @endif
                @php
                    $total = $subtotal;

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


                @if($subtotal > 0)
                <div class="d_flex promocode_form sto_">
                    <input autocomplete="off" class="promo_code coupon_code" type="text" data-total="{{$total}}" placeholder="{{ __('marketing.coupon_code') }}">
                    <button class="apply_promo d_flex coupon_apply_btn" onclick="couponApply(this, '{{$total}}')" data-total="{{$total}}">{{__('common.apply')}}</button>
                </div>
                @endif
                <div class="sub_sale_prices sto_">
                    <div class="d_flex">
                        <span class="sub_sp">{{ __('common.subtotal') }}:</span>
                        <span class="sale_price">{{ single_price($subtotal) }}</span>
                    </div>
                    <div class="d_flex sto_">
                        <span class="sub_sale">Sale::</span>
                        <span class="price_of">-{{single_price($discount)}}</span>
                    </div>
                </div>
                <div class="d_flex total_bl">
                    <span class="total_pr">{{__('common.total')}}:</span>
                    <span class="total_num">{{single_price($total)}}</span>
                </div>
                <div class="total_bl">
                    <a href="{{ route('frontend.cart') }}" class="goto_check sto_ d_flex">{{ __('defaultTheme.view_shopping_cart') }}</a>
                </div>
            </div>
        </div>
    </div>
</nav>
</div>
</header>
@section('content')
@show

<footer>
<div class="wrapper">
<div class="f_logo_soc d_flex">
    <a href="{{url('/')}}">
        <img src="{{showImage(app('general_setting')->logo)}}" alt="">
    </a>
    <div class="d_flex soc_for_f">
        <a class="f_social" href="https://www.pinterest.com/Areve3d" target="_blank">
            <svg width="35" height="36" viewBox="0 0 35 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path opacity="0.7"
                      d="M34.6907 17.9053C34.6907 14.8279 33.8514 11.8904 32.3127 9.23262C30.774 6.57486 28.6758 4.47663 26.0181 2.93793C23.3603 1.39923 20.4228 0.559937 17.3454 0.559937C14.198 0.559937 11.3304 1.39923 8.67268 2.93793C6.01493 4.47663 3.84676 6.57486 2.30805 9.23262C0.769351 11.8904 0 14.8279 0 17.9053C0 21.5422 0.979174 24.8295 3.00746 27.767C5.03575 30.7045 7.69351 32.8027 11.0507 34.0617C10.7709 31.9634 10.8409 30.2849 11.1206 29.0959L13.079 20.4931C12.7293 19.7937 12.5894 18.9544 12.5894 17.9053C12.5894 16.7163 12.8691 15.7371 13.4986 14.8978C14.0581 14.1285 14.8275 13.7088 15.7367 13.7088C16.4361 13.7088 16.9257 13.9886 17.3454 14.4083C17.6951 14.8279 17.9049 15.3874 17.9049 16.0868C17.9049 16.7863 17.6251 17.9752 17.1355 19.6538C16.7858 20.633 16.576 21.4024 16.5061 21.8919C16.2963 22.7312 16.4361 23.5006 16.9957 24.1301C17.4853 24.7595 18.1847 25.0393 19.0939 25.0393C20.6326 25.0393 21.8915 24.3399 22.9407 22.8012C23.9898 21.3324 24.5493 19.444 24.5493 17.066C24.5493 15.0377 23.8499 13.3591 22.521 12.0303C21.1222 10.7014 19.3737 10.002 17.2754 10.002C15.6668 10.002 14.268 10.4216 13.079 11.121C11.89 11.8204 10.9807 12.7996 10.3513 13.9187C9.7218 15.0377 9.44204 16.2967 9.44204 17.5556C9.44204 18.325 9.51198 19.0943 9.79174 19.7937C10.0016 20.4931 10.2813 21.1226 10.701 21.5422C10.8409 21.6821 10.8409 21.8919 10.8409 22.0318L10.3513 23.9902C10.2813 24.2699 10.0715 24.3399 9.79174 24.2C8.74263 23.7804 7.90333 22.8711 7.20392 21.5422C6.50451 20.2134 6.22475 18.8845 6.22475 17.4857C6.22475 15.6672 6.6444 13.9187 7.55363 12.31C8.46286 10.7014 9.7218 9.44244 11.4004 8.46327C13.2189 7.4841 15.3171 6.99451 17.6951 6.99451C19.6534 6.99451 21.4719 7.4841 23.1505 8.32339C24.7591 9.16268 26.0181 10.3517 26.9972 11.8904C27.9065 13.4291 28.396 15.1776 28.396 17.136C28.396 19.0943 27.9764 20.8428 27.2071 22.4515C26.4377 24.1301 25.3886 25.389 23.9898 26.3682C22.5909 27.3473 21.1222 27.767 19.4436 27.767C18.5344 27.767 17.6951 27.6271 16.9957 27.2075C16.2263 26.8578 15.7367 26.3682 15.457 25.8086L14.3379 29.9352C14.0581 31.1242 13.3587 32.6629 12.2397 34.4813C13.8483 35.0409 15.5269 35.2507 17.3454 35.2507C20.4228 35.2507 23.3603 34.4813 26.0181 32.9426C28.6758 31.4039 30.774 29.2357 32.3127 26.578C33.8514 23.9202 34.6907 21.0527 34.6907 17.9053Z"
                      fill="#00AAAD"/>
            </svg>
        </a>
        <a class="f_social" href="https://www.instagram.com/areve3d" target="_blank">
            <svg width="33" height="32" viewBox="0 0 33 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path opacity="0.7"
                      d="M16.4932 7.86149C17.892 7.86149 19.2209 8.28113 20.4798 8.98054C21.7388 9.67995 22.718 10.6591 23.4174 11.9181C24.1168 13.177 24.5364 14.5059 24.5364 15.9047C24.5364 17.3735 24.1168 18.7023 23.4174 19.9613C22.718 21.2202 21.7388 22.1994 20.4798 22.8988C19.2209 23.5982 17.892 23.9479 16.4932 23.9479C15.0244 23.9479 13.6956 23.5982 12.4366 22.8988C11.1777 22.1994 10.1985 21.2202 9.4991 19.9613C8.79969 18.7023 8.44999 17.3735 8.44999 15.9047C8.44999 14.5059 8.79969 13.177 9.4991 11.9181C10.1985 10.6591 11.1777 9.67995 12.4366 8.98054C13.6956 8.28113 15.0244 7.86149 16.4932 7.86149ZM16.4932 21.1503C17.892 21.1503 19.151 20.6607 20.2001 19.6116C21.1793 18.6324 21.7388 17.3735 21.7388 15.9047C21.7388 14.5059 21.1793 13.2469 20.2001 12.1978C19.151 11.2187 17.892 10.6591 16.4932 10.6591C15.0244 10.6591 13.7655 11.2187 12.7863 12.1978C11.7372 13.2469 11.2476 14.5059 11.2476 15.9047C11.2476 17.3735 11.7372 18.6324 12.7863 19.6116C13.7655 20.6607 15.0244 21.1503 16.4932 21.1503ZM26.7745 7.51178C26.7745 7.02219 26.5647 6.60255 26.215 6.1829C25.7954 5.8332 25.3757 5.62337 24.8861 5.62337C24.3266 5.62337 23.9069 5.8332 23.5572 6.1829C23.1376 6.60255 22.9977 7.02219 22.9977 7.51178C22.9977 8.07131 23.1376 8.49095 23.5572 8.84066C23.9069 9.26031 24.3266 9.40019 24.8861 9.40019C25.3757 9.40019 25.7954 9.26031 26.1451 8.84066C26.4948 8.49095 26.7046 8.07131 26.7745 7.51178ZM32.09 9.40019C32.09 10.7291 32.16 12.8972 32.16 15.9047C32.16 18.9821 32.09 21.1503 32.0201 22.4792C31.9502 23.808 31.7403 24.9271 31.4606 25.9063C31.0409 27.0953 30.3415 28.1444 29.5022 28.9837C28.6629 29.823 27.6138 30.4524 26.4948 30.8721C25.5156 31.2218 24.3266 31.4316 22.9977 31.5015C21.6688 31.5715 19.5007 31.5715 16.4932 31.5715C13.4158 31.5715 11.2476 31.5715 9.91875 31.5015C8.58987 31.4316 7.47081 31.2218 6.49164 30.8021C5.30264 30.4524 4.25353 29.823 3.41423 28.9837C2.57494 28.1444 1.94547 27.0953 1.52583 25.9063C1.17612 24.9271 0.966298 23.808 0.896357 22.4792C0.826416 21.1503 0.826416 18.9821 0.826416 15.9047C0.826416 12.8972 0.826416 10.7291 0.896357 9.40019C0.966298 8.07131 1.17612 6.88231 1.52583 5.90314C1.94547 4.78408 2.57494 3.73497 3.41423 2.89567C4.25353 2.05638 5.30264 1.35697 6.49164 0.937325C7.47081 0.657561 8.58987 0.447738 9.91875 0.377797C11.2476 0.307856 13.4158 0.237915 16.4932 0.237915C19.5007 0.237915 21.6688 0.307856 22.9977 0.377797C24.3266 0.447738 25.5156 0.657561 26.4948 0.937325C27.6138 1.35697 28.6629 2.05638 29.5022 2.89567C30.3415 3.73497 31.0409 4.78408 31.4606 5.90314C31.7403 6.88231 31.9502 8.07131 32.09 9.40019ZM28.7329 25.1369C29.0126 24.3676 29.1525 23.1086 29.2924 21.3601C29.2924 20.3809 29.3624 18.9122 29.3624 17.0238V14.7856C29.3624 12.8972 29.2924 11.4285 29.2924 10.4493C29.1525 8.70078 29.0126 7.44184 28.7329 6.67249C28.1734 5.27367 27.1242 4.22455 25.7254 3.66502C24.9561 3.38526 23.6971 3.24538 21.9486 3.1055C20.8995 3.1055 19.4307 3.03556 17.6123 3.03556H15.3741C13.4857 3.03556 12.017 3.1055 11.0378 3.1055C9.28928 3.24538 8.03034 3.38526 7.26099 3.66502C5.79223 4.22455 4.81305 5.27367 4.25353 6.67249C3.97376 7.44184 3.76394 8.70078 3.694 10.4493C3.62406 11.4984 3.62406 12.9672 3.62406 14.7856V17.0238C3.62406 18.9122 3.62406 20.3809 3.694 21.3601C3.76394 23.1086 3.97376 24.3676 4.25353 25.1369C4.81305 26.6057 5.86217 27.5849 7.26099 28.1444C8.03034 28.4241 9.28928 28.634 11.0378 28.7039C12.017 28.7738 13.4857 28.7738 15.3741 28.7738H17.6123C19.5007 28.7738 20.9694 28.7738 21.9486 28.7039C23.6971 28.634 24.9561 28.4241 25.7254 28.1444C27.1242 27.5849 28.1734 26.5357 28.7329 25.1369Z"
                      fill="#00AAAD"/>
            </svg>
        </a>
        <a class="f_social" href="https://www.facebook.com/profile.php?id=100086396290429" target="_blank">
            <svg width="20" height="36" viewBox="0 0 20 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path opacity="0.7"
                      d="M18.2712 20.143L19.2504 13.7084H13.0257V9.51198C13.0257 7.69351 13.8649 6.01493 16.6626 6.01493H19.5302V0.489587C19.5302 0.489587 16.9423 0 14.4944 0C9.38872 0 6.03155 3.14735 6.03155 8.74263V13.7084H0.296387V20.143H6.03155V35.8098H13.0257V20.143H18.2712Z"
                      fill="#00AAAD"/>
            </svg>
        </a>
        <a class="f_social" href="https://www.linkedin.com/in/areve3d" target="_blank">
            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path opacity="0.7"
                      d="M7.66061 31.5715V10.6591H1.15609V31.5715H7.66061ZM4.44332 7.79154C5.42249 7.79154 6.33173 7.44184 7.10108 6.67249C7.80049 5.97308 8.22013 5.06385 8.22013 4.01473C8.22013 3.03556 7.80049 2.12632 7.10108 1.35697C6.33173 0.657561 5.42249 0.237915 4.44332 0.237915C3.3942 0.237915 2.48497 0.657561 1.78556 1.35697C1.01621 2.12632 0.666504 3.03556 0.666504 4.01473C0.666504 5.06385 1.01621 5.97308 1.78556 6.67249C2.48497 7.44184 3.3942 7.79154 4.44332 7.79154ZM32.0001 31.5715V20.1012C32.0001 16.8839 31.5105 14.5059 30.6013 12.9672C29.3423 11.0788 27.2441 10.0996 24.2366 10.0996C22.6979 10.0996 21.439 10.5192 20.3199 11.2187C19.2708 11.8481 18.5015 12.6175 18.0818 13.5267H18.0119V10.6591H11.7871V31.5715H18.2217V21.2202C18.2217 19.6116 18.4315 18.3526 18.9211 17.5133C19.4806 16.3943 20.5298 15.8348 22.0685 15.8348C23.5372 15.8348 24.5164 16.4642 25.0759 17.7232C25.3557 18.4925 25.4956 19.6815 25.4956 21.3601V31.5715H32.0001Z"
                      fill="#00AAAD"/>
            </svg>
        </a>
    </div>
</div>
</div>

<div class="hr_foot"></div>
<div class="wrapper">
<div class="d_flex sto_ f_padds">
    <div class="d_flex foot_bar">
        <a href="{{url('/about-areve3d')}}" class="f_a_bar">About Us</a>
        <a href="{{url('/blog')}}" class="f_a_bar">Blog</a>
        <a href="{{url('/terms/conditions')}}" class="f_a_bar">Terms & Conditions</a>
        <a @auth href="{{route('message.index')}}" @endauth class=" @guest login_btn @endguest f_a_bar">Complain & suggestions</a>
        <a href="{{url('/privacy/policy')}}" class="f_a_bar">Privacy Policy</a>

        <a @auth href="{{route('message.index')}}" @endauth class=" @guest login_btn @endguest  our_mail d_flex">
            <svg width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path opacity="0.7"
                      d="M17.7347 0.661377H1.83462C0.802647 0.661377 0 1.50225 0 2.496V13.5037C0 14.5357 0.802647 15.3384 1.83462 15.3384H17.7347C18.7284 15.3384 19.5693 14.5357 19.5693 13.5037V2.496C19.5693 1.50225 18.7284 0.661377 17.7347 0.661377ZM17.7347 2.496V4.06307C16.8556 4.78928 15.4796 5.85947 12.5748 8.15275C11.925 8.64963 10.6637 9.87271 9.78465 9.83449C8.86734 9.87271 7.60604 8.64963 6.95628 8.15275C4.05146 5.85947 2.67549 4.78928 1.83462 4.06307V2.496H17.7347ZM1.83462 13.5037V6.43279C2.67549 7.12078 3.93679 8.11453 5.80964 9.60516C6.65051 10.2549 8.14114 11.7073 9.78465 11.6691C11.3899 11.7073 12.8424 10.2549 13.7214 9.60516C15.5943 8.11453 16.8556 7.12078 17.7347 6.43279V13.5037H1.83462Z"
                      fill="#717171"/>
            </svg>
            <span class="f_a_bar">Contact with us</span>
        </a>
    </div>

    <div class="d_flex card_col">
        <a class="master_cart">
            <img src="{{asset(asset_path('new/img/mastercart.jpg'))}}" alt="">
        </a>
        <a class="visa_">
            <img src="{{asset(asset_path('new/img/visa.jpg'))}}" alt="">
        </a>
    </div>
</div>
</div>
</footer>
<input type="hidden" name="filterCatCol" class="filterCatCol" value="0">
</section>

<script  src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"
integrity="sha512-XtmMtDEcNz2j7ekrtHvOVR4iwwaD6o/FUJe6+Zq+HgcCsk3kj4uSQQR8weQ2QVj1o0Pk6PwYLohm206ZzNfubg=="
crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script async src="{{asset(asset_path('new/js/slide.js'))}}"></script>
<script async src="{{asset(asset_path('new/js/login.js'))}}"></script>
<script async src="{{asset(asset_path('new/js/product.js'))}}"></script>
<script async src="{{asset(asset_path('new/js/script.js'))}}"></script>
@yield('js')
<script>
 function likes(id,type=1) {
let data = {
'id': id,
    type
}

    $.post('{{route('like')}}', data, function (data) {
    console.log(data)
$('.likes' + id).text(data)

    return data.id;

})

}

setTimeout(function (){
    $('.category_box_input').removeAttr('readonly')
}, 1000)

</script>
<script type="text/javascript">


$('.edit_addr').click(function (){
$('.add_addr').attr('data-billing',$(this).attr('data-ag'))
@php
$prev_address = \Modules\Customer\Entities\CustomerAddress::where('customer_id', auth()->id())->where('is_billing_default', 1)->first();
@endphp
@if($prev_address)
$('#name').val('{{$prev_address->name}}')
$('#email').val('{{$prev_address->email}}')
$('#lastname').val('{{$prev_address->lastname}}')
$('#street').val('{{$prev_address->street}}')
$('#phone').val('{{$prev_address->phone}}')
$('#country').val('{{$prev_address->country}}')
$('#state').val('{{$prev_address->state}}')
$('#city').val('{{$prev_address->city}}')
$('#zip_code').val('{{$prev_address->postal_code}}')
@endif
})


$(document).on('click', '#payment_btn_triggerr', function(e){
e.preventDefault()

let data = {
    address_id: $('.add_addr').data('billing'),
     name: $('#name').val(),
     email: $('#email').val(),
     lastname: $('#lastname').val(),
     street: $('#street').val(),
     phone: $('#phone').val(),
     country: $('#country').val(),
     state: $('#state').val(),
     city: $('#city').val(),
     postal_code: $('#zip_code').val(),
    _token: $('#token').val()
}

$.post("{{route('frontend.checkout.billing.address.store')}}", data, function (response) {
    console.log('lava')
    location.reload()
    // pay_button_ameria($('.total_padd').data('total'),$('.total_padd').data('ids'));
}).fail(function (response) {
    console.log(response.responseJSON.errors)
    if(response.responseJSON.errors.name){
        $('#name').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
    }
    if(response.responseJSON.errors.lastname){
        $('#lastname').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
    }
    if(response.responseJSON.errors.street){
        $('#street').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
    }
    if(response.responseJSON.errors.email){
        $('#email').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
    }
    if(response.responseJSON.errors.phone){
        $('#phone').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
    }
    if(response.responseJSON.errors.country){
        $('#country').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
    }
    if(response.responseJSON.errors.state){
        $('#state').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
    }
    if(response.responseJSON.errors.city){
        $('#city').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
    }
    if(response.responseJSON.errors.postal_code){
        $('#zip_code').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
    }

    return false;
});
});


(function ($) {
"use strict";

var filterType = [];

$(document).ready(function () {
{{--$(document).on('click', '.getFilterUpdateByIndex', function (event) {--}}
{{--    var sort_by = $(this).attr('data-id');--}}
{{--    var paginate_by_id = $(this).attr('data-href');--}}
{{--    let prev_stat = 0;--}}
{{--    console.log(sort_by)--}}

{{--    if ('{{request()->has('slug1')}}') prev_stat = 1;--}}
{{--    console.log(prev_stat)--}}
{{--    if (prev_stat == 0) {--}}
{{--        if(paginate_by_id){--}}
{{--            var url = paginate_by_id;--}}
{{--        }else {--}}
{{--            var url = '{{route('frontend.category.fetch-data')}}';--}}
{{--        }--}}

{{--    } else {--}}
{{--        if(paginate_by_id){--}}
{{--            var url = paginate_by_id;--}}
{{--        }else {--}}
{{--            var url = '{{route('frontend.category_page_product_filter_page')}}';--}}
{{--        }--}}
{{--    }--}}
{{--    --}}{{--var url = '{{route('frontend.category.fetch-data')}}';--}}
{{--    $.get(url, {sort_by: sort_by, paginate: 16, 'slug1': '{{request()->slug1}}'}, function (data) {--}}
{{--        $('#dataWithPaginate').html(data);--}}
{{--    });--}}
{{--});--}}

{{--@if(request()->has('slug'))--}}
{{--let type = $('.{{request()->slug}}').data('id');--}}
{{--let el = $('.{{request()->slug}}').data('value');--}}
{{--getProductByChoice(type, el);--}}
{{--@endif--}}

{{--$(document).on('click', '.getProductByChoice', function (event) {--}}
{{--    let type = $(this).data('id');--}}
{{--    let el = $(this).data('value');--}}
{{--    console.log('aaaaaaaaaaa')--}}
{{--    getProductByChoice(type, el);--}}
{{--});--}}



{{--$(document).on('click', '.page-item a', function (event) {--}}
{{--    event.preventDefault();--}}
{{--    let page = $(this).attr('href').split('page=')[1];--}}

{{--    var filterStatus = $('.filterCatCol').val();--}}
{{--    if (filterStatus == 0) {--}}
{{--        fetch_data(page);--}}
{{--    } else {--}}
{{--        fetch_filter_data(page);--}}
{{--    }--}}

{{--});--}}

{{--function fetch_data(page) {--}}
{{--    $('#pre-loader').show();--}}
{{--    var paginate = $('#paginate_by').val();--}}
{{--    var sort_by = $('#product_short_list').val();--}}
{{--    if (sort_by != null && paginate != null) {--}}
{{--        var url = "{{route('frontend.category.fetch-data')}}" + '?sort_by=' + sort_by + '&paginate=' + paginate + '&page=' + page;--}}
{{--    } else if (sort_by == null && paginate != null) {--}}
{{--        var url = "{{route('frontend.category.fetch-data')}}" + '?paginate=' + paginate + '&page=' + page;--}}
{{--    } else {--}}
{{--        var url = "{{route('frontend.category.fetch-data')}}" + '?page=' + page;--}}
{{--    }--}}
{{--    if (page != 'undefined') {--}}
{{--        $.ajax({--}}
{{--            url: url,--}}
{{--            success: function (data) {--}}
{{--                $('#dataWithPaginate').html(data);--}}
{{--                $('#product_short_list').niceSelect();--}}
{{--                $('#paginate_by').niceSelect();--}}
{{--                $('#pre-loader').hide();--}}
{{--            }--}}
{{--        });--}}
{{--    } else {--}}
{{--        toastr.warning("{{__('defaultTheme.maximum_quantity_limit_exceed')}}", "{{__('common.warning')}}");--}}
{{--    }--}}

{{--}--}}

{{--function fetch_filter_data(page) {--}}
{{--    $('#pre-loader').show();--}}
{{--    var paginate = $('#paginate_by').val();--}}
{{--    var sort_by = $('#product_short_list').val();--}}
{{--    var seller_id = $('#seller_id').val();--}}
{{--    if (sort_by != null && paginate != null) {--}}
{{--        var url = "{{route('frontend.category_page_product_filter_page')}}" + '?sort_by=' + sort_by + '&paginate=' + paginate + '&page=' + page;--}}
{{--    } else if (sort_by == null && paginate != null) {--}}
{{--        var url = "{{route('frontend.category_page_product_filter_page')}}" + '?paginate=' + paginate + '&page=' + page;--}}
{{--    } else {--}}
{{--        var url = "{{route('frontend.category_page_product_filter_page')}}" + '?page=' + page;--}}
{{--    }--}}
{{--    if (page != 'undefined') {--}}
{{--        $.ajax({--}}
{{--            url: url,--}}
{{--            success: function (data) {--}}
{{--                $('#dataWithPaginate').html(data);--}}
{{--                $('#product_short_list').niceSelect();--}}
{{--                $('#paginate_by').niceSelect();--}}
{{--                $('.filterCatCol').val(1);--}}
{{--                $('#pre-loader').hide();--}}
{{--            }--}}
{{--        });--}}
{{--    } else {--}}
{{--        toastr.warning("{{__('defaultTheme.this_is_undefined')}}", "{{__('common.warning')}}");--}}
{{--    }--}}

{{--}--}}

{{--let minimum_price = 0;--}}
{{--let maximum_price = 0;--}}
{{--let price_range_gloval = 0;--}}
{{--$(document).on('change', '.js-range-slider-0', function (event) {--}}
{{--    var price_range = $(this).val().split(';');--}}
{{--    minimum_price = price_range[0];--}}
{{--    maximum_price = price_range[1];--}}
{{--    price_range_gloval = price_range;--}}
{{--    myEfficientFn();--}}
{{--});--}}
{{--var myEfficientFn = debounce(function () {--}}
{{--    $('#min_price').val(minimum_price);--}}
{{--    $('#max_price').val(maximum_price);--}}
{{--    getProductByChoice("price_range", price_range_gloval);--}}
{{--}, 500);--}}

function debounce(func, wait, immediate) {
    var timeout;
    return function () {
        var context = this, args = arguments;
        var later = function () {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
};

{{--function getProductByChoice(type, el) {--}}
{{--    var objNew = {filterTypeId: type, filterTypeValue: [el]};--}}

{{--    var objExistIndex = filterType.findIndex((objData) => objData.filterTypeId === type);--}}
{{--    if (type == "cat") {--}}
{{--        $.post('{{ route('frontend.get_brands_by_type') }}', {--}}
{{--            _token: '{{ csrf_token() }}',--}}
{{--            id: el,--}}
{{--            type: type--}}
{{--        }, function (data) {--}}
{{--            $('.brandDiv').html(data);--}}
{{--        });--}}
{{--    }--}}
{{--    if (type == "cat" || type == "brand") {--}}
{{--        $.post('{{ route('frontend.get_colors_by_type') }}', {--}}
{{--            _token: '{{ csrf_token() }}',--}}
{{--            id: el,--}}
{{--            type: type--}}
{{--        }, function (data) {--}}
{{--            $('.colorDiv').html(data);--}}
{{--        });--}}
{{--        $.post('{{ route('frontend.get_attribute_by_type') }}', {--}}
{{--            _token: '{{ csrf_token() }}',--}}
{{--            id: el,--}}
{{--            type: type--}}
{{--        }, function (data) {--}}
{{--            $('.attributeDiv').html(data);--}}
{{--        });--}}
{{--    }--}}
{{--    if (objExistIndex < 0) {--}}
{{--        filterType.push(objNew);--}}
{{--    } else {--}}
{{--        var objExist = filterType[objExistIndex];--}}
{{--        if (objExist && objExist.filterTypeId == "price_range") {--}}
{{--            objExist.filterTypeValue.pop(el);--}}
{{--        }--}}
{{--        if (objExist && objExist.filterTypeId == "rating") {--}}
{{--            objExist.filterTypeValue.pop(el);--}}
{{--        }--}}
{{--        if (objExist.filterTypeValue.includes(el)) {--}}
{{--            objExist.filterTypeValue.pop(el);--}}
{{--        } else {--}}
{{--            objExist.filterTypeValue.push(el);--}}
{{--        }--}}
{{--    }--}}
{{--    // $('#pre-loader').show();--}}
{{--    $.post('{{ route('frontend.category_page_product_filter') }}', {--}}
{{--        _token: '{{ csrf_token() }}',--}}
{{--        filterType: filterType--}}
{{--    }, function (data) {--}}
{{--        $('#dataWithPaginate').html(data);--}}
{{--        // $('#product_short_list').niceSelect();--}}
{{--        // $('#paginate_by').niceSelect();--}}
{{--        $('.filterCatCol').val(1);--}}
{{--        // $('#pre-loader').hide();--}}
{{--    });--}}
{{--}--}}
});
})(jQuery);

//    shearh button
$(".category_box_input").keypress(function (e) {
console.log('textareaclass')
if(e.which === 13 && !e.shiftKey) {
e.preventDefault();
var input_data = $('.category_box_input').val();
var genUrl = "{{url('/category')}}" + '/' + input_data + '?item=search';
console.log(input_data)
location.replace(genUrl);
}
});




$(document).on('click', '#search_form', function (event) {

// event.preventDefault();
var input_data = $('.category_box_input').val();
var genUrl = "{{url('/category')}}" + '/' + input_data + '?item=search';
console.log(input_data)
location.replace(genUrl);
});

$(document).on('click', '#modalSubscribeBtn', function (event) {
event.preventDefault();


$("#modalSubscribeBtn").prop('disabled', true);
$('#modalSubscribeBtn').text('{{ __("common.submitting") }}');


let formData = {
'email': $('#modalSubscription_email_id').val(),
'_token': "{{ csrf_token() }}"
}
console.log(formData)
// $('.message_div_modal').html('');
// $('.message_div_modal').addClass('d-none');
$.ajax({
url: "{{ route('subscription.store') }}",
type: "POST",
// cache: false,
// contentType: false,
// processData: false,
data: formData,
success: function (response) {
    {{--toastr.success("{{__('defaultTheme.subscribe_successfully')}}", "{{__('common.success')}}");--}}
    $("#modalSubscribeBtn").prop('disabled', false);
    $('#modalSubscribeBtn').text("{{ __('defaultTheme.subscribe') }}");
    $('#modalSubscription_email_id').val('');
    $("#subscriptionModal").hide();
    $('.response').text('{{__('common.newsletter_success')}}');

    setTimeout(function (){
        $('.response').text('');
    }, 2000);
},
error: function (response) {
    // $('.message_div_modal').removeClass('d-none');
    {{--$('.message_div_modal').addClass('error_color');--}}
    {{--$('.message_div_modal').html(`--}}
    {{--            <span class="text-danger">${response.responseJSON.errors.email}</span>--}}
    {{--        `);--}}
    $("#modalSubscribeBtn").prop('disabled', false);
    $('#modalSubscribeBtn').text("{{ __('defaultTheme.subscribe') }}");
    $('.response').text('{{__('common.newsletter_error')}}');
}
});
});


//add product
$(document).on('click', ".addToCartFromThumnail:not(.disabled)", function () {
// event.preventDefault();
// var className = this.className;
// $("."+className).prop("disabled", true);
  $(this).addClass("disabled");
if ($(this).data('producttype') == 1) {
let is_stock_manage = $(this).data('stock_manage');
let stock = $(this).data('stock');
let min_qty = $(this).data('min_qty');

if (is_stock_manage == 1 && stock > min_qty) {
    addToCart($(this).attr('data-product-sku'), $(this).attr('data-seller'), min_qty, $(this).attr('data-base-price'), 0, 'product')
    // $("."+className).prop("disabled", false);

} else if (is_stock_manage == 0) {
    addToCart($(this).attr('data-product-sku'), $(this).attr('data-seller'), min_qty, $(this).attr('data-base-price'), 0, 'product')
    // $("."+className).prop("disabled", false);
} else {
    {{--toastr.warning("{{__('defaultTheme.out_of_stock')}}");--}}
    // $("."+className).prop("disabled", false);
}

} else {
// $('#pre-loader').show();
$.post('{{ route('frontend.item.show_in_modal') }}', {
    _token: '{{ csrf_token() }}',
    product_id: $(this).attr('data-product-id')
}, function (data) {
});
}
});

function addToCart(product_sku_id, seller_id, qty, price, shipping_type, type) {
console.log(product_sku_id, seller_id, qty, price, shipping_type, type)
{{--$('#add_to_cart_btn').prop('disabled',true);--}}
{{--$('#add_to_cart_btn').html("{{__('defaultTheme.adding')}}");--}}
var formData = new FormData();
formData.append('_token', "{{ csrf_token() }}");
formData.append('price', price);
formData.append('qty', qty);
formData.append('product_id', product_sku_id);
formData.append('seller_id', seller_id);
formData.append('shipping_method_id', shipping_type);
formData.append('type', type);
// $('#pre-loader').removeClass('d-none');

// var base_url = $('#url').val();
$.ajax({
url: '{{url('/')}}' + "/cart/store",
type: "POST",
cache: false,
contentType: false,
processData: false,
data: formData,
success: function (response) {
    if (response == 'out_of_stock') {
        {{--toastr.error('No more product to buy.');--}}
        {{--$('#pre-loader').addClass('d-none');--}}
        {{--$('#add_to_cart_btn').prop('disabled',false);--}}
        {{--$('#add_to_cart_btn').html("{{__('defaultTheme.add_to_cart')}}");--}}
    } else {
        location.reload();
    }
},
error: function (response) {
    console.log('lav  chi')
}
});
}

//profile

$('.toggle_input').click(function (){
$.post( "{{route('store_notif')}}",{notification_send : $(this).is(':checked')})
})

@if(isset(request()->all()['a']))
@if(request()->all()['a'] == 'purchases')
$('.prof_menu_sp').removeClass('prof_menu_sp_active');
$('.my_purchases').addClass('prof_menu_sp_active');
$('.for_edit_profile').hide().removeClass('for_edit_profile_active');
$('.for_my_purchases').show(200).addClass('for_my_purchases_active');
$('.for_my_comments').hide().removeClass('for_my_comments_active');
$('.for_settings').hide().removeClass('for_settings_active');
$('.for_my_favorites').hide().removeClass('for_my_favorites_active');
@endif
@if(request()->all()['a'] == 'comments')
$('.prof_menu_sp').removeClass('prof_menu_sp_active');
$('.my_comments').addClass('prof_menu_sp_active');
$('.for_edit_profile').hide().removeClass('for_edit_profile_active');
$('.for_my_purchases').hide().removeClass('for_my_purchases_active');
$('.for_my_comments').show(200).addClass('for_my_comments_active');
$('.for_my_favorites').hide().removeClass('for_my_favorites_active');
$('.for_settings').hide().removeClass('for_settings_active');
@endif
@if(request()->all()['a'] == 'favorites')
$('.prof_menu_sp').removeClass('prof_menu_sp_active');
$('.my_favorites').addClass('prof_menu_sp_active');
$('.for_edit_profile').hide().removeClass('for_edit_profile_active');
$('.for_my_purchases').hide().removeClass('for_my_purchases_active');
$('.for_my_comments').hide().removeClass('for_my_comments_active');
$('.for_settings').hide().removeClass('for_settings_active');
$('.for_my_favorites').show(200).addClass('for_my_favorites_active');
@endif
@if(request()->all()['a'] == 'Settings')
$('.prof_menu_sp').removeClass('prof_menu_sp_active');
$('.settings_').addClass('prof_menu_sp_active');
$('.for_edit_profile').hide().removeClass('for_edit_profile_active');
$('.for_my_purchases').hide().removeClass('for_my_purchases_active');
$('.for_my_comments').hide().removeClass('for_my_comments_active');
$('.for_my_favorites').hide().removeClass('for_my_favorites_active');
$('.for_settings').show(200).addClass('for_settings_active');
@endif
@endif

//-----------------------------------------profile update info
$(document).on('submit', '#update_info', function (e) {
e.preventDefault();
console.log($(this).serializeArray());
// $('#pre-loader').show();

var formElement = $('#update_info').serializeArray();
var formData = new FormData();
formElement.forEach(element => {
formData.append(element.name, element.value);
});
formData.append('_token', "{{ csrf_token() }}");

let avatar = $('#file')[0].files[0];
console.log(avatar)
if (avatar) {
formData.append('avatar', avatar)
}
console.log(formData.keys)
// basic_info_remove_validate_error();
$.ajax({
url: "{{route('customer.update.info')}}",
type: "POST",
cache: false,
contentType: false,
processData: false,
data: formData,
success: function (response) {
    location.reload()
    // $('.info_error').text('');
    $('#editmail').text(response.email);
    if (avatar) {
        var image_path = "{{asset(asset_path(''))}}" + "/" + response.avatar;
        $('.customer_img img').attr('src', image_path);
    }
    $('#file').val('');
    {{--toastr.success("{{__('common.updated_successfully')}}","{{__('common.success')}}");--}}
    // $('#pre-loader').hide();
},
error: function (response) {
    console.log(response)
        {{--toastr.error(response.responseJSON.error, "{{__('common.error')}}");--}}
        console.log(response.responseJSON.errors)
    if(response.responseJSON.errors.name){
        $('#namee').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
    }
    if(response.responseJSON.errors.lastname){
        $('#lastnamee').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
    }
    if(response.responseJSON.errors.street){
        $('#streete').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
    }
    if(response.responseJSON.errors.email){
        $('#emaile').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
    }
    if(response.responseJSON.errors.phone){
        $('#phonee').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
    }
    if(response.responseJSON.errors.country){
        $('#countrye').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
    }
    if(response.responseJSON.errors.state){
        $('#statee').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
    }
    if(response.responseJSON.errors.city){
        $('#citye').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
    }
    if(response.responseJSON.errors.postal_code){
        $('#zip_codee').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
    }
        // $('#pre-loader').addClass('d-none');
        // return false;

    // basic_info_update_validate_error(response);
    {{--toastr.error("{{__('common.error_message')}}", "{{__('common.success')}}");--}}
    // $('#pre-loader').hide();
}

});
});

//--------------------password
$(document).on('click', '.change_password', function (e) {
e.preventDefault();
$('#pre-loader').show();
var formData = new FormData();
formData.append('_token', "{{ csrf_token() }}");
formData.append('password', $('#newpass').val());
formData.append('password_confirmation', $('#confirmpass').val());
console.log(formData)
$.ajax({
url: "{{route('change_password')}}",
type: "POST",
cache: false,
contentType: false,
processData: false,
data: formData,
success: function (response) {
    $('#newpass').css({
        'border-color': 'white',
        'background': 'rgba(113, 113, 113, 0.1)'
    });

    $('#confirmpass').css({
        'border-color': 'white',
        'background': 'rgba(113, 113, 113, 0.1)'
    });

    window.location.href = '/profile?a=Settings';

    // $('.error').text('');
    // $("#update_pass").trigger("reset");
    {{--toastr.success(response, "{{__('common.success')}}");--}}
    // $('#pre-loader').hide();

},
error: function (response) {
    console.log(response.responseJSON.error)
    $('#newpass').css({
        'border-color': '#CE3C5C',
        'background': 'rgba(206, 60, 92, 0.1)'
    });

    $('#confirmpass').css({
        'border-color': '#CE3C5C',
        'background': 'rgba(206, 60, 92, 0.1)'
    });

    {{--if(response.responseJSON.error){--}}
    {{--    toastr.error(response.responseJSON.error ,"{{__('common.error')}}");--}}
    {{--    $('#pre-loader').addClass('d-none');--}}
    {{--    return false;--}}
    {{--}--}}
    {{--$('.error').text('');--}}
    {{--if (response.responseJSON.errors.current_password) {--}}
    {{--    $('.validation-old-pass-error').text(response.responseJSON.errors.current_password);--}}
    {{--}--}}
    {{--if (response.responseJSON.errors.new_password) {--}}
    {{--    $('.validation-new-pass-error').text(response.responseJSON.errors.new_password);--}}
    {{--}--}}
    {{--if (response.responseJSON.errors.new_password_confirmation) {--}}
    {{--    $('.validation-new-pass-confirm-error').text(response.responseJSON.errors.new_password_confirmation);--}}
    {{--}--}}

    {{--toastr.error("{{__('common.error_message')}}" ,"{{__('common.error')}}");--}}
    {{--$('#pre-loader').hide();--}}
}
});

});

$(document).on('click', '.change_email', function (e) {
e.preventDefault();

var formData = new FormData();
formData.append('_token', "{{ csrf_token() }}");
formData.append('password', $('#mailpass').val());
formData.append('email', $('#email_').val());
console.log(formData)
$.ajax({
url: "{{route('change_email')}}",
type: "POST",
cache: false,
contentType: false,
processData: false,
data: formData,
success: function (response) {
    $('#mailpass').css({
        'border-color': 'white',
        'background': 'rgba(113, 113, 113, 0.1)'
    });

    $('#email_').css({
        'border-color': 'white',
        'background': 'rgba(113, 113, 113, 0.1)'
    });

    window.location.href = '/profile?a=Settings';

    // $('.error').text('');
    // $("#update_pass").trigger("reset");
    {{--toastr.success(response, "{{__('common.success')}}");--}}
    // $('#pre-loader').hide();

},
error: function (response) {
    console.log(response.responseJSON.errors)
    $('#mailpass').css({
        'border-color': '#CE3C5C',
        'background': 'rgba(206, 60, 92, 0.1)'
    });

    $('#email_').css({
        'border-color': '#CE3C5C',
        'background': 'rgba(206, 60, 92, 0.1)'
    });

    {{--if(response.responseJSON.error){--}}
    {{--    toastr.error(response.responseJSON.error ,"{{__('common.error')}}");--}}
    {{--    $('#pre-loader').addClass('d-none');--}}
    {{--    return false;--}}
    {{--}--}}
    {{--$('.error').text('');--}}
    {{--if (response.responseJSON.errors.current_password) {--}}
    {{--    $('.validation-old-pass-error').text(response.responseJSON.errors.current_password);--}}
    {{--}--}}
    {{--if (response.responseJSON.errors.new_password) {--}}
    {{--    $('.validation-new-pass-error').text(response.responseJSON.errors.new_password);--}}
    {{--}--}}
    {{--if (response.responseJSON.errors.new_password_confirmation) {--}}
    {{--    $('.validation-new-pass-confirm-error').text(response.responseJSON.errors.new_password_confirmation);--}}
    {{--}--}}

    {{--toastr.error("{{__('common.error_message')}}" ,"{{__('common.error')}}");--}}
    {{--$('#pre-loader').hide();--}}
}
});
});


function delete_notif(id){
let data = {'id':id}
$.post("/delete/notif", data)
.done(function (data) {
    location.reload()
    // console.log(location.reload())
    // window.location.href = '/product/'+slug+'?comment';
    // console.log(slug)
})
.fail(function (xhr, textStatus, errorThrown) {
    // if(to_user_id){
    //     $('.textareaclass').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
    // }else {
    //     console.log(xhr.responseJSON.errors)
    //     $('#textareaa').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
    // }
    // ;
});
}

//comments
function comment_store(product_id, slug, to_user_id, text, id = null) {
    let data = {}
    if ('{{auth()->user()}}') {
        if (to_user_id) {
            data = {
                product_id,
                to_user_id,
                'user_id': '{{auth()->id()}}',
                text
            }
        } else {
            data = {
                product_id,
                'user_id': '{{auth()->id()}}',
                'text': text ?? $('#textareaa').val(),
                'like_id': id
            }
        }

        $.post("/store_comment", data)
            .done(function (data) {
                const productUrl = window.location.href.split('#')[0];
                window.location.href = productUrl + '#comments';
                window.location.reload();
            })
            .fail(function (xhr, textStatus, errorThrown) {
                if (to_user_id) {
                    $('.textareaclass').css('border-color', 'rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
                } else {
                    console.log(xhr.responseJSON.errors)
                    $('#textareaa1').css('border-color', 'rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
                }
            });
    }

}

function review_store(product_id, slug, text, is_positive_like) {
    let data = {}
    if ('{{auth()->user()}}') {
        data = {
            product_id,
            'user_id': '{{auth()->id()}}',
            'text': text,
            'is_positive_like': is_positive_like,
            'product_url':  window.location.href.split('?')[0]
        }

        $.post("/store_review", data)
            .done(function (data) {
                const productUrl = window.location.href.split('#')[0];
                window.location.href = productUrl + '#reviews';
                window.location.reload();
            })
            .fail(function (xhr, textStatus, errorThrown) {
                $('#textareaa1').css('border-color', 'rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
            });
    }
}

function comment_delete(id) {
let data = {
    id
}
$.post("/delete_comment", data)
    .done(function (data) {
        location.reload()
        // window.location.href = '/';
        console.log(data)
    })
    .fail(function (xhr, textStatus, errorThrown) {
        console.log(xhr.responseJSON.errors);
    });
}

//add to wishlist
$(document).on('click', '.add_to_wishlist', function (event) {
event.preventDefault();
let product_id = $(this).data('product_id');
let seller_id = $(this).data('seller_id');
let is_login = '{{auth()->check()}}';
let type = 'product';

if($(this).data('wish')){
$.post('{{ route('frontend.wishlist.remove') }}', {
      'id' : $(this).data('wish')
},function (){
    location.reload()
    }
)
}else {
if (is_login == '1') {
    console.log(product_id, seller_id, type)
    addToWishlist(product_id, seller_id, type);
    // $(this).addClass('is_wishlist');
} else {
    $('.login_btn').click()
    {{--toastr.warning("{{__('defaultTheme.please_login_first')}}","{{__('common.warning')}}");--}}
}
}


});


// add to wishlist
function addToWishlist(seller_product_id, seller_id, type) {
{{--$('#wishlist_btn').addClass('wishlist_disabled');--}}
{{--$('#wishlist_btn').html("{{__('defaultTheme.adding')}}");--}}
{{--$('#pre-loader').show();--}}

$.post('{{ route('frontend.wishlist.store') }}', {
_token: '{{ csrf_token() }}',
seller_product_id: seller_product_id,
seller_id: seller_id,
type: type
}, function (data) {
console.log(data)
location.reload();
{{--if(data == 1){--}}
{{--    $('.add_to_wishlist').css('background-color','red')--}}
{{--    toastr.success("{{__('defaultTheme.successfully_added_to_wishlist')}}","{{__('common.success')}}");--}}
{{--    $('#wishlist_btn').removeClass('wishlist_disabled');--}}
{{--    $('#wishlist_btn').html("{{__('defaultTheme.add_to_wishlist')}}");--}}
{{--}else if(data == 3){--}}
{{--    toastr.warning("{{__('defaultTheme.product_already_in_wishList')}}","{{__('defaultTheme.thanks')}}");--}}
{{--    $('#wishlist_btn').removeClass('wishlist_disabled');--}}
{{--    $('#wishlist_btn').html("{{__('defaultTheme.add_to_wishlist')}}");--}}
{{--}--}}
{{--else{--}}
{{--    toastr.error("{{__('common.error_message')}}","{{__('common.error')}}");--}}
{{--    $('#wishlist_btn').removeClass('wishlist_disabled');--}}
{{--    $('#wishlist_btn').html("{{__('defaultTheme.add_to_wishlist')}}");--}}
{{--}--}}
{{--$('#pre-loader').hide();--}}
});

}


//delete  card
$(document).on('click', '.cart_item_delete_btn', function (event) {
event.preventDefault();
let unique_id = $(this).data('unique_id');
let product_id = $(this).data('product_id');
let id = $(this).data('id');
console.log(unique_id, product_id, id)
console.log(window.location.pathname)
cartProductDelete(id, product_id, unique_id);
});

function cartProductDelete(id, p_id, btn_id) {
    var formData = new FormData();
    formData.append('_token', "{{ csrf_token() }}");
    formData.append('id', id);
    formData.append('p_id', p_id);

    $.ajax({
        url: '{{url('/')}}' + "/cart/delete",
        type: "POST",
        cache: false,
        contentType: false,
        processData: false,
        data: formData,
        success: function (response) {
            window.location.pathname === '/checkout' ? window.location.pathname = '/cart' : location.reload()
        },
        error: function (response) {
            console.log('error')
        }
    });
}

function couponApply(clickedButton, total) {
    let coupon_code = $(clickedButton).parent().find('.coupon_code').val();

    if (coupon_code) {
        $('#pre-loader').show();

        let formData = new FormData();
        formData.append('_token', "{{ csrf_token() }}");
        formData.append('coupon_code', coupon_code);
        formData.append('shopping_amount', total);
        $.ajax({
            url: '{{route('frontend.checkout.coupon-apply')}}',
            type: "POST",
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: function (response) {
                if (response.error) {
                    console.log(response.error)
                    $('.coupon_code').css('border-color', 'rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
                    $('#pre-loader').hide();
                } else {
                    location.reload()
                }
            },
            error: function (response) {
                $('.coupon_code').css('border-color', 'rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
            }
        });
    } else {
        $('.coupon_code').css('border-color', 'rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
    }
}

$(document).on('click', '#coupon_delete', function (event) {
event.preventDefault();
couponDelete();
});


//reset password
$('.forget_password_button').click(()=>{
$.post("/password/email", {"_token": "{{ csrf_token() }}" , 'email':$('.forget_password').val()} )
.done( function(data) {
    if(data.code == 1){
        $.post('/parol/reset').then(function (){
            window.location.href = '/';
        })
    }
})
.fail( function(xhr, textStatus, errorThrown) {

    Object.keys(xhr.responseJSON.errors).forEach((e)=>{
        if(e == 'email'){
            $('.forget_password').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
        }
    })
    console.log(xhr.responseJSON.errors);
});
})



function couponDelete() {
$('#pre-loader').show();
let base_url = $('#url').val();
let url = base_url + '/checkout/coupon-delete';
$.get(url, function (response) {
$('#mainDiv').html(response.MainCheckout);
$('#pre-loader').hide();
toastr.success("{{__('defaultTheme.coupon_deleted_successfully')}}", "{{__('common.success')}}");
});
}

$(document).ready(function (){
    @if(isset(auth()->user()->id) && auth()->user()->id == 1)
        document.querySelector('.category_box_input').value = "";
    @endif
});

</script>

</body>
</html>
