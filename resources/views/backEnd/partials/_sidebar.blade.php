<!-- sidebar part here -->

<nav id="sidebar" class="sidebar">
    <div class="sidebar-header update_sidebar">
        <a class="large_logo" href="{{ url('/login') }}">
{{--            <img src="{{showImage(app('general_setting')->logo)}}" alt="">--}}
            <img src="{{asset(asset_path('new/img/Frame 4658.png'))}}" alt="">
        </a>
{{--        <a class="mini_logo" href="{{ url('/login') }}">--}}
{{--            <img src="{{showImage(app('general_setting')->favicon)}}" alt="">--}}
{{--            <img src="{{showImage(app('general_setting')->favicon)}}" alt="">--}}
{{--        </a>--}}
{{--        <a id="close_sidebar" class="d-lg-none">--}}
{{--            <i class="ti-close"></i>--}}
{{--        </a>--}}
    </div>
    @if(auth()->check() && auth()->user()->role->type == "admin")
        <ul id="sidebar_menu">
        <li class=" sortable_li"
            data-position="1" data-status="{{ menuManagerCheck(1,1)->status }}">

            <a @if (auth()->check() && auth()->user()->role->type == "superadmin" || auth()->check() && auth()->user()->role->type == "admin" || auth()->check() && auth()->user()->role->type == "staff") href="{{ url('/message') }}"  @endif aria-expanded="false">
                <div class="nav_icon_small">
                    <i class="fas fa-comment-alt" style="color: #415094"></i>
                </div>
                <div class="nav_title">
                    <span>Messages</span>
                </div>
            </a>
        </li>


            <li>
                <a href="javascript:;" class="has-arrow" >
                    <div class="nav_icon_small">
                        <span class="fas fa-shopping-cart"></span>
                    </div>
                    <div class="nav_title">
                        <span>{{ __('order.order_manage') }}</span>
                    </div>
                </a>
                <ul id="order_manage_ul">
            <li data-position="{{ menuManagerCheck(2,14,'order_manage.total_sales_get_data')->position }}">
                <a href="{{route('order_manage.total_sales_index')}}" @if (strpos(request()->getUri(),'total-sales-list') != false || strpos(request()->getUri(),'sales-details') != false) class="active" @endif>{{ __('order.total_order') }}</a>
            </li>
                </ul>
            </li>


            <li class="{{ request()->is('admin/comments')  ?'mm-active' : '' }} "
               >

                <a @if (auth()->check() && auth()->user()->role->type == "superadmin" || auth()->check() && auth()->user()->role->type == "admin" || auth()->check() && auth()->user()->role->type == "staff") href="{{ url('/admin/comments') }}"  @endif aria-expanded="false">
                    <div class="nav_icon_small">
                        <i class="fas fa-comment" style="color: #415094"></i>
                    </div>
                    <div class="nav_title">
                        <span>Comments</span>
                    </div>
                </a>
            </li>
{{--            @if (permissionCheck('review.product.index') && menuManagerCheck(2,13,'review.product.index')->status == 1)--}}
            <li class="{{ request()->is('review/product-list')  ?'mm-active' : '' }} ">

                <a @if (auth()->check() && auth()->user()->role->type == "superadmin" || auth()->check() && auth()->user()->role->type == "admin" || auth()->check() && auth()->user()->role->type == "staff") href="{{ route('review.product.index') }}"  @endif aria-expanded="false">
                    <div class="nav_icon_small">
                        <i class="fas fa-quote-left" style="color: #415094"></i>
                    </div>
                    <div class="nav_title">
                        <span>{{ __('review.product_review') }}</span>
                    </div>
                </a>
            </li>
{{--                <li data-position="{{ menuManagerCheck(2,13,'review.product.index')->position }}">--}}
{{--                    <div class="nav_icon_small">--}}
{{--                        <i class="fas fa-comment" style="color: #415094"></i>--}}
{{--                    </div>--}}
{{--                    <a href="{{ route('review.product.index') }}" @if (request()->is('review/product-list')) class="active"--}}
{{--                            @endif>{{ __('review.product_review') }}</a>--}}
{{--                </li>--}}
{{--            @endif--}}
        </ul>



    @else

    <ul id="sidebar_menu">

        <li class="" data-position="0" >
            <a href="javascript:;" class="has-arrow" aria-expanded="false">
                <div class="nav_icon_small">
                    <span class="fas fa-user"></span>
                </div>
                <div class="nav_title">
                    <span>Artoors</span>
                </div>
            </a>
            <ul >
                <li class="nav-item mb_5">
                    <a class="nav-link  active" href="{{route('admin.popap')}}" >Best Discount</a>
                </li>
                @if (permissionCheck('product.category.index') && menuManagerCheck(2,12,'product.category.index')->status == 1)
                    <li data-position="{{ menuManagerCheck(2,12,'product.category.index')->position }}">
                        <a href="{{ route('product.category.index') }}" @if (strpos(request()->getUri(),'category') != false) class="active" @endif>{{__('product.category')}}</a>
                    </li>
                @endif
                @if (permissionCheck('product.create') && menuManagerCheck(2,12,'product.create')->status == 1)
                    <li data-position="{{ menuManagerCheck(2,12,'product.create')->position }}">
                        <a href="{{ route('product.create') }}" @if (request()->is('product/create')) class="active" @endif>{{__('product.add_new_product')}}</a>
                    </li>
                @endif
                @if (permissionCheck('product.index') && menuManagerCheck(2,12,'product.index')->status == 1)
                    <li data-position="{{ menuManagerCheck(2,12,'product.index')->position }}">
                        <a href="{{ route('product.index') }}" @if (request()->is('product')) class="active" @endif>{{__('product.product_list')}}</a>
                    </li>
                @endif
                @if (permissionCheck('marketing.coupon.get-data') && menuManagerCheck(2,10,'marketing.coupon.get-data')->status == 1)
                    <li data-position="{{ menuManagerCheck(2,10,'marketing.coupon.get-data')->position }}">
                        <a href="{{route('marketing.coupon')}}" @if (strpos(request()->getUri(),'coupon') != false) class="active" @endif>{{__('marketing.coupons')}}</a>
                    </li>
                @endif
                @if (menuManagerCheck(2,5,'customer.show_details')->status == 1)
                    <li data-position="{{ menuManagerCheck(2,5,'customer.show_details')->position }}">
                        <a href="{{route('cusotmer.list_active')}}" @if (request()->is('customer/active-customer-list') || request()->is('customer/profile/details')) class="active" @endif>{{ __('common.all_customer') }}</a>
                    </li>
                @endif
                @if (permissionCheck('appearance.header.index') && menuManagerCheck(2,3,'appearance.header.index')->status == 1)
                    <li data-position="{{ menuManagerCheck(2,3,'appearance.header.index')->position }}">
                        <a href="{{route('appearance.header.setup',1)}}" class="{{request()->is('appearance/headers*') ? 'active' : ''}}">{{__('appearance.header')}}</a>
                    </li>
                @endif
                @if (permissionCheck('review.product.index') && menuManagerCheck(2,13,'review.product.index')->status == 1)
                    <li data-position="{{ menuManagerCheck(2,13,'review.product.index')->position }}">
                        <a href="{{ route('review.product.index') }}" @if (request()->is('review/product-list')) class="active"
                                @endif>{{ __('review.product_review') }}</a>
                    </li>
                @endif
            </ul>
        </li>

        <li class="{{ request()->is('admin/comments')  ?'mm-active' : '' }} sortable_li"
            data-position="{{ menuManagerCheck(1,1)->position }}" data-status="{{ menuManagerCheck(1,1)->status }}">

            <a @if (auth()->check() && auth()->user()->role->type == "superadmin" || auth()->check() && auth()->user()->role->type == "admin" || auth()->check() && auth()->user()->role->type == "staff") href="{{ url('/admin/comments') }}"  @endif aria-expanded="false">
                <div class="nav_icon_small">
                    <i class="fas fa-comment" style="color: #415094"></i>
                </div>
                <div class="nav_title">
                    <span>Comments</span>
                </div>
            </a>
        </li>


