<div class="main-title d-md-flex form_div_header">
    <h3 class="mb-3 mr-30 mb_xs_15px mb_sm_20px">{{__('product.add_category')}} </h3>
    @if (permissionCheck('product.bulk_category_upload_page'))
        <ul class="d-flex">
            <li><a class="primary-btn radius_30px mr-10 fix-gr-bg" href="{{ route('product.bulk_category_upload_page') }}"><i class="ti-plus"></i>{{ __('product.bulk_category_upload') }}</a></li>
        </ul>
    @endif
</div>

<form method="POST" action="" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data"
id="add_category_form">

    <div class="white-box">
        <div class="add-visitor">
            <div class="row">

                <div class="col-lg-12">
                    <div class="primary_input mb-25">
                        <label class="primary_input_label" for="name">
                            {{__('common.name')}}
                            <span class="text-danger">*</span>
                        </label>
                        <input class="primary_input_field name" type="text" id="name" name="name" autocomplete="off"  placeholder="{{__('common.name')}}">
                    </div>
                    <span class="text-danger" id="error_name"></span>
                </div>

                <div class="col-lg-12">
                    <div class="primary_input mb-25">
                        <label class="primary_input_label" for="slug">
                           {{__('common.slug')}}
                            <span class="text-danger">*</span>
                        </label>
                        <input class="primary_input_field slug" type="text" id="slug" name="slug" autocomplete="off" placeholder="{{__('common.slug')}}">
                    </div>
                    <span class="text-danger"  id="error_slug"></span>
                </div>

                @if(isModuleActive('MultiVendor'))
                <div class="col-lg-12">
                    <div class="primary_input mb-25">
                        <label class="primary_input_label" for="name">
                            {{__('common.commission_rate')}}
                        </label>
                        <input class="primary_input_field commission_rate" type="number" min="0" step="{{step_decimal()}}" value="0" id="commission_rate" name="commission_rate" autocomplete="off"  placeholder="{{__('common.commission_rate')}}">
                    </div>
                    <span class="text-danger" id="error_commission_rate"></span>
                </div>
                @endif

                <div class="col-lg-12">
                    <div class="primary_input mb-25">
                        <label class="primary_input_label" for="icon">
                           {{__('common.icon')}} ({{ __('product.to_use_themefy_icon_please_type_here_or_select_fontawesome_from_list') }})
                        </label>
                        <input class="primary_input_field" type="text" id="icon" name="icon"
                        autocomplete="off" placeholder="{{__('common.icon')}}">
                    </div>
                    <span class="text-danger"  id="error_icon"></span>
                </div>

                <div class="col-xl-12 mt-20">
                    <div class="primary_input">
                        <label class="primary_input_label" for="">{{ __('product.searchable') }}</label>
                        <ul id="theme_nav" class="permission_list sms_list ">
                            <li>
                                <label data-id="bg_option" class="primary_checkbox d-flex mr-12 extra_width">
                                    <input name="searchable" id="searchable_active" value="1" checked="true"
                                        class="active" type="radio">
                                    <span class="checkmark"></span>
                                </label>
                                <p>{{ __('common.active') }}</p>
                            </li>
                            <li>
                                <label data-id="color_option" class="primary_checkbox d-flex mr-12 extra_width">
                                    <input name="searchable" id="searchable_inactive" value="0"
                                        class="de_active" type="radio">
                                    <span class="checkmark"></span>
                                </label>
                                <p>{{ __('common.inactive') }}</p>
                            </li>
                        </ul>
                        <span class="text-danger" id="error_searchable"></span>
                    </div>
                </div>

                 <div class="col-xl-12">
                    <div class="primary_input">
                        <label class="primary_input_label" for="">{{ __('common.status') }}</label>
                        <ul id="theme_nav" class="permission_list sms_list ">
                            <li>
                                <label data-id="bg_option" class="primary_checkbox d-flex mr-12 extra_width">
                                    <input name="status" id="status_active" value="1" checked="true" class="active"
                                        type="radio">
                                    <span class="checkmark"></span>
                                </label>
                                <p>{{ __('common.active') }}</p>
                            </li>
                            <li>
                                <label data-id="color_option" class="primary_checkbox d-flex mr-12 extra_width">
                                    <input name="status" value="0" id="status_inactive" class="de_active" type="radio">
                                    <span class="checkmark"></span>
                                </label>
                                <p>{{ __('common.inactive') }}</p>
                            </li>
                        </ul>
                        <span class="text-danger" id="error_status"></span>
                    </div>
                </div>


                <div class="col-xl-12">
                    <div class="primary_input">
                        <ul id="theme_nav" class="permission_list sms_list ">
                            <li>
                                <label data-id="bg_option" class="primary_checkbox d-flex mr-12">
                                    <input class="in_sub_cat" name="category_type" id="sub_cat" value="subCategory" type="checkbox">
                                    <span class="checkmark"></span>
                                </label>
                                <p>{{ __('product.add_as_sub_category') }}</p>
                            </li>
                        </ul>
                        <span class="text-danger" id=""></span>
                    </div>
                </div>


                <div class="col-xl-12 d-none in_parent_div" id="sub_cat_div">
                    <div class="primary_input mb-25">
                        <label class="primary_input_label" for="">{{ __('product.parent_category') }} <span class="text-danger">*</span></label>
                        <select class="primary_select mb-25" name="parent_id" id="parent_id">
                            @foreach($CategoryList->where('status', 1)->where('parent_id', 0) as $item)

                                <option value="{{$item->id}}"><span>-></span> {{ $item->name}}</option>
                                @if(count($item->subCategories) > 0)
                                    @foreach($item->subCategories as $subItem)
                                        @include('product::category.components.category_select',['subItem' => $subItem])
                                    @endforeach
                                @endif
                            @endforeach
                        </select>

                        <span class="text-danger"></span>

                    </div>
                </div>

                <div class="col-xl-12 upload_photo_div">
                    <div class="primary_input">
                        <label class="primary_input_label" for="">{{__('common.upload_photo')}}</label>

                        <span class="text-danger" id="photo_error"></span>
                    </div>
                </div>
                <div class="single_p col-xl-12 upload_photo_div">
                    <h6>Ratio: (225 X 225)PX</h6>


                    <div class="primary_input mb-25">
                        <div class="primary_file_uploader">
                          <input class="primary-input" type="text" id="image_file" placeholder="{{__('common.browse_image_file')}}" readonly="">
                          <button class="" type="button">
                              <label class="primary-btn small fix-gr-bg" for="image">{{__("common.browse")}} </label>
                              <input type="file" class="d-none" accept="image/*" name="image" id="image">
                          </button>
                       </div>

                       @error('logo')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    <div class="form_img_div">
                        <img id="catImgShow" src="{{ showImage('backend/img/default.png') }}" alt="">
                    </div>

                </div>

                <div class="col-lg-12">
                    <div class="primary_input mb-25">
                        <label class="primary_input_label" for="title">
                            {{__('common.title')}}
                            <span class="text-danger">*</span>
                        </label>
                        <input class="primary_input_field title" type="text" id="title" name="title" autocomplete="off" placeholder="{{__('common.title')}}">
                    </div>
                    <span class="text-danger"  id="error_title"></span>
                </div>

                <div class="col-lg-12">
                    <div class="primary_input mb-25">
                        <label class="primary_input_label" for="description">
                            {{__('common.description')}}
                            <span class="text-danger">*</span>
                        </label>
                        <textarea class="primary_input_field description" type="text" id="description" name="description" autocomplete="off" placeholder="{{__('common.description')}}"></textarea>
                    </div>
                    <span class="text-danger"  id="error_description"></span>
                </div>

                <div class="col-lg-12">
                    <div class="primary_input mb-25">
                        <label class="primary_input_label" for="meta_title">
                            {{__('common.meta_title')}}
                            <span class="text-danger">*</span>
                        </label>
                        <input class="primary_input_field meta_title" type="text" id="meta_title" name="meta_title" autocomplete="off" placeholder="{{__('common.meta_title')}}">
                    </div>
                    <span class="text-danger"  id="error_meta_title"></span>
                </div>

                <div class="col-lg-12">
                    <div class="primary_input mb-25">
                        <label class="primary_input_label" for="meta_description">
                            {{__('common.meta_description')}}
                            <span class="text-danger">*</span>
                        </label>
                        <textarea style="padding: 15px; height: 250px" class="primary_input_field meta_description" type="text" id="meta_description" name="meta_description" autocomplete="off" placeholder="{{__('common.meta_description')}}"></textarea>
                    </div>
                    <span class="text-danger"  id="error_meta_description"></span>
                </div>

                <div class="col-lg-12">
                    <div class="primary_input mb-25">
                        <label class="primary_input_label" for="meta_keyword">
                            Meta keyword
                            <span class="text-danger">*</span>
                        </label>
                        <input class="primary_input_field meta_keyword" type="text" id="meta_keyword" name="meta_keyword" autocomplete="off" placeholder="Meta keyword">
                    </div>
                    <span class="text-danger"  id="error_meta_keyword"></span>
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
                                <input type="hidden" class="selected_files">
                            </button>
                        </div>
                        <div class="product_image_all_div">

                        </div>
                    </div>
                    <span class="text-danger" id="error_meta_image"></span>
                </div>
                <div class="col-lg-12">
                    <div class="primary_input mb-15">
                        <label class="primary_input_label" for="">Meta image alt</label>
                        <input class="primary_input_field" name="meta_image_alt"
                               placeholder="Meta image alt" type="text"
                               value="">
                        <span class="text-danger"></span>
                    </div>
                    <span class="text-danger" id="error_meta_image_alt"></span>
                </div>

                <div class="col-lg-12">
                    <div class="primary_input mb-15">
                        <label class="primary_input_label" for=""> Scheme Markup </label>
                        <textarea style="height: 250px;" class="primary_input_field" name="scheme_markup"
                                  placeholder="Scheme Markup">{{ old('scheme_markup') }}</textarea>
                        <span class="text-danger">{{ $errors->first('scheme_markup') }}</span>
                    </div>
                </div>



            </div>

                <div class="row mt-40">
                        <div class="col-lg-12 text-center">
                            <button id="create_btn" type="submit" class="primary-btn fix-gr-bg submit_btn" data-toggle="tooltip" title=""
                                data-original-title="">
                                <span class="ti-check"></span>
                                {{__('common.save')}} </button>
                        </div>
                </div>
        </div>
    </div>
</form>
<script>
    $('#description').summernote();
</script>