@extends('frontend.default.layouts.newApp')

@php $blog_seo = App\Models\BlogSeo::first(); @endphp

@section('title'){{$blog_seo->title}}@endsection

@section('share_meta')
    @php
        if($blog_seo->scheme_markup){
            echo '<script type="application/ld+json">';
            echo $blog_seo->scheme_markup;
            echo '</script>';
        }
    @endphp
    <meta name="title" content="{{$blog_seo->meta_title}}"/>
    <meta name="description" content="{{$blog_seo->meta_description}}"/>
    <meta property="og:title" content="{{$blog_seo->meta_title}}"/>
    <meta property="og:description" content="{{$blog_seo->meta_description}}"/>
    <meta property="og:url" content="{{URL::full()}}" />
    <meta property="og:image" content="{{showImage($blog_seo->meta_image)}}"/>
    <meta property="og:image:width" content="400"/>
    <meta property="og:image:height" content="300"/>
    <meta property="og:image:alt" content="{{$blog_seo->meta_image_alt}}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:locale" content="en_EN"/>
    <meta name ="keywords" content="{{$blog_seo->meta_keyword}}">
@endsection

@section('content')
    <main>
    @include('frontend.default.includes.mainInclude')
<!-- blog part here -->
        <section class="blog_part bg-white padding_top wrapper">
            <div class="d_flex from_to">
                <a class="from_this" href="{{url('/')}}">Home</a>
                <span class="slashes">/</span>
                <span class="this_page">Blog</span>
            </div>
            <h1>BLOG</h1>
    <div class="container">
        <div class="row">
            <div class="col-lg-9">
                <div class="blog_post">
                @if($posts->count() > 0)
                   @foreach($posts as $post)
                    <div class="single_blog_post d-flex align-items-center">
                        <div class="single_blog_post_img" >
                            <div class="blog_img_main_div">
                            <a class="blog_img_div" href="{{route('blog.single.page',$post->title)}}">
                              <img src="{{
                              isset($post->image_url)? showImage($post->image_url):showImage('backend/img/default.png')}}" alt="{{$post->image_alt}}">
                            </a>
                            </div>

                        </div>
                        <div class="single_blog_post_content">
                        <h4><a href="{{route('blog.single.page',$post->title)}}">{{$post->title}}</a></h4>
                            <p>{{$post->excerpt}}</p>
                            <div class="blog_post_details">
                                <a href="javascript:void(0);"> <i class="ti-calendar"></i> {{ \Carbon\Carbon::parse($post->published_at)->format('d/m/Y')}}</a>


                            </div>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="col-lg-12 col-md-12">
                        <div class="card h-100">
                            <div class="single-post post-style-1 p-2">
                            <strong>{{ __('blog.no_post_found') }}</strong>
                            </div><!-- single-post -->
                        </div><!-- card -->
                    </div><!-- col-lg-4 col-md-6 -->
                    @endif
                    </div>

                <div class="pagination_part">
                    <nav aria-label="Page navigation example">
                        @if ($posts->lastPage() > 1)
                        <ul class="pagination">
                            <li class="{{ ($posts->currentPage() == 1) ? ' disabled' : '' }} page-item">
                                <a class="page-link" href="{{ $posts->url(1) }}"><i class="ti-arrow-left"></i></a>
                            </li>
                            @for ($i = 1; $i <= $posts->lastPage(); $i++)
                                <li class="{{ ($posts->currentPage() == $i) ? ' active' : '' }} page-item">
                                    <a class="page-link"  href="{{ $posts->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor
                            <li class="{{ ($posts->currentPage() == $posts->lastPage()) ? ' disabled' : '' }} page-item">
                                <a href="{{ $posts->url($posts->currentPage()+1) }}" class="page-link" ><i class="ti-arrow-right"></i></a>
                            </li>
                        </ul>
                        @endif
                    </nav>
                </div>

            </div>


        </div>
    </div>
</section>
<!-- blog part end -->
    </main>


@endsection
