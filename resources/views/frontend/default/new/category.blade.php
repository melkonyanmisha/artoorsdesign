@extends('frontend.default.layouts.newApp')

@if((!isset(request()->item) && request()->item != 'search'))
    @php
        $cat = '';

//        if(str_contains($_SERVER['REQUEST_URI'], "category")){
//            $cat = str_replace('category', '', $_SERVER['REQUEST_URI']);
//
//            if(str_contains($cat, "/")){
//                $cat = str_replace('/', '', $cat);
//            }
//        }

        if(isset($slug)){
            $cat = $slug;
        }

        if($cat == 'all-products'){
            $cat = 'all-designs';
        }

        $category_seo = Modules\Product\Entities\Category::where('status', 1)->where('searchable', 1)->where('slug', $cat)->first();

        if(!isset($category_seo)){
            return abort(404);
        }
    @endphp

    @section('title'){{$category_seo->meta_title}}@endsection
    @section('share_meta')
        @php
            if($category_seo->scheme_markup){
                echo '<script type="application/ld+json">';
                echo $category_seo->scheme_markup;
                echo '</script>';
            }
        @endphp
        <meta name="title" content="{{$category_seo->meta_title}}"/>
        <meta name="description" content="{{$category_seo->meta_description}}"/>
        <meta property="og:title" content="{{$category_seo->meta_title}}"/>
        <meta property="og:description" content="{{$category_seo->meta_description}}"/>
        <meta property="og:url" content="{{URL::full()}}" />
        <meta property="og:image" content="{{showImage($category_seo->meta_image)}}" />
        <meta property="og:image:width" content="400"/>
        <meta property="og:image:height" content="300"/>
        <meta property="og:image:alt" content="{{$category_seo->meta_image_alt}}"/>
        <meta property="og:type" content="website"/>
        <meta property="og:locale" content="en_EN"/>
        <meta name ="keywords" content="{{$category_seo->meta_keyword}}">
    @endsection
@else
    @section('title'){{"Search Result $slug | Artoors Designe"}}@endsection
    @section('share_meta')

        <meta name="title" content="{{$slug}}"/>
        <meta name="description" content="Get the best results about 3d Modelings when you search with us. Our powerful search engine quickly finds what you're looking for and provides accurate, comprehensive results. Get started now and find the best matches for {{$slug}}"/>
        <meta property="og:title" content="Search Result {{$slug}} | Artoors Designe"/>
        <meta property="og:description" content="Get the best results about 3d Modelings when you search with us. Our powerful search engine quickly finds what you're looking for and provides accurate, comprehensive results. Get started now and find the best matches for {{$slug}}"/>
        <meta property="og:url" content="{{URL::full()}}" />

        @if((isset($_GET['page']) && $_GET['page'] == 1 && $products->lastPage() != $_GET['page']) || (!isset($_GET['page']) && $products->lastPage() != 1))
            <link rel="next canonical">
        @elseif($products->lastPage() != 1 && isset($_GET['page']) && $_GET['page'] != $products->lastPage() && $_GET['page'] != 1)
            <link rel="next prev canonical">
        @elseif(isset($_GET['page']) && $_GET['page'] != $products->lastPage())
            <link rel="prev canonical">
        @endif
    @endsection
@endif

