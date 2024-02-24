@php
    use App\Services\CartService;
    use App\Models\Wishlist;
    use App\Models\Comment;
    use App\Services\ProductService;
    use App\Models\Review;
    use Modules\Seller\Entities\SellerProduct;
    use App\Http\Controllers\ExchangeController;
@endphp

@extends('frontend.default.layouts.newApp')

@section('title')
    {{$product->product->product_name}}
@endsection

@section('share_meta')
    @php
        if($product->product->scheme_markup){
            echo '<script type="application/ld+json">';
            echo $product->product->scheme_markup;
            echo '</script>';
        }
    @endphp

    <meta name="title" content="{{$product->product->meta_title}}"/>
    <meta name="description" content="{{$product->product->meta_description}}"/>
    <meta property="og:title" content="{{$product->product->meta_title}}"/>
    <meta property="og:description" content="{{$product->product->meta_description}}"/>
    <meta property="og:url" content="{{URL::full()}}"/>
    <meta property="og:image"
          content="@if ($product->thum_img != null){{showImage($product->thum_img)}}@else{{showImage($product->product->thumbnail_image_source)}}@endif"/>
    <meta property="og:image:width" content="400"/>
    <meta property="og:image:height" content="300"/>
    <meta property="og:image:alt" content="{{$product->product->product_name}}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:locale" content="en_EN"/>
    <meta name="keywords" content="{{$product->product->meta_keyword}}">
@endsection

