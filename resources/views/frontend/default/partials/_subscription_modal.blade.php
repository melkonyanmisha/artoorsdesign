

{{--@php--}}
{{--$modal = false;--}}
{{--if(Session::get('ip') == NULL){--}}
{{--Session::put('ip',request()->ip());--}}
{{--$modal = true;--}}
{{--}--}}
{{--if($popupContent->status == 0){--}}
{{--    $modal = false;--}}
{{--}--}}
{{--@endphp--}}

{{--@if (!$modal)--}}
    <div class="subscribe_section sto_">
        <div class="subscribe_block d_flex">
            <h2>{{$popupContent->title}}</h2>
            <span class="under_title">{{$popupContent->subtitle}}</span>
            <div class="add_mail_section d_flex" id="modalSubscriptionForm">
                <input autocomplete="off" type="email" id="modalSubscription_email_id"  class="add_email_adr" placeholder="Email">
                <button id="modalSubscribeBtn" class="subs_btn">{{__('defaultTheme.subscribe')}}</button>
                <div class="col-lg-12 message_div_modal d-none">
            </div>
        </div>
        <div class="response"></div>
    </div>

{{--<div class="d-none" id="subscriptionDiv">--}}
{{--    <div class="newsletter_form_wrapper newsletter_active" id="subscriptionModal">--}}
{{--        <div class="newsletter_form_inner">--}}
{{--            <div class="close_modal">--}}
{{--                <i class="ti-close"></i>--}}
{{--            </div>--}}
{{--            <div class="newsletter_form_thumb">--}}
{{--            </div>--}}
{{--            <div class="newsletter_form text-center">--}}
{{--                <h3>{{$popupContent->title}}</h3>--}}
{{--                <p>{{$popupContent->subtitle}}</p>--}}

{{--                <form action="" id="modalSubscriptionForm">--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-lg-12">--}}
{{--                            <input type="text" name="email" class="primary_input3 mb_10" id="modalSubscription_email_id"--}}
{{--                                placeholder="{{__('defaultTheme.enter_email_address')}}" />--}}
{{--                        </div>--}}
{{--                        <div class="col-lg-12 mt-10">--}}
{{--                            <button id="modalSubscribeBtn"--}}
{{--                                class="theme_btn w-100 text-center">{{__('defaultTheme.subscribe')}}</button>--}}
{{--                        </div>--}}
{{--                        <div class="col-lg-12 message_div_modal d-none">--}}

{{--                        </div>--}}
{{--                    </div>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
{{--@endif--}}