@section('content')
    <main>
        @include('frontend.default.includes.mainInclude')
            <section class="wrapper">
                @section('breadcrumb')
                    {{ __('common.category') }}
                @endsection
                @include('frontend.default.partials._breadcrumb')
                <div class="d_flex catalog_section">
                    <div class="all_category">
                        <div class="d_flex all_category_title">
                            <span class="allctgr">
                                @yield('breadcrumb')
                            </span>
                        </div>
                        <div class="catalog_filter">

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
                                        <a href="{{route('frontend.category_slug',['slug' => $category->slug])}}"
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
                    <div class="d_flex fl_end" >
                        @if((!isset(request()->item) && request()->item != 'search'))
                            <section class="category_about_us">
                                <div class="wrapper_for_swiper wrapper" >
                                    <div class="category_about_us_section d_flex">
                                        <div class="d_flex sto_">
                                            <h1 class="block_title">{{$category_seo->title}}</h1>
                                        </div>
                                        <div class="category_about_us" style="text-align: justify; width: 100%">
                                            {!! $category_seo->description !!}
                                        </div>
                                    </div>
                                </div>
                            </section>
                        @else
                            <div class="category_about_us_section d_flex">
                                <div class="d_flex sto_">
                                    @if(str_contains("page", request()->url()) && isset($_GET['page']))
                                        <h1 style="font-size: 25px; margin-bottom: 15px">Search Result {{$slug}} – Page {{$_GET['page']}}</h1>
                                    @else
                                        <h1 style="font-size: 25px; margin-bottom: 15px">Search Result {{$slug}} – Page 1</h1>
                                    @endif
                                </div>
                                <div class="category_about_us" style="width: 100%">
                                    <p style="text-align: justify">Here you will find the best matches for your {{$slug}} quickly & easily! Our leading search engine technology allows you to discover and explore the best content on the Artoors Design website, tailored specifically to your needs. With powerful filters and advanced search tools, never miss out on relevant information again</p>
                                </div>
                            </div>
                        @endif
                        @if(isset($products) && count($products))
                        <div class="sort_by">
                            <div class="sortby_sp d_flex">
                                Sort by
                                <svg width="15" height="9" viewBox="0 0 15 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 1L7.5 7.5L14 1" stroke="#717171" stroke-width="2"/>
                                </svg>
                            </div>
                            <div class="sorts_drpdwn ">
                                <span class="sort_sp getFilterUpdateByIndex sort_sp_active" data-id="new">{{ __('common.new') }}</span>
                                <span class="sort_sp getFilterUpdateByIndex" data-id="old">{{ __('common.old') }}</span>
                                <span class="sort_sp getFilterUpdateByIndex" data-id="alpha_asc">{{ __('defaultTheme.name_a_to_z') }}</span>
                                <span class="sort_sp getFilterUpdateByIndex" data-id="alpha_desc">{{ __('defaultTheme.name_z_to_a') }}</span>
                                <span class="sort_sp getFilterUpdateByIndex"
                                      data-id="low_to_high">{{ __('defaultTheme.price_low_to_high') }}</span>
                                <span class="sort_sp getFilterUpdateByIndex"
                                      data-id="high_to_low">{{ __('defaultTheme.price_high_to_low') }}</span>
                            </div>
                        </div>
                        @endif
                        <div class="products">
                            @if(isset($products) && count($products))
                                @include('frontend.default.partials.category_paginate_data')
                            @else
                                <div  class="sto_ new_class" >
                                    <img src="{{asset(asset_path('/new/img/product_not_found.png'))}}">
                                    <span class="about_error_sp">{{__("common.product_not_found")}}</span>
                                </div>
                            @endif
                        </div>
                        @if(isset($products) && count($products))
                        <div class="d_flex pagination">
                            <div class='choose_pagination_quantity d_flex'> <span> Per page: </span>
                                    <a class="per_page per_page_active" data-id="12">12</a>
                                    <a class="per_page" data-id="24">24</a>
                                    <a class="per_page" data-id="36">36</a>
                                    <a class="per_page" data-id="72">72</a>
                                    <a class="per_page" data-id="144">144</a>
                            </div>
                        </div>
                        @endif
{{--                                @if($products->lastPage() > 1)--}}

{{--                                <div class="d_flex pagination_block">--}}
{{--                                    <a href="{{ $products->previousPageUrl() }}" class="prev_next_page">--}}
{{--                                        <svg width="9" height="15" viewBox="0 0 9 15" fill="none" xmlns="http://www.w3.org/2000/svg">--}}
{{--                                            <path d="M8 1.25L2 7.25L8 13.25" stroke="#282828" stroke-width="2.25"/>--}}
{{--                                        </svg>--}}
{{--                                    </a>--}}
{{--                                    @if($products->lastPage() > 1)--}}
{{--                                        <a href="{{ $products->url(1) }}"--}}
{{--                                           class="pagination_sp @if($products->currentPage() == 1) pagination_sp_active @endif">1</a>--}}
{{--                                    @endif--}}
{{--                                    @if($products->lastPage() > 2)--}}
{{--                                        <a href="{{ $products->url(2) }}"--}}
{{--                                           class="pagination_sp @if($products->currentPage() == 2) pagination_sp_active @endif">2</a>--}}
{{--                                    @endif--}}
{{--                                    @if($products->lastPage() > 3)--}}
{{--                                        <a href="{{ $products->url(3) }}"--}}
{{--                                           class="pagination_sp @if($products->currentPage() == 3) pagination_sp_active @endif">3</a>--}}
{{--                                    @endif--}}
{{--                                    @if( $products->lastPage() > 4)--}}
{{--                                        <a>...</a>--}}
{{--                                    @endif--}}
{{--                                    <a href="{{ $products->url($products->lastPage()) }}"--}}
{{--                                       class="pagination_sp  @if($products->currentPage() == $products->lastPage()) pagination_sp_active @endif">{{ $products->lastPage() }}</a>--}}
{{--                                    <a href="{{ $products->nextPageUrl()  }}" class="prev_next_page">--}}
{{--                                        <svg width="9" height="15" viewBox="0 0 9 15" fill="none" xmlns="http://www.w3.org/2000/svg">--}}
{{--                                            <path d="M1.25 1.25L7.25 7.25L1.25 13.25" stroke="#282828" stroke-width="2.25"/>--}}
{{--                                        </svg>--}}
{{--                                    </a>--}}
{{--                                </div>--}}
{{--                                @endif--}}
                    </div>
                </div>
            </section>
    </main>