{{--        <li class=" sortable_li"--}}
{{--            data-position="{{ menuManagerCheck(1,1)->position }}" data-status="{{ menuManagerCheck(1,1)->status }}">--}}

{{--            <a @if (auth()->check() && auth()->user()->role->type == "superadmin" || auth()->check() && auth()->user()->role->type == "admin" || auth()->check() && auth()->user()->role->type == "staff") href="{{ url('/message') }}"  @endif aria-expanded="false">--}}
{{--                <div class="nav_icon_small">--}}
{{--                    <i class="fas fa-comment-alt" style="color: #415094"></i>--}}
{{--                </div>--}}
{{--                <div class="nav_title">--}}
{{--                    <span>Messages</span>--}}
{{--                </div>--}}
{{--            </a>--}}
{{--        </li>--}}

    @if(permissionCheck('admin.dashboard'))
            <li class="{{ request()->is('admin-dashboard') || request()->is('seller/dashboard') ?'mm-active' : '' }} sortable_li"
                data-position="{{ menuManagerCheck(1,1)->position }}" data-status="{{ menuManagerCheck(1,1)->status }}">

                <a @if (auth()->check() && auth()->user()->role->type == "superadmin" || auth()->check() && auth()->user()->role->type == "admin" || auth()->check() && auth()->user()->role->type == "staff") href="{{ route('admin.dashboard') }}"  @endif aria-expanded="false">
                    <div class="nav_icon_small">
                        <span class="fas fa-th"></span>
                    </div>
                    <div class="nav_title">
                        <span>{{ __('common.dashboard') }}</span>
                    </div>
                </a>
            </li>
        @endif


        @if(permissionCheck('seller.dashboard') && auth()->user()->role->type == 'seller' && isModuleActive('MultiVendor'))
            <li class="{{ request()->is('seller/dashboard') ?'mm-active' : '' }} sortable_li"
                data-position="{{ menuManagerCheck(1,34)->position }}" data-status="{{ menuManagerCheck(1,34)->status }}">
                <a href="{{ route('seller.dashboard') }}" aria-expanded="false">
                    <div class="nav_icon_small">
                        <span class="fas fa-th"></span>
                    </div>
                    <div class="nav_title">
                        <span>{{ __('common.dashboard') }}</span>
                    </div>
                </a>
            </li>
        @endif



        @if(isModuleActive('MultiVendor'))
            @include('multivendor::menu')
        @endif

        @if(isModuleActive('Lead'))
            @include('lead::menu')
        @endif

        @include('adminreport::seller.menu')

