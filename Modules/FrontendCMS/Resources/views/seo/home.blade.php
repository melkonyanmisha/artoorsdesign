@extends('backEnd.master')

@section('styles')
    <link rel="stylesheet" href="{{ asset(asset_path('backend/vendors/css/icon-picker.css')) }}" />

    <link rel="stylesheet" href="{{asset(asset_path('modules/product/css/product_edit.css'))}}" />
    <style>
        .link_color{
            color: var(--text_color);
        }
    </style>
    <style>
        #myProgress {
            width: 100%;
            background-color: #ddd;
            border-radius: 30px;
        }
        #myProgress1 {
            width: 100%;
            background-color: #ddd;
            border-radius: 30px;
        }
        #myProgress2 {
            width: 100%;
            background-color: #ddd;
            border-radius: 30px;
        }

        #myBar {
            width: 0%;
            height: 30px;
            background-color: #c738d8;
            text-align: center;
            line-height: 30px;
            color: white;
        }

        #myBar1 {
            width: 0%;
            height: 30px;
            background-color: #c738d8;
            text-align: center;
            line-height: 30px;
            color: white;
        }

        #myBar2 {
            width: 0%;
            height: 30px;
            background-color: #c738d8;
            text-align: center;
            line-height: 30px;
            color: white;
        }
    </style>
@endsection
@section('mainContent')

    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-20 white_box">
            <form action="{{route('update.home-seo',['id' => $info->id])}}" method="POST" enctype="multipart/form-data"
                  id="choice_form">
                @csrf
                @method('PUT')
                <div class="row justify-content-center">
                    <div class="col-12">
                        <div class="box_header common_table_header">
                            <div class="main-title d-md-flex">
                                <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">Edit Home Page SEO</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <ul class="nav nav-tabs justify-content-end mt-sm-md-20 mb-30 grid_gap_5" role="tablist">


                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade active show" id="GenaralInfo">

                        <div class="row">
                            <div class="col-lg-8">
                                <div class="white_box_50px box_shadow_white mb-20 pt-0 p-15">
                                    <div class="row">

                                        <input type="hidden" name="id" value="{{$info->id}}">



                                        <div class="col-lg-12">
                                            <div class="main-title d-flex">
                                                <h3 class="mb-3 mr-30">{{ __('common.seo_info') }}</h3>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="primary_input mb-15">
                                                <label class="primary_input_label" for="">
                                                    {{ __('common.title') }}</label>
                                                <input class="primary_input_field" name="title"
                                                       placeholder="{{ __('common.title') }}" type="text"
                                                       value="{{$info->title}}">
                                                <span class="text-danger">{{ $errors->first('title') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="primary_input mb-15">
                                                <label class="primary_input_label" for="">
                                                    {{ __('common.meta_title') }}</label>
                                                <input class="primary_input_field" name="meta_title"
                                                       placeholder="{{ __('common.meta_title') }}" type="text"
                                                       value="{{$info->meta_title}}">
                                                <span class="text-danger">{{ $errors->first('meta_title') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="primary_input mb-15">
                                                <label class="primary_input_label" for="">Meta keywords</label>
                                                <input class="primary_input_field" name="meta_keyword"
                                                       placeholder="Meta keyword" type="text"
                                                       value="{{$info->meta_keyword}}">
                                                <span class="text-danger">{{ $errors->first('meta_keyword') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="primary_input mb-15">
                                                <label class="primary_input_label" for="">
                                                    {{ __('common.meta_description') }}</label>
                                                <textarea class="primary_textarea height_112 meta_description"
                                                          placeholder="{{ __('common.meta_description') }}"
                                                          name="meta_description"
                                                          spellcheck="false">{{$info->meta_description}}</textarea>
                                                <span class="text-danger">{{ $errors->first('meta_description') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="primary_input mb-25">
                                                <label class="primary_input_label" for="">{{ __('product.meta_image') }}
                                                    (300x300)PX</label>
                                                <div class="primary_file_uploader" data-toggle="amazuploader" data-multiple="false" data-type="image" data-name="meta_image">
                                                    <input class="primary-input" type="text" id="meta_image_file"
                                                           placeholder="{{__('common.browse_image_file')}}" readonly="">
                                                    <button class="" type="button">
                                                        <label class="primary-btn small fix-gr-bg" for="meta_image">{{
                                                            __('product.meta_image') }} </label>
                                                        <input type="hidden" class="selected_files" value="{{$info->media_id}}">
                                                    </button>
                                                </div>
                                                <div class="product_image_all_div">

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="primary_input mb-15">
                                                <label class="primary_input_label" for="">Meta image alt</label>
                                                <input class="primary_input_field" name="meta_image_alt"
                                                       placeholder="Meta image alt" type="text"
                                                       value="{{$info->meta_image_alt}}">
                                                <span class="text-danger">{{ $errors->first('meta_image_alt') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="primary_input mb-15">
                                                <label class="primary_input_label" for="">
                                                    Scheme Markup</label>
                                                <textarea style="height: 250px;" class="primary_input_field" name="scheme_markup"
                                                          placeholder="Scheme Markup">{{ $info->scheme_markup }}</textarea>
                                                <span class="text-danger">{{ $errors->first('scheme_markup') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="primary_input mb-15">
                                                <label class="primary_input_label" for="">Product Slider Title</label>
                                                <input class="primary_input_field" name="product_slider_title"
                                                       placeholder="Product Slider Title" type="text"
                                                       value="{{$info->product_slider_title}}">
                                                <span class="text-danger">{{ $errors->first('product_slider_title') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="primary_input mb-15">
                                                <label class="primary_input_label" for="">
                                                    Product Slider Descr</label>
                                                <textarea class="primary_textarea height_112 product_slider_descr summernote"
                                                          placeholder="Product Slider Descr"
                                                          name="product_slider_descr"
                                                          spellcheck="false">{{$info->product_slider_descr}}</textarea>
                                                <span class="text-danger">{{ $errors->first('product_slider_descr') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-12 text-center">
                        <button class="primary_btn_2 mt-5 text-center saveBtn"><i class="ti-check"></i>{{ __('common.update') }}
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </section>
@endsection
@push('scripts')
    <script src="{{asset(asset_path('backend/vendors/js/icon-picker.js'))}}"></script>
    <script>
        (function($) {
            "use strict";
            $(document).ready(function () {
                $('.summernote').summernote();
            })
        })(jQuery);

    </script>

@endpush