@section('content')
    <main>
        @include('frontend.default.includes.mainInclude')

        <section class="wrapper">
            <div class="d_flex from_to">
                <a class="from_this" href="{{url('/')}}">
                    Home
                </a>
                <span class="slashes">/</span>
                <a class="from_this"
                   href="{{route('frontend.category_slug',['slug' => $product->product->categories[0]->slug])}}">
                    {{$product->product->categories[0]['name']}}
                </a>
                <span class="slashes">/</span>
                <span class="this_page">{{$product->product_name}}</span>
            </div>
            <div class="d_flex product_section">
                @php $images_array = explode(',', $product->product->images_alt); @endphp

                <div class="prod_slide_section d_flex">
                    @if(count($product->product->gallary_images) > 0)
                        <div class="for_prod_slider d_flex">
                            @php
                                $i = 0;
                                $j = 0;
                            @endphp

                            @while($i<4)
                                @if(!empty($product->product->gallary_images[$i]))
                                    @php
                                        $image = $product->product->gallary_images[$i]
                                    @endphp
                                    <div class="prod_mini_">
                                        <img class="prod_mini_img" src="{{showImage($image->images_source)}}"
                                             alt="{{isset($images_array[$i]) ? $images_array[$i] : ''}}">
                                    </div>
                                @endif
                                @php
                                    $i++;
                                @endphp
                            @endwhile

                        </div>
                    @endif
                    <div class="d_flex general_and_mini">
                        <div class="general_prod_img" id='myImg'>
                            <img class="general_big_img"
                                 @if ($product->thum_img != null) src="{{showImage($product->thum_img)}}"
                                 @else src="{{showImage($product->product->thumbnail_image_source)}}"
                                 @endif alt="{{$images_array[0]}}">
                            {{--                            <div class="zoom_prod">--}}
                            {{--                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"--}}
                            {{--                                     xmlns="http://www.w3.org/2000/svg">--}}
                            {{--                                    <path d="M14.0156 9.67969C14.0156 9.41016 13.7461 9.14062 13.4766 9.14062H10.4219V6.08594C10.4219 5.81641 10.1523 5.54688 9.88281 5.54688H8.80469C8.49023 5.54688 8.26562 5.81641 8.26562 6.08594V9.14062H5.21094C4.89648 9.14062 4.67188 9.41016 4.67188 9.67969V10.7578C4.67188 11.0723 4.89648 11.2969 5.21094 11.2969H8.26562V14.3516C8.26562 14.666 8.49023 14.8906 8.80469 14.8906H9.88281C10.1523 14.8906 10.4219 14.666 10.4219 14.3516V11.2969H13.4766C13.7461 11.2969 14.0156 11.0723 14.0156 10.7578V9.67969ZM22.8203 22.707C23.0449 22.5273 23.0449 22.168 22.8203 21.9434L17.3848 16.5078C17.25 16.418 17.1152 16.3281 16.9805 16.3281H16.3965C17.7891 14.7109 18.6875 12.5547 18.6875 10.2188C18.6875 5.09766 14.4648 0.875 9.34375 0.875C4.17773 0.875 0 5.09766 0 10.2188C0 15.3848 4.17773 19.5625 9.34375 19.5625C11.6797 19.5625 13.791 18.709 15.4531 17.3164V17.9004C15.4531 18.0352 15.498 18.1699 15.5879 18.3047L21.0234 23.7402C21.248 23.9648 21.6074 23.9648 21.7871 23.7402L22.8203 22.707ZM16.5312 10.2188C16.5312 14.2168 13.2969 17.4062 9.34375 17.4062C5.3457 17.4062 2.15625 14.2168 2.15625 10.2188C2.15625 6.26562 5.3457 3.03125 9.34375 3.03125C13.2969 3.03125 16.5312 6.26562 16.5312 10.2188Z"--}}
                            {{--                                          fill="#717171"/>--}}
                            {{--                                </svg>--}}
                            {{--                            </div>--}}
                        </div>
                        <div class="d_flex right_mini_imgs">
                            @while($j<3)
                                @if(!empty($product->product->gallary_images[$i]))
                                    @php
                                        $image = $product->product->gallary_images[$i]
                                    @endphp
                                    <div class="prod_mini_">
                                        <img class="prod_mini_img" src="{{showImage($image->images_source)}}"
                                             alt="{{isset($images_array[$i]) ? $images_array[$i] : ''}}">
                                    </div>
                                @endif
                                @php
                                    $j++;
                                    $i++;
                                @endphp
                            @endwhile
                        </div>
                    </div>
                </div>
                <div class="about_prod_model d_flex sto_">
                    <div class="d_flex sto_">
                        <div class="model_id">
                            <span>Model ID:</span>
                            <span>{{$product->skus->first()->sku->sku}}</span>
                        </div>
                        <div class="d_flex eye_cool">
                            @auth
                                @php
                                    if(single_price($product->skus->max('selling_price')) != '$ 0.00'){
                                        $product->viewed++;
                                        $product->save();
                                    }
                                @endphp
                            @endauth
                            <script>
                                function likes(id) {
                                    let data = {
                                        'id': id
                                    }
                                    $.post("{{ route('like') }}", data, function (data) {
                                        $(".likes").text(data);
                                    });
                                }
                            </script>
                            {{--                                @auth--}}
                            {{--                                    <a class="like_post d_flex" tabindex="-1"--}}
                            {{--                                       @auth onclick="likes('{{$product->id}}')" @endauth>--}}
                            {{--                                        <svg width="18" height="18" viewBox="0 0 15 16" fill="none"--}}
                            {{--                                             xmlns="http://www.w3.org/2000/svg">--}}
                            {{--                                            <path d="M14.5625 8.96875C14.8438 8.5 15 8 15 7.40625C15 6.03125 13.8125 4.75 12.3125 4.75H11.1562C11.3125 4.34375 11.4375 3.875 11.4375 3.28125C11.4375 1 10.25 0 8.46875 0C6.53125 0 6.65625 2.96875 6.21875 3.40625C5.5 4.125 4.65625 5.5 4.0625 6H1C0.4375 6 0 6.46875 0 7V14.5C0 15.0625 0.4375 15.5 1 15.5H3C3.4375 15.5 3.84375 15.1875 3.9375 14.7812C5.34375 14.8125 6.3125 16 9.5 16C9.75 16 10 16 10.2188 16C12.625 16 13.6875 14.7812 13.7188 13.0312C14.1562 12.4688 14.375 11.6875 14.2812 10.9375C14.5938 10.375 14.6875 9.6875 14.5625 8.96875ZM12.625 10.6562C13.0312 11.3125 12.6562 12.1875 12.1875 12.4688C12.4375 13.9688 11.625 14.5 10.5312 14.5H9.34375C7.125 14.5 5.65625 13.3438 4 13.3438V7.5H4.3125C5.21875 7.5 6.4375 5.3125 7.28125 4.46875C8.15625 3.59375 7.875 2.09375 8.46875 1.5C9.9375 1.5 9.9375 2.53125 9.9375 3.28125C9.9375 4.5 9.0625 5.0625 9.0625 6.25H12.3125C12.9688 6.25 13.4688 6.84375 13.5 7.4375C13.5 8 13.0938 8.59375 12.7812 8.59375C13.2188 9.0625 13.3125 10.0312 12.625 10.6562ZM2.75 13.5C2.75 13.9375 2.40625 14.25 2 14.25C1.5625 14.25 1.25 13.9375 1.25 13.5C1.25 13.0938 1.5625 12.75 2 12.75C2.40625 12.75 2.75 13.0938 2.75 13.5Z"--}}
                            {{--                                                  fill="#717171"></path>--}}
                            {{--                                        </svg>--}}
                            {{--                                        <span class="likes">{{count(\App\Models\Like::where('product_id',$product->id)->get())}}</span>--}}
                            {{--                                    </a>--}}
                            {{--                                @endauth--}}
                        </div>
                    </div>
                    <h1 class="prod_name">{{$product->product_name}}</h1>
                    <div class="d_flex price_prod">
                        @if($product->hasDeal)
                            @if ($product->product->product_type == 1)
                                {{@$product->skus->first()->selling_price == 0 ? 'Free':single_price(@$product->skus->first()->selling_price)}}
                            @else
                                @if (selling_price($product->skus->min('selling_price'),$product->hasDeal->discount_type,$product->hasDeal->discount) === selling_price($product->skus->max('selling_price'),$product->hasDeal->discount_type,$product->hasDeal->discount))
                                    {{single_price(selling_price($product->skus->min('selling_price'),$product->hasDeal->discount_type,$product->hasDeal->discount))}}
                                @else
                                    {{single_price(selling_price($product->skus->min('selling_price'),$product->hasDeal->discount_type,$product->hasDeal->discount))}}
                                    - {{single_price(selling_price($product->skus->max('selling_price'),$product->hasDeal->discount_type,$product->hasDeal->discount))}}
                                @endif
                            @endif
                        @else
                            @if ($product->product->product_type == 1)

                                @if($product->hasDiscount == 'yes')
                                    <span class="this_moment_price">{{single_price(selling_price(@$product->skus->first()->selling_price,@$product->discount_type,@$product->discount))}}</span>
                                    <span class="sale_for">
                                         @if($product->discount != 0 )
                                            -@if($product->discount_type != 0)
                                                $
                                            @endif {{$product->discount}} @if($product->discount_type == 0)
                                                %
                                            @endif
                                        @endif
                                    </span>
                                @else
                                    <span class="this_moment_price"> {{(@$product->skus->first()->selling_price == 0)?'Free':single_price(@$product->skus->first()->selling_price)}}</span>

                                @endif
                            @else
                                @if (selling_price($product->skus->min('selling_price'),$product->discount_type,$product->discount) == selling_price($product->skus->max('selling_price'),$product->discount_type,$product->discount))
                                    {{single_price(selling_price($product->skus->min('selling_price'),$product->discount_type,$product->discount))}}

                                @else
                                    @if($product->hasDiscount == 'yes')
                                        {{single_price(selling_price($product->skus->min('selling_price'),$product->discount_type,$product->discount))}}

                                    @else
                                        {{single_price(@$product->skus->min('selling_price'),$product->discount_type,$product->discount)}}
                                    @endif -

                                    @if($product->hasDiscount == 'yes')
                                        {{single_price(selling_price(@$product->skus->max('selling_price'),$product->discount_type,$product->discount))}}

                                    @else
                                        {{single_price(@$product->skus->max('selling_price'),$product->discount_type,$product->discount)}}
                                    @endif

                                @endif
                            @endif
                        @endif

                        @if($product->hasDeal || $product->hasDiscount == 'yes')

                            <span class="prev_price">
                            @php
                                if(ExchangeController::getInstance()->needToConvert()){
                                    $convertedPrice = ExchangeController::getInstance()->convertPriceToAMD($product->skus->max('selling_price'), 'USD');
                                    echo $convertedPrice['price'] . ExchangeController::getInstance()->getAMDSymbol();
                                }else{
                                    echo  ExchangeController::getInstance()->getUSDSymbol() .  $product->skus->max('selling_price');
                                }
                            @endphp

                            </span>
                        @endif

                    </div>
                    {{--                    @if($product->skus->max('selling_price') != '0.0')--}}

                    {{--                    <div class="start_block d_flex">--}}

                    {{--                            <span class="star_span" data-value="1" id="1">--}}
                    {{--                                    <svg width="28" height="27" viewBox="0 0 28 27" fill="none"--}}
                    {{--                                         xmlns="http://www.w3.org/2000/svg">--}}
                    {{--                                        <path d="M13.7147 0.878115C13.8045 0.601722 14.1955 0.601722 14.2853 0.878115L17.3004 10.1574C17.3405 10.2811 17.4557 10.3647 17.5857 10.3647H27.3425C27.6332 10.3647 27.754 10.7366 27.5189 10.9075L19.6254 16.6424C19.5203 16.7188 19.4763 16.8542 19.5164 16.9778L22.5315 26.2571C22.6213 26.5335 22.3049 26.7634 22.0698 26.5925L14.1763 20.8576C14.0712 20.7812 13.9288 20.7812 13.8237 20.8576L5.93019 26.5925C5.69508 26.7634 5.37873 26.5335 5.46854 26.2571L8.48358 16.9778C8.52374 16.8542 8.47974 16.7188 8.3746 16.6424L0.481122 10.9075C0.246008 10.7366 0.36684 10.3647 0.657457 10.3647H10.4143C10.5443 10.3647 10.6595 10.2811 10.6996 10.1575L13.7147 0.878115Z"--}}
                    {{--                                              fill="#D3D3D3"/>--}}
                    {{--                                    </svg>--}}
                    {{--                                </span>--}}
                    {{--                            <span class="star_span" data-value="2" id="2">--}}
                    {{--                                    <svg width="28" height="27" viewBox="0 0 28 27" fill="none"--}}
                    {{--                                         xmlns="http://www.w3.org/2000/svg">--}}
                    {{--                                        <path d="M13.7147 0.878115C13.8045 0.601722 14.1955 0.601722 14.2853 0.878115L17.3004 10.1574C17.3405 10.2811 17.4557 10.3647 17.5857 10.3647H27.3425C27.6332 10.3647 27.754 10.7366 27.5189 10.9075L19.6254 16.6424C19.5203 16.7188 19.4763 16.8542 19.5164 16.9778L22.5315 26.2571C22.6213 26.5335 22.3049 26.7634 22.0698 26.5925L14.1763 20.8576C14.0712 20.7812 13.9288 20.7812 13.8237 20.8576L5.93019 26.5925C5.69508 26.7634 5.37873 26.5335 5.46854 26.2571L8.48358 16.9778C8.52374 16.8542 8.47974 16.7188 8.3746 16.6424L0.481122 10.9075C0.246008 10.7366 0.36684 10.3647 0.657457 10.3647H10.4143C10.5443 10.3647 10.6595 10.2811 10.6996 10.1575L13.7147 0.878115Z"--}}
                    {{--                                              fill="#D3D3D3"/>--}}
                    {{--                                    </svg>--}}
                    {{--                                </span>--}}
                    {{--                            <span class="star_span" data-value="3" id="3">--}}
                    {{--                                    <svg width="28" height="27" viewBox="0 0 28 27" fill="none"--}}
                    {{--                                         xmlns="http://www.w3.org/2000/svg">--}}
                    {{--                                        <path d="M13.7147 0.878115C13.8045 0.601722 14.1955 0.601722 14.2853 0.878115L17.3004 10.1574C17.3405 10.2811 17.4557 10.3647 17.5857 10.3647H27.3425C27.6332 10.3647 27.754 10.7366 27.5189 10.9075L19.6254 16.6424C19.5203 16.7188 19.4763 16.8542 19.5164 16.9778L22.5315 26.2571C22.6213 26.5335 22.3049 26.7634 22.0698 26.5925L14.1763 20.8576C14.0712 20.7812 13.9288 20.7812 13.8237 20.8576L5.93019 26.5925C5.69508 26.7634 5.37873 26.5335 5.46854 26.2571L8.48358 16.9778C8.52374 16.8542 8.47974 16.7188 8.3746 16.6424L0.481122 10.9075C0.246008 10.7366 0.36684 10.3647 0.657457 10.3647H10.4143C10.5443 10.3647 10.6595 10.2811 10.6996 10.1575L13.7147 0.878115Z"--}}
                    {{--                                              fill="#D3D3D3"/>--}}
                    {{--                                    </svg>--}}
                    {{--                                </span>--}}
                    {{--                            <span class="star_span" data-value="4" id="4">--}}
                    {{--                                    <svg width="28" height="27" viewBox="0 0 28 27" fill="none"--}}
                    {{--                                         xmlns="http://www.w3.org/2000/svg">--}}
                    {{--                                        <path d="M13.7147 0.878115C13.8045 0.601722 14.1955 0.601722 14.2853 0.878115L17.3004 10.1574C17.3405 10.2811 17.4557 10.3647 17.5857 10.3647H27.3425C27.6332 10.3647 27.754 10.7366 27.5189 10.9075L19.6254 16.6424C19.5203 16.7188 19.4763 16.8542 19.5164 16.9778L22.5315 26.2571C22.6213 26.5335 22.3049 26.7634 22.0698 26.5925L14.1763 20.8576C14.0712 20.7812 13.9288 20.7812 13.8237 20.8576L5.93019 26.5925C5.69508 26.7634 5.37873 26.5335 5.46854 26.2571L8.48358 16.9778C8.52374 16.8542 8.47974 16.7188 8.3746 16.6424L0.481122 10.9075C0.246008 10.7366 0.36684 10.3647 0.657457 10.3647H10.4143C10.5443 10.3647 10.6595 10.2811 10.6996 10.1575L13.7147 0.878115Z"--}}
                    {{--                                              fill="#D3D3D3"/>--}}
                    {{--                                    </svg>--}}
                    {{--                                </span>--}}
                    {{--                            <span class="star_span" data-value="5" id="5">--}}
                    {{--                                    <svg width="28" height="27" viewBox="0 0 28 27" fill="none"--}}
                    {{--                                         xmlns="http://www.w3.org/2000/svg">--}}
                    {{--                                        <path d="M13.7147 0.878115C13.8045 0.601722 14.1955 0.601722 14.2853 0.878115L17.3004 10.1574C17.3405 10.2811 17.4557 10.3647 17.5857 10.3647H27.3425C27.6332 10.3647 27.754 10.7366 27.5189 10.9075L19.6254 16.6424C19.5203 16.7188 19.4763 16.8542 19.5164 16.9778L22.5315 26.2571C22.6213 26.5335 22.3049 26.7634 22.0698 26.5925L14.1763 20.8576C14.0712 20.7812 13.9288 20.7812 13.8237 20.8576L5.93019 26.5925C5.69508 26.7634 5.37873 26.5335 5.46854 26.2571L8.48358 16.9778C8.52374 16.8542 8.47974 16.7188 8.3746 16.6424L0.481122 10.9075C0.246008 10.7366 0.36684 10.3647 0.657457 10.3647H10.4143C10.5443 10.3647 10.6595 10.2811 10.6996 10.1575L13.7147 0.878115Z"--}}
                    {{--                                              fill="#D3D3D3"/>--}}
                    {{--                                    </svg>--}}
                    {{--                                </span>--}}

                    {{--                            @php--}}
                    {{--                                $astxers = \App\Models\Astx::where('product_id',$product->id)->pluck('astx');--}}
                    {{--                                $xz[1] = 0;--}}
                    {{--                                $xz[2] = 0;--}}
                    {{--                                $xz[3] = 0;--}}
                    {{--                                $xz[4] = 0;--}}
                    {{--                                $xz[5] = 0;--}}
                    {{--                                foreach ($astxers as $astx){--}}
                    {{--                                    if($astx == 1)$xz[1]++;--}}
                    {{--                                    if($astx == 2)$xz[2]++;--}}
                    {{--                                    if($astx == 3)$xz[3]++;--}}
                    {{--                                    if($astx == 4)$xz[4]++;--}}
                    {{--                                    if($astx == 5)$xz[5]++;--}}
                    {{--                                }--}}
                    {{--                                $max = max($xz);--}}
                    {{--                                if(!empty($astxers[0])){--}}
                    {{--                                    $tokos = $astxers->sum() / count($astxers);--}}
                    {{--                                    $tiv= round($tokos);--}}
                    {{--                                }--}}

                    {{--                            @endphp--}}


                    {{--                    </div>--}}
                    {{--                    @endif--}}

                    <div class="d_flex sto_">
                        <span class="twenty_sp">3D model format</span>
                        <span class="twenty_sp">
                            @php
                                $productFileTypesTxts = ProductService::getProductFileTypes($product);
                                echo implode(", ", $productFileTypesTxts)
                            @endphp
                        </span>
                    </div>
                    <input autocomplete="off" type="hidden" name="base_sku_price" id="base_sku_price" value="
                        @if(@$product->hasDeal)
                    {{ selling_price($product->skus->first()->selling_price,$product->hasDeal->discount_type,$product->hasDeal->discount) }}
                    @else
                    @if($product->hasDiscount == 'yes')
                    {{selling_price(@$product->skus->first()->selling_price,@$product->discount_type,@$product->discount)}}

                    @else
                    {{@$product->skus->first()->selling_price}}
                    @endif
                    @endif
                            ">
                    <input autocomplete="off" type="hidden" name="final_price" id="final_price" value="
                        @if(@$product->hasDeal)
                    {{ selling_price($product->skus->first()->selling_price,$product->hasDeal->discount_type,$product->hasDeal->discount) }}
                    @else
                    @if($product->hasDiscount == 'yes')
                    {{selling_price(@$product->skus->first()->selling_price,@$product->discount_type,@$product->discount)}}

                    @else
                    {{@$product->skus->first()->selling_price}}
                    @endif
                    @endif
                            ">
                    <div class="d_flex sto_ publ_margns">
                        <div class="d_flex publ_sms">
                            @auth
                                <a href="{{route('message.index')}}" class="prod_sms">
                                    <svg width="30" height="23" viewBox="0 0 30 23" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M27.1875 0H2.8125C1.23047 0 0 1.31771 0 2.875V20.125C0 21.7422 1.23047 23 2.8125 23H27.1875C28.7109 23 30 21.7422 30 20.125V2.875C30 1.31771 28.7109 0 27.1875 0ZM27.1875 2.875V5.33073C25.8398 6.46875 23.7305 8.14583 19.2773 11.7396C18.2812 12.5182 16.3477 14.4349 15 14.375C13.5938 14.4349 11.6602 12.5182 10.6641 11.7396C6.21094 8.14583 4.10156 6.46875 2.8125 5.33073V2.875H27.1875ZM2.8125 20.125V9.04427C4.10156 10.1224 6.03516 11.6797 8.90625 14.0156C10.1953 15.0339 12.4805 17.3099 15 17.25C17.4609 17.3099 19.6875 15.0339 21.0352 14.0156C23.9062 11.6797 25.8398 10.1224 27.1875 9.04427V20.125H2.8125Z"
                                              fill="#393939"/>
                                    </svg>
                                </a>
                            @endauth
                            <span>Publish date.</span>
                        </div>
                        <span class="twenty_sp">{{$product->created_at->toDateString()}}</span>
                    </div>

                    @if( CartService::isProductPurchased(@$product->product()->first()) || intval($product->skus->max('selling_price')) === 0)
                        @include('product::products.download_product_partial', ['product' => $product->product()->first()])
                    @else
                        @include('product::products.add_to_cart_partial', ['product' => $product])
                    @endif

                    @if( !CartService::isProductPurchased(@$product->product()->first()))
                        <div @if(!empty(Wishlist::where('user_id',auth()->id())->where('seller_product_id',$product->id)->first()->id))
                                 data-wish="{{Wishlist::where('user_id',auth()->id())->where('seller_product_id',$product->id)->first()->id}}"
                             @endif
                             @if(!auth()->check())
                                 class="prod_like d_flex add_to_wishlist add_catalog_btn login_btn"
                             @else
                                 class="prod_like d_flex add_to_wishlist add_catalog_btn"
                             @endif
                             data-product_id="{{$product->id}}"
                             data-seller_id="{{$product->user_id}}">
                            <span style="color: #FFFFFF; font-size: 18px;">
                                Add To Wishlist
                            </span>
                        </div>
                    @endif

                    <span class="twenty_sp">Share with friends</span>
                    <div class="d_flex soc_prod">
                        <a href="http://pinterest.com/pin/create/button/?url={{request()->url()}}&media=@if($product->thum_img != null){{showImage($product->thum_img)}} @else {{showImage($product->product->thumbnail_image_source)}} @endif&description={{$product->product_name}}"
                           class="pin-it-button" count-layout="horizontal" class="soc_svgs" target="_blank">
                            <svg width="29" height="30" viewBox="0 0 29 30" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.7"
                                      d="M28.7568 14.8426C28.7568 12.2915 28.0611 9.8565 26.7856 7.65336C25.5101 5.45021 23.7707 3.71089 21.5676 2.43539C19.3645 1.15989 16.9294 0.464157 14.3784 0.464157C11.7694 0.464157 9.39234 1.15989 7.1892 2.43539C4.98606 3.71089 3.18876 5.45021 1.91325 7.65336C0.637752 9.8565 0 12.2915 0 14.8426C0 17.8574 0.811684 20.5823 2.49303 23.0174C4.17437 25.4524 6.37752 27.1917 9.16043 28.2353C8.92852 26.496 8.9865 25.1046 9.21841 24.1189L10.8418 16.9877C10.5519 16.4079 10.4359 15.7122 10.4359 14.8426C10.4359 13.8569 10.6678 13.0453 11.1896 12.3495C11.6535 11.7118 12.2912 11.3639 13.0449 11.3639C13.6247 11.3639 14.0305 11.5958 14.3784 11.9437C14.6683 12.2915 14.8422 12.7554 14.8422 13.3351C14.8422 13.9149 14.6103 14.9005 14.2045 16.292C13.9146 17.1037 13.7406 17.7414 13.6827 18.1473C13.5087 18.843 13.6247 19.4807 14.0885 20.0025C14.4944 20.5243 15.0741 20.7563 15.8278 20.7563C17.1033 20.7563 18.1469 20.1765 19.0166 18.901C19.8863 17.6834 20.3501 16.1181 20.3501 14.1468C20.3501 12.4655 19.7703 11.074 18.6687 9.97245C17.5092 8.87088 16.0597 8.29111 14.3204 8.29111C12.9869 8.29111 11.8274 8.63897 10.8418 9.21875C9.85616 9.79852 9.10245 10.6102 8.58066 11.5378C8.05886 12.4655 7.82695 13.5091 7.82695 14.5527C7.82695 15.1904 7.88493 15.8282 8.11684 16.4079C8.29077 16.9877 8.52268 17.5095 8.87054 17.8574C8.9865 17.9733 8.9865 18.1473 8.9865 18.2632L8.58066 19.8866C8.52268 20.1185 8.34875 20.1765 8.11684 20.0605C7.24718 19.7127 6.55145 18.959 5.97167 17.8574C5.3919 16.7558 5.15999 15.6542 5.15999 14.4947C5.15999 12.9873 5.50785 11.5378 6.26156 10.2044C7.01527 8.87088 8.05886 7.82729 9.45032 7.0156C10.9577 6.20392 12.6971 5.79808 14.6683 5.79808C16.2917 5.79808 17.7991 6.20392 19.1905 6.89965C20.524 7.59538 21.5676 8.58099 22.3793 9.8565C23.133 11.132 23.5388 12.5814 23.5388 14.2048C23.5388 15.8282 23.191 17.2776 22.5532 18.6111C21.9155 20.0025 21.0458 21.0461 19.8863 21.8578C18.7267 22.6695 17.5092 23.0174 16.1177 23.0174C15.364 23.0174 14.6683 22.9014 14.0885 22.5536C13.4508 22.2637 13.0449 21.8578 12.813 21.394L11.8854 24.8147C11.6535 25.8003 11.0737 27.0758 10.146 28.5832C11.4795 29.047 12.871 29.221 14.3784 29.221C16.9294 29.221 19.3645 28.5832 21.5676 27.3077C23.7707 26.0322 25.5101 24.2349 26.7856 22.0318C28.0611 19.8286 28.7568 17.4515 28.7568 14.8426Z"
                                      fill="#717171"/>
                            </svg>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{request()->url()}}" class="soc_svgs"
                           target="_blank">
                            <svg width="29" height="30" viewBox="0 0 29 30" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.7"
                                      d="M15.1985 16.6975L16.0102 11.3636H10.8502V7.88493C10.8502 6.37752 11.546 4.98606 13.8651 4.98606H16.2421V0.405842C16.2421 0.405842 14.097 0 12.0678 0C7.8354 0 5.05249 2.60898 5.05249 7.24718V11.3636H0.29834V16.6975H5.05249V29.6844H10.8502V16.6975H15.1985Z"
                                      fill="#717171"/>
                            </svg>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{request()->url()}}" class="soc_svgs"
                           target="_blank">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="29px" height="30px"
                                 clip-rule="evenodd" baseProfile="basic">
                                <polygon fill="#616161" points="41,6 9.929,42 6.215,42 37.287,6"/>
                                <polygon fill="#fff" fill-rule="evenodd" points="31.143,41 7.82,7 16.777,7 40.1,41"
                                         clip-rule="evenodd"/>
                                <path fill="#616161"
                                      d="M15.724,9l20.578,30h-4.106L11.618,9H15.724 M17.304,6H5.922l24.694,36h11.382L17.304,6L17.304,6z"/>
                            </svg>
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{request()->url()}}"
                           class="soc_svgs" target="_blank">
                            <svg width="29" height="30" viewBox="0 0 29 30" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.7"
                                      d="M5.82411 26.829V9.49374H0.432209V26.829H5.82411ZM3.15715 7.11666C3.96883 7.11666 4.72254 6.82678 5.36029 6.18902C5.94006 5.60925 6.28793 4.85554 6.28793 3.98588C6.28793 3.1742 5.94006 2.42049 5.36029 1.78274C4.72254 1.20297 3.96883 0.855103 3.15715 0.855103C2.28749 0.855103 1.53378 1.20297 0.954006 1.78274C0.316254 2.42049 0.0263672 3.1742 0.0263672 3.98588C0.0263672 4.85554 0.316254 5.60925 0.954006 6.18902C1.53378 6.82678 2.28749 7.11666 3.15715 7.11666ZM26.0003 26.829V17.3207C26.0003 14.6537 25.5944 12.6825 24.8407 11.407C23.7971 9.8416 22.0578 9.02992 19.5648 9.02992C18.2893 9.02992 17.2457 9.37778 16.318 9.95756C15.4484 10.4794 14.8106 11.1171 14.4627 11.8708H14.4048V9.49374H9.24478V26.829H14.5787V18.2483C14.5787 16.9148 14.7526 15.8713 15.1585 15.1755C15.6223 14.2479 16.492 13.7841 17.7675 13.7841C18.985 13.7841 19.7967 14.3059 20.2605 15.3495C20.4924 15.9872 20.6084 16.9728 20.6084 18.3643V26.829H26.0003Z"
                                      fill="#717171"/>
                            </svg>
                        </a>
                    </div>
                    <div class="product_copyright_text">
                        <i>
                            Please note that all models on website are not allowed to be resold, as doing so would be
                            illegal.
                        </i>
                    </div>
                </div>
            </div>
            <div id='zoomed_prod' class='zoomed_prod'>
                <div class='zoom_imgs_block'>
                            <span class="close_zoom">
                                <svg width="17" height="17" viewBox="0 0 17 17" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 1L16 16M16 1L1 16" stroke="#717171"/>
                                </svg>
                            </span>

                    @if ($product->thum_img != null)
                        <div class="mySlides">
                            <img src="{{showImage($product->thum_img)}}" class="chosen_img">
                        </div>
                    @else
                        <div class="mySlides">
                            <img src="{{showImage($product->product->thumbnail_image_source)}}" class="chosen_img">
                        </div>
                    @endif
                    @if(count($product->product->gallary_images) > 0)
                        @php
                            $i = 0;
                            $j = 0;
                        @endphp

                        @while($i < count($product->product->gallary_images))
                            @if(!empty($product->product->gallary_images[$i]))
                                @php
                                    $image = $product->product->gallary_images[$i]
                                @endphp
                                <div class="mySlides">
                                    <img src="{{showImage($image->images_source)}}" class="chosen_img">
                                </div>
                            @endif
                            @php
                                $i++;
                            @endphp
                        @endwhile
                    @endif


                    {{--                    @foreach($product->product->gallary_images as $image)--}}
                    {{--                        <div class="mySlides">--}}
                    {{--                            <img src="{{showImage($image->images_source)}}" class="chosen_img">--}}
                    {{--                        </div>--}}
                    {{--                    @endforeach--}}

                </div>
                <div class="position">
                    <div class="prev">
                        <svg width="12" height="18" viewBox="0 0 12 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 2L3 9L10 16" stroke="#717171" stroke-width="3"/>
                        </svg>
                    </div>
                    <div class="next">
                        <svg width="12" height="18" viewBox="0 0 12 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 2L9 9L2 16" stroke="#717171" stroke-width="3"/>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="d_flex">
                <div class="d_flex rows_ sto_">
                    <div class="d_flex two_line_title">
                        <div class="description description_active"> {{__('defaultTheme.product_description')}} </div>
                        {{--                        @auth--}}
                        <div class="comments" id="comments"> Comments</div>
                        {{--                        @endauth--}}
                        <div class="_reviews" id="reviews">Reviews</div>
                    </div>
                    <div class="for_description for_description_active">
                        <p>
                        @auth
                            @php
                                echo $product->product->description_guest.'<br>'.$product->product->description;
                            @endphp
                        @else
                            @php
                                echo $product->product->description_guest.'<br>';
                            @endphp
                            <div class='d_flex log_in_to_see'>
                                <div class='login_closed d_flex login_btn'>
                                    <svg width="72" height="58" viewBox="0 0 72 58" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M64.8001 32.625V23.5625C64.8001 18.5781 60.7502 14.5 55.8002 14.5C50.9628 14.5 46.8004 18.5781 46.8004 23.5625V32.625C42.7504 32.625 39.6005 35.9102 39.6005 39.875V50.75C39.6005 54.8281 42.7504 58 46.8004 58H64.8001C68.7375 58 72 54.8281 72 50.75V39.875C72 35.9102 68.7375 32.625 64.8001 32.625ZM50.4003 23.5625C50.4003 20.6172 52.7628 18.125 55.8002 18.125C58.7252 18.125 61.2002 20.6172 61.2002 23.5625V32.625H50.4003V23.5625ZM68.4001 50.75C68.4001 52.7891 66.7126 54.375 64.8001 54.375H46.8004C44.7754 54.375 43.2004 52.7891 43.2004 50.75V39.875C43.2004 37.9492 44.7754 36.25 46.8004 36.25H64.8001C66.7126 36.25 68.4001 37.9492 68.4001 39.875V50.75ZM55.8002 42.5938C54.2253 42.5938 53.1003 43.8398 53.1003 45.3125C53.1003 46.8984 54.2253 48.0312 55.8002 48.0312C57.2627 48.0312 58.5002 46.8984 58.5002 45.3125C58.5002 43.8398 57.2627 42.5938 55.8002 42.5938ZM25.2007 29C33.0756 29 39.6005 22.543 39.6005 14.5C39.6005 6.57031 33.0756 0 25.2007 0C17.2133 0 10.8009 6.57031 10.8009 14.5C10.8009 22.543 17.2133 29 25.2007 29ZM25.2007 3.625C31.0506 3.625 36.0005 8.60938 36.0005 14.5C36.0005 20.5039 31.0506 25.375 25.2007 25.375C19.2383 25.375 14.4009 20.5039 14.4009 14.5C14.4009 8.60938 19.2383 3.625 25.2007 3.625ZM19.4633 38.0625H30.8256C31.9506 38.0625 32.9631 38.2891 33.9756 38.4023C35.1005 38.7422 36.2255 37.7227 36.0005 36.5898C36.0005 36.5898 36.0005 36.5898 36.0005 36.4766C35.888 35.6836 35.3255 35.0039 34.5381 34.8906C33.4131 34.6641 32.1756 34.4375 30.8256 34.4375H19.4633C8.66344 34.4375 -0.111429 43.2734 0.00106977 54.1484C0.00106977 56.3008 1.68854 58 3.82601 58H34.2006C35.1005 58 36.0005 57.207 36.0005 56.1875C36.0005 55.2812 35.1005 54.375 34.2006 54.375H3.82601C3.71351 54.375 3.60102 54.2617 3.60102 54.1484C3.48852 45.3125 10.6884 38.0625 19.4633 38.0625Z"
                                              fill="#00AAAD"/>
                                    </svg>
                                </div>
                                <span class='login_descrip_toosee'>
                                        You need to login or register to see the content
                                    </span>
                            </div>
                            @endauth
                            </p>
                    </div>

                    <div class="for_comments d_flex sto_" data-slug="{{$product->slug}}" id="for_comments">
                        <div class="d_flex comment_area sto_">
                            <div class="d_flex add_comment_area sto_">
                                @auth

                                    <div class="prof_pic">
                                        <img src="{{auth()->user()->avatar?showImage(auth()->user()->avatar):showImage('frontend/default/img/avatar.jpg')}}"
                                             alt="">
                                    </div>
                                    <textarea name="" id="textareaa" cols="30" rows="6"
                                              placeholder="Leave a comment, share your feedback." class="comment_text"
                                              data-id="{{$product->id}}"></textarea>
                                @endauth

                            </div>
                            @php
                                $comments = Comment::where('product_id',$product->id)->whereNull('to_user_id')->latest()->get();
                            @endphp


                            @foreach($comments as $comment)
                                <div class="d_flex added_comms sto_">
                                    <div class="prof_pic">
                                        @php
                                            $user_info = \App\Models\User::find($comment->user_id);
                                        @endphp

                                        <img src="{{$user_info->avatar?showImage($user_info->avatar):showImage('frontend/default/img/avatar.jpg')}}"
                                             alt="">
                                    </div>

                                    <div class="d_flex comments_about">
                                        <span class="twenty_sp name_com">{{$user_info->first_name}} {{$user_info->last_name}}</span>
                                        <div class='d_flex comm_already_boath'>
                                            <span class="com_date">
                                            @php
                                                \Carbon\Carbon::setLocale('en');
                                                    $diffInDays = \Carbon\Carbon::parse($comment->created_at)->diffInDays()
                                            @endphp

                                                @php
                                                    $showDiff = \Carbon\Carbon::parse($comment->created_at)->diffForHumans()
                                                @endphp

                                                @if($diffInDays > 0)

                                                    @php
                                                        $showDiff .= ', '.\Carbon\Carbon::parse($comment->created_at)->addDays($diffInDays)->diffInHours().' Hours'
                                                    @endphp

                                                @endif

                                                {{$showDiff}}

                                        </span>
                                            @auth
                                                @if(\App\Models\Paymant_products::where('user_id',$user_info->id)->where('product_id',$product->id)->first())
                                                    <div class='this_user_already_ d_flex'>
                                                        <svg width="15" height="17" viewBox="0 0 15 17" fill="none"
                                                             xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M9.42857 3.5C9.42857 1.58594 7.875 0 6 0C4.09821 0 2.57143 1.58594 2.57143 3.5H0V11.8125C0 13.043 0.9375 14 2.14286 14H9.85714C11.0357 14 12 13.043 12 11.8125V3.5H9.42857ZM6 1.3125C7.17857 1.3125 8.14286 2.29688 8.14286 3.5H3.85714C3.85714 2.29688 4.79464 1.3125 6 1.3125ZM10.7143 11.8125C10.7143 12.3047 10.3125 12.6875 9.85714 12.6875H2.14286C1.66071 12.6875 1.28571 12.3047 1.28571 11.8125V4.8125H2.57143V5.90625C2.57143 6.28906 2.83929 6.5625 3.21429 6.5625C3.5625 6.5625 3.85714 6.28906 3.85714 5.90625V4.8125H8.14286V5.90625C8.14286 6.28906 8.41071 6.5625 8.78571 6.5625C9.13393 6.5625 9.42857 6.28906 9.42857 5.90625V4.8125H10.7143V11.8125Z"
                                                                  fill="white"/>
                                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                                  d="M15 13C15 10.7909 13.2091 9 11 9C8.79086 9 7 10.7909 7 13C7 15.2091 8.79086 17 11 17C13.2091 17 15 15.2091 15 13ZM12.2094 11.5196L10.3429 13.386L9.60334 12.6464L9.55624 12.6049C9.36009 12.4523 9.07648 12.4662 8.89623 12.6464C8.70097 12.8417 8.70097 13.1583 8.89623 13.3536L9.98939 14.4467L10.0365 14.4883C10.2326 14.6408 10.5163 14.6269 10.6965 14.4467L12.9165 12.2267L12.9581 12.1796C13.1106 11.9834 13.0968 11.6998 12.9165 11.5196C12.7213 11.3243 12.4047 11.3243 12.2094 11.5196Z"
                                                                  fill="white"/>
                                                            <path d="M10.3429 13.386L12.2094 11.5196C12.4047 11.3243 12.7213 11.3243 12.9165 11.5196C13.0968 11.6998 13.1106 11.9834 12.9581 12.1796L12.9165 12.2267L10.6965 14.4467C10.5163 14.6269 10.2326 14.6408 10.0365 14.4883L9.98939 14.4467L8.89623 13.3536C8.70097 13.1583 8.70097 12.8417 8.89623 12.6464C9.07648 12.4662 9.36009 12.4523 9.55624 12.6049L9.60334 12.6464L10.3429 13.386Z"
                                                                  fill="#00AAAD"/>
                                                        </svg>
                                                        Already Bought
                                                    </div>
                                                @endif
                                            @endauth
                                        </div>
                                        <p class="twenty_sp">{{$comment->text}}</p>
                                        @auth
                                            <span class="reply_com">Reply</span>
                                        @endauth
                                        <form class="reply_block">
                                            <textarea id="textareaId" class="textareaclass"
                                                      data-id="{{$comment->id}}"></textarea>
                                        </form>
                                        @php
                                            $repliks = Comment::where('product_id',$product->id)->where('to_user_id',$comment->id)->latest()->get()
                                        @endphp
                                        @foreach($repliks as $replik)
                                            <div class="d_flex added_comms sto_">
                                                <div class="prof_pic">
                                                    @php
                                                        $user_info = \App\Models\User::find($replik->user_id);
                                                    @endphp
                                                    <img src="{{$user_info->avatar?showImage($user_info->avatar):showImage('frontend/default/img/avatar.jpg')}}"
                                                         alt="">
                                                </div>
                                                <div class="d_flex comments_about">
                                                    <span class="twenty_sp name_com">{{$user_info->first_name}} {{$user_info->last_name}}</span>
                                                    <div class='d_flex comm_already_boath for_replik_already'>
                                                        <span class="com_date">
                                                    @php
                                                        $diffInDays = \Carbon\Carbon::parse($replik->created_at)->diffInDays()
                                                    @endphp

                                                            @php
                                                                $showDiff = \Carbon\Carbon::parse($replik->created_at)->diffForHumans()
                                                            @endphp

                                                            @if($diffInDays > 0)

                                                                @php
                                                                    $showDiff .= ', '.\Carbon\Carbon::parse($replik->created_at)->addDays($diffInDays)->diffInHours().' Hours'
                                                                @endphp

                                                            @endif

                                                            {{$showDiff}}


                                                </span>
                                                        @if(\App\Models\Paymant_products::where('user_id',$user_info->id)->where('product_id',$product->id)->first())
                                                            <div class="this_user_already_ d_flex">
                                                                <svg width="15" height="17" viewBox="0 0 15 17"
                                                                     fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M9.42857 3.5C9.42857 1.58594 7.875 0 6 0C4.09821 0 2.57143 1.58594 2.57143 3.5H0V11.8125C0 13.043 0.9375 14 2.14286 14H9.85714C11.0357 14 12 13.043 12 11.8125V3.5H9.42857ZM6 1.3125C7.17857 1.3125 8.14286 2.29688 8.14286 3.5H3.85714C3.85714 2.29688 4.79464 1.3125 6 1.3125ZM10.7143 11.8125C10.7143 12.3047 10.3125 12.6875 9.85714 12.6875H2.14286C1.66071 12.6875 1.28571 12.3047 1.28571 11.8125V4.8125H2.57143V5.90625C2.57143 6.28906 2.83929 6.5625 3.21429 6.5625C3.5625 6.5625 3.85714 6.28906 3.85714 5.90625V4.8125H8.14286V5.90625C8.14286 6.28906 8.41071 6.5625 8.78571 6.5625C9.13393 6.5625 9.42857 6.28906 9.42857 5.90625V4.8125H10.7143V11.8125Z"
                                                                          fill="white"></path>
                                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                                          d="M15 13C15 10.7909 13.2091 9 11 9C8.79086 9 7 10.7909 7 13C7 15.2091 8.79086 17 11 17C13.2091 17 15 15.2091 15 13ZM12.2094 11.5196L10.3429 13.386L9.60334 12.6464L9.55624 12.6049C9.36009 12.4523 9.07648 12.4662 8.89623 12.6464C8.70097 12.8417 8.70097 13.1583 8.89623 13.3536L9.98939 14.4467L10.0365 14.4883C10.2326 14.6408 10.5163 14.6269 10.6965 14.4467L12.9165 12.2267L12.9581 12.1796C13.1106 11.9834 13.0968 11.6998 12.9165 11.5196C12.7213 11.3243 12.4047 11.3243 12.2094 11.5196Z"
                                                                          fill="white"></path>
                                                                    <path d="M10.3429 13.386L12.2094 11.5196C12.4047 11.3243 12.7213 11.3243 12.9165 11.5196C13.0968 11.6998 13.1106 11.9834 12.9581 12.1796L12.9165 12.2267L10.6965 14.4467C10.5163 14.6269 10.2326 14.6408 10.0365 14.4883L9.98939 14.4467L8.89623 13.3536C8.70097 13.1583 8.70097 12.8417 8.89623 12.6464C9.07648 12.4662 9.36009 12.4523 9.55624 12.6049L9.60334 12.6464L10.3429 13.386Z"
                                                                          fill="#00AAAD"></path>
                                                                </svg>
                                                                Already Bought
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <p class="twenty_sp">{{$replik->text}}</p>

                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                </div>
                            @endforeach
                        </div>

                        {{--                        @if($product->skus->max('selling_price') != '0.0')--}}
                        {{--                            <div class="d_flex rating_block">--}}
                        {{--                                <span class="avarage_rat">avarage rating</span>--}}
                        {{--                                <div class="d_flex nums_start">--}}
                        {{--                                    @if(isset($max) && $max != 0)--}}
                        {{--                                    <div class="nums_row d_flex">--}}
                        {{--                                        <div class="nums_lines d_flex">--}}
                        {{--                                            <span class="numbers_l">1</span>--}}
                        {{--                                            <span class="lines_n" ><span style="width: {{$xz[1]/$max*100}}%" class="review_line"></span></span>--}}
                        {{--                                        </div>--}}
                        {{--                                        <div class="nums_lines d_flex">--}}
                        {{--                                            <span class="numbers_l">2</span>--}}
                        {{--                                            <span class="lines_n" ><span style="width: {{$xz[2]/$max*100}}%" class="review_line"></span></span>--}}
                        {{--                                        </div>--}}
                        {{--                                        <div class="nums_lines d_flex">--}}
                        {{--                                            <span class="numbers_l">3</span>--}}
                        {{--                                            <span class="lines_n" ><span style="width: {{$xz[3]/$max*100}}%" class="review_line"></span></span>--}}
                        {{--                                        </div>--}}
                        {{--                                        <div class="nums_lines d_flex">--}}
                        {{--                                            <span class="numbers_l">4</span>--}}
                        {{--                                            <span class="lines_n" ><span style="width: {{$xz[4]/$max*100}}%" class="review_line"></span></span>--}}
                        {{--                                        </div>--}}
                        {{--                                        <div class="nums_lines d_flex">--}}
                        {{--                                            <span class="numbers_l">5</span>--}}
                        {{--                                            <span class="lines_n" ><span style="width: {{$xz[5]/$max*100}}%" class="review_line"></span></span>--}}
                        {{--                                        </div>--}}
                        {{--                                    </div>--}}
                        {{--                                    @else--}}
                        {{--                                    <div class="nums_row d_flex">--}}
                        {{--                                        <div class="nums_lines d_flex">--}}
                        {{--                                            <span class="numbers_l">1</span>--}}
                        {{--                                            <span class="lines_n" ><span style="width: 0%" class="review_line"></span></span>--}}
                        {{--                                        </div>--}}
                        {{--                                        <div class="nums_lines d_flex">--}}
                        {{--                                            <span class="numbers_l">2</span>--}}
                        {{--                                            <span class="lines_n" ><span style="width: 0%" class="review_line"></span></span>--}}
                        {{--                                        </div>--}}
                        {{--                                        <div class="nums_lines d_flex">--}}
                        {{--                                            <span class="numbers_l">3</span>--}}
                        {{--                                            <span class="lines_n" ><span style="width: 0%" class="review_line"></span></span>--}}
                        {{--                                        </div>--}}
                        {{--                                        <div class="nums_lines d_flex">--}}
                        {{--                                            <span class="numbers_l">4</span>--}}
                        {{--                                            <span class="lines_n" ><span style="width: 0%" class="review_line"></span></span>--}}
                        {{--                                        </div>--}}
                        {{--                                        <div class="nums_lines d_flex">--}}
                        {{--                                            <span class="numbers_l">5</span>--}}
                        {{--                                            <span class="lines_n" ><span style="width: 0%" class="review_line"></span></span>--}}
                        {{--                                        </div>--}}
                        {{--                                    </div>--}}
                        {{--                                    @endif--}}
                        {{--                                    <div class="reviews_ d_flex">--}}
                        {{--                                        <span class="rev_num">{{$tokos??0.0}}</span>--}}
                        {{--                                        <div class="start_block d_flex">--}}
                        {{--                                                <span class="star_span 1" data-value="1">--}}
                        {{--                                                    <svg width="28" height="27" viewBox="0 0 28 27" fill="none"--}}
                        {{--                                                         xmlns="http://www.w3.org/2000/svg">--}}
                        {{--                                                        <path d="M13.7147 0.878115C13.8045 0.601722 14.1955 0.601722 14.2853 0.878115L17.3004 10.1574C17.3405 10.2811 17.4557 10.3647 17.5857 10.3647H27.3425C27.6332 10.3647 27.754 10.7366 27.5189 10.9075L19.6254 16.6424C19.5203 16.7188 19.4763 16.8542 19.5164 16.9778L22.5315 26.2571C22.6213 26.5335 22.3049 26.7634 22.0698 26.5925L14.1763 20.8576C14.0712 20.7812 13.9288 20.7812 13.8237 20.8576L5.93019 26.5925C5.69508 26.7634 5.37873 26.5335 5.46854 26.2571L8.48358 16.9778C8.52374 16.8542 8.47974 16.7188 8.3746 16.6424L0.481122 10.9075C0.246008 10.7366 0.36684 10.3647 0.657457 10.3647H10.4143C10.5443 10.3647 10.6595 10.2811 10.6996 10.1575L13.7147 0.878115Z"--}}
                        {{--                                                              fill="#D3D3D3"></path>--}}
                        {{--                                                    </svg>--}}
                        {{--                                                </span>--}}
                        {{--                                            <span class="star_span 2" id="2" data-value="2">--}}
                        {{--                                                    <svg width="28" height="27" viewBox="0 0 28 27" fill="none"--}}
                        {{--                                                         xmlns="http://www.w3.org/2000/svg">--}}
                        {{--                                                        <path d="M13.7147 0.878115C13.8045 0.601722 14.1955 0.601722 14.2853 0.878115L17.3004 10.1574C17.3405 10.2811 17.4557 10.3647 17.5857 10.3647H27.3425C27.6332 10.3647 27.754 10.7366 27.5189 10.9075L19.6254 16.6424C19.5203 16.7188 19.4763 16.8542 19.5164 16.9778L22.5315 26.2571C22.6213 26.5335 22.3049 26.7634 22.0698 26.5925L14.1763 20.8576C14.0712 20.7812 13.9288 20.7812 13.8237 20.8576L5.93019 26.5925C5.69508 26.7634 5.37873 26.5335 5.46854 26.2571L8.48358 16.9778C8.52374 16.8542 8.47974 16.7188 8.3746 16.6424L0.481122 10.9075C0.246008 10.7366 0.36684 10.3647 0.657457 10.3647H10.4143C10.5443 10.3647 10.6595 10.2811 10.6996 10.1575L13.7147 0.878115Z"--}}
                        {{--                                                              fill="#D3D3D3"></path>--}}
                        {{--                                                    </svg>--}}
                        {{--                                                </span>--}}
                        {{--                                            <span class="star_span 3" id="3" data-value="3">--}}
                        {{--                                                    <svg width="28" height="27" viewBox="0 0 28 27" fill="none"--}}
                        {{--                                                         xmlns="http://www.w3.org/2000/svg">--}}
                        {{--                                                        <path d="M13.7147 0.878115C13.8045 0.601722 14.1955 0.601722 14.2853 0.878115L17.3004 10.1574C17.3405 10.2811 17.4557 10.3647 17.5857 10.3647H27.3425C27.6332 10.3647 27.754 10.7366 27.5189 10.9075L19.6254 16.6424C19.5203 16.7188 19.4763 16.8542 19.5164 16.9778L22.5315 26.2571C22.6213 26.5335 22.3049 26.7634 22.0698 26.5925L14.1763 20.8576C14.0712 20.7812 13.9288 20.7812 13.8237 20.8576L5.93019 26.5925C5.69508 26.7634 5.37873 26.5335 5.46854 26.2571L8.48358 16.9778C8.52374 16.8542 8.47974 16.7188 8.3746 16.6424L0.481122 10.9075C0.246008 10.7366 0.36684 10.3647 0.657457 10.3647H10.4143C10.5443 10.3647 10.6595 10.2811 10.6996 10.1575L13.7147 0.878115Z"--}}
                        {{--                                                              fill="#D3D3D3"></path>--}}
                        {{--                                                    </svg>--}}
                        {{--                                                </span>--}}
                        {{--                                            <span class="star_span 4" id="4" data-value="4">--}}
                        {{--                                                    <svg width="28" height="27" viewBox="0 0 28 27" fill="none"--}}
                        {{--                                                         xmlns="http://www.w3.org/2000/svg">--}}
                        {{--                                                        <path d="M13.7147 0.878115C13.8045 0.601722 14.1955 0.601722 14.2853 0.878115L17.3004 10.1574C17.3405 10.2811 17.4557 10.3647 17.5857 10.3647H27.3425C27.6332 10.3647 27.754 10.7366 27.5189 10.9075L19.6254 16.6424C19.5203 16.7188 19.4763 16.8542 19.5164 16.9778L22.5315 26.2571C22.6213 26.5335 22.3049 26.7634 22.0698 26.5925L14.1763 20.8576C14.0712 20.7812 13.9288 20.7812 13.8237 20.8576L5.93019 26.5925C5.69508 26.7634 5.37873 26.5335 5.46854 26.2571L8.48358 16.9778C8.52374 16.8542 8.47974 16.7188 8.3746 16.6424L0.481122 10.9075C0.246008 10.7366 0.36684 10.3647 0.657457 10.3647H10.4143C10.5443 10.3647 10.6595 10.2811 10.6996 10.1575L13.7147 0.878115Z"--}}
                        {{--                                                              fill="#D3D3D3"></path>--}}
                        {{--                                                    </svg>--}}
                        {{--                                                </span>--}}
                        {{--                                            <span class="star_span 5" id="5" data-value="5">--}}
                        {{--                                                    <svg width="28" height="27" viewBox="0 0 28 27" fill="none"--}}
                        {{--                                                         xmlns="http://www.w3.org/2000/svg">--}}
                        {{--                                                        <path d="M13.7147 0.878115C13.8045 0.601722 14.1955 0.601722 14.2853 0.878115L17.3004 10.1574C17.3405 10.2811 17.4557 10.3647 17.5857 10.3647H27.3425C27.6332 10.3647 27.754 10.7366 27.5189 10.9075L19.6254 16.6424C19.5203 16.7188 19.4763 16.8542 19.5164 16.9778L22.5315 26.2571C22.6213 26.5335 22.3049 26.7634 22.0698 26.5925L14.1763 20.8576C14.0712 20.7812 13.9288 20.7812 13.8237 20.8576L5.93019 26.5925C5.69508 26.7634 5.37873 26.5335 5.46854 26.2571L8.48358 16.9778C8.52374 16.8542 8.47974 16.7188 8.3746 16.6424L0.481122 10.9075C0.246008 10.7366 0.36684 10.3647 0.657457 10.3647H10.4143C10.5443 10.3647 10.6595 10.2811 10.6996 10.1575L13.7147 0.878115Z"--}}
                        {{--                                                              fill="#D3D3D3"></path>--}}
                        {{--                                                    </svg>--}}
                        {{--                                                </span>--}}
                        {{--                                        </div>--}}
                        {{--                                        <span class="rev_last">based on {{$product->viewed}} reviews</span>--}}
                        {{--                                    </div>--}}
                        {{--                                </div>--}}
                        {{--                            </div>--}}
                        {{--                        @endif--}}
                    </div>
                    <div class="for_reviews">
                        <div class="prod_rev_and_btn">
                            <div class="like_dislike_line">
                                <div class="like_dislike_pr">
                                    <span class="prdr_vsp"> Product Review </span>
                                    <div class="d_flex eye_cool">
                                        <div class="watched_">
                                            <svg width="16" height="14" viewBox="0 0 16 14" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path d="M3 5H1C0.4375 5 0 5.4375 0 5.96875V12.9375C0 13.4688 0.4375 13.9062 1 13.9062H3C3.53125 13.9062 4 13.4688 4 12.9375V6C4 5.46875 3.53125 5 3 5ZM16 6.09375C16 4.96875 15.0312 4.03125 13.9062 4.03125H10.7812C11.0625 3.25 11.25 2.5625 11.25 2.15625C11.25 1.09375 10.4062 0.03125 9.03125 0.03125C7.59375 0.03125 7.1875 1.03125 6.875 1.84375C5.875 4.375 5 3.90625 5 4.75C5 5.15625 5.3125 5.5 5.75 5.5C5.90625 5.5 6.0625 5.46875 6.1875 5.34375C8.59375 3.4375 8 1.53125 9.03125 1.53125C9.5625 1.53125 9.75 1.90625 9.75 2.15625C9.75 2.40625 9.5 3.40625 8.9375 4.40625C8.875 4.53125 8.84375 4.65625 8.84375 4.78125C8.84375 5.21875 9.1875 5.5 9.59375 5.5H13.875C14.2188 5.53125 14.5 5.78125 14.5 6.09375C14.5 6.40625 14.25 6.6875 13.9375 6.6875C13.5312 6.71875 13.2188 7.0625 13.2188 7.4375C13.2188 7.9375 13.5938 7.9375 13.5938 8.34375C13.5938 9.125 12.5 8.71875 12.5 9.65625C12.5 10 12.6875 10.0625 12.6875 10.3438C12.6875 11.0625 11.75 10.7812 11.75 11.625C11.75 11.7812 11.8125 11.8125 11.8125 11.9375C11.8125 12.25 11.5312 12.5312 11.2188 12.5312H9.5625C8.75 12.5312 7.96875 12.25 7.34375 11.7812L6.1875 10.9062C6.0625 10.8125 5.90625 10.75 5.75 10.75C5.3125 10.75 4.96875 11.125 4.96875 11.5C4.96875 11.75 5.09375 11.9688 5.28125 12.125L6.4375 12.9688C7.34375 13.6562 8.4375 14 9.5625 14H11.2188C12.3125 14 13.2188 13.1562 13.2812 12.0625C13.8438 11.6875 14.1875 11.0625 14.1875 10.3438C14.1875 10.25 14.1875 10.1562 14.1875 10.0625C14.7188 9.6875 15.0938 9.0625 15.0938 8.34375C15.0938 8.1875 15.0625 8.03125 15.0312 7.84375C15.5938 7.46875 16 6.84375 16 6.09375Z"
                                                      fill="#323232"/>
                                            </svg>
                                            <span>
                                                {{count(Review::where('product_id',$product->product_id)->where('is_positive_like',1)->get())}}
                                            </span>
                                        </div>
                                        <div class="watched_" tabindex="-1">
                                            <svg width="16" height="14" viewBox="0 0 16 14" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path d="M4 8V1.03125C4 0.46875 3.53125 0.03125 3 0.03125H1C0.4375 0.03125 0 0.5 0 1.03125V8C0 8.53125 0.4375 8.96875 1 8.96875H3C3.53125 9 4 8.5625 4 8ZM15.0312 6.1875C15.0625 6.03125 15.0938 5.84375 15.0938 5.6875C15.0938 4.96875 14.7188 4.34375 14.1875 3.96875C14.1875 3.875 14.1875 3.78125 14.1875 3.6875C14.1875 2.96875 13.8438 2.34375 13.2812 1.96875C13.2188 0.875 12.3125 0.03125 11.2188 0.03125H9.5625C8.4375 0.03125 7.34375 0.375 6.4375 1.0625L5.28125 1.90625C5.09375 2.0625 5 2.28125 5 2.53125C5 2.90625 5.3125 3.28125 5.75 3.28125C5.90625 3.28125 6.0625 3.21875 6.1875 3.125L7.34375 2.25C7.96875 1.78125 8.75 1.5 9.5625 1.5H11.2188C11.5312 1.5 11.8125 1.78125 11.8125 2.09375C11.8125 2.21875 11.75 2.25 11.75 2.40625C11.75 3.25 12.6875 2.96875 12.6875 3.6875C12.6875 3.96875 12.5 4 12.5 4.375C12.5 4.8125 12.8438 5.0625 13.0938 5.09375C13.375 5.15625 13.5938 5.40625 13.5938 5.6875C13.5938 6.09375 13.2188 6.09375 13.2188 6.59375C13.2188 6.96875 13.5312 7.3125 13.9375 7.34375C14.25 7.34375 14.5 7.625 14.5 7.90625C14.5 8.21875 14.2188 8.5 13.9062 8.5H9.59375C9.1875 8.5 8.84375 8.8125 8.84375 9.21875C8.84375 9.34375 8.875 9.46875 8.9375 9.5625C9.5 10.625 9.75 11.625 9.75 11.875C9.75 12.125 9.5625 12.5 9.03125 12.5C8.65625 12.5 8.59375 12.5 8.28125 11.625C7.5 9.6875 6.34375 8.53125 5.75 8.53125C5.3125 8.53125 5 8.875 5 9.28125C5 9.5 5.09375 9.71875 5.28125 9.84375C7.4375 11.5625 6.59375 14 9.03125 14C10.4062 14 11.25 12.9062 11.25 11.875C11.25 11.4688 11.0625 10.75 10.7812 10H13.9062C15.0312 10 16 9.0625 16 7.90625C16 7.1875 15.5938 6.53125 15.0312 6.1875Z"
                                                      fill="#323232"/>
                                            </svg>

                                            <span class="likes">
                                                {{count(Review::where('product_id',$product->product_id)->where('is_positive_like',0)->get())}}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                @php
                                    if(count(\App\Models\Like::where('product_id',$product->id)->where('type',1)->get()) + count(\App\Models\Like::where('product_id',$product->id)->where('type',0)->get()) == 0){
                                        $tokos = 50;
                                    }else{
                                        $tokos = count(\App\Models\Like::where('product_id',$product->id)->where('type',1)->get())/(count(\App\Models\Like::where('product_id',$product->id)->where('type',1)->get()) + count(\App\Models\Like::where('product_id',$product->id)->where('type',0)->get()));
                                        $tokos *= 100;
                                    }

                                @endphp
                                <div class="like_dislike_colors">
                                    <div class="color_for_like" style="width: {{$tokos}}%"></div>
                                </div>
                            </div>

                            @if(\App\Models\Paymant_products::where('user_id',auth()->id())->where('product_id',$product->id)->first())
                                <span class="write_review">Write Review</span>
                            @endif
                            <div class="write_review_popup_block">
                                <div class='name_rev'>
                                    <span class='rev_sp_nam'>Review</span>
                                    <span class='rev_nameof_prod'>{{$product->product->product_name}}</span>
                                </div>
                                <div class='revo_p'>
                                    Dear users your rating and user names will be shown publicly. You can edit your
                                    ratings in the future. Dear users please leave reviews fairly and in any
                                    incomprehensible situation please contact us. Sincerely Artoorsdesign.
                                </div>
                                <div class='close_revo'>
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10.8799 9.00008L16.6132 3.28008C16.8643 3.02901 17.0054 2.68849 17.0054 2.33342C17.0054 1.97835 16.8643 1.63782 16.6132 1.38675C16.3622 1.13568 16.0217 0.994629 15.6666 0.994629C15.3115 0.994629 14.971 1.13568 14.7199 1.38675L8.99992 7.12008L3.27992 1.38675C3.02885 1.13568 2.68832 0.994629 2.33325 0.994629C1.97818 0.994629 1.63766 1.13568 1.38659 1.38675C1.13551 1.63782 0.994463 1.97835 0.994463 2.33342C0.994463 2.68849 1.13551 3.02901 1.38659 3.28008L7.11992 9.00008L1.38659 14.7201C1.26161 14.844 1.16242 14.9915 1.09473 15.154C1.02704 15.3165 0.992188 15.4907 0.992188 15.6667C0.992188 15.8428 1.02704 16.017 1.09473 16.1795C1.16242 16.342 1.26161 16.4895 1.38659 16.6134C1.51054 16.7384 1.658 16.8376 1.82048 16.9053C1.98296 16.973 2.15724 17.0078 2.33325 17.0078C2.50927 17.0078 2.68354 16.973 2.84602 16.9053C3.0085 16.8376 3.15597 16.7384 3.27992 16.6134L8.99992 10.8801L14.7199 16.6134C14.8439 16.7384 14.9913 16.8376 15.1538 16.9053C15.3163 16.973 15.4906 17.0078 15.6666 17.0078C15.8426 17.0078 16.0169 16.973 16.1794 16.9053C16.3418 16.8376 16.4893 16.7384 16.6132 16.6134C16.7382 16.4895 16.8374 16.342 16.9051 16.1795C16.9728 16.017 17.0076 15.8428 17.0076 15.6667C17.0076 15.4907 16.9728 15.3165 16.9051 15.154C16.8374 14.9915 16.7382 14.844 16.6132 14.7201L10.8799 9.00008Z"
                                              fill="#6B6B6B"></path>
                                    </svg>
                                </div>
                                <span class='rating_s'>Leave a rating</span>
                                <div class='name_rev'>
                                    <div class='negative_ positive_' data-id="1">
                                        <svg width="16" height="14" viewBox="0 0 16 14" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path d="M3 5H1C0.4375 5 0 5.4375 0 5.96875V12.9375C0 13.4688 0.4375 13.9062 1 13.9062H3C3.53125 13.9062 4 13.4688 4 12.9375V6C4 5.46875 3.53125 5 3 5ZM16 6.09375C16 4.96875 15.0312 4.03125 13.9062 4.03125H10.7812C11.0625 3.25 11.25 2.5625 11.25 2.15625C11.25 1.09375 10.4062 0.03125 9.03125 0.03125C7.59375 0.03125 7.1875 1.03125 6.875 1.84375C5.875 4.375 5 3.90625 5 4.75C5 5.15625 5.3125 5.5 5.75 5.5C5.90625 5.5 6.0625 5.46875 6.1875 5.34375C8.59375 3.4375 8 1.53125 9.03125 1.53125C9.5625 1.53125 9.75 1.90625 9.75 2.15625C9.75 2.40625 9.5 3.40625 8.9375 4.40625C8.875 4.53125 8.84375 4.65625 8.84375 4.78125C8.84375 5.21875 9.1875 5.5 9.59375 5.5H13.875C14.2188 5.53125 14.5 5.78125 14.5 6.09375C14.5 6.40625 14.25 6.6875 13.9375 6.6875C13.5312 6.71875 13.2188 7.0625 13.2188 7.4375C13.2188 7.9375 13.5938 7.9375 13.5938 8.34375C13.5938 9.125 12.5 8.71875 12.5 9.65625C12.5 10 12.6875 10.0625 12.6875 10.3438C12.6875 11.0625 11.75 10.7812 11.75 11.625C11.75 11.7812 11.8125 11.8125 11.8125 11.9375C11.8125 12.25 11.5312 12.5312 11.2188 12.5312H9.5625C8.75 12.5312 7.96875 12.25 7.34375 11.7812L6.1875 10.9062C6.0625 10.8125 5.90625 10.75 5.75 10.75C5.3125 10.75 4.96875 11.125 4.96875 11.5C4.96875 11.75 5.09375 11.9688 5.28125 12.125L6.4375 12.9688C7.34375 13.6562 8.4375 14 9.5625 14H11.2188C12.3125 14 13.2188 13.1562 13.2812 12.0625C13.8438 11.6875 14.1875 11.0625 14.1875 10.3438C14.1875 10.25 14.1875 10.1562 14.1875 10.0625C14.7188 9.6875 15.0938 9.0625 15.0938 8.34375C15.0938 8.1875 15.0625 8.03125 15.0312 7.84375C15.5938 7.46875 16 6.84375 16 6.09375Z"
                                                  fill="#323232"/>
                                        </svg>

                                        Positive
                                    </div>
                                    <div class='negative_' data-id="0">
                                        <svg width="16" height="14" viewBox="0 0 16 14" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4 8V1.03125C4 0.46875 3.53125 0.03125 3 0.03125H1C0.4375 0.03125 0 0.5 0 1.03125V8C0 8.53125 0.4375 8.96875 1 8.96875H3C3.53125 9 4 8.5625 4 8ZM15.0312 6.1875C15.0625 6.03125 15.0938 5.84375 15.0938 5.6875C15.0938 4.96875 14.7188 4.34375 14.1875 3.96875C14.1875 3.875 14.1875 3.78125 14.1875 3.6875C14.1875 2.96875 13.8438 2.34375 13.2812 1.96875C13.2188 0.875 12.3125 0.03125 11.2188 0.03125H9.5625C8.4375 0.03125 7.34375 0.375 6.4375 1.0625L5.28125 1.90625C5.09375 2.0625 5 2.28125 5 2.53125C5 2.90625 5.3125 3.28125 5.75 3.28125C5.90625 3.28125 6.0625 3.21875 6.1875 3.125L7.34375 2.25C7.96875 1.78125 8.75 1.5 9.5625 1.5H11.2188C11.5312 1.5 11.8125 1.78125 11.8125 2.09375C11.8125 2.21875 11.75 2.25 11.75 2.40625C11.75 3.25 12.6875 2.96875 12.6875 3.6875C12.6875 3.96875 12.5 4 12.5 4.375C12.5 4.8125 12.8438 5.0625 13.0938 5.09375C13.375 5.15625 13.5938 5.40625 13.5938 5.6875C13.5938 6.09375 13.2188 6.09375 13.2188 6.59375C13.2188 6.96875 13.5312 7.3125 13.9375 7.34375C14.25 7.34375 14.5 7.625 14.5 7.90625C14.5 8.21875 14.2188 8.5 13.9062 8.5H9.59375C9.1875 8.5 8.84375 8.8125 8.84375 9.21875C8.84375 9.34375 8.875 9.46875 8.9375 9.5625C9.5 10.625 9.75 11.625 9.75 11.875C9.75 12.125 9.5625 12.5 9.03125 12.5C8.65625 12.5 8.59375 12.5 8.28125 11.625C7.5 9.6875 6.34375 8.53125 5.75 8.53125C5.3125 8.53125 5 8.875 5 9.28125C5 9.5 5.09375 9.71875 5.28125 9.84375C7.4375 11.5625 6.59375 14 9.03125 14C10.4062 14 11.25 12.9062 11.25 11.875C11.25 11.4688 11.0625 10.75 10.7812 10H13.9062C15.0312 10 16 9.0625 16 7.90625C16 7.1875 15.5938 6.53125 15.0312 6.1875Z"
                                                  fill="#323232"/>
                                        </svg>

                                        Negative
                                    </div>
                                </div>
                                <span class='rating_s'>Write a comment</span>
                                <textarea name="" id="textareaa1" cols="30" rows="6" placeholder="Type here ..."
                                          class="comment_text"></textarea>
                                <div class='cancel_rate'>
                                    <span class='cencel_revo'>Cancel</span>
                                    <span class='positive_ rate_model' data-like="1" data-id="{{$product->product_id}}"
                                          data-slug="{{$product->slug}}"> Rate model</span>
                                </div>
                            </div>
                        </div>
                        <div class="reviewers_review">
                            @php
                                $reviews = App\Models\Review::where('product_id',$product->product_id)->latest()->get();
                            @endphp
                            @foreach($reviews as $review)
                                <div class="d_flex added_comms sto_">
                                    <div class="prof_pic">
                                        @php
                                            $user_info = \App\Models\User::find($review->user_id);
                                        @endphp

                                        <img src="{{$user_info->avatar?showImage($user_info->avatar):showImage('frontend/default/img/avatar.jpg')}}"
                                             alt="">
                                    </div>
                                    <div class="d_flex eye_cool">
                                        <div class="watched_">
                                            @if($review->is_positive_like === 1)
                                                <svg width="15" height="16" viewBox="0 0 15 16" fill="none"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M14.5625 8.96875C14.8438 8.5 15 8 15 7.40625C15 6.03125 13.8125 4.75 12.3125 4.75H11.1562C11.3125 4.34375 11.4375 3.875 11.4375 3.28125C11.4375 1 10.25 0 8.46875 0C6.53125 0 6.65625 2.96875 6.21875 3.40625C5.5 4.125 4.65625 5.5 4.0625 6H1C0.4375 6 0 6.46875 0 7V14.5C0 15.0625 0.4375 15.5 1 15.5H3C3.4375 15.5 3.84375 15.1875 3.9375 14.7812C5.34375 14.8125 6.3125 16 9.5 16C9.75 16 10 16 10.2188 16C12.625 16 13.6875 14.7812 13.7188 13.0312C14.1562 12.4688 14.375 11.6875 14.2812 10.9375C14.5938 10.375 14.6875 9.6875 14.5625 8.96875ZM12.625 10.6562C13.0312 11.3125 12.6562 12.1875 12.1875 12.4688C12.4375 13.9688 11.625 14.5 10.5312 14.5H9.34375C7.125 14.5 5.65625 13.3438 4 13.3438V7.5H4.3125C5.21875 7.5 6.4375 5.3125 7.28125 4.46875C8.15625 3.59375 7.875 2.09375 8.46875 1.5C9.9375 1.5 9.9375 2.53125 9.9375 3.28125C9.9375 4.5 9.0625 5.0625 9.0625 6.25H12.3125C12.9688 6.25 13.4688 6.84375 13.5 7.4375C13.5 8 13.0938 8.59375 12.7812 8.59375C13.2188 9.0625 13.3125 10.0312 12.625 10.6562ZM2.75 13.5C2.75 13.9375 2.40625 14.25 2 14.25C1.5625 14.25 1.25 13.9375 1.25 13.5C1.25 13.0938 1.5625 12.75 2 12.75C2.40625 12.75 2.75 13.0938 2.75 13.5Z"
                                                          fill="#717171"></path>
                                                </svg>
                                            @else
                                                <svg width="16" height="14" viewBox="0 0 16 14" fill="none"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M4 8V1.03125C4 0.46875 3.53125 0.03125 3 0.03125H1C0.4375 0.03125 0 0.5 0 1.03125V8C0 8.53125 0.4375 8.96875 1 8.96875H3C3.53125 9 4 8.5625 4 8ZM15.0312 6.1875C15.0625 6.03125 15.0938 5.84375 15.0938 5.6875C15.0938 4.96875 14.7188 4.34375 14.1875 3.96875C14.1875 3.875 14.1875 3.78125 14.1875 3.6875C14.1875 2.96875 13.8438 2.34375 13.2812 1.96875C13.2188 0.875 12.3125 0.03125 11.2188 0.03125H9.5625C8.4375 0.03125 7.34375 0.375 6.4375 1.0625L5.28125 1.90625C5.09375 2.0625 5 2.28125 5 2.53125C5 2.90625 5.3125 3.28125 5.75 3.28125C5.90625 3.28125 6.0625 3.21875 6.1875 3.125L7.34375 2.25C7.96875 1.78125 8.75 1.5 9.5625 1.5H11.2188C11.5312 1.5 11.8125 1.78125 11.8125 2.09375C11.8125 2.21875 11.75 2.25 11.75 2.40625C11.75 3.25 12.6875 2.96875 12.6875 3.6875C12.6875 3.96875 12.5 4 12.5 4.375C12.5 4.8125 12.8438 5.0625 13.0938 5.09375C13.375 5.15625 13.5938 5.40625 13.5938 5.6875C13.5938 6.09375 13.2188 6.09375 13.2188 6.59375C13.2188 6.96875 13.5312 7.3125 13.9375 7.34375C14.25 7.34375 14.5 7.625 14.5 7.90625C14.5 8.21875 14.2188 8.5 13.9062 8.5H9.59375C9.1875 8.5 8.84375 8.8125 8.84375 9.21875C8.84375 9.34375 8.875 9.46875 8.9375 9.5625C9.5 10.625 9.75 11.625 9.75 11.875C9.75 12.125 9.5625 12.5 9.03125 12.5C8.65625 12.5 8.59375 12.5 8.28125 11.625C7.5 9.6875 6.34375 8.53125 5.75 8.53125C5.3125 8.53125 5 8.875 5 9.28125C5 9.5 5.09375 9.71875 5.28125 9.84375C7.4375 11.5625 6.59375 14 9.03125 14C10.4062 14 11.25 12.9062 11.25 11.875C11.25 11.4688 11.0625 10.75 10.7812 10H13.9062C15.0312 10 16 9.0625 16 7.90625C16 7.1875 15.5938 6.53125 15.0312 6.1875Z"
                                                          fill="#323232"></path>
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="good_or_bad">{{$review->text}}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    {{--                    @auth--}}
                    {{--                        @if(\App\Models\Paymant_products::where('user_id',auth()->id())->where('product_id',$product->id)->first())--}}
                    {{--                            <script>--}}
                    {{--                                $('.star_span').click(function () {--}}
                    {{--                                    let astx = $(this).data('value')--}}
                    {{--                                    let id = '{{$product->id}}'--}}
                    {{--                                    let data = {astx, id}--}}
                    {{--                                    $.post('{{route('astx')}}', data, function (data) {--}}
                    {{--                                    });--}}
                    {{--                                })--}}
                    {{--                            </script>--}}
                    {{--                        @endif--}}
                    {{--                    @endauth--}}
                </div>
                @if(isset($tiv))
                    <script>

                        $('.{{$tiv}}').addClass('star_span_actives');
                        $('.{{$tiv}}').prevAll(".star_span").addClass('star_span_actives');
                        $('.{{$tiv}}').nextAll(".star_span").removeClass('star_span_actives');
                        $('#{{$tiv}}').addClass('star_span_actives');
                        $('#{{$tiv}}').prevAll(".star_span").addClass('star_span_actives');
                        $('#{{$tiv}}').nextAll(".star_span").removeClass('star_span_actives');

                    </script>
                @endif
            </div>
        </section>

        <script>
            const description = $('.description');
            const for_description = $('.for_description');
            const comments = $('#comments');
            const for_comments = $('#for_comments');
            const reviews = $('._reviews');
            const for_reviews = $('.for_reviews');


            if (window.location.hash === '#comments') {
                $(comments).addClass('comments_active');
                $(for_comments).addClass('for_comments_active');
                $(description).removeClass('description_active');
                $(for_description).removeClass('for_description_active');
                $(reviews).removeClass('_reviews_active');
                $('.for_reviews').removeClass('for_reviews_active');
            } else if (window.location.hash === '#reviews') {
                $(reviews).addClass('_reviews_active');
                $(for_reviews).addClass('for_reviews_active');
                $(description).removeClass('description_active');
                $(for_description).removeClass('for_description_active');
                $(comments).removeClass('comments_active');
                $(for_comments).removeClass('for_comments_active');
            }
        </script>

        <section class="wrapper">
            <div class="new_models_section sto_ d_flex also_like">
                <div class="d_flex sto_ for_mob_view">
                    <span class="second_title">Simillar Products</span>
                    <a href="{{route('frontend.category_slug', ['slug' => 'all-products'])}}" class="view_all">View
                        All</a>
                </div>
                <div class="products_slide sto_ d_flex gray_slider">
                    @php
                        $products = [];

                        if(!empty($product->product->categories)){
                            foreach ($product->product->categories as $currentCategory){
                                $categorySlugs[] = $currentCategory->slug;
                            }

                            $products = SellerProduct::with('skus', 'product.categories')
                                ->where('status', 1)
                                ->whereHas('product', function ($query) use ($categorySlugs) {
                                    $query->where('status', 1)
                                        ->whereHas('categories', function ($categoryQuery) use ($categorySlugs) {
                                            $categoryQuery->whereIn('slug', $categorySlugs);
                                        });
                                })->orderBy('created_at', 'desc')
                                ->get();
                        }

                    @endphp

                    @if(count($products) > 0)
                        @foreach($products as $product)
                            <div class="model_product">
                                <a href="{{singleProductURL(@$product->seller->slug, $product->slug, $product->product->categories[0]->slug)}}">
                                    <div class="model_img">
                                        <img @if ($product->thum_img != null) src="{{asset(asset_path($product->thum_img))}}"
                                             @else src="{{asset(asset_path(@$product->product->thumbnail_image_source))}}"
                                             @endif  alt="{{@$product->product_name?@$product->product_name:@$product->product->product_name}}">
                                    </div>
                                </a>

                                @if($product->discount != 0 )
                                    <span class="sale_red"> -@if($product->discount_type != 0)
                                            $
                                        @endif {{$product->discount}} @if($product->discount_type == 0)
                                            %
                                        @endif</span>
                                @endif
                                <div class="add_to_fav add_to_wishlist"
                                     @if(!empty(Wishlist::where('user_id',auth()->id())->where('seller_product_id',$product->id)->first()->id))
                                         data-wish="{{Wishlist::where('user_id',auth()->id())->where('seller_product_id',$product->id)->first()->id}}"
                                     @endif
                                     data-product_id="{{$product->id}}" data-seller_id="{{$product->user_id}}">
                                    @guest
                                        <svg width="27" height="23" viewBox="0 0 27 23" fill="none"
                                             xmlns="http://www.w3.org/2000/svg" class="add_to_wishlistt">
                                            <path d="M23.4972 1.67509C20.524 -0.836734 15.9617 -0.477902 13.1423 2.44402C10.2716 -0.477902 5.70932 -0.836734 2.73614 1.67509C-1.1085 4.90459 -0.544619 10.1846 2.22352 13.004L11.1943 22.1798C11.7069 22.6924 12.3734 23 13.1423 23C13.8599 23 14.5263 22.6924 15.039 22.1798L24.061 13.004C26.7779 10.1846 27.3418 4.90459 23.4972 1.67509ZM22.2669 11.261L13.2961 20.4369C13.1935 20.5394 13.091 20.5394 12.9372 20.4369L3.96642 11.261C2.06973 9.36436 1.7109 5.77604 4.32525 3.57178C6.32446 1.88014 9.40017 2.13645 11.3481 4.0844L13.1423 5.92982L14.9364 4.0844C16.8331 2.13645 19.9088 1.88014 21.908 3.52052C24.5224 5.77604 24.1636 9.36436 22.2669 11.261Z"
                                                  fill="#00AAAD"/>
                                        </svg>
                                    @endguest
                                    @auth
                                        @if(!empty(Wishlist::where('user_id',auth()->id())->where('seller_product_id',$product->id)->first()->id))
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
                                           href="{{singleProductURL(@$product->seller->slug, $product->slug, $product->product->categories[0]->slug)}}">
                                            @if($product->product_name != null)
                                                {{ @$product->product_name }}
                                            @else
                                                {{$product->product->product_name}}
                                            @endif</a>
                                        {{--                                        <span class="data_of_model">--}}
                                        {{--                                            {{$product->created_at->toDateString()}}--}}
                                        {{--                                </span>--}}
                                    </div>

                                    <div class="d_flex sto_ for_sale_height">

                                        <span class="twenty_sp">
                                            @php
                                                $productFileTypesTxts = ProductService::getProductFileTypes($product);
                                                echo implode(", ", $productFileTypesTxts);
                                            @endphp
                                        </span>

                                        <div class='d_flex sale_price_col'>
                                            @if(($product->hasDeal || $product->hasDiscount == 'yes') && @$product->skus->first()->selling_price)
                                                <span class="prev_price">
                                                @php
                                                    if(ExchangeController::getInstance()->needToConvert()){
                                                        $convertedPrice = ExchangeController::getInstance()->convertPriceToAMD($product->skus->max('selling_price'), 'USD');
                                                        echo $convertedPrice['price'] . ExchangeController::getInstance()->getAMDSymbol();
                                                    }else{
                                                        echo  ExchangeController::getInstance()->getUSDSymbol() .  $product->skus->max('selling_price');
                                                    }
                                                @endphp
                                            </span>
                                            @endif
                                            <span class="price_of_prod">
                                                @if($product->hasDeal)
                                                    {{single_price(selling_price(@$product->skus->first()->selling_price,$product->hasDeal->discount_type,$product->hasDeal->discount))}}
                                                @else

                                                    @if($product->hasDiscount == 'yes')
                                                        {{@$product->skus->first()->selling_price == 0 ? 'Free' : single_price(selling_price(@$product->skus->first()->selling_price,$product->discount_type,$product->discount))}}
                                                    @else
                                                        {{@$product->skus->first()->selling_price == 0  ? 'Free' : single_price(@$product->skus->first()->selling_price)}}
                                                    @endif

                                                @endif
                                            </span>
                                        </div>
                                    </div>

                                    @php
                                        $disabledAddToCartClass = "";
                                        if( CartService::isProductInCart($product)) {
                                          $disabledAddToCartClass = "disabled";
                                        }
                                    @endphp
                                    <a @if(single_price($product->skus->max('selling_price')) == '$ 0.00')
                                           @auth
                                               href="{{$product->product->video_link}}"
                                       class="add_catalog_btn"
                                       @else
                                           class="add_catalog_btn login_btn"

                                       @endauth
                                       @else
                                           @auth class="{{ $disabledAddToCartClass }} addToCartFromThumnail add_catalog_btn"
                                       @elseif(single_price($product->skus->max('selling_price')) == '$ 0.00') class="{{ $disabledAddToCartClass }} addToCartFromThumnail add_catalog_btn"
                                       @else class="add_catalog_btn" @endauth
                                       @endif
                                       tabindex="-1"
                                       data-producttype="{{ @$product->product->product_type }}"
                                       data-seller={{ $product->user_id }} data-product-sku={{ @$product->skus->first()->id }}
                                       @if(@$product->hasDeal)
                                               data-base-price={{ selling_price(@$product->skus->first()->selling_price,@$product->hasDeal->discount_type,@$product->hasDeal->discount, @$product->tax) }}
                                       @else
                                        data-base-price={{selling_price( @$product->skus->first()->selling_price,  @$product->discount_type, @$product->discount,  @$product->tax) }}

                                       @endif
                                               data-shipping-method=0
                                       data-product-id={{ $product->id }}
                                               data-stock_manage="{{$product->stock_manage}}"
                                       data-stock="{{@$product->skus->first()->product_stock}}"
                                       data-min_qty="{{$product->product->minimum_order_qty}}"
                                    >
                                        @if(single_price($product->skus->max('selling_price')) == '$ 0.00')
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
                                </div>
                            </div>

                        @endforeach
                    @endif

                </div>
            </div>

        </section>

    </main>