{{--        @include('shipping::menu')--}}


        @if (auth()->check() && auth()->user()->role->type == "superadmin" || auth()->check() && auth()->user()->role->type == "admin" || auth()->check() && auth()->user()->role->type == "staff")
            @include('frontendcms::menu')
{{--            @include('appearance::menu')--}}
            @include('blog::menu')
{{--            @include('customer::menu')--}}
        @endif

        @include('seller::menu')
        @if (auth()->check() && auth()->user()->role->type == "superadmin" || auth()->check() && auth()->user()->role->type == "admin" || auth()->check() && auth()->user()->role->type == "staff")
{{--            @include('wallet::menu')--}}
{{--            @include('contactrequest::menu')--}}
        @endif
        @include('marketing::menu')

{{--        @if (auth()->check() && auth()->user()->role->type == "superadmin" || auth()->check() && auth()->user()->role->type == "admin" || auth()->check() && auth()->user()->role->type == "staff")--}}
{{--            @include('giftcard::menu')--}}
{{--        @endif--}}

        @if(isModuleActive("Affiliate") && permissionCheck('affiliate'))
            @include('affiliate::menu')
        @endif

        @if(isModuleActive('Affiliate') && permissionCheck('page_builder'))
            @include('pagebuilder::menu')
        @endif

{{--        @if(permissionCheck('form_builder'))--}}
{{--            @include('formbuilder::menu')--}}
{{--        @endif--}}

{{--        @include('product::menu')--}}
{{--        @include('review::menu')--}}
        @include('ordermanage::menu')
{{--        @include('refund::menu')--}}
        @if (auth()->check() && auth()->user()->role->type == "superadmin" || auth()->check() && auth()->user()->role->type == "admin" || auth()->check() && auth()->user()->role->type == "staff")
            @include('generalsetting::menu')
{{--            @include('paymentgateway::menu')--}}
            @if (isModuleActive('AmazonS3'))

                <li class="{{request()->is('admin/file-storage/setting') ? 'mm-active' :''}} sortable_li" data-position="{{ menuManagerCheck(1,17)->position }}" data-status="{{ menuManagerCheck(1,17)->status }}">
                    <a href="{{ route('file-storage.index') }}" aria-expanded="false">
                        <div class="nav_icon_small">
                            <span class="ti-files"></span>
                        </div>
                        <div class="nav_title">
                            <span>{{__('general_settings.file_storage')}}</span>
                            @if (config('app.sync'))
                            <span class="demo_addons">Addon</span>
                            @endif
                        </div>
                    </a>
                </li>
            @endif
            @include('setup::menu')
{{--            @if (file_exists(base_path().'/Modules/GST/'))--}}
{{--                @include('gst::menu')--}}
{{--            @endif--}}
{{--            @include('account::menu')--}}
        @endif

{{--        @include('sidebarmanager::menu')--}}
        @if(isModuleActive('Otp'))
            @include('otp::menu')
        @endif
{{--        @include('utilities::menu')--}}


