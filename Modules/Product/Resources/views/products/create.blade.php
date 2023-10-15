@extends('backEnd.master')

@section('styles')
<link rel="stylesheet" href="{{ asset(asset_path('backend/vendors/css/icon-picker.css')) }}" />

<link rel="stylesheet" href="{{asset(asset_path('modules/product/css/product_create.css'))}}" />
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
        <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data" id="choice_form">
            @csrf
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="box_header common_table_header">
                        <div class="main-title d-md-flex">
                            <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{ __('product.add_new_product') }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <ul class="nav nav-tabs justify-content-end mt-sm-md-20 mb-30 grid_gap_5" role="tablist">
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link active show" href="#GenaralInfo" role="tab" data-toggle="tab" id="1"--}}
{{--                        aria-selected="true">{{__('product.general_information')}}</a>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link show" href="#RelatedProduct" role="tab" data-toggle="tab" id="2"--}}
{{--                        aria-selected="false">{{__('product.related_product')}}</a>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link show" href="#UpSale" role="tab" data-toggle="tab" id="3"--}}
{{--                        aria-selected="false">{{__('common.up_sale')}}</a>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link show" href="#CrossSale" role="tab" data-toggle="tab" id="4"--}}
{{--                        aria-selected="true">{{__('common.cross_sale')}}</a>--}}
{{--                </li>--}}

            </ul>


            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade active show" id="GenaralInfo">

                    <div class="row">
                        <div class="col-lg-8">
                            <div class="white_box pt-0 box_shadow_white mb-20 p-15">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="main-title d-flex">
                                            <h3 class="mb-2 mr-30">{{ __('product.product_information') }}</h3>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">

                                        <input type="hidden" value="1" id="product_type" name="product_type">

{{--                                        <div class="primary_input">--}}
{{--                                            <label class="primary_input_label" for="">{{ __('common.type') }} <span--}}
{{--                                                    class="text-danger">*</span></label>--}}
{{--                                            <ul id="theme_nav" class="permission_list sms_list ">--}}
{{--                                                <li>--}}
{{--                                                    <label data-id="bg_option" class="primary_checkbox d-flex mr-12">--}}
{{--                                                        <input name="product_type" id="single_prod" value="1" checked--}}
{{--                                                            class="active prod_type" type="radio">--}}
{{--                                                        <span class="checkmark"></span>--}}
{{--                                                    </label>--}}
{{--                                                    <p>{{ __('product.single') }}</p>--}}
{{--                                                </li>--}}
{{--                                                <li>--}}
{{--                                                    <label data-id="color_option" class="primary_checkbox d-flex mr-12">--}}
{{--                                                        <input name="product_type" value="2" id="variant_prod"--}}
{{--                                                            class="de_active prod_type" type="radio">--}}
{{--                                                        <span class="checkmark"></span>--}}
{{--                                                    </label>--}}
{{--                                                    <p>{{ __('product.variant') }}</p>--}}
{{--                                                </li>--}}
{{--                                            </ul>--}}
{{--                                        </div>--}}

                                    </div>
                                    <div class="col-lg-6">
                                        <div class="primary_input mb-15">
                                            <label class="primary_input_label" for=""> {{ __('common.name') }} <span
                                                    class="text-danger">*</span></label>
                                            <input class="primary_input_field" name="product_name" id="product_name"
                                                placeholder="{{ __('common.name') }}" type="text"
                                                value="{{ old('product_name') }}" required="1">
                                            <span class="text-danger" id="error_product_name">{{ $errors->first('product_name') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 sku_single_div">
                                        <div class="primary_input mb-15">
                                            <label class="primary_input_label" for=""> {{ __('common.model_number') }}
                                                <span class="text-danger">*</span></label>
                                            <input class="primary_input_field" name="sku" id="sku_single"
                                                placeholder="{{ __('common.model_number') }}" type="text" required="1" value="{{\Modules\Product\Entities\Product::latest()->first()->id + 11564512}}">

                                            <span id="error_single_sku" class="text-danger">{{ $errors->first('sku') }}</span>
                                        </div>
                                    </div>
{{--                                    <div class="col-lg-3">--}}
{{--                                        <div class="primary_input mb-15">--}}
{{--                                            <label class="primary_input_label" for="model_number">--}}
{{--                                                {{ __('common.model_number') }}</label>--}}
{{--                                            <input class="primary_input_field" name="model_number"--}}
{{--                                                placeholder="{{ __('common.model_number') }}" type="text"--}}
{{--                                                value="{{ old('model_number') }}">--}}
{{--                                            <span class="text-danger">{{ $errors->first('model_number') }}</span>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                    <input type="hidden" name="unit_type_id" value="1">
                                    <div class="col-lg-3" id="category_select_div">
                                        @include('product::products.components._category_list_select')
                                    </div>
{{--                                    <div class="col-lg-3" id="brand_select_div">--}}
{{--                                        @include('product::products.components._brand_list_select')--}}
{{--                                    </div>--}}
{{--                                    <div class="col-lg-3" id="unit_select_div">--}}
{{--                                        @include('product::products.components._unit_list_select')--}}
{{--                                    </div>--}}
{{--                                    <div class="col-lg-3">--}}
{{--                                        <div class="primary_input mb-15">--}}
{{--                                            <label class="primary_input_label" for="">{{ __('product.barcode_type')--}}
{{--                                                }}</label>--}}
{{--                                            <select name="barcode_type" id="barcode_type" class="primary_select mb-15">--}}
{{--                                                <option disabled>{{ __('product.select_barcode') }}</option>--}}
{{--                                                @foreach (barcodeList() as $key => $barcode)--}}
{{--                                                <option value="{{ $barcode }}" @if($key==0) selected @endif>{{ $barcode--}}
{{--                                                    }}</option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
{{--                                            <span class="text-danger">{{ $errors->first('barcode_type') }}</span>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-lg-3">--}}
{{--                                        <div class="primary_input mb-15">--}}
{{--                                            <label class="primary_input_label" for=""> {{--}}
{{--                                                __('product.minimum_order_qty') }} <span class="text-danger">*</span>--}}
{{--                                            </label>--}}
{{--                                            <input class="primary_input_field" name="minimum_order_qty"--}}
{{--                                                id="minimum_order_qty" value="1" type="number" min="1" step="0"--}}
{{--                                                required="1">--}}
{{--                                            <span class="text-danger" id="error_minumum_qty">{{--}}
{{--                                                $errors->first('minimum_order_qty') }}</span>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                    <input class="primary_input_field" name="minimum_order_qty"
                                           id="minimum_order_qty" value="1" type="hidden" min="1" step="0"
                                           required="1">
{{--                                    <div class="col-lg-3">--}}
{{--                                        <div class="primary_input mb-15">--}}
{{--                                            <label class="primary_input_label" for=""> {{ __('product.max_order_qty')--}}
{{--                                                }}</label>--}}
                                            <input class="primary_input_field" name="max_order_qty"
                                                type="hidden" min="0" step="0">
{{--                                            <span class="text-danger">{{ $errors->first('max_order_qty') }}</span>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}



                                    <div class="col-lg-12">
                                        <div class="single_field ">
                                            <label for="">@lang('blog.tags') (@lang('product.comma_separated'))<span class="text-danger">*</span></label>
                                        </div>
                                        <div class="tagInput_field mb_26">
                                            <input name="tags" id="tags" class="tag-input" type="text"
                                                data-role="tagsinput" />
                                        </div>
                                        <br>
                                        <div class="suggeted_tags">
                                            <label>@lang('blog.suggested_tags')</label>
                                            <div id="tag_show" class="suggested_tag_show">
                                            </div>
                                        </div>
                                        <br>
                                        <span class="text-danger" id="error_tags"></span>
                                    </div>

                                    <div class="col-lg-12 attribute_div" id="attribute_select_div">
                                        @include('product::products.components._attribute_list_select')
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="customer_choice_options" id="customer_choice_options">

                                        </div>
                                    </div>

                                    <div class="col-lg-12 sku_combination overflow-auto">

                                    </div>


{{--                                    <div class="col-xl-12">--}}
{{--                                        <div class="primary_input">--}}
{{--                                            <ul id="theme_nav" class="permission_list sms_list ">--}}
{{--                                                <li>--}}
{{--                                                    <label data-id="bg_option" class="primary_checkbox d-flex mr-12">--}}
{{--                                                        <input name="is_physical" id="is_physical" checked value="1"--}}
{{--                                                            type="checkbox">--}}
{{--                                                        <span class="checkmark"></span>--}}
{{--                                                    </label>--}}
{{--                                                    <p>{{ __('product.is_physical_product') }}</p>--}}
{{--                                                    <input type="hidden" name="is_physical" value="1"--}}
{{--                                                        id="is_physical_prod">--}}
{{--                                                </li>--}}
{{--                                            </ul>--}}

{{--                                        </div>--}}
{{--                                    </div>--}}
                                    <div class="col-lg-12  weight_height_div">
{{--                                        <div class="main-title d-flex">--}}
{{--                                            <h3 class="mb-3 mr-30">{{ __('product.weight_height_info') }}</h3>--}}
{{--                                        </div>--}}
                                       <div class="row">
                                           <input type="hidden" name="is_physical" value="1"
                                                  id="is_physical_prod">
                                        <input type="hidden" name="weight" value="1">
                                        <input type="hidden" name="length" value="1">
                                        <input type="hidden" name="breadth" value="1">
                                        <input type="hidden" name="height" value="1">
{{--                                           <div class="col-lg-3">--}}
{{--                                               <div class="primary_input mb-15">--}}
{{--                                                   <label class="primary_input_label" for=""> {{ __('product.weight')}} [Gm] <span class="text-danger">*</span></label>--}}
{{--                                                   <input class="primary_input_field" name="weight" id="weight"--}}
{{--                                                          type="number" min="0" step="{{step_decimal()}}">--}}
{{--                                                   <span class="text-danger" id="error_weight">{{ $errors->first('weight') }}</span>--}}
{{--                                               </div>--}}
{{--                                           </div>--}}

{{--                                           <div class="col-lg-3">--}}
{{--                                               <div class="primary_input mb-15">--}}
{{--                                                   <label class="primary_input_label" for=""> {{ __('product.length')}} [Cm] <span class="text-danger">*</span></label>--}}
{{--                                                   <input class="primary_input_field" name="length" id="length"--}}
{{--                                                          type="number" min="0" step="{{step_decimal()}}">--}}
{{--                                                   <span class="text-danger" id="error_length">{{ $errors->first('length') }}</span>--}}
{{--                                               </div>--}}
{{--                                           </div>--}}

{{--                                           <div class="col-lg-3">--}}
{{--                                               <div class="primary_input mb-15">--}}
{{--                                                   <label class="primary_input_label" for=""> {{ __('product.breadth')}} [Cm] <span class="text-danger">*</span></label>--}}
{{--                                                   <input class="primary_input_field" name="breadth" id="breadth"--}}
{{--                                                          type="number" min="0" step="{{step_decimal()}}">--}}
{{--                                                   <span class="text-danger" id="error_breadth">{{ $errors->first('breadth') }}</span>--}}
{{--                                               </div>--}}
{{--                                           </div>--}}

{{--                                           <div class="col-lg-3">--}}
{{--                                               <div class="primary_input mb-15">--}}
{{--                                                   <label class="primary_input_label" for=""> {{ __('product.height')}} [Cm] <span class="text-danger">*</span></label>--}}
{{--                                                   <input class="primary_input_field" name="height" id="height"--}}
{{--                                                          type="number" min="0" step="{{step_decimal()}}">--}}
{{--                                                   <span class="text-danger" id="error_height">{{ $errors->first('height') }}</span>--}}
{{--                                               </div>--}}
{{--                                           </div>--}}
                                       </div>
                                    </div>
                                    <input type="hidden" value="0" name="additional_shipping">
{{--                                    <div id="phisical_shipping_div" class="col-lg-12">--}}
{{--                                        <div class="row">--}}
{{--                                            <div class="col-lg-12">--}}
{{--                                                <div class="primary_input mb-15">--}}
{{--                                                    <label class="primary_input_label" for="additional_shipping"> {{--}}
{{--                                                        __('product.additional_shipping_charge') }}--}}
{{--                                                    </label>--}}
{{--                                                    <input class="primary_input_field" name="additional_shipping"--}}
{{--                                                        type="number" min="0" step="{{step_decimal()}}"--}}
{{--                                                        value="{{old('additional_shipping')?old('additional_shipping'):0}}">--}}
{{--                                                    <span class="text-danger">{{ $errors->first('additional_shipping')--}}
{{--                                                        }}</span>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}

{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                    <div class="col-lg-12 digital_file_upload_div" style="display: flex !important;">--}}
{{--                                        <div class="primary_input mb-25">--}}
{{--                                            <label class="primary_input_label" for="">{{--}}
{{--                                                __('product.program_file_upload') }}</label>--}}
{{--                                            <div class="primary_file_uploader">--}}
{{--                                                <input class="primary-input" type="text" id="pdf_place"--}}
{{--                                                    placeholder="{{__('product.upload_file')}}" readonly="" >--}}
{{--                                                <button class="" type="button">--}}
{{--                                                    <label class="primary-btn small fix-gr-bg" for="digital_file">{{--}}
{{--                                                        __('product.Browse') }} </label>--}}
{{--                                                    <input type="file" class="d-none" name="digital_file"--}}
{{--                                                        id="digital_file">--}}
{{--                                                </button>--}}
{{--                                            </div>--}}
{{--                                            <span class="text-danger">{{ $errors->first('documents') }}</span>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-lg-12 " style="display: flex !important;">--}}
{{--                                        <div class="primary_input mb-25">--}}
{{--                                            <label class="primary_input_label" for="">{{--}}
{{--                                                __('product.program_file_upload') }}</label>--}}
{{--                                            <div class="primary_file_uploader">--}}
{{--                                                <input class="primary-input" type="text" id="pdf_place"--}}
{{--                                                       placeholder="{{__('product.upload_file')}}" readonly="" >--}}
{{--                                                <button class="" type="button">--}}
{{--                                                    <label class="primary-btn small fix-gr-bg" for="digital_file">{{--}}
{{--                                                        __('product.Browse') }} </label>--}}
{{--                                                    <input type="file" class="d-none" name="digital_file"--}}
{{--                                                           id="digital_file">--}}
{{--                                                </button>--}}
{{--                                            </div>--}}
{{--                                            <span class="text-danger">{{ $errors->first('documents') }}</span>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

                                </div>



                                <div class="row">


                                    <div class="col-lg-12">
                                        <div class="main-title d-flex">
                                            <h3 class="mb-3 mr-30">{{ __('product.price_info_and_stock') }}</h3>
                                        </div>
                                    </div>

                                    @if(!isModuleActive('MultiVendor'))
                                        <input type="hidden" value="0" name="stock_manage">
{{--                                        <div class="col-lg-12" id="stock_manage_div">--}}
{{--                                            <div class="primary_input mb-25">--}}
{{--                                                <label class="primary_input_label" for="">{{ __('Stock Manage') }}</label>--}}
{{--                                                <select class="primary_select mb-25" name="stock_manage"--}}
{{--                                                    id="stock_manage">--}}
{{--                                                    <option value="1">{{ __('common.yes') }}</option>--}}
{{--                                                    <option value="0" selected>{{ __('common.no') }}</option>--}}
{{--                                                </select>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-lg-6 d-none" id="single_stock_div">--}}
{{--                                            <div class="primary_input mb-15">--}}
{{--                                                <label class="primary_input_label" for="single_stock"> {{--}}
{{--                                                    __('product.product_stock') }}--}}
{{--                                                </label>--}}
{{--                                                <input class="primary_input_field" name="single_stock" id="single_stock"--}}
{{--                                                    type="number" min="0" step="0"--}}
{{--                                                    value="{{old('single_stock')?old('single_stock'):0}}">--}}
{{--                                                <span class="text-danger">{{ $errors->first('single_stock') }}</span>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}

                                    @endif

                                    <div class="col-lg-6 selling_price_div">
                                        <div class="primary_input mb-15">
                                            <label class="primary_input_label" for=""> {{ __('product.selling_price') }}
                                                <span class="text-danger">*</span></label>
                                            <input class="primary_input_field" name="selling_price" id="selling_price"
                                                placeholder="{{ __('product.selling_price') }}" type="number" min="0"
                                                step="{{step_decimal()}}" value="0" required>
                                            <span class="text-danger" id="error_selling_price">{{
                                                $errors->first('selling_price') }}</span>
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="primary_input mb-15">
                                            <label class="primary_input_label" for=""> {{ __('product.discount') }}
                                            </label>
                                            <input class="primary_input_field" name="discount" id="discount"
                                                placeholder="{{ __('product.discount') }}" type="number" min="0"
                                                step="{{step_decimal()}}" value="0">
                                            <span class="text-danger" id="error_discunt">{{ $errors->first('discount')
                                                }}</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label" for="">{{ __('product.discount_type')
                                                }}</label>
                                            <select class="primary_select mb-25" name="discount_type"
                                                id="discount_type">
                                                <option value="1">{{ __('common.amount') }}</option>
                                                <option value="0">{{ __('common.percentage') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label" for="">{{ __('gst.GST_group')
                                                }}</label>
                                            <select class="primary_select mb-25" name="gst_group" id="tax_type">
                                                <option value="" selected disabled>{{__('common.select_one')}}</option>
                                                @foreach($gst_groups as $group)
                                                    <option value="{{$group->id}}">{{ $group->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6" id="gst_list_div">

                                    </div>

                                    <div class="col-lg-12">
                                        <div class="main-title d-flex">
                                            <h3 class="mb-3 mr-30">{{ __('common.description') }}</h3>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="primary_input mb-15">

                                            <textarea class="summernote" name="description"></textarea>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="main-title d-flex">
                                            <h3 class="mb-3 mr-30">{{ __('common.description') }} Guest</h3>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="primary_input mb-15">

                                            <textarea class="summernote" name="description_guest"></textarea>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="main-title d-flex">
                                            <h3 class="mb-3 mr-30">{{ __('product.specifications') }}</h3>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="primary_input mb-15">

                                            <textarea class="summernote summernote2" id="specification"
                                                name="specification"></textarea>
                                        </div>
                                    </div>


                                    <div class="col-lg-12">
                                        <div class="main-title d-flex">
                                            <h3 class="mb-3 mr-30">{{ __('common.seo_info') }}</h3>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="primary_input mb-15">
                                            <label class="primary_input_label" for=""> {{ __('common.meta_title')
                                                }}</label>
                                            <input class="primary_input_field" name="meta_title"
                                                placeholder="{{ __('common.meta_title') }}" type="text"
                                                value="{{ old('meta_title') }}">
                                            <span class="text-danger">{{ $errors->first('meta_title') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="primary_input mb-15">
                                            <label class="primary_input_label" for="">Meta keywords</label>
                                            <input class="primary_input_field" name="meta_keyword"
                                                placeholder="Meta keyword" type="text"
                                                value="{{ old('meta_keyword') }}">
                                            <span class="text-danger">{{ $errors->first('meta_keyword') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="primary_input mb-15">
                                            <label class="primary_input_label" for="">
                                                {{ __('common.meta_description') }}</label>
                                            <textarea class="primary_textarea height_112 meta_description"
                                                placeholder="{{ __('common.meta_description') }}"
                                                name="meta_description" spellcheck="false"></textarea>
                                            <span class="text-danger">{{ $errors->first('meta_description') }}</span>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="primary_input mb-15">
                                            <label class="primary_input_label" for=""> {{ __('common.meta_image_alt')
                                                }}</label>
                                            <input class="primary_input_field" name="meta_image_alt"
                                                   placeholder="{{ __('common.meta_image_alt') }}" type="text"
                                                   value="{{ old('meta_image_alt') }}">
                                            <span class="text-danger">{{ $errors->first('meta_image_alt') }}</span>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="primary_input mb-15">
                                            <label class="primary_input_label" for=""> Scheme Markup </label>
                                            <textarea style="height: 250px;" class="primary_input_field" name="scheme_markup"
                                                   placeholder="Scheme Markup">{{ old('scheme_markup') }}</textarea>
                                            <span class="text-danger">{{ $errors->first('scheme_markup') }}</span>
                                        </div>
                                    </div>

{{--                                    <div class="col-lg-12">--}}
{{--                                        <div class="primary_input mb-25">--}}
{{--                                            <label class="primary_input_label" for="">{{ __('product.meta_image') }}--}}
{{--                                                (300x300)PX</label>--}}
{{--                                            <div class="primary_file_uploader" data-toggle="amazuploader" data-multiple="false" data-type="image" data-name="meta_image">--}}
{{--                                                <input class="primary-input file_amount" type="text" id="meta_image_file"--}}
{{--                                                    placeholder="{{__('common.browse_image_file')}}" readonly="">--}}
{{--                                                <button class="" type="button">--}}
{{--                                                    <label class="primary-btn small fix-gr-bg" for="meta_image">{{--}}
{{--                                                        __('product.meta_image') }} </label>--}}
{{--                                                    <input type="hidden" class="selected_files" value="">--}}
{{--                                                </button>--}}
{{--                                            </div>--}}
{{--                                            <div class="product_image_all_div">--}}

{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="white_box pt-0 box_shadow_white p-15">
                                <div class="row image_section">
                                    <div class="col-lg-12">
                                        <div class="main-title d-flex">
                                            <h3 class="mb-3 mr-30">{{ __('product.product_image_info') }}</h3>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="primary_input mb-25">

                                            <div class="primary_file_uploader" data-toggle="amazuploader" data-multiple="true" data-type="image" data-name="images[]">
                                                <input class="primary-input file_amount" type="text" id="thumbnail_image_file"
                                                    placeholder="{{ __('Choose Images') }}" readonly="">
                                                <button class="" type="button">
                                                    <label class="primary-btn small fix-gr-bg" for="thumbnail_image">{{
                                                        __('product.Browse') }} </label>

                                                    <input type="hidden" class="selected_files image_selected_files" value="">
                                                </button>
                                                <span class="text-danger" id="error_thumbnail"></span>
                                            </div>

                                            <div class="product_image_all_div">

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 images_alt">

                                    </div>
                                </div>
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div class="row">
                                    3D model format

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="1" name="file_type[]">
                                        <label class="form-check-label" for="inlineCheckbox1">3DM</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="2" name="file_type[]">
                                        <label class="form-check-label" for="inlineCheckbox2">STL</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="3" name="file_type[]" >
                                        <label class="form-check-label" for="inlineCheckbox3">Obj </label>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="main-title d-flex">
                                            <h3 class="mb-3 mr-30">Upload Files</h3>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="primary_input mb-25">
{{--                                            <label class="primary_input_label" for="">--}}
{{--                                                {{__('product.pdf_specifications') }}--}}
{{--                                                --}}
{{--                                            </label>--}}
{{--                                            <input type="hidden" value="" name="pdf">--}}
{{--                                            <div class="primary_file_uploader">--}}
{{--                                                <input class="primary-input" type="text" id="pdf_place1"--}}
{{--                                                    placeholder="File Link"  name="pdf_file">--}}
{{--                                                <button class="" type="button">--}}
{{--                                                    <label class="primary-btn small fix-gr-bg" for="pdf">Upload Files </label>--}}
{{--                                                    <input   type="file" class="d-none" name="pdf_file[]" id="pdf" multiple>--}}
{{--                                                </button>--}}
{{--                                            </div>--}}
{{--                                            <br>--}}
{{--                                            <div id="myProgress" style="display: none">--}}
{{--                                                <div id="myBar">0%</div>--}}
{{--                                            </div>--}}
{{--                                            <div id="myProgress1" style="display: none">--}}
{{--                                                <div id="myBar1">0%</div>--}}
{{--                                            </div>--}}
{{--                                            <div id="myProgress2" style="display: none">--}}
{{--                                                <div id="myBar2">0%</div>--}}
{{--                                            </div>--}}
{{--                                            <script>--}}
{{--                                                // var i = 0;--}}
{{--                                                // var s = 0;--}}
{{--                                                //--}}
{{--                                                $('#pdf').change(--}}
{{--                                                    function() {--}}
{{--                                                        var images = document.getElementById('pdf');--}}
{{--                                                        var length = images.files.length;--}}

{{--                                                        // for (var i = 0; i < length; i++) {--}}

{{--                                                        var formData = new FormData();--}}
{{--                                                        var image = images.files[0];--}}
{{--                                                        if(image){--}}
{{--                                                            formData.append('image', image);--}}
{{--                                                            formData.append('_token', '{{csrf_token()}}');--}}

{{--                                                            var xmlhttp = new XMLHttpRequest();--}}

{{--                                                            (function () {--}}
{{--                                                                xmlhttp.upload.addEventListener('progress', function (e) {--}}
{{--                                                                    document.getElementById("myProgress").style.display = "block"--}}
{{--                                                                    document.getElementById("myBar").style.width = Math.round((e.loaded / e.total) * 100) + "%";--}}
{{--                                                                    document.getElementById("myBar").innerHTML = Math.round((e.loaded / e.total) * 100)  + "%";--}}
{{--                                                                }, false);--}}
{{--                                                            })();--}}

{{--                                                            xmlhttp.open("post", "/pictures/uploadImage");--}}
{{--                                                            xmlhttp.send(formData);--}}
{{--                                                        }--}}


{{--                                                        var formData = new FormData();--}}
{{--                                                        var image = images.files[1];--}}
{{--                                                        if(image){--}}
{{--                                                            formData.append('image', image);--}}
{{--                                                            formData.append('_token', '{{csrf_token()}}');--}}

{{--                                                            var xmlhttp = new XMLHttpRequest();--}}


{{--                                                            (function () {--}}
{{--                                                                xmlhttp.upload.addEventListener('progress', function (e) {--}}
{{--                                                                    document.getElementById("myProgress1").style.display = "block"--}}
{{--                                                                    document.getElementById("myBar1").style.width = Math.round((e.loaded / e.total) * 100) + "%";--}}
{{--                                                                    document.getElementById("myBar1").innerHTML = Math.round((e.loaded / e.total) * 100)  + "%";--}}
{{--                                                                }, false);--}}
{{--                                                            })();--}}

{{--                                                            xmlhttp.open("post", "/pictures/uploadImage");--}}
{{--                                                            xmlhttp.send(formData);--}}
{{--                                                        }--}}


{{--                                                        var formData = new FormData();--}}
{{--                                                        var image = images.files[2];--}}
{{--                                                        if(image){--}}
{{--                                                            formData.append('image', image);--}}
{{--                                                            formData.append('_token', '{{csrf_token()}}');--}}

{{--                                                            var xmlhttp = new XMLHttpRequest();--}}


{{--                                                            (function () {--}}
{{--                                                                xmlhttp.upload.addEventListener('progress', function (e) {--}}
{{--                                                                    document.getElementById("myProgress2").style.display = "block"--}}
{{--                                                                    document.getElementById("myBar2").style.width = Math.round((e.loaded / e.total) * 100) + "%";--}}
{{--                                                                    document.getElementById("myBar2").innerHTML = Math.round((e.loaded / e.total) * 100)  + "%";--}}
{{--                                                                }, false);--}}
{{--                                                            })();--}}

{{--                                                            xmlhttp.open("post", "/pictures/uploadImage");--}}
{{--                                                            xmlhttp.send(formData);--}}
{{--                                                        }--}}

{{--                                                        // }--}}
{{--                                                })--}}


{{--                                            </script>--}}
                                            <span class="text-danger">{{ $errors->first('documents') }}</span>
                                        </div>
                                    </div>




{{--                                    <div class="col-lg-12">--}}
{{--                                        <div class="main-title d-flex">--}}
{{--                                            <h3 class="mb-3 mr-30">{{ __('product.product_videos_info') }}</h3>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                    <input value="youtube" name="video_provider" type="hidden">
{{--                                    <div class="col-lg-12">--}}
{{--                                        <div class="primary_input mb-25">--}}
{{--                                            <label class="primary_input_label" for="">{{ __('product.video_provider')--}}
{{--                                                }}</label>--}}
{{--                                            <select class="primary_select mb-25" name="video_provider"--}}
{{--                                                id="video_provider">--}}
{{--                                                <option value="youtube">{{ __('product.youtube') }}</option>--}}
{{--                                                <option value="daily_motion">{{ __('product.daily_motion') }}</option>--}}
{{--                                            </select>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-lg-12">--}}
{{--                                        <div class="primary_input mb-15">--}}
{{--                                            <label class="primary_input_label" for=""> {{ __('product.video_link')--}}
{{--                                                }}</label>--}}
{{--                                            <input class="primary_input_field" name="video_link"--}}
{{--                                                placeholder="{{ __('product.video_link') }}" type="text"--}}
{{--                                                value="{{ old('video_link') }}">--}}
{{--                                            <span class="text-danger">{{ $errors->first('video_link') }}</span>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                    @include('product::products.upload_file')
                                    <input class="primary_input_field" name="video_link"
                                           placeholder="Files Link" type="hidden"
                                           value="" id="video_link">
                                    <div class="col-lg-12">
                                        <div class="main-title d-flex">
                                            <h3 class="mb-3 mr-30">{{ __('product.others_info') }}</h3>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">{{ __('common.status') }} <span
                                                    class="text-danger">*</span></label>
                                            <ul id="theme_nav" class="permission_list sms_list ">
                                                <li>
                                                    <label data-id="bg_option" class="primary_checkbox d-flex mr-12">
                                                        <input name="status" id="status_active" value="1" checked
                                                            class="active" type="radio">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                    <p>{{ __('common.publish') }}</p>
                                                </li>
                                                <li>
                                                    <label data-id="color_option" class="primary_checkbox d-flex mr-12">
                                                        <input name="status" value="0" id="status_inactive"
                                                            class="de_active" type="radio">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                    <p>{{ __('common.pending') }}</p>
                                                </li>
                                            </ul>
                                            <span class="text-danger" id="status_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">{{
                                                __('common.make_Display_in_details_page') }} <span
                                                    class="text-danger">*</span></label>
                                            <ul id="theme_nav" class="permission_list sms_list ">
                                                <li>
                                                    <label data-id="bg_option" class="primary_checkbox d-flex mr-12">
                                                        <input name="display_in_details" id="status_active" value="1"
                                                            checked class="active" type="radio">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                    <p>{{ __('common.up_sale') }}</p>
                                                </li>
                                                <li>
                                                    <label data-id="color_option" class="primary_checkbox d-flex mr-12">
                                                        <input name="display_in_details" value="2" id="status_inactive"
                                                            class="de_active" type="radio">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                    <p>{{ __('common.cross_sale') }}</p>
                                                </li>
                                            </ul>
                                            <span class="text-danger" id="status_error"></span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>


                <div role="tabpanel" class="tab-pane fade" id="RelatedProduct">
                    <div class="box_header common_table_header ">
                        <div class="main-title d-md-flex">
                            <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{ __('product.related_product') }}</h3>

                        </div>
                    </div>
                    <div class="QA_section QA_section_heading_custom check_box_table">
                        <div class="QA_table">
                            <!-- table-responsive -->
                            <div class="table-responsive" id="product_list_div">
                                <table class="table">
                                    <thead>

                                        <tr>
                                            <th width="10%" scope="col">
                                                <label class="primary_checkbox d-flex ">
                                                    <input type="checkbox" id="relatedProductAll">
                                                    <span class="checkmark"></span>
                                                </label>
                                            </th>
                                            <th width="20%" scope="col">{{ __('common.name') }}</th>
                                            <th width="15%" scope="col">{{ __('product.brand') }}</th>
                                            <th width="10%" scope="col">{{ __('product.thumbnail') }}</th>
                                            <th width="10%" scope="col">{{ __('product.created_at') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tablecontentsrelatedProduct">
                                        @foreach ($products as $key => $item)
                                        <tr>
                                            <th scope="col">
                                                <label class="primary_checkbox d-flex">
                                                    <input name="related_product[]" id="related_product_{{$key}}"
                                                        @if(isset($product) &&
                                                        @$product->relatedProducts->where('related_sale_product_id',$item->id)->first())
                                                    checked @endif value="{{$item->id}}" type="checkbox">
                                                    <span class="checkmark"></span>
                                                </label>
                                            </th>
                                            <td>{{ $item->product_name }}</td>
                                            <td>{{ @$item->brand->name }}</td>
                                            <td>
                                                <div class="product_img_div">
                                                    <img class="product_list_img"
                                                        src="{{ showImage($item->thumbnail_image_source) }}"
                                                        alt="{{ $item->product_name }}">
                                                </div>
                                            </td>
                                            <td>{{ date(app('general_setting')->dateFormat->format, strtotime($item->created_at)) }}</td>

                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="col-12 text-center mb-20">
                                    <button class="primary_btn_2 mt-5 text-center lodeMoreRelatedSale">{{
                                        __('common.load_more') }}</button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>


                <div role="tabpanel" class="tab-pane fade" id="UpSale">

                    <div class="box_header common_table_header ">
                        <div class="main-title d-md-flex">
                            <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{ __('common.up_sale') }}</h3>

                        </div>
                    </div>
                    <div class="QA_section QA_section_heading_custom check_box_table">
                        <div class="QA_table">
                            <!-- table-responsive -->
                            <div class="table-responsive" id="product_list_div">
                                <table class="table">
                                    <thead>

                                        <tr>
                                            <th width="10%" scope="col">
                                                <label class="primary_checkbox d-flex ">
                                                    <input type="checkbox" id="upSaleAll">
                                                    <span class="checkmark"></span>
                                                </label>
                                            </th>
                                            <th width="20%" scope="col">{{ __('common.name') }}</th>
                                            <th width="15%" scope="col">{{ __('product.brand') }}</th>
                                            <th width="10%" scope="col">{{ __('product.thumbnail') }}</th>
                                            <th width="10%" scope="col">{{ __('product.created_at') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tablecontentsupSaleAll">
                                        @foreach ($products as $key => $item)
                                        <tr>
                                            <th scope="col">
                                                <label class="primary_checkbox d-flex">
                                                    <input name="up_sale[]" id="up_sale_{{$key}}" @if(isset($product) &&
                                                        @$product->upSales->where('up_sale_product_id',$item->id)->first())
                                                    checked @endif value="{{$item->id}}" type="checkbox">
                                                    <span class="checkmark"></span>
                                                </label>
                                            </th>
                                            <td>{{ $item->product_name }}</td>
                                            <td>{{ @$item->brand->name }}</td>
                                            <td>
                                                <div class="product_img_div">
                                                    <img class="product_list_img"
                                                        src="{{ showImage($item->thumbnail_image_source) }}"
                                                        alt="{{ $item->product_name }}">
                                                </div>

                                            </td>
                                            <td>{{ date(app('general_setting')->dateFormat->format, strtotime($item->created_at)) }}</td>

                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="col-12 text-center mb-20">
                                    <button class="primary_btn_2 mt-5 text-center lodeMoreUpSale">{{
                                        __('common.load_more') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div role="tabpanel" class="tab-pane fade" id="CrossSale">

                    <div class="box_header common_table_header ">
                        <div class="main-title d-md-flex">
                            <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{ __('common.cross_sale') }}</h3>

                        </div>
                    </div>
                    <div class="QA_section QA_section_heading_custom check_box_table">
                        <div class="QA_table">
                            <!-- table-responsive -->
                            <div class="table-responsive" id="product_list_div">
                                <table class="table">
                                    <thead>

                                        <tr>
                                            <th width="10%" scope="col">
                                                <label class="primary_checkbox d-flex ">
                                                    <input type="checkbox" id="crossSaleAll">
                                                    <span class="checkmark"></span>
                                                </label>
                                            </th>
                                            <th width="20%" scope="col">{{ __('common.name') }}</th>
                                            <th width="15%" scope="col">{{ __('product.brand') }}</th>
                                            <th width="10%" scope="col">{{ __('product.thumbnail') }}</th>
                                            <th width="10%" scope="col">{{ __('product.created_at') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tablecontentscrossSaleAll">
                                        @foreach ($products as $key => $item)
                                        <tr>
                                            <th scope="col">
                                                <label class="primary_checkbox d-flex">
                                                    <input name="cross_sale[]" id="cross_sale_{{$key}}"
                                                        @if(isset($product) &&
                                                        @$product->crossSales->where('cross_sale_product_id',$item->id)->first())
                                                    checked @endif value="{{$item->id}}" type="checkbox">
                                                    <span class="checkmark"></span>
                                                </label>
                                            </th>
                                            <td>{{ $item->product_name }}</td>
                                            <td>{{ @$item->brand->name }}</td>
                                            <td>
                                                <div class="product_img_div">
                                                    <img class="product_list_img"
                                                        src="{{ showImage($item->thumbnail_image_source) }}"
                                                        alt="{{ $item->product_name }}">
                                                </div>
                                            </td>
                                            <td>{{ date(app('general_setting')->dateFormat->format, strtotime($item->created_at)) }}</td>

                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="col-12 text-center mb-20">
                                    <button class="primary_btn_2 mt-5 text-center lodeMoreCrossSale">{{
                                        __('common.load_more') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-warning mt-30 text-center">
                        {{__('product.save_information')}}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>

                <input type="hidden" name="request_from" value="main_product_form">

                <div class="col-12 text-center">
                    <input type="hidden" name="save_type" id="save_type">
                    <button class="primary_btn_2 mt-5 text-center saveBtn" data-value="only_save"><i
                            class="ti-check"></i>{{ __('common.save') }}</button>

                    @if(isModuleActive('MultiVendor'))
                    <button class="primary_btn_2 mt-5 text-center saveBtn" data-value="save_publish"><i
                            class="ti-check"></i>{{ __('common.save') }} & {{ __('common.publish') }}</button>
                    @endif
                </div>
            </div>

        </form>
    </div>
</section>

@include('product::products.components._create_category_modal')
@include('product::products.components._create_brand_modal')
@include('product::products.components._create_unit_modal')
@include('product::products.components._create_attribute_modal')
@include('product::products.components._create_shipping_modal')
@endsection
@include('product::products.create_script')
