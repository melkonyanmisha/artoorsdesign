@extends('frontend.default.layouts.newApp')

@section('title'){{$post->title}}@endsection

@section('share_meta')
    @php
        if($post->scheme_markup){
            echo '<script type="application/ld+json">';
            echo $post->scheme_markup;
            echo '</script>';
        }
    @endphp
    <meta name="title" content="{{$post->meta_title}}"/>
    <meta name="description" content="{{$post->meta_description}}"/>
    <meta property="og:title" content="{{$post->meta_title}}"/>
    <meta property="og:description" content="{{$post->meta_description}}"/>
    <meta property="og:url" content="{{URL::full()}}" />
    <meta property="og:image" content="{{showImage($post->image_url)}}" />
    <meta property="og:image:width" content="400"/>
    <meta property="og:image:height" content="300"/>
    <meta property="og:image:alt" content="{{$post->title}}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:locale" content="en_EN"/>
    <meta name ="keywords" content="{{$post->meta_keywords}}">
@endsection

@section('content')
    <main>
    @include('frontend.default.includes.mainInclude')

<!-- blog part here -->
<section class="blog_part bg-white padding_top">
<div class="ne-znayu">
    <div class="d_flex from_to ">
        <a class="from_this" href="{{url('/')}}">Home</a>
        <span class="slashes">/</span>
        <a class="from_this" href="{{url('/blog')}}">Blog</a>
        <span class="slashes">/</span>
        <span class="this_page">{{$post->title}}</span>
    </div>
</div>
    <div class="container">
        <div class="row">

            <div class="col-lg-9">


                <div class="blog_details_part">
                    <div class="blog_details_img">

                        @if(isset($post->image_url))
                        <img src="{{showImage($post->image_url)}}" alt="{{$post->image_alt}}">
                        @endif
                    </div>
                    <div class="blog_details_content">
                        <div class="blog_details_text">
                            <h1>{{$post->title}}</h1>
                            <p id="laraberg">@php echo $post->content; @endphp</p>
                        </div>



                </div>
            </div>
        </div>
            

        </div>
    </div>
</section>
<!-- blog part end -->
    </main>

@endsection
@push('scripts')
    <script type="text/javascript">

        (function($){
            "use strict";
            $(document).ready(function(){
                $(".for_submit").on('click',function(){
                    $("#comment_form").submit();
                });

                $(document).on("click", ".reply",function(){
                    $('.reply-form').toggle();
                    $('.reply-form').empty();
                    var well = $(this).closest('.media-body');
                    var cid = $(this).attr("cid");
                    var pid = $(this).attr('post_id');
                    var token = $(this).attr('token');
                    var form = '<form method="post" action="{{route('blog.replay')}}"><input type="hidden" name="_token" value="'+token+'"><input type="hidden" name="comment_id" value="'+ cid +'"><input type="hidden" name="post_id" value="'+pid+'"><div class="form-group"><textarea class="form-control" name="replay" placeholder="Enter your reply" > </textarea> </div> <div class="form-group"> <input class="btn btn-primary" type="submit"> </div></form>';

                    well.find(".reply-form").append(form);

                });


                //replay replay
                $(document).on("click", ".rreply",function(){
                    $('.rreply-form').toggle();
                    $('.rreply-form').empty();
                    var well = $(this).closest('.media-body');
                    var cid = $(this).attr("cid");
                    var pid = $(this).attr('post_id');
                    var token = $(this).attr('token');
                    var replay_id =$(this).attr('replay_id');
                    var form = '<form method="post" action="{{route('blog.replay')}}"><input type="hidden" name="_token" value="'+token+'"><input type="hidden" name="comment_id" value="'+ cid +'"><input type="hidden" name="post_id" value="'+pid+'"><input type="hidden" name="replay_id" value="'+replay_id+'"><div class="form-group"><textarea class="form-control" name="replay" placeholder="Enter your reply" > </textarea> </div> <div class="form-group"> <input class="btn btn-primary" type="submit"> </div></form>';

                    well.find(".rreply-form").append(form);

                });

                //replay replay replay
                $(document).on("click", ".rrreply",function(){
                    $('.rrreply-form').toggle();
                    $('.rrreply-form').empty();
                    var well = $(this).parent().parent().parent().parent();
                    var cid = $(this).attr("cid");
                    var pid = $(this).attr('post_id');
                    var token = $(this).attr('token');
                    var replay_id =$(this).attr('replay_id');
                    var form = '<form method="post" action="{{route('blog.replay')}}"><input type="hidden" name="_token" value="'+token+'"><input type="hidden" name="comment_id" value="'+ cid +'"><input type="hidden" name="post_id" value="'+pid+'"><input type="hidden" name="replay_id" value="'+replay_id+'"><div class="form-group"><textarea class="form-control" name="replay" placeholder="Enter your reply" > </textarea> </div> <div class="form-group"> <input class="btn btn-primary" type="submit"> </div></form>';

                    well.find(".rrreply-form").append(form);

                });


                $(document).on('click','.likebtn',function(){

                    var formData= new FormData();
                    var pid = $(this).attr('pid');
                    var c = $('#like-bs3').html();


                    formData.append('_token', "{{ csrf_token() }}");
                    formData.append('pid', pid);
                    $.ajax({
                        url: "{{ route('blog.post.like') }}",
                        type: "POST",
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        success: function(response) {
                            if (response.dislike) {
                                toastr.success(response.dislike)
                                $('#like-bs3').html(parseInt(c)-1);
                                $('.likebtn').addClass("btn-info");
                            }
                            else if (response.like) {
                                toastr.success(response.like)
                                $('#like-bs3').html(parseInt(c)+1);
                                $('.likebtn').removeClass("btn-info");
                            }


                        },
                        error: function(response) {
                            toastr.error("{{__('common.error_message')}}","{{__('common.error')}}");
                        }
                    });

                });

                $(document).on('click', '.guest_btn_class', function(event){
                    event.preventDefault();
                    toastr.info('To add favorite list. You need to login first.','Info',{closeButton: true,progressBar: true,});
                });

            });
        })(jQuery);

    </script>
@endpush