{{--        @include('supportticket::menu')--}}
        @if (auth()->check() && auth()->user()->role->type == "superadmin" || auth()->check() && auth()->user()->role->type == "admin" || auth()->check() && auth()->user()->role->type == "staff")
{{--            @include('useractivitylog::menu')--}}
            @include('backEnd.menu')
{{--            @include('visitor::menu')--}}
            @include('adminreport::menu')
{{--            @include('backup::menu')--}}
        @endif

        @if(permissionCheck('seller_subscription_payment') && isModuleActive('MultiVendor'))
            @if (auth()->check() && auth()->user()->role->type == "seller")
                <li class="{{ request()->is('seller/my-subscription-payment-list') ?'mm-active' : '' }} sortable_li" data-position="{{ menuManagerCheck(1,29)->position }}" data-status="{{ menuManagerCheck(1,29)->status }}">
                    <a class="" href="{{ route('seller.my_subscription_payment_list') }}" aria-expanded="false">
                        <div class="nav_icon_small">
                            <span class="fas fa-th"></span>
                        </div>
                        <div class="nav_title">
                            <span>{{ __('common.subscription_payment') }}</span>
                        </div>
                    </a>
                </li>
            @endif
        @endif
        <li class="" data-position="1" >
            <a href="javascript:;" class="has-arrow" aria-expanded="false">
                <div class="nav_icon_small">
                    <span class="fas fa-user"></span>
                </div>
                <div class="nav_title">
                    <span>SEO STATIC PAGES</span>
                </div>
            </a>
            <ul >
                <li class="nav-item mb_5">
                    <a class="nav-link  active" href="{{route('admin.home-page')}}" >HOME PAGE</a>
                </li>
                <li class="nav-item mb_5">
                    <a class="nav-link  active" href="{{route('front.privacy-policy-page')}}" >Privacy Policy PAGE</a>
                </li>
                <li class="nav-item mb_5">
                    <a class="nav-link  active" href="{{route('front.terms-conditions-page')}}" >Terms & Conditions PAGE</a>
                </li>

                <li class="nav-item mb_5">
                    <a class="nav-link  active" href="{{route('front.contact-us-page')}}" >Message PAGE</a>
                </li>
                <li class="nav-item mb_5">
                    <a class="nav-link  active" href="{{route('front.blog-page')}}" >Blog PAGE</a>
                </li>
            </ul>
        </li>
        
        {{--        @if(permissionCheck('media-manager'))--}}
{{--            @php--}}
{{--                $media_manager_backend = false;--}}
{{--                if(request()->is('media-manager/*'))--}}
{{--                {--}}
{{--                    $media_manager_backend = true;--}}
{{--                }--}}
{{--            @endphp--}}
{{--            <li class="{{ $media_manager_backend ?'mm-active' : '' }} sortable_li" data-position="{{ menuManagerCheck(1,42)->position }}" data-status="{{ menuManagerCheck(1,42)->status }}">--}}
{{--                <a href="javascript:;" class="has-arrow" aria-expanded="{{ $media_manager_backend ? 'true' : 'false' }}">--}}
{{--                    <div class="nav_icon_small">--}}
{{--                        <span class="ti-image"></span>--}}
{{--                    </div>--}}
{{--                    <div class="nav_title">--}}
{{--                        <span>{{ __('Media Manager') }}</span>--}}
{{--                    </div>--}}
{{--                </a>--}}
{{--                <ul id="media_ul">--}}
{{--                    @if(permissionCheck('media-manager.upload_files') && menuManagerCheck(2,42,'media-manager.upload_files')->status == 1)--}}
{{--                        <li data-position="{{ menuManagerCheck(2,42,'media-manager.upload_files')->position }}">--}}
{{--                            <a href="{{route('media-manager.upload_files')}}" @if (request()->is('media-manager/upload-files'))--}}
{{--                                class="active" @endif>{{ __('All Upload Files') }}</a>--}}
{{--                        </li>--}}
{{--                    @endif--}}

{{--                    @if(permissionCheck('media-manager.new-upload') && menuManagerCheck(2,42,'media-manager.new-upload')->status == 1)--}}
{{--                        <li data-position="{{ menuManagerCheck(2,42,'media-manager.new-upload')->position }}">--}}
{{--                            <a href="{{route('media-manager.new-upload')}}" @if (request()->is('new-upload')) class="active"--}}
{{--                                @endif>{{ __('New Uplaod') }}</a>--}}
{{--                        </li>--}}
{{--                    @endif--}}
{{--                </ul>--}}
{{--            </li>--}}
{{--        @endif--}}



        @if(permissionCheck('customer_panel') && isModuleActive('MultiVendor'))

        @php
            $customer_backkend = false;
            if(strpos(request()->getUri(),'my-purchase-orders') != false || strpos(request()->getUri(),'purchased-gift-cards') != false || strpos(request()->getUri(),'my-wishlist') != false || request()->is('refund/my-refund-list') || request()->is('digital-products') || request()->is('profile/coupons') || request()->is('profile/referral') || request()->is('profile'))
            {
                $customer_backkend = true;
            }
        @endphp

        <li class="{{ $customer_backkend ?'mm-active' : '' }} sortable_li" data-position="{{ menuManagerCheck(1,26)->position }}" data-status="{{ menuManagerCheck(1,26)->status }}">
            <a href="javascript:;" class="has-arrow" aria-expanded="{{ $customer_backkend ? 'true' : 'false' }}">
                <div class="nav_icon_small">
                    <span class="fas fa-th"></span>
                </div>
                <div class="nav_title">
                    <span>{{__('customer_panel.customer_panel')}}</span>
                </div>
            </a>

            <ul class="mm-collapse">
                @if(permissionCheck('frontend.my_purchase_order_list') && menuManagerCheck(2,26,'frontend.my_purchase_order_list')->status == 1)
                <li data-position="{{ menuManagerCheck(2,26,'frontend.my_purchase_order_list')->position }}">
                    <a href="{{ route('frontend.my_purchase_order_list') }}" @if (request()->is('my-purchase-orders')) class="active" @endif>{{__('customer_panel.my_purchases')}}</a>
                </li>
                @endif
                @if(permissionCheck('frontend.purchased-gift-card') && menuManagerCheck(2,26,'frontend.purchased-gift-card')->status == 1)
                <li data-position="{{ menuManagerCheck(2,26,'frontend.purchased-gift-card')->position }}">
                    <a href="{{ route('frontend.purchased-gift-card') }}" @if (request()->is('purchased-gift-cards')) class="active" @endif>{{__('customer_panel.gift_card')}}</a>
                </li>
                @endif
                @if(permissionCheck('frontend.digital_product') && menuManagerCheck(2,26,'frontend.digital_product')->status == 1)
                <li data-position="{{ menuManagerCheck(2,26,'frontend.digital_product')->position }}">
                    <a href="{{ route('frontend.digital_product') }}" @if (request()->is('digital-products')) class="active" @endif>{{ __('customer_panel.digital_product') }}</a>
                </li>
                @endif
                @if(permissionCheck('frontend.my-wishlist') && menuManagerCheck(2,26,'frontend.my-wishlist')->status == 1)
                <li data-position="{{ menuManagerCheck(2,26,'frontend.my-wishlist')->position }}">
                    <a href="{{route('frontend.my-wishlist')}}" @if (request()->is('my-wishlist')) class="active" @endif>{{__('customer_panel.my_wishlist')}}</a>
                </li>
                @endif
                @if(permissionCheck('refund.frontend.index') && menuManagerCheck(2,26,'refund.frontend.index')->status == 1)
                <li data-position="{{ menuManagerCheck(2,26,'refund.frontend.index')->position }}">
                    <a href="{{route('refund.frontend.index')}}" @if (request()->is('refund/my-refund-list')) class="active" @endif>{{__('customer_panel.refund_dispute')}}</a>
                </li>
                @endif
                @if(permissionCheck('customer_panel.coupon') && menuManagerCheck(2,26,'customer_panel.coupon')->status == 1)
                <li data-position="{{ menuManagerCheck(2,26,'customer_panel.coupon')->position }}">
                    <a href="{{route('customer_panel.coupon')}}" @if (request()->is('profile/coupons')) class="active" @endif>{{__('customer_panel.my_coupon')}}</a>
                </li>
                @endif
                @if(permissionCheck('frontend.customer_profile') && menuManagerCheck(2,26,'frontend.customer_profile')->status == 1)
                <li data-position="{{ menuManagerCheck(2,26,'frontend.customer_profile')->position }}">
                    <a href="{{route('frontend.customer_profile')}}" @if (request()->is('profile')) class="active" @endif>{{__('customer_panel.my_account')}}</a>
                </li>
                @endif

                @if(permissionCheck('customer_panel.referral') && menuManagerCheck(2,26,'customer_panel.referral')->status == 1)
                <li data-position="{{ menuManagerCheck(2,26,'customer_panel.referral')->position }}">
                    <a href="{{route('customer_panel.referral')}}" @if (request()->is('profile/referral')) class="active" @endif>{{__('customer_panel.my_referral')}}</a>
                </li>
                @endif

            </ul>
        </li>
        @endif

    </ul>
    @endif
</nav>
<!-- sidebar part end -->
