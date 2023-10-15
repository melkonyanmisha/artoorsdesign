@extends('backEnd.master')

@section('mainContent')
    <style>
        select{
            padding: 15px;
            width: 100%;
            border-color: #e1e3ed;
            border-radius: 50px;
        }
    </style>
    <div class="col-12">
        <div class="box_header">
            <div class="main-title d-flex justify-content-between w-100">
                <h3 class="mb-0 mr-30">Scheme Markups</h3>

            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="white_box_50px box_shadow_white">
            <form id="formData" action="{{route('scheme-markup.store')}}" method="POST"
                  enctype="multipart/form-data">
                @csrf
                <div class="row">

                    <div class="col-xl-12">
                        <div class="primary_input mb-25">
                            <select class="form-select" aria-label="Default select example">
                                <option value="home">Home</option>
                                <option value="blog">Blog</option>
                                <option value="product">Product</option>
                                <option value="category">Category</option>
                                <option value="terms_conditions">Terms & Conditions</option>
                                <option value="privacy_policy">Privacy Policy</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-xl-12">
                        <div class="primary_input mb-25">
                            <label class="primary_input_label" for="title">{{ __('common.title') }} <span class="text-danger">*</span></label>
                            <input name="title" id="title" class="primary_input_field" type="text" value="{{ old('title') }}" required>
                        </div>

                        @error('title')
                            <span class="text-danger" id="error_title">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-xl-12">
                        <div class="primary_input mb-25">
                            <label class="primary_input_label" for="json">Json <span class="text-danger">*</span></label>
                            <textarea name="json" id="json" class="primary_input_field" style="height: 250px; padding: 15px" value="{{ old('json') }}" required></textarea>
                        </div>
                        @error('json')
                            <span class="text-danger" id="error_json">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-xl-12">
                        <div class="primary_input">
                            <label class="primary_input_label" for="">{{ __('common.status') }} <span class="text-danger">*</span></label></label>
                            <ul id="theme_nav" class="permission_list sms_list ">
                                <li>
                                    <label data-id="bg_option" class="primary_checkbox d-flex mr-12">
                                        <input name="status" id="status_active" value="1" checked="true" class="active"
                                               type="radio">
                                        <span class="checkmark"></span>
                                    </label>
                                    <p>{{ __('common.active') }}</p>
                                </li>
                                <li>
                                    <label data-id="color_option" class="primary_checkbox d-flex mr-12">
                                        <input name="status" value="0" id="status_inactive" class="de_active" type="radio">
                                        <span class="checkmark"></span>
                                    </label>
                                    <p>{{ __('common.inactive') }}</p>
                                </li>
                            </ul>
                        </div>
                        @error('status')
                        <span class="text-danger" id="error_status">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-lg-12 text-center">
                        <div class="d-flex justify-content-center">
                            <button class="primary-btn semi_large2  fix-gr-bg mr-1" id="save_button_parent" type="submit" dusk="save"><i
                                        class="ti-check"></i>{{ __('common.save') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')

{{--    <script>--}}
{{--        (function($){--}}
{{--            "use strict";--}}
{{--            $(document).ready(function(){--}}


{{--                $(document).on('keyup', '#title', function(event){--}}
{{--                    processSlug($(this).val(), '#slug');--}}
{{--                });--}}


{{--                $('#description').summernote({--}}
{{--                    placeholder: 'Description',--}}
{{--                    tabsize: 2,--}}
{{--                    height: 400,--}}
{{--                    codeviewFilter: true,--}}
{{--                    codeviewIframeFilter: true--}}
{{--                });--}}

{{--            });--}}
{{--        })(jQuery);--}}
{{--    </script>--}}

@endpush
