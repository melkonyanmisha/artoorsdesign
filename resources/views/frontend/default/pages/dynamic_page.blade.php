@extends('frontend.default.layouts.newApp')

@if(str_contains(url()->current(), 'terms/conditions'))
    @php $terms = App\Models\ThermSeo::first(); @endphp
    @section('title'){{$terms->title}}@endsection
    @section('share_meta')
        @php
            if($terms->scheme_markup){
                echo '<script type="application/ld+json">';
                echo $terms->scheme_markup;
                echo '</script>';
            }
        @endphp
        <meta name="title" content="{{$terms->meta_title}}"/>
        <meta name="description" content="{{$terms->meta_description}}"/>
        <meta property="og:title" content="{{$terms->meta_title}}"/>
        <meta property="og:description" content="{{$terms->meta_description}}"/>
        <meta property="og:url" content="{{URL::full()}}" />
        <meta property="og:image" content="{{showImage($terms->meta_image)}}" />
        <meta property="og:image:width" content="400"/>
        <meta property="og:image:height" content="300"/>
        <meta property="og:image:alt" content="{{$terms->meta_image_alt}}"/>
        <meta property="og:type" content="website"/>
        <meta property="og:locale" content="en_EN"/>
        <meta name ="keywords" content="{{$terms->meta_keyword}}">
    @endsection
@elseif(str_contains(url()->current(), 'privacy/policy'))
    @php $privacy = App\Models\PrivacySeo::first(); @endphp
    @section('title'){{$privacy->title}}@endsection
    @section('share_meta')
        @php
            if($privacy->scheme_markup){
                echo '<script type="application/ld+json">';
                echo $privacy->scheme_markup;
                echo '</script>';
            }
        @endphp
        <meta name="title" content="{{$privacy->meta_title}}"/>
        <meta name="description" content="{{$privacy->meta_description}}"/>
        <meta property="og:title" content="{{$privacy->meta_title}}"/>
        <meta property="og:description" content="{{$privacy->meta_description}}"/>
        <meta property="og:url" content="{{URL::full()}}" />
        <meta property="og:image" content="{{showImage($privacy->meta_image)}}" />
        <meta property="og:image:width" content="400"/>
        <meta property="og:image:height" content="300"/>
        <meta property="og:image:alt" content="{{$privacy->meta_image_alt}}"/>
        <meta property="og:type" content="website"/>
        <meta property="og:locale" content="en_EN"/>
        <meta name ="keywords" content="{{$privacy->meta_keyword}}">
    @endsection
@endif

@if($pageData->is_page_builder == 1)

{{--@section('styles')--}}
{{--    @if($pageData->module == 'Affiliate')--}}
{{--        <link rel="stylesheet" type="text/css" href="{{asset('Modules/PageBuilder/Resources/assets/css/affiliate.css')}}">--}}
{{--    @endif--}}
{{--    <style>--}}
{{--        .row{--}}
{{--            margin: 0!important;--}}
{{--        }--}}
{{--        a:hover {--}}
{{--            color: var(--background_color) !important;--}}
{{--        }--}}
{{--    </style>--}}
{{--@endsection--}}

@section('content')
    @php echo $pageData->description; @endphp

@endsection

@else

@section('breadcrumb')
    @php

        $arr = explode(' ',trim($pageData->title));
    @endphp
    {{$pageData->title}}
@endsection
{{--@section('title')--}}
{{--    @php--}}

{{--        $arr = explode(' ',trim($pageData->title));--}}
{{--    @endphp--}}
{{--    {{$pageData->title}}--}}
{{--@endsection--}}

@section('content')
    <main>
        @include('frontend.default.includes.mainInclude')
        <section class="wrapper">
        @section('breadcrumb')
            {{ __('common.category') }}
        @endsection
        @include('frontend.default.partials._breadcrumb')


        <div class="title_dascription">
            <h1> {{$pageData->title}} </h1>
            <div class='term_use_cond'>
                {!! html_entity_decode($pageData->description) !!}

            </div>
{{--            @php echo $pageData->description; @endphp--}}

        </div>

        </section>
    </main>

    <!-- policy part end -->

@endsection

@endif