@endsection

@section('js')
    <script>
        $(document).ready(function () {

            $('.rate_model').click(function () {
                if ($(this).attr('data-like') !== undefined) {
                    review_store($(this).attr('data-id'), $(this).attr('data-slug'), $('#textareaa1').val(), $(this).attr('data-like'))
                }
            })

            const slides = Array.from(document.querySelector(".zoom_imgs_block").children)
            document.querySelector(".general_big_img").addEventListener("click", () => {
                document.querySelectorAll(".mySlides").forEach((slide) => {
                    slide.style.display = "none"
                })
                const current = slides.find(el => el.children[0].src == document.querySelector(".general_big_img").src)
                current.style.display = "block"
            })
            document.querySelector(".prev").addEventListener("click", () => {
                const current = slides.find(el => el.style.display == "block")
                let count = slides.indexOf(current) - 1
                if (count <= 1) {
                    count = slides.length - 1
                }

                document.querySelector(".zoom_imgs_block").children[count].style.display = "block"
                current.style.display = "none"
            })
            document.querySelector(".next").addEventListener("click", () => {
                const current = slides.find(el => el.style.display == "block")
                let count = slides.indexOf(current) + 1
                if (count >= slides.length) {
                    count = 1
                }

                document.querySelector(".zoom_imgs_block").children[count].style.display = "block"
                current.style.display = "none"
            })

            // The case when clicked "Review" in Dashboard/My Purchases page
            if (window.location.search === '?review') {
                const targetOffset = $("._reviews").offset().top - 400;

                setTimeout(function () {
                    $("html, body").animate({
                        scrollTop: targetOffset
                    }, 300)

                    $('._reviews').trigger('click');
                    $('.write_review').trigger('click');
                }, 2000)
            }
        })
    </script>
@endsection
