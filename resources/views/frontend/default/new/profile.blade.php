@extends('frontend.default.layouts.newApp')

@section('content')
    <main>
        @include('frontend.default.includes.mainInclude')
        <section class="wrapper">
{{--            <div class="d_flex from_to">--}}
{{--                <a class="from_this" href="{{url('/')}}">Home</a>--}}
{{--                <span class="slashes">/</span>--}}
{{--                <span class="this_page">My Profile</span>--}}
{{--            </div>--}}
            @section('breadcrumb')
                My Profile
            @endsection
            @include('frontend.default.partials._breadcrumb')
            <div class="d_flex my_profile_section">
                <div class="profile_menu">
                    <span class="prof_menu_sp prof_menu_sp_active edit_profile">Edit My Profile</span>
                    <span class="prof_menu_sp my_purchases">My Purchases</span>
                    <span class="prof_menu_sp my_comments">My Comments</span>
                    <span class="prof_menu_sp my_favorites">My Favorites</span>
                    <span class="prof_menu_sp settings_">Settings</span>
                </div>
                <div class="for_edit_profile for_edit_profile_active">
                    <h1>Edit My Profile</h1>
                    @php
                        $prev_address = \Modules\Customer\Entities\CustomerAddress::where('customer_id', auth()->id())->where('is_billing_default', 1)->first();
                    @endphp
                    @if($prev_address)
                        <form class="edit_prof_section" id="update_info">
                            <div class="prof_img_name d_flex">
                                <div class="edit_prof_img">
                                    <img class="customer_img" src="{{$user_info->avatar?showImage($user_info->avatar):showImage('frontend/default/img/avatar.jpg')}}" alt="">
                                </div>
                                <div class="name_edit_name d_flex">
                                    <span class="editmail">{{$user_info->email}}</span>
                                    <div class="edit_prof_pic d_flex">
                                        <input type="file" id="file" name="file">
                                        <svg width="21" height="19" viewBox="0 0 21 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M3.38852 0.539732C2.11377 0.798862 1.16509 1.78561 0.96634 3.05914C0.933575 3.26903 0.925128 4.81359 0.933916 8.98699C0.946908 15.1413 0.930469 14.6697 1.15297 15.2644C1.40403 15.9354 2.14086 16.6723 2.81192 16.9233C3.39791 17.1426 3.11279 17.1288 7.32794 17.1415C10.3029 17.1505 11.2498 17.1423 11.3783 17.1069C11.8532 16.9757 12.0597 16.3566 11.7641 15.9504C11.706 15.8706 11.2381 15.2622 10.7242 14.5985C10.2105 13.9348 9.79011 13.3808 9.79019 13.3674C9.79027 13.3461 15.1631 6.49655 15.2402 6.41943C15.2565 6.40314 16.0611 7.33213 17.0281 8.48384L18.7864 10.5779L18.8053 11.3573C18.8239 12.1248 18.8258 12.1391 18.9259 12.2902C19.0824 12.5265 19.2676 12.6234 19.5629 12.6234C19.8583 12.6234 20.0434 12.5265 20.1999 12.29L20.3015 12.1365V7.58823C20.3015 3.45809 20.2957 3.01907 20.2383 2.8127C20.1268 2.41156 20.0175 2.15959 19.8321 1.87682C19.4012 1.2194 18.8446 0.818787 18.0104 0.565679C17.8186 0.507497 17.2207 0.501777 10.7181 0.495982C4.97762 0.490868 3.58806 0.499163 3.38852 0.539732ZM17.9402 2.15872C18.2849 2.3291 18.4996 2.54846 18.6665 2.90058L18.7864 3.15361L18.7972 5.19908C18.8031 6.32409 18.8031 7.46531 18.7972 7.73516L18.7864 8.22573L17.2635 6.40917C16.426 5.41003 15.6845 4.55877 15.6158 4.51745C15.4422 4.41305 15.0476 4.41317 14.8713 4.51768C14.7887 4.56658 13.7066 5.91234 11.7878 8.35251C10.1617 10.4202 8.82124 12.1111 8.80886 12.1099C8.79643 12.1087 8.32741 11.5117 7.76661 10.7832C7.11214 9.93313 6.69835 9.42851 6.6113 9.37438C6.42854 9.26074 6.04138 9.25653 5.85565 9.36619C5.71387 9.44991 4.03307 11.6012 3.89041 11.8816C3.56693 12.5173 4.25985 13.1855 4.90512 12.8601C5.04425 12.79 5.17459 12.643 5.64232 12.029C5.95482 11.6188 6.21903 11.2828 6.22944 11.2825C6.24391 11.282 9.18481 15.0684 9.53837 15.5427L9.6086 15.637L6.60274 15.6264L3.59685 15.6158L3.34382 15.4959C2.99166 15.3291 2.77234 15.1144 2.60196 14.7696L2.46048 14.4834V8.8185V3.15361L2.58037 2.90058C2.64628 2.76145 2.76984 2.57206 2.85488 2.47978C3.02219 2.29827 3.3659 2.09584 3.60132 2.0402C3.68829 2.01963 6.58888 2.00781 10.7011 2.01133L17.6539 2.01724L17.9402 2.15872ZM5.66891 3.28702C5.25429 3.39566 4.96072 3.56752 4.63724 3.89097C4.30913 4.21908 4.14098 4.50968 4.03151 4.93756C3.89485 5.472 3.96579 6.0531 4.22913 6.55568C4.39011 6.86296 4.84966 7.32251 5.15694 7.48349C5.65952 7.74683 6.24062 7.81777 6.77505 7.68111C7.20294 7.57164 7.49354 7.40349 7.82165 7.07538C8.14976 6.74727 8.31791 6.45667 8.42738 6.02879C8.56404 5.49435 8.4931 4.91325 8.22976 4.41067C8.06878 4.10339 7.60923 3.64384 7.30195 3.48286C6.80077 3.22028 6.1991 3.14812 5.66891 3.28702ZM6.64555 4.85332C6.87138 5.00275 6.96809 5.19158 6.96809 5.48318C6.96809 5.77856 6.87119 5.96371 6.63475 6.12019C6.42342 6.26004 6.0535 6.26549 5.83865 6.13193C5.5519 5.95367 5.41717 5.59973 5.50785 5.26298C5.55997 5.06938 5.79179 4.82139 5.97228 4.76616C6.17088 4.70537 6.48308 4.74578 6.64555 4.85332ZM16.4648 11.3571C16.2946 11.4092 16.0536 11.6595 16.0049 11.8349C15.981 11.9211 15.9647 12.418 15.9646 13.0664L15.9644 14.1534L14.7967 14.1649L13.6291 14.1764L13.4756 14.278C13.2392 14.4346 13.1424 14.6197 13.1424 14.9151C13.1424 15.2104 13.2392 15.3956 13.4756 15.5521L13.6291 15.6537L14.7947 15.6652L15.9603 15.6767L15.9718 16.8394C15.9831 17.9877 15.9844 18.004 16.0732 18.1468C16.1226 18.2263 16.2322 18.3371 16.3166 18.393C16.4446 18.4777 16.5122 18.4946 16.7219 18.4946C16.9316 18.4946 16.9993 18.4777 17.1272 18.393C17.2117 18.3371 17.3213 18.2263 17.3707 18.1468C17.4594 18.004 17.4607 17.9877 17.4721 16.8394L17.4835 15.6767L18.6463 15.6652C19.7945 15.6539 19.8109 15.6526 19.9537 15.5638C20.0332 15.5144 20.144 15.4048 20.1999 15.3204C20.2845 15.1924 20.3015 15.1248 20.3015 14.9151C20.3015 14.7054 20.2845 14.6377 20.1999 14.5098C20.144 14.4253 20.0332 14.3157 19.9537 14.2663C19.8109 14.1776 19.7945 14.1763 18.6463 14.1649L17.4835 14.1535L17.4721 12.9907C17.4607 11.8425 17.4594 11.8261 17.3707 11.6833C17.1921 11.396 16.7996 11.2547 16.4648 11.3571Z" fill="white"/>
                                        </svg>
                                        Upload Avatar Image
                                    </div>
                                </div>
                            </div>
                            <div class="d_flex sto_ inp_cols">
                                <div class="d_flex inps_labs">
                                    <label for="name">{{__('common.first_name')}}</label>
                                    <input type="text" id="namee"  placeholder="{{__('common.first_name')}}" value="{{$prev_address->name}}" name="first_name">
                                </div>
                                <div class="d_flex inps_labs">
                                    <label for="lastname">{{__('common.last_name')}}</label>
                                    <input type="text" id="lastnamee"  placeholder="{{__('common.last_name')}}" value="{{$prev_address->lastname}}" name="last_name">
                                </div>
                            </div>
{{--                            <div class="d_flex sto_ inp_cols">--}}
{{--                                <div class="d_flex inps_labs">--}}
{{--                                    <label for="address">Street adress</label>--}}
{{--                                    <input type="text" id="streete" name="street" value="{{$prev_address->street}}">--}}
{{--                                </div>--}}
{{--                                <div class="d_flex inps_labs">--}}
{{--                                    <label for="city">City</label>--}}
{{--                                    <input type="text" id="citye" name="city" value="{{$prev_address->city}}">--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="d_flex sto_ inp_cols">--}}
{{--                                <div class="d_flex inps_labs">--}}
{{--                                    <label for="state">State</label>--}}
{{--                                    <input type="text" id="statee" name="state" value="{{$prev_address->state}}">--}}
{{--                                </div>--}}
{{--                                <div class="d_flex inps_labs">--}}
{{--                                    <label for="zip">Zip code</label>--}}
{{--                                    <input type="text" id="zip_codee" name="zip_code" value="{{$prev_address->postal_code}}">--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <div class="d_flex sto_ inp_cols">
                                <div class="d_flex inps_labs">
                                    <label for="country">Country</label>
                                    <input type="text" id="countrye" name="country" value="{{$prev_address->country}}">
                                </div>
                                <div class="d_flex inps_labs">
                                    <label for="email">Email</label>
                                    <input type="email" id="emaile" name="email" placeholder="{{__('common.email_address')}}" value="{{$prev_address->email}}">
                                </div>
                            </div>
                            <div class="d_flex sto_ inp_cols">
{{--                                <div class="d_flex inps_labs">--}}
{{--                                    <label for="phone">Phone number</label>--}}
{{--                                    <input type="text" id="phonee" name="phone" placeholder="{{__('common.phone_number')}}" value="{{$prev_address->phone}}">--}}
{{--                                </div>--}}
                                <button class="save_edits_btn save_edits_btnnn d_flex" data-ag="{{auth()->user()->customerAddresses->where('is_billing_default',1)->first()->id}}" type="submit">Save</button>
                            </div>
                        </form>

                    @else
                    <form class="edit_prof_section" id="update_info">
                        <div class="prof_img_name d_flex">
                            <div class="edit_prof_img">
                                <img class="customer_img"  src="{{$user_info->avatar?showImage($user_info->avatar):showImage('frontend/default/img/avatar.jpg')}}" alt="">
                            </div>
                            <div class="name_edit_name d_flex">
                                <span class="editmail">{{$user_info->email}}</span>
                                <div class="edit_prof_pic d_flex">
                                    <input type="file" id="file" name="file" >
                                    <svg width="21" height="19" viewBox="0 0 21 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M3.38852 0.539732C2.11377 0.798862 1.16509 1.78561 0.96634 3.05914C0.933575 3.26903 0.925128 4.81359 0.933916 8.98699C0.946908 15.1413 0.930469 14.6697 1.15297 15.2644C1.40403 15.9354 2.14086 16.6723 2.81192 16.9233C3.39791 17.1426 3.11279 17.1288 7.32794 17.1415C10.3029 17.1505 11.2498 17.1423 11.3783 17.1069C11.8532 16.9757 12.0597 16.3566 11.7641 15.9504C11.706 15.8706 11.2381 15.2622 10.7242 14.5985C10.2105 13.9348 9.79011 13.3808 9.79019 13.3674C9.79027 13.3461 15.1631 6.49655 15.2402 6.41943C15.2565 6.40314 16.0611 7.33213 17.0281 8.48384L18.7864 10.5779L18.8053 11.3573C18.8239 12.1248 18.8258 12.1391 18.9259 12.2902C19.0824 12.5265 19.2676 12.6234 19.5629 12.6234C19.8583 12.6234 20.0434 12.5265 20.1999 12.29L20.3015 12.1365V7.58823C20.3015 3.45809 20.2957 3.01907 20.2383 2.8127C20.1268 2.41156 20.0175 2.15959 19.8321 1.87682C19.4012 1.2194 18.8446 0.818787 18.0104 0.565679C17.8186 0.507497 17.2207 0.501777 10.7181 0.495982C4.97762 0.490868 3.58806 0.499163 3.38852 0.539732ZM17.9402 2.15872C18.2849 2.3291 18.4996 2.54846 18.6665 2.90058L18.7864 3.15361L18.7972 5.19908C18.8031 6.32409 18.8031 7.46531 18.7972 7.73516L18.7864 8.22573L17.2635 6.40917C16.426 5.41003 15.6845 4.55877 15.6158 4.51745C15.4422 4.41305 15.0476 4.41317 14.8713 4.51768C14.7887 4.56658 13.7066 5.91234 11.7878 8.35251C10.1617 10.4202 8.82124 12.1111 8.80886 12.1099C8.79643 12.1087 8.32741 11.5117 7.76661 10.7832C7.11214 9.93313 6.69835 9.42851 6.6113 9.37438C6.42854 9.26074 6.04138 9.25653 5.85565 9.36619C5.71387 9.44991 4.03307 11.6012 3.89041 11.8816C3.56693 12.5173 4.25985 13.1855 4.90512 12.8601C5.04425 12.79 5.17459 12.643 5.64232 12.029C5.95482 11.6188 6.21903 11.2828 6.22944 11.2825C6.24391 11.282 9.18481 15.0684 9.53837 15.5427L9.6086 15.637L6.60274 15.6264L3.59685 15.6158L3.34382 15.4959C2.99166 15.3291 2.77234 15.1144 2.60196 14.7696L2.46048 14.4834V8.8185V3.15361L2.58037 2.90058C2.64628 2.76145 2.76984 2.57206 2.85488 2.47978C3.02219 2.29827 3.3659 2.09584 3.60132 2.0402C3.68829 2.01963 6.58888 2.00781 10.7011 2.01133L17.6539 2.01724L17.9402 2.15872ZM5.66891 3.28702C5.25429 3.39566 4.96072 3.56752 4.63724 3.89097C4.30913 4.21908 4.14098 4.50968 4.03151 4.93756C3.89485 5.472 3.96579 6.0531 4.22913 6.55568C4.39011 6.86296 4.84966 7.32251 5.15694 7.48349C5.65952 7.74683 6.24062 7.81777 6.77505 7.68111C7.20294 7.57164 7.49354 7.40349 7.82165 7.07538C8.14976 6.74727 8.31791 6.45667 8.42738 6.02879C8.56404 5.49435 8.4931 4.91325 8.22976 4.41067C8.06878 4.10339 7.60923 3.64384 7.30195 3.48286C6.80077 3.22028 6.1991 3.14812 5.66891 3.28702ZM6.64555 4.85332C6.87138 5.00275 6.96809 5.19158 6.96809 5.48318C6.96809 5.77856 6.87119 5.96371 6.63475 6.12019C6.42342 6.26004 6.0535 6.26549 5.83865 6.13193C5.5519 5.95367 5.41717 5.59973 5.50785 5.26298C5.55997 5.06938 5.79179 4.82139 5.97228 4.76616C6.17088 4.70537 6.48308 4.74578 6.64555 4.85332ZM16.4648 11.3571C16.2946 11.4092 16.0536 11.6595 16.0049 11.8349C15.981 11.9211 15.9647 12.418 15.9646 13.0664L15.9644 14.1534L14.7967 14.1649L13.6291 14.1764L13.4756 14.278C13.2392 14.4346 13.1424 14.6197 13.1424 14.9151C13.1424 15.2104 13.2392 15.3956 13.4756 15.5521L13.6291 15.6537L14.7947 15.6652L15.9603 15.6767L15.9718 16.8394C15.9831 17.9877 15.9844 18.004 16.0732 18.1468C16.1226 18.2263 16.2322 18.3371 16.3166 18.393C16.4446 18.4777 16.5122 18.4946 16.7219 18.4946C16.9316 18.4946 16.9993 18.4777 17.1272 18.393C17.2117 18.3371 17.3213 18.2263 17.3707 18.1468C17.4594 18.004 17.4607 17.9877 17.4721 16.8394L17.4835 15.6767L18.6463 15.6652C19.7945 15.6539 19.8109 15.6526 19.9537 15.5638C20.0332 15.5144 20.144 15.4048 20.1999 15.3204C20.2845 15.1924 20.3015 15.1248 20.3015 14.9151C20.3015 14.7054 20.2845 14.6377 20.1999 14.5098C20.144 14.4253 20.0332 14.3157 19.9537 14.2663C19.8109 14.1776 19.7945 14.1763 18.6463 14.1649L17.4835 14.1535L17.4721 12.9907C17.4607 11.8425 17.4594 11.8261 17.3707 11.6833C17.1921 11.396 16.7996 11.2547 16.4648 11.3571Z" fill="white"/>
                                    </svg>
                                    Ajouter une photo
                                </div>
                            </div>
                        </div>
                        <div class="d_flex sto_ inp_cols">
                            <div class="d_flex inps_labs">
                                <label for="name">{{__('common.first_name')}}</label>
                                <input type="text" id="namee"  placeholder="{{__('common.first_name')}}" value="" name="first_name">
                            </div>
                            <div class="d_flex inps_labs">
                                <label for="lastname">{{__('common.last_name')}}</label>
                                <input type="text" id="lastnamee"  placeholder="{{__('common.last_name')}}" value="" name="last_name">
                            </div>
                        </div>
{{--                        <div class="d_flex sto_ inp_cols">--}}
{{--                            <div class="d_flex inps_labs">--}}
{{--                                <label for="address">Street adress</label>--}}
{{--                                <input type="text" id="streete" name="street" value="">--}}
{{--                            </div>--}}
{{--                            <div class="d_flex inps_labs">--}}
{{--                                <label for="city">City</label>--}}
{{--                                <input type="text" id="citye" name="city" value="">--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="d_flex sto_ inp_cols">--}}
{{--                            <div class="d_flex inps_labs">--}}
{{--                                <label for="state">State</label>--}}
{{--                                <input type="text" id="statee" name="state" value="">--}}
{{--                            </div>--}}
{{--                            <div class="d_flex inps_labs">--}}
{{--                                <label for="zip">Zip code</label>--}}
{{--                                <input type="text" id="zip_codee" name="zip_code" value="">--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <div class="d_flex sto_ inp_cols">
                            <div class="d_flex inps_labs">
                                <label for="country">Country</label>
                                <input type="text" id="countrye" name="country" value="">
                            </div>
                            <div class="d_flex inps_labs">
                                <label for="email">Email</label>
                                <input type="email" id="emaile" name="email" placeholder="{{__('common.email_address')}}" value="">
                            </div>
                        </div>
                        <div class="d_flex sto_ inp_cols">
{{--                            <div class="d_flex inps_labs">--}}
{{--                                <label for="phone">Phone number</label>--}}
{{--                                <input type="text" id="phonee"  name="phone" placeholder="{{__('common.phone_number')}}" value="">--}}
{{--                            </div>--}}
                            <button class="save_edits_btn save_edits_btnnn d_flex"  type="submit" >Save</button>
                        </div>
                    </form>
                    @endif

                    <script>

                        const reader = new FileReader();
                        const inputReader = document.querySelectorAll('.edit_prof_pic');
                        const file_id_img = document.querySelectorAll('.customer_img');
                        var result;
                        function setImg(i) {
                            file_id_img[i].src = result
                        }
                        reader.addEventListener("load", (e) => {
                            result = e.target.result
                        })
                        for(let i = 0;i < inputReader.length;i++) {
                            inputReader[i].addEventListener("change", (e) => {
                                reader.readAsDataURL(e.target.files[0]);
                                setTimeout(() => {
                                    setImg(i);
                                },200);
                            });
                        }

                        $('.save_edits_btnnn').click(function (){

                            let data = {
                                address_id: $(this).attr('data-ag')??0,
                                name: $('#namee').val(),
                                email: $('#emaile').val(),
                                lastname: $('#lastnamee').val(),
                                street: $('#streete').val(),
                                phone: $('#phonee').val(),
                                country: $('#countrye').val(),
                                state: $('#statee').val(),
                                city: $('#citye').val(),
                                postal_code: $('#zip_codee').val(),
                                _token: $('#tokene').val()
                            }

                            console.log(data)

                            $.post("{{route('frontend.checkout.billing.address.store')}}", data, function (response) {
                                // console.log('lava')
                                // location.reload()
                                // pay_button_ameria($('.total_padd').data('total'),$('.total_padd').data('ids'));
                            }).fail(function (response) {
                                console.log(response.responseJSON.errors)
                                if(response.responseJSON.errors.name){
                                    $('#namee').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
                                }
                                if(response.responseJSON.errors.lastname){
                                    $('#lastnamee').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
                                }
                                if(response.responseJSON.errors.street){
                                    $('#streete').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
                                }
                                if(response.responseJSON.errors.email){
                                    $('#emaile').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
                                }
                                if(response.responseJSON.errors.phone){
                                    $('#phonee').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
                                }
                                if(response.responseJSON.errors.country){
                                    $('#countrye').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
                                }
                                if(response.responseJSON.errors.state){
                                    $('#statee').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
                                }
                                if(response.responseJSON.errors.city){
                                    $('#citye').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
                                }
                                if(response.responseJSON.errors.postal_code){
                                    $('#zip_codee').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
                                }

                                return false;
                            });
                        })

                    </script>
                </div>
                <div class="for_my_purchases">
                    <h2>My Purchases</h2>
                    <div class="purchases_section">
                        <div class="purchases_block_">
                            <div class="purch_block d_flex sto_">
                                <span class="for_descrip">Model Name</span>
                                <span class="for_item">
                                    Grand total
                                </span>
                                <span class="for_model">{{__('common.order_id')}}</span>
                                <span class="for_date">{{__('defaultTheme.order_date')}}</span>

                                <span class="for_descrip">Status</span>
                                <span class="for_amount">Discount total</span>
                                <span class="for_amount">Generate Invoice</span>
                            </div>
{{--                            @if(session()->get('pdf'))--}}
{{--                                <script>--}}
{{--                                    $.ajax({--}}
{{--                                        url: '{{route('minchev_download')}}',--}}
{{--                                        success: function (data) {--}}
{{--                                            $('.for_amount').append(data)--}}
{{--                                            $('.for_amount').find('#submit').submit()--}}
{{--                                        }--}}
{{--                                    });--}}
{{--                                </script>--}}
{{--                            @endif--}}
                            @php
                                if($pdf = session()->get('exav')){
                                    if(\File::exists(public_path($pdf)))
                                    \File::delete(public_path($pdf));

                                    session()->forget('exav');
                                }

                            @endphp

                            @php
                                $orders = \App\Models\Order::where('customer_id',auth()->id())->latest()->paginate(request()->paginate_id??25);
                            @endphp
                            @foreach($orders as $order)
                                @foreach ($order->packages[0]->products as $product)
                                    <div class="puchas_sp purch_block d_flex sto_">
                                        @if(!empty($product->seller_product_sku))
                                        <a style="color: #00AAAD" href="{{singleProductURL($product->seller_product_sku->product->seller->slug, $product->seller_product_sku->product->slug, $product->seller_product_sku->product->product->categories[0]->slug)}}" class="for_descrip descrip_text">
                                            @if($product->seller_product_sku->product->product_name != null)
                                                {{$product->seller_product_sku->product->product_name}}
                                            @else {{ $product->seller_product_sku->product->product->product_name}} @endif
                                        </a>
                                        @else
                                            <a style="color: rgba(113, 113, 113, 0.7)" disabled class="for_descrip descrip_text">
                                                Product removed
                                            </a>
                                        @endif
                                            <span class="item_img for_item">
                                                {{$order->grand_total}}$
        {{--                                        <img src="img/proff.jpg" alt="">--}}
                                            </span>
                                        <span class="for_model">{{ $order->order_number }}</span>
                                        <span class="for_date">{{$order->created_at->toDateString()}}</span>



                                        <span class="for_model">{{ $order->status() }}</span>
                                        <span class="for_amount"> {{$order->discount_total }}$</span>
                                        <a href="{{ route('order_manage.print_order_details', $order->id) }}"
                                           target="_blank"
                                           class="for_amount" style="color: #00AAAD">Generate </a>
                                    </div>
                                @endforeach
                            @endforeach

                        </div>
                    </div>


                @if($orders->appends(['a' => 'purchases'])->lastPage() > 1)
                        <div class="d_flex pagination">
                            <div class='choose_pagination_quantity d_flex'> <span> Per page: </span>
                                <a href="{{route('frontend.customer_profile',['paginate_id'=>12,'a' => 'purchases'])}}">12</a>
                                <a href="{{route('frontend.customer_profile',['paginate_id'=>24,'a' => 'purchases'])}}">24</a>
                                <a href="{{route('frontend.customer_profile',['paginate_id'=>36,'a' => 'purchases'])}}">36</a>
                                <a href="{{route('frontend.customer_profile',['paginate_id'=>72,'a' => 'purchases'])}}">72</a>
                                <a href="{{route('frontend.customer_profile',['paginate_id'=>144,'a' => 'purchases'])}}">144</a>
                            </div>
                            <div class="d_flex pagination_block">
                                <a href="{{ $orders->appends(['a' => 'purchases'])->previousPageUrl() }}" class="prev_next_page">
                                    <svg width="9" height="15" viewBox="0 0 9 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8 1.25L2 7.25L8 13.25" stroke="#282828" stroke-width="2.25"/>
                                    </svg>
                                </a>
                                @if($orders->appends(['a' => 'purchases'])->lastPage() > 1)
                                    <a href="{{ $orders->appends(['a' => 'purchases'])->url(1) }}" class="pagination_sp @if($orders->appends(['a' => 'purchases'])->currentPage() == 1) pagination_sp_active @endif">1</a>
                                @endif
                                @if($orders->lastPage() > 2)
                                    <a href="{{ $orders->appends(['a' => 'purchases'])->url(2) }}" class="pagination_sp @if($orders->appends(['a' => 'purchases'])->currentPage() == 2) pagination_sp_active @endif">2</a>
                                @endif
                                @if($orders->appends(['a' => 'purchases'])->lastPage() > 3)
                                    <a href="{{ $orders->appends(['a' => 'purchases'])->url(3) }}" class="pagination_sp @if($orders->appends(['a' => 'purchases'])->currentPage() == 3) pagination_sp_active @endif">3</a>
                                @endif
                                @if( $orders->appends(['a' => 'purchases'])->lastPage() > 4)
                                    <a>...</a>
                                @endif
                                <a href="{{ $orders->appends(['a' => 'purchases'])->url($orders->appends(['a' => 'purchases'])->lastPage()) }}" class="pagination_sp  @if($orders->appends(['a' => 'purchases'])->currentPage() == $orders->appends(['a' => 'purchases'])->lastPage()) pagination_sp_active @endif">{{ $orders->appends(['a' => 'purchases'])->lastPage() }}</a>
                                <a href="{{ $orders->appends(['a' => 'purchases'])->nextPageUrl()  }}" class="prev_next_page">
                                    <svg width="9" height="15" viewBox="0 0 9 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1.25 1.25L7.25 7.25L1.25 13.25" stroke="#282828" stroke-width="2.25"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="for_my_comments">
                    <h3>My Comments</h3>

                    @php
                    $products = \App\Models\Comment::where('user_id',auth()->id())->latest()->paginate(request()->comment_id??4);
                    @endphp
                    <div class="d_flex sto_ my_comm_block">
                        @foreach($products as $product)
                        <div class="comment_block sto_ d_flex">
                            <div class="d_flex img_and_com">
                                <div class="item_img">
                                    <img src="{{auth()->user()->avatar?showImage(auth()->user()->avatar):showImage('frontend/default/img/avatar.jpg')}}" alt="">
                                </div>
                                <div class="d_flex rows_ max_comm_view">
                                    <span class="com_text">{{$product->text}}</span>
                                    @php
                                    $a = \Modules\Seller\Entities\SellerProduct::where('id',$product->product_id)->first()
                                    @endphp
                                    <span class="comented_"> Commented on <a href="{{singleProductURL($a->seller->slug, $a->slug, $a->product->categories[0]->slug)}}" class="prod_name_" >{{$a->product->product_name}}</a> </span>
                                </div>
                            </div>
                            <div class="d_flex loc_delete">
                                <div class="clock_ d_flex">
                                    <svg width="13" height="14" viewBox="0 0 13 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M6.65885 0.858948C3.26953 0.858948 0.523438 3.60504 0.523438 6.99436C0.523438 10.3837 3.26953 13.1298 6.65885 13.1298C10.0482 13.1298 12.7943 10.3837 12.7943 6.99436C12.7943 3.60504 10.0482 0.858948 6.65885 0.858948ZM6.65885 11.9423C3.91276 11.9423 1.71094 9.74046 1.71094 6.99436C1.71094 4.27301 3.91276 2.04645 6.65885 2.04645C9.38021 2.04645 11.6068 4.27301 11.6068 6.99436C11.6068 9.74046 9.38021 11.9423 6.65885 11.9423ZM8.16797 9.36936C8.31641 9.46832 8.48958 9.44358 8.58854 9.29515L9.05859 8.67666C9.15755 8.52822 9.13281 8.35504 8.98438 8.25608L7.35156 7.04384V3.53082C7.35156 3.38239 7.20312 3.23395 7.05469 3.23395H6.26302C6.08984 3.23395 5.96615 3.38239 5.96615 3.53082V7.61285C5.96615 7.68707 5.99089 7.78603 6.0651 7.83551L8.16797 9.36936Z" fill="#717171"/>
                                    </svg>
                                    <span>{{$product->created_at->toDateString()}}</span>
                                </div>
                                <span class="remove_comment" onclick="comment_delete('{{$product->id}}')">
                                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0.728516 0.577698L16.9922 16.8407M16.9922 0.577698L0.728516 16.8414" stroke="#717171"/>
                                        </svg>
                                    </span>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    @if($products->appends(['a' => 'comments'])->lastPage() > 1)
                        <div class="d_flex pagination">
                            <div class='choose_pagination_quantity d_flex'> <span> Per page: </span>
                                <a href="{{route('frontend.customer_profile',['comment_id'=>12,'a' => 'comments'])}}">12</a>
                                <a href="{{route('frontend.customer_profile',['comment_id'=>24,'a' => 'comments'])}}">24</a>
                                <a href="{{route('frontend.customer_profile',['comment_id'=>36,'a' => 'comments'])}}">36</a>
                                <a href="{{route('frontend.customer_profile',['comment_id'=>72,'a' => 'comments'])}}">72</a>
                                <a href="{{route('frontend.customer_profile',['comment_id'=>144,'a' => 'comments'])}}">144</a>
                            </div>
                            <div class="d_flex pagination_block">
                                <a href="{{ $products->appends(['a' => 'comments'])->previousPageUrl() }}" class="prev_next_page">
                                    <svg width="9" height="15" viewBox="0 0 9 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8 1.25L2 7.25L8 13.25" stroke="#282828" stroke-width="2.25"/>
                                    </svg>
                                </a>
                                @if($products->appends(['a' => 'comments'])->lastPage() > 1)
                                    <a href="{{ $products->appends(['a' => 'comments'])->url(1) }}" class="pagination_sp @if($products->appends(['a' => 'comments'])->currentPage() == 1) pagination_sp_active @endif">1</a>
                                @endif
                                @if($products->lastPage() > 2)
                                    <a href="{{ $products->appends(['a' => 'comments'])->url(2) }}" class="pagination_sp @if($products->appends(['a' => 'comments'])->currentPage() == 2) pagination_sp_active @endif">2</a>
                                @endif
                                @if($products->appends(['a' => 'comments'])->lastPage() > 3)
                                    <a href="{{ $products->appends(['a' => 'comments'])->url(3) }}" class="pagination_sp @if($products->appends(['a' => 'comments'])->currentPage() == 3) pagination_sp_active @endif">3</a>
                                @endif
                                @if( $products->appends(['a' => 'comments'])->lastPage() > 4)
                                    <a>...</a>
                                @endif
                                <a href="{{ $products->appends(['a' => 'comments'])->url($products->appends(['a' => 'comments'])->lastPage()) }}" class="pagination_sp  @if($products->appends(['a' => 'comments'])->currentPage() == $products->appends(['a' => 'comments'])->lastPage()) pagination_sp_active @endif">{{ $products->lastPage() }}</a>
                                <a href="{{ $products->appends(['a' => 'comments'])->nextPageUrl()  }}" class="prev_next_page">
                                    <svg width="9" height="15" viewBox="0 0 9 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1.25 1.25L7.25 7.25L1.25 13.25" stroke="#282828" stroke-width="2.25"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endif

                </div>
                <div class="for_my_favorites">
                    @php
                        if (auth()->user()->role->type != 'customer')
                        {
                            $products = App\Models\Wishlist::with('user', 'seller', 'product', 'product.product')->whereHas('product', function($query){
                                $query->where('status', 1)->whereHas('product', function($query){
                                    $query->where('status', 1);
                                });
                            })->where('user_id',auth()->user()->id)->paginate(12);
                        }else {
                            $products = App\Models\Wishlist::with('user', 'seller', 'product', 'product.product')->whereHas('product', function($query){
                                $query->where('status', 1)->whereHas('product', function($query){
                                    $query->where('status', 1);
                                });
                            })->where('user_id',auth()->user()->id)->get();
                        }
                    @endphp
                    <h4>My Favorites</h4>
                    <div class="favorite_prods d_flex">
                        @foreach($products as $product)
                        <div class="model_product">
                            <a href="{{singleProductURL(@$product->product->seller->slug, @$product->product->slug, @$product->product->product->categories[0]->slug)}}" target="_blank" >
                                <div class="product_img_iner">
                                    <img @if (@$product->product->thum_img != null) src="{{showImage(@$product->product->thum_img)}}" @else src="{{showImage(@$product->product->product->thumbnail_image_source)}}" @endif alt="{{@$product->product->product->product_name}}" class="img-fluid" />
                                </div>
                            </a>
                            @if($product->product->discount != 0 )
                                <span class="sale_red"> -@if($product->product->discount_type != 0)
                                        $@endif {{$product->product->discount}} @if($product->product->discount_type == 0)
                                        %@endif</span>
                            @endif
                            <div class="add_to_fav add_to_wishlist"
                                 @if(!empty(\App\Models\Wishlist::where('user_id',auth()->id())->where('seller_product_id',$product->product->id)->first()->id))
                                 data-wish="{{\App\Models\Wishlist::where('user_id',auth()->id())->where('seller_product_id',$product->product->id)->first()->id}}"
                                 @endif
                                 data-product_id="{{$product->product->id}}" data-seller_id="{{$product->product->user_id}}">
                                @guest
                                    <svg width="27" height="23" viewBox="0 0 27 23" fill="none" xmlns="http://www.w3.org/2000/svg" class="add_to_wishlistt">
                                        <path d="M23.4972 1.67509C20.524 -0.836734 15.9617 -0.477902 13.1423 2.44402C10.2716 -0.477902 5.70932 -0.836734 2.73614 1.67509C-1.1085 4.90459 -0.544619 10.1846 2.22352 13.004L11.1943 22.1798C11.7069 22.6924 12.3734 23 13.1423 23C13.8599 23 14.5263 22.6924 15.039 22.1798L24.061 13.004C26.7779 10.1846 27.3418 4.90459 23.4972 1.67509ZM22.2669 11.261L13.2961 20.4369C13.1935 20.5394 13.091 20.5394 12.9372 20.4369L3.96642 11.261C2.06973 9.36436 1.7109 5.77604 4.32525 3.57178C6.32446 1.88014 9.40017 2.13645 11.3481 4.0844L13.1423 5.92982L14.9364 4.0844C16.8331 2.13645 19.9088 1.88014 21.908 3.52052C24.5224 5.77604 24.1636 9.36436 22.2669 11.261Z" fill="#00AAAD"/>
                                    </svg>
                                @endguest
                                @auth
                                    @if(!empty(\App\Models\Wishlist::where('user_id',auth()->id())->where('seller_product_id',$product->product->id)->first()->id))
                                        <svg width="28" height="24" viewBox="0 0 28 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M24.2611 2C21.2879 -0.511827 16.7256 -0.152994 13.9062 2.76893C11.0355 -0.152994 6.47324 -0.511827 3.50005 2C-0.344584 5.22949 0.219297 10.5095 2.98743 13.3289L11.9583 22.5047C12.4709 23.0173 13.1373 23.3249 13.9062 23.3249C14.6239 23.3249 15.2903 23.0173 15.8029 22.5047L24.825 13.3289C27.5418 10.5095 28.1057 5.22949 24.2611 2Z" fill="#00AAAD"/>
                                        </svg>
                                    @else
                                        <svg width="27" height="23" viewBox="0 0 27 23" fill="none" xmlns="http://www.w3.org/2000/svg" class="add_to_wishlistt">
                                            <path d="M23.4972 1.67509C20.524 -0.836734 15.9617 -0.477902 13.1423 2.44402C10.2716 -0.477902 5.70932 -0.836734 2.73614 1.67509C-1.1085 4.90459 -0.544619 10.1846 2.22352 13.004L11.1943 22.1798C11.7069 22.6924 12.3734 23 13.1423 23C13.8599 23 14.5263 22.6924 15.039 22.1798L24.061 13.004C26.7779 10.1846 27.3418 4.90459 23.4972 1.67509ZM22.2669 11.261L13.2961 20.4369C13.1935 20.5394 13.091 20.5394 12.9372 20.4369L3.96642 11.261C2.06973 9.36436 1.7109 5.77604 4.32525 3.57178C6.32446 1.88014 9.40017 2.13645 11.3481 4.0844L13.1423 5.92982L14.9364 4.0844C16.8331 2.13645 19.9088 1.88014 21.908 3.52052C24.5224 5.77604 24.1636 9.36436 22.2669 11.261Z" fill="#00AAAD"/>
                                        </svg>



                                    @endif
                                @endauth

                            </div>
                            <div class="about_model sto_ d_flex">
                                <div class="d_flex sto_ col_titles_mob">
                                    <a href="{{singleProductURL(@$product->product->seller->slug, @$product->product->slug,  @$product->product->product->categories[0]->slug)}}" target="_blank">
                                        @if (@$product->product->product_name)
                                            {{@$product->product->product_name}}
                                        @else
                                            {{$product->product->product->product_name}}
                                        @endif</a>

                                    <span class="data_of_model">{{$product->created_at->toDateString()}}</span>
                                </div>
                                <div class="d_flex sto_ for_sale_height">
                                    @php
                                        $reviews = @$product->product->reviews->where('status',1)->pluck('rating');
                                          if(count($reviews)>0){
                                              $value = 0;
                                              $rating = 0;
                                              foreach($reviews as $review){
                                                  $value += $review;
                                              }
                                              $rating = $value/count($reviews);
                                              $total_review = count($reviews);
                                          }else{
                                              $rating = 0;
                                              $total_review = 0;
                                          }
                                    @endphp
                                    <div class="d_flex eye_cool">
                                        <div class="watched_">
                                            <svg width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M9 2.5C8.65625 2.53125 8.3125 2.5625 8 2.65625C8.15625 2.90625 8.21875 3.21875 8.25 3.5C8.25 4.46875 7.4375 5.25 6.5 5.25C6.1875 5.25 5.875 5.1875 5.65625 5.03125C5.5625 5.34375 5.5 5.65625 5.5 6C5.5 7.9375 7.0625 9.5 9 9.5C10.9375 9.5 12.5 7.9375 12.5 6C12.5 4.09375 10.9375 2.53125 9 2.53125V2.5ZM17.875 5.5625C16.1875 2.25 12.8125 0 9 0C5.15625 0 1.78125 2.25 0.09375 5.5625C0.03125 5.6875 0 5.84375 0 6C0 6.1875 0.03125 6.34375 0.09375 6.46875C1.78125 9.78125 5.15625 12 9 12C12.8125 12 16.1875 9.78125 17.875 6.46875C17.9375 6.34375 17.9688 6.1875 17.9688 6.03125C17.9688 5.84375 17.9375 5.6875 17.875 5.5625ZM9 10.5C5.90625 10.5 3.0625 8.78125 1.5625 6C3.0625 3.21875 5.90625 1.5 9 1.5C12.0625 1.5 14.9062 3.21875 16.4062 6C14.9062 8.78125 12.0625 10.5 9 10.5Z" fill="#717171"></path>
                                            </svg>
                                            <span>{{$product->product->viewed}}</span>
                                        </div>
{{--                                        <a href="{{route('download',['filename' => $product->product->product->pdf])}}" class='download_' @if($product->product->skus->first()->selling_price != 0.0 || is_null($product->product->product->pdf)) style='display:none'  @endif>--}}
{{--                                            <svg width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">--}}
{{--                                                <path d="M16.5 9H13.5938L15.0625 7.5625C16 6.625 15.3125 5 14 5H12V1.5C12 0.6875 11.3125 0 10.5 0H7.5C6.65625 0 6 0.6875 6 1.5V5H4C2.65625 5 1.96875 6.625 2.9375 7.5625L4.375 9H1.5C0.65625 9 0 9.6875 0 10.5V14.5C0 15.3438 0.65625 16 1.5 16H16.5C17.3125 16 18 15.3438 18 14.5V10.5C18 9.6875 17.3125 9 16.5 9ZM4 6.5H7.5V1.5H10.5V6.5H14L9 11.5L4 6.5ZM16.5 14.5H1.5V10.5H5.875L7.9375 12.5625C8.5 13.1562 9.46875 13.1562 10.0312 12.5625L12.0938 10.5H16.5V14.5ZM13.75 12.5C13.75 12.9375 14.0625 13.25 14.5 13.25C14.9062 13.25 15.25 12.9375 15.25 12.5C15.25 12.0938 14.9062 11.75 14.5 11.75C14.0625 11.75 13.75 12.0938 13.75 12.5Z" fill="#323232"/>--}}
{{--                                            </svg>--}}
{{--                                            <span> {{$product->product->product->downloads}}</span>--}}
{{--                                        </a>--}}
{{--                                        <a class="like_post" onclick="likes('{{$product->product->id}}')">--}}
{{--                                            <svg width="15" height="16" viewBox="0 0 15 16" fill="none" xmlns="http://www.w3.org/2000/svg">--}}
{{--                                                <path d="M14.5625 8.96875C14.8438 8.5 15 8 15 7.40625C15 6.03125 13.8125 4.75 12.3125 4.75H11.1562C11.3125 4.34375 11.4375 3.875 11.4375 3.28125C11.4375 1 10.25 0 8.46875 0C6.53125 0 6.65625 2.96875 6.21875 3.40625C5.5 4.125 4.65625 5.5 4.0625 6H1C0.4375 6 0 6.46875 0 7V14.5C0 15.0625 0.4375 15.5 1 15.5H3C3.4375 15.5 3.84375 15.1875 3.9375 14.7812C5.34375 14.8125 6.3125 16 9.5 16C9.75 16 10 16 10.2188 16C12.625 16 13.6875 14.7812 13.7188 13.0312C14.1562 12.4688 14.375 11.6875 14.2812 10.9375C14.5938 10.375 14.6875 9.6875 14.5625 8.96875ZM12.625 10.6562C13.0312 11.3125 12.6562 12.1875 12.1875 12.4688C12.4375 13.9688 11.625 14.5 10.5312 14.5H9.34375C7.125 14.5 5.65625 13.3438 4 13.3438V7.5H4.3125C5.21875 7.5 6.4375 5.3125 7.28125 4.46875C8.15625 3.59375 7.875 2.09375 8.46875 1.5C9.9375 1.5 9.9375 2.53125 9.9375 3.28125C9.9375 4.5 9.0625 5.0625 9.0625 6.25H12.3125C12.9688 6.25 13.4688 6.84375 13.5 7.4375C13.5 8 13.0938 8.59375 12.7812 8.59375C13.2188 9.0625 13.3125 10.0312 12.625 10.6562ZM2.75 13.5C2.75 13.9375 2.40625 14.25 2 14.25C1.5625 14.25 1.25 13.9375 1.25 13.5C1.25 13.0938 1.5625 12.75 2 12.75C2.40625 12.75 2.75 13.0938 2.75 13.5Z" fill="#717171"/>--}}
{{--                                            </svg>--}}
{{--                                            <span class="likes{{$product->product->id}}">{{count(\App\Models\Like::where('product_id',$product->product->id)->get())}}</span>--}}
{{--                                        </a>--}}
                                    </div>
                                    <div class='d_flex sale_price_col'>
                                        @if(($product->product->hasDeal || $product->product->hasDiscount == 'yes') && single_price(@$product->product->skus->first()->selling_price) != '$ 0.00')
                                            <span class="prev_price">{{$product->product->skus->max('selling_price')}}$</span>
                                        @endif
                                        <span class="price_of_prod">
                                        @if($product->product->hasDeal)

                                            {{single_price(selling_price(@$product->product->skus->first()->selling_price,$product->product->hasDeal->discount_type,$product->product->hasDeal->discount))}}
                                        @else

                                            @if($product->product->hasDiscount == 'yes')
                                                {{single_price(selling_price(@$product->product->skus->first()->selling_price,$product->product->discount_type,$product->product->discount))}}
                                                @if($product->product->hasDeal || $product->product->hasDiscount == 'yes')
                                                    <span class="prev_price">{{$product->product->skus->max('selling_price')}}$</span>
                                                @endif
                                            @else
                                                {{(single_price(@$product->product->skus->first()->selling_price) == '$ 0.00')?'Free':single_price(@$product->product->skus->first()->selling_price)}}
                                                {{--                                            {{single_price(@$product->skus->first()->selling_price)}}--}}
                                            @endif

                                        @endif
                                    </span>
                                    </div>
                                </div>

                                @if( \App\Services\CartService::isProductPurchased(@$product->product()->first()))
                                    @include('product::products.download_product_partial', ['product' => $product->product()->first()])
                                @else
                                @php
                                    $disabledAddToCartClass = "";
                                    if( \App\Services\CartService::isProductInCart(@$product->product->skus->first()->id)) {
                                      $disabledAddToCartClass = "disabled";
                                    }
                                @endphp
                                <a @if(single_price($product->product->skus->max('selling_price')) == '$ 0.00')
                                       href="{{$product->product->product->video_link}}"
                                   class="add_catalog_btn"
                                   @else
                                       @auth class="{{ $disabledAddToCartClass }} addToCartFromThumnail add_catalog_btn"
                                   @elseif(single_price($product->product->skus->max('selling_price')) == '$ 0.00') class="{{ $disabledAddToCartClass }} addToCartFromThumnail add_catalog_btn"
                                   @else class="add_catalog_btn" @endauth
                                   @endif data-producttype="{{ @$product->product->product->product_type }}" data-seller={{ @$product->product->user_id }} data-product-sku={{ @$product->product->skus->first()->id }}
                @if(@$product->product->hasDeal)
                    data-base-price={{ selling_price(@$product->product->skus->first()->selling_price,@$product->product->hasDeal->discount_type,$product->product->hasDeal->discount) }}
                @else
                  @if(@$product->product->hasDiscount == 'yes')
                    data-base-price={{ selling_price(@$product->product->skus->first()->selling_price,@$product->product->discount_type,@$product->product->discount) }}
                  @else
                    data-base-price={{ @$product->product->skus->first()->selling_price }}
                  @endif
                @endif
                data-shipping-method={{ @$product->product->product->shippingMethods->first()->shipping_method_id }}
                data-product-id={{ @$product->product->id }}
                data-stock_manage="{{$product->product->stock_manage}}"
                                   data-stock="{{@$product->product->skus->first()->product_stock}}"
                                   data-min_qty="{{$product->product->product->minimum_order_qty}}"
                                >
                                    @if(single_price($product->product->skus->max('selling_price')) == '$ 0.00')
                                        <svg width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <g clip-path="url(#clip0_794_15016)">
                                                <path d="M16.5 9H13.5938L15.0625 7.5625C16 6.625 15.3125 5 14 5H12V1.5C12 0.6875 11.3125 0 10.5 0H7.5C6.65625 0 6 0.6875 6 1.5V5H4C2.65625 5 1.96875 6.625 2.9375 7.5625L4.375 9H1.5C0.65625 9 0 9.6875 0 10.5V14.5C0 15.3438 0.65625 16 1.5 16H16.5C17.3125 16 18 15.3438 18 14.5V10.5C18 9.6875 17.3125 9 16.5 9ZM4 6.5H7.5V1.5H10.5V6.5H14L9 11.5L4 6.5ZM16.5 14.5H1.5V10.5H5.875L7.9375 12.5625C8.5 13.1562 9.46875 13.1562 10.0312 12.5625L12.0938 10.5H16.5V14.5ZM13.75 12.5C13.75 12.9375 14.0625 13.25 14.5 13.25C14.9062 13.25 15.25 12.9375 15.25 12.5C15.25 12.0938 14.9062 11.75 14.5 11.75C14.0625 11.75 13.75 12.0938 13.75 12.5Z" fill="white"/>
                                            </g>
                                            <defs>
                                                <clipPath id="clip0_794_15016">
                                                    <rect width="18" height="16" fill="white"/>
                                                </clipPath>
                                            </defs>
                                        </svg>

                                    @else
                                        +
                                        <svg width="21" height="18" viewBox="0 0 21 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M19.645 2.25H5.33643L5.02002 0.703125C4.94971 0.316406 4.59815 0 4.21143 0H0.695801C0.449707 0 0.273926 0.210938 0.273926 0.421875V1.26562C0.273926 1.51172 0.449707 1.6875 0.695801 1.6875H3.5083L5.93408 14.2031C5.54736 14.625 5.33643 15.1523 5.33643 15.75C5.33643 17.0156 6.3208 18 7.58643 18C8.8169 18 9.83643 17.0156 9.83643 15.75C9.83643 15.3633 9.6958 14.9766 9.52002 14.625H14.6177C14.4419 14.9766 14.3364 15.3633 14.3364 15.75C14.3364 17.0156 15.3208 18 16.5864 18C17.8169 18 18.8364 17.0156 18.8364 15.75C18.8364 15.1172 18.5552 14.5547 18.1333 14.1328L18.1685 13.9922C18.2739 13.4648 17.8872 12.9375 17.3247 12.9375H7.41065L7.09424 11.25H18.063C18.4849 11.25 18.8013 11.0039 18.9067 10.6172L20.4888 3.30469C20.5942 2.77734 20.2075 2.25 19.645 2.25ZM7.58643 16.5938C7.09424 16.5938 6.74268 16.2422 6.74268 15.75C6.74268 15.293 7.09424 14.9062 7.58643 14.9062C8.04346 14.9062 8.43018 15.293 8.43018 15.75C8.43018 16.2422 8.04346 16.5938 7.58643 16.5938ZM16.5864 16.5938C16.0942 16.5938 15.7427 16.2422 15.7427 15.75C15.7427 15.293 16.0942 14.9062 16.5864 14.9062C17.0435 14.9062 17.4302 15.293 17.4302 15.75C17.4302 16.2422 17.0435 16.5938 16.5864 16.5938ZM17.395 9.5625H6.74268L5.65283 3.9375H18.6255L17.395 9.5625Z"
                                                  fill="white"/>
                                        </svg>
                                    @endif
                                </a>
                            @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="for_settings">
                    <h5>Settings</h5>
                    <div class="setting_section sto_">
                        <div class="change_notifs d_flex">
                            <span>Email notification preferences</span>
                            <div class="save_adress_btn_switch">
                                <label class="save_adress_btn_switch_lab" for="myToggle">
                                    <input class="toggle_input" name="" @if(auth()->user()->notification_send)checked @endif type="checkbox" id="myToggle">
                                    <div class="toggle_fill"></div>
                                </label>
                            </div>
                        </div>
                        <form class="change_notifs">
                            <div class="d_flex sto_">
                                <span>Change your account password</span>
                                <p class="change_pass">Edit</p>
                            </div>
                            <div class="new_pass_confirm">
                                <div class="d_flex sto_ inp_cols">
                                    <div class="d_flex inps_labs">
                                        <label for="newpass">New password</label>
                                        <input type="password" id="newpass">
                                    </div>
                                    <div class="d_flex inps_labs">
                                        <label for="confirmpass">Confirm new password</label>
                                        <input type="password" id="confirmpass">
                                    </div>
                                </div>
                                <div class="d_flex sto_ inp_cols">
                                    <button class="save_edits_btn d_flex change_password">Save</button>
                                    <a class="d_flex close_edit">Close</a>
                                </div>
                            </div>
                        </form>
                        <form class="change_notifs">
                            <div class="d_flex sto_">
                                <span>Set or change your account's email address</span>
                                <p class="change_pass">Edit</p>
                            </div>
                            <div class="new_pass_confirm">
                                <div class="d_flex sto_ inp_cols">
                                    <div class="d_flex inps_labs">
                                        <label for="email_">Email</label>
                                        <input type="email" id="email_">
                                    </div>
                                    <div class="d_flex inps_labs">
                                        <label for="mailpass">Password</label>
                                        <input type="password" id="mailpass">
                                    </div>
                                </div>
                                <div class="d_flex sto_ inp_cols">
                                    <button class="d_flex save_mails  change_email">Save</button>
                                    <a class="d_flex close_edit">Close</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
@section('js')
    <script>
        $("#namee").keypress(function (e) {
            console.log('textareaclass')
            if(e.which === 13 && !e.shiftKey) {
                poxel_click()
            }
        });
        $("#lastnamee").keypress(function (e) {
            console.log('textareaclass')
            if(e.which === 13 && !e.shiftKey) {
                poxel_click()
            }
        });
        $("#streete").keypress(function (e) {
            console.log('textareaclass')
            if(e.which === 13 && !e.shiftKey) {
                poxel_click()
            }
        });
        $("#citye").keypress(function (e) {
            console.log('textareaclass')
            if(e.which === 13 && !e.shiftKey) {
                poxel_click()
            }
        });
        $("#statee").keypress(function (e) {
            console.log('textareaclass')
            if(e.which === 13 && !e.shiftKey) {
                poxel_click()
            }
        });
        $("#zip_codee").keypress(function (e) {
            console.log('textareaclass')
            if(e.which === 13 && !e.shiftKey) {
                poxel_click()
            }
        });
        $("#countrye").keypress(function (e) {
            console.log('textareaclass')
            if(e.which === 13 && !e.shiftKey) {
                poxel_click()
            }
        });
        $("#emaile").keypress(function (e) {
            console.log('textareaclass')
            if(e.which === 13 && !e.shiftKey) {
                poxel_click()
            }
        });
        $("#phonee").keypress(function (e) {
            console.log('textareaclass')
            if(e.which === 13 && !e.shiftKey) {
                poxel_click()
            }
        });
        $("#newpass").keypress(function (e) {
            console.log('textareaclass')
            if(e.which === 13 && !e.shiftKey) {
                $('.change_password').click()
            }
        });
        $("#confirmpass").keypress(function (e) {
            console.log('textareaclass')
            if(e.which === 13 && !e.shiftKey) {
                $('.change_password').click()
            }
        });
        $("#email_").keypress(function (e) {
            console.log('textareaclass')
            if(e.which === 13 && !e.shiftKey) {
                $('.change_email').click()
            }
        });
        $("#mailpass").keypress(function (e) {
            console.log('textareaclass')
            if(e.which === 13 && !e.shiftKey) {
                $('.change_email').click()
            }
        });

        function poxel_click(){

            let data = {
                address_id: $(this).attr('data-ag')??0,
                name: $('#namee').val(),
                email: $('#emaile').val(),
                lastname: $('#lastnamee').val(),
                street: $('#streete').val(),
                phone: $('#phonee').val(),
                country: $('#countrye').val(),
                state: $('#statee').val(),
                city: $('#citye').val(),
                postal_code: $('#zip_codee').val(),
                _token: $('#tokene').val()
            }

            console.log(data)

            $.post("{{route('frontend.checkout.billing.address.store')}}", data, function (response) {
                console.log('lava')
                // location.reload()
                // pay_button_ameria($('.total_padd').data('total'),$('.total_padd').data('ids'));
            }).fail(function (response) {
                console.log(response.responseJSON.errors)
                if(response.responseJSON.errors.name){
                    $('#namee').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
                }
                if(response.responseJSON.errors.lastname){
                    $('#lastnamee').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
                }
                if(response.responseJSON.errors.street){
                    $('#streete').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
                }
                if(response.responseJSON.errors.email){
                    $('#emaile').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
                }
                if(response.responseJSON.errors.phone){
                    $('#phonee').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
                }
                if(response.responseJSON.errors.country){
                    $('#countrye').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
                }
                if(response.responseJSON.errors.state){
                    $('#statee').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
                }
                if(response.responseJSON.errors.city){
                    $('#citye').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
                }
                if(response.responseJSON.errors.postal_code){
                    $('#zip_codee').css('border-color','rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
                }

                return false;
            });
        }



    </script>
@endsection