@endsection
@section('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            $('body').on('click', '.pagination a', function(e) {
                e.preventDefault();
                let sorting = $('.sorts_drpdwn').find('.sort_sp_active').data('id');
                let url = '{{Request::fullUrl()}}';
                let pagination = $('.choose_pagination_quantity').find('.per_page_active').data('id');
                let page = $(this).data('id');

                let fullUrl = url;

                if (sorting) {
                    @if(isset($_GET['item']))
                        fullUrl = fullUrl + '&sort_by=' + sorting;
                    @else
                        fullUrl = fullUrl + '?sort_by=' + sorting;
                    @endif
                }

                if (pagination) {
                    fullUrl = fullUrl + '&paginate_id=' + pagination;
                }

                fullUrl = fullUrl.replace("amp;", "");

                if (page) {
                    fullUrl = fullUrl + '&page=' + page;
                }

                window.history.pushState('', '', fullUrl);

                sendAjaxQuery(fullUrl);
            });

            $('.sorts_drpdwn').find('.sort_sp').each(function () {
                $(this).click(function () {
                    $('.sorts_drpdwn').find('.sort_sp_active').removeClass('sort_sp_active');
                    $(this).addClass('sort_sp_active');
                    let sorting = $(this).data('id');
                    let url = '{{Request::fullUrl()}}';
                    let pagination = $('.choose_pagination_quantity').find('.per_page_active').data('id');

                    let page = $('.pagination_block').find('.pagination_sp_active').data('id');

                    $('body').on('click', '.pagination a', function (e) {
                        e.preventDefault();

                        page = $(this).data('id');
                    });

                    let fullUrl = url;

                    if (sorting) {
                        @if(isset($_GET['item']))
                            fullUrl = fullUrl + '&sort_by=' + sorting;
                        @else
                            fullUrl = fullUrl + '?sort_by=' + sorting;
                        @endif
                    }

                    if (pagination) {
                        fullUrl = fullUrl + '&paginate_id=' + pagination;
                    }

                    fullUrl = fullUrl.replace("amp;", "");

                    if (page) {
                        fullUrl = fullUrl + '&page=' + page;
                    }

                    window.history.pushState('', '', fullUrl);

                    sendAjaxQuery(fullUrl);
                });
            });
            $('.choose_pagination_quantity').find('.per_page').each(function () {
                $(this).click(function () {
                    $('.choose_pagination_quantity').find('.per_page_active').removeClass('per_page_active');
                    $(this).addClass('per_page_active');
                    let sorting = $('.sorts_drpdwn').find('.sort_sp_active').data('id');
                    let url = '{{Request::fullUrl()}}';
                    let pagination = $(this).data('id');

                    let page = $('.pagination_block').find('.pagination_sp_active').data('id');

                    $('body').on('click', '.pagination a', function (e) {
                        e.preventDefault();

                        page = $(this).data('id');
                    });

                    let fullUrl = url;

                    if (sorting) {
                        @if(isset($_GET['item']))
                            fullUrl = fullUrl + '&sort_by=' + sorting;
                        @else
                            fullUrl = fullUrl + '?sort_by=' + sorting;
                        @endif
                    }

                    if (pagination) {
                        fullUrl = fullUrl + '&paginate_id=' + pagination + '&page=1';
                    }

                    fullUrl = fullUrl.replace("amp;", "");

                    if (page) {
                        fullUrl = fullUrl + '&page=' + page;
                    }

                    window.history.pushState('', '', fullUrl);

                    sendAjaxQuery(fullUrl);
                });
            });
            $('.pagination_sp').each(function () {
                $(this).click(function () {
                    $('.pagination_block').find('.pagination_sp_active').removeClass('pagination_sp_active');
                    $(this).addClass('pagination_sp_active');
                });
            });
            function sendAjaxQuery(fullUrl){
                let page = fullUrl.charAt(fullUrl.length - 1);
                $.ajax({
                    url: fullUrl,
                    data: {
                        type: 'ajax'
                    },
                    success: function(response) {
                        $('.products').empty();
                        $('.products').html(response.search);

                        @if((isset(request()->item) && request()->item == 'search'))
                        if(page == 1 && '{{$products->lastPage()}}' != page && '{{$products->lastPage()}}' != 1){
                            document.querySelector("link").rel = 'next canonical';
                        }else if('{{$products->lastPage()}}' != 1 && page != '{{$products->lastPage()}}' && page != 1){
                            document.querySelector("link").rel = 'next prev canonical';
                        }else if(page == '{{$products->lastPage()}}'){
                            document.querySelector("link").rel = 'prev canonical';
                        }
                        @endif
                    },
                });
            }
        });
    </script>
@endsection