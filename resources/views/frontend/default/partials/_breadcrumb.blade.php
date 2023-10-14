@php
    $actual_link = \Illuminate\Support\Facades\URL::current();
    $base_url = url('/');
@endphp
<!-- breadcrumb part here -->
{{--<section class="breadcrumb_cs">--}}
{{--    <div class="container">--}}
{{--        <div class="row">--}}
{{--            <div class="col-lg-12">--}}
{{--                <div class="breadcrumb_content d-flex justify-content-between align-items-center">--}}
{{--                    <h2> @yield('breadcrumb') </h2>--}}
{{--                    <div class="troggle_btn">--}}
{{--                        <label class='toggle-label'>--}}
{{--                            <input id="bredCumb_switch" type='checkbox' />--}}
{{--                            <span class='back'>--}}
{{--                                <span class='toggle'></span>--}}
{{--                                <span class='label {{$actual_link == $base_url?'on':'off'}}'>{{ __('common.home') }} </span>--}}
{{--                                <span class='label {{$actual_link != $base_url?'on':'off'}}'> {{isset($name)?$name:'This Page'}} </span>--}}
{{--                            </span>--}}
{{--                        </label>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</section>--}}
@if(isset($cat))
    @if($cat == 'all-designs')
        <div class="d_flex from_to">
            <a class="from_this" href="{{url('/')}}">{{ __('common.home') }}</a>
            <span class="slashes">/</span>
            <a href="{{route('frontend.category_slug',['slug' => 'all-products'])}}"><span class="this_page">@yield('breadcrumb')</span></a>
        </div>
    @else
        <div class="d_flex from_to">
            <a class="from_this" href="{{url('/')}}">{{ __('common.home') }}</a>
            <span class="slashes">/</span>
            <a href="{{route('frontend.category_slug',['slug' => 'all-products'])}}"><span class="this_page">@yield('breadcrumb')</span></a>
            <span class="slashes">/</span>
            <a href="{{route('frontend.category_slug',['slug' => $cat])}}"><span class="this_page">{{ucfirst($cat)}}</span></a>
        </div>
    @endif
@else
<div class="d_flex from_to">
    <a class="from_this" href="{{url('/')}}">{{ __('common.home') }}</a>
    <span class="slashes">/</span>
    <span class="this_page">@yield('breadcrumb')</span>
</div>
@endif
<!-- breadcrumb part end -->
