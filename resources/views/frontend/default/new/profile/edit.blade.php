@php
    use Modules\Customer\Entities\CustomerAddress;

@endphp


<div class="for_edit_profile for_edit_profile_active">
    <h1>Edit My Profile</h1>
    @php
        $prev_address = CustomerAddress::where('customer_id', auth()->id())->where('is_billing_default', 1)->first();
    @endphp
    @if($prev_address)
        <form class="edit_prof_section" id="update_info">
            <div class="prof_img_name d_flex">
                <div class="edit_prof_img">
                    <img class="customer_img"
                         src="{{$user_info->avatar?showImage($user_info->avatar):showImage('frontend/default/img/avatar.jpg')}}"
                         alt="">
                </div>
                <div class="name_edit_name d_flex">
                    <span class="editmail">{{$user_info->email}}</span>
                    <div class="edit_prof_pic d_flex">
                        <input type="file" id="file" name="file">
                        <svg width="21" height="19" viewBox="0 0 21 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                  d="M3.38852 0.539732C2.11377 0.798862 1.16509 1.78561 0.96634 3.05914C0.933575 3.26903 0.925128 4.81359 0.933916 8.98699C0.946908 15.1413 0.930469 14.6697 1.15297 15.2644C1.40403 15.9354 2.14086 16.6723 2.81192 16.9233C3.39791 17.1426 3.11279 17.1288 7.32794 17.1415C10.3029 17.1505 11.2498 17.1423 11.3783 17.1069C11.8532 16.9757 12.0597 16.3566 11.7641 15.9504C11.706 15.8706 11.2381 15.2622 10.7242 14.5985C10.2105 13.9348 9.79011 13.3808 9.79019 13.3674C9.79027 13.3461 15.1631 6.49655 15.2402 6.41943C15.2565 6.40314 16.0611 7.33213 17.0281 8.48384L18.7864 10.5779L18.8053 11.3573C18.8239 12.1248 18.8258 12.1391 18.9259 12.2902C19.0824 12.5265 19.2676 12.6234 19.5629 12.6234C19.8583 12.6234 20.0434 12.5265 20.1999 12.29L20.3015 12.1365V7.58823C20.3015 3.45809 20.2957 3.01907 20.2383 2.8127C20.1268 2.41156 20.0175 2.15959 19.8321 1.87682C19.4012 1.2194 18.8446 0.818787 18.0104 0.565679C17.8186 0.507497 17.2207 0.501777 10.7181 0.495982C4.97762 0.490868 3.58806 0.499163 3.38852 0.539732ZM17.9402 2.15872C18.2849 2.3291 18.4996 2.54846 18.6665 2.90058L18.7864 3.15361L18.7972 5.19908C18.8031 6.32409 18.8031 7.46531 18.7972 7.73516L18.7864 8.22573L17.2635 6.40917C16.426 5.41003 15.6845 4.55877 15.6158 4.51745C15.4422 4.41305 15.0476 4.41317 14.8713 4.51768C14.7887 4.56658 13.7066 5.91234 11.7878 8.35251C10.1617 10.4202 8.82124 12.1111 8.80886 12.1099C8.79643 12.1087 8.32741 11.5117 7.76661 10.7832C7.11214 9.93313 6.69835 9.42851 6.6113 9.37438C6.42854 9.26074 6.04138 9.25653 5.85565 9.36619C5.71387 9.44991 4.03307 11.6012 3.89041 11.8816C3.56693 12.5173 4.25985 13.1855 4.90512 12.8601C5.04425 12.79 5.17459 12.643 5.64232 12.029C5.95482 11.6188 6.21903 11.2828 6.22944 11.2825C6.24391 11.282 9.18481 15.0684 9.53837 15.5427L9.6086 15.637L6.60274 15.6264L3.59685 15.6158L3.34382 15.4959C2.99166 15.3291 2.77234 15.1144 2.60196 14.7696L2.46048 14.4834V8.8185V3.15361L2.58037 2.90058C2.64628 2.76145 2.76984 2.57206 2.85488 2.47978C3.02219 2.29827 3.3659 2.09584 3.60132 2.0402C3.68829 2.01963 6.58888 2.00781 10.7011 2.01133L17.6539 2.01724L17.9402 2.15872ZM5.66891 3.28702C5.25429 3.39566 4.96072 3.56752 4.63724 3.89097C4.30913 4.21908 4.14098 4.50968 4.03151 4.93756C3.89485 5.472 3.96579 6.0531 4.22913 6.55568C4.39011 6.86296 4.84966 7.32251 5.15694 7.48349C5.65952 7.74683 6.24062 7.81777 6.77505 7.68111C7.20294 7.57164 7.49354 7.40349 7.82165 7.07538C8.14976 6.74727 8.31791 6.45667 8.42738 6.02879C8.56404 5.49435 8.4931 4.91325 8.22976 4.41067C8.06878 4.10339 7.60923 3.64384 7.30195 3.48286C6.80077 3.22028 6.1991 3.14812 5.66891 3.28702ZM6.64555 4.85332C6.87138 5.00275 6.96809 5.19158 6.96809 5.48318C6.96809 5.77856 6.87119 5.96371 6.63475 6.12019C6.42342 6.26004 6.0535 6.26549 5.83865 6.13193C5.5519 5.95367 5.41717 5.59973 5.50785 5.26298C5.55997 5.06938 5.79179 4.82139 5.97228 4.76616C6.17088 4.70537 6.48308 4.74578 6.64555 4.85332ZM16.4648 11.3571C16.2946 11.4092 16.0536 11.6595 16.0049 11.8349C15.981 11.9211 15.9647 12.418 15.9646 13.0664L15.9644 14.1534L14.7967 14.1649L13.6291 14.1764L13.4756 14.278C13.2392 14.4346 13.1424 14.6197 13.1424 14.9151C13.1424 15.2104 13.2392 15.3956 13.4756 15.5521L13.6291 15.6537L14.7947 15.6652L15.9603 15.6767L15.9718 16.8394C15.9831 17.9877 15.9844 18.004 16.0732 18.1468C16.1226 18.2263 16.2322 18.3371 16.3166 18.393C16.4446 18.4777 16.5122 18.4946 16.7219 18.4946C16.9316 18.4946 16.9993 18.4777 17.1272 18.393C17.2117 18.3371 17.3213 18.2263 17.3707 18.1468C17.4594 18.004 17.4607 17.9877 17.4721 16.8394L17.4835 15.6767L18.6463 15.6652C19.7945 15.6539 19.8109 15.6526 19.9537 15.5638C20.0332 15.5144 20.144 15.4048 20.1999 15.3204C20.2845 15.1924 20.3015 15.1248 20.3015 14.9151C20.3015 14.7054 20.2845 14.6377 20.1999 14.5098C20.144 14.4253 20.0332 14.3157 19.9537 14.2663C19.8109 14.1776 19.7945 14.1763 18.6463 14.1649L17.4835 14.1535L17.4721 12.9907C17.4607 11.8425 17.4594 11.8261 17.3707 11.6833C17.1921 11.396 16.7996 11.2547 16.4648 11.3571Z"
                                  fill="white"/>
                        </svg>
                        Upload Avatar Image
                    </div>
                </div>
            </div>
            <div class="d_flex sto_ inp_cols">
                <div class="d_flex inps_labs">
                    <label for="name">{{__('common.first_name')}}</label>
                    <input type="text" id="name" placeholder="{{__('common.first_name')}}"
                           value="{{$prev_address->name}}" name="first_name">
                </div>
                <div class="d_flex inps_labs">
                    <label for="lastname">{{__('common.last_name')}}</label>
                    <input type="text" id="lastname" placeholder="{{__('common.last_name')}}"
                           value="{{$prev_address->lastname}}" name="last_name">
                </div>
            </div>
            {{--                            <div class="d_flex sto_ inp_cols">--}}
            {{--                                <div class="d_flex inps_labs">--}}
            {{--                                    <label for="address">Street adress</label>--}}
            {{--                                    <input type="text" id="street" name="street" value="{{$prev_address->street}}">--}}
            {{--                                </div>--}}
            {{--                                <div class="d_flex inps_labs">--}}
            {{--                                    <label for="city">City</label>--}}
            {{--                                    <input type="text" id="city" name="city" value="{{$prev_address->city}}">--}}
            {{--                                </div>--}}
            {{--                            </div>--}}
            {{--                            <div class="d_flex sto_ inp_cols">--}}
            {{--                                <div class="d_flex inps_labs">--}}
            {{--                                    <label for="state">State</label>--}}
            {{--                                    <input type="text" id="state" name="state" value="{{$prev_address->state}}">--}}
            {{--                                </div>--}}
            {{--                                <div class="d_flex inps_labs">--}}
            {{--                                    <label for="zip">Zip code</label>--}}
            {{--                                    <input type="text" id="zip_code" name="zip_code" value="{{$prev_address->postal_code}}">--}}
            {{--                                </div>--}}
            {{--                            </div>--}}


            <div class="d_flex sto_ inp_cols">
                <div class="d_flex inps_labs">
                    <label for="country">Country</label>
                    <input type="text" id="country" name="country" value="{{$prev_address->country}}">
                </div>
                <div class="d_flex inps_labs">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="{{__('common.email_address')}}"
                           value="{{$prev_address->email}}">
                </div>
            </div>
            <div class="d_flex sto_ inp_cols">
                {{--                                <div class="d_flex inps_labs">--}}
                {{--                                    <label for="phone">Phone number</label>--}}
                {{--                                    <input type="text" id="phone" name="phone" placeholder="{{__('common.phone_number')}}" value="{{$prev_address->phone}}">--}}
                {{--                                </div>--}}
                <button class="save_edits_btn save_btn d_flex"
                        data-ag="{{auth()->user()->customerAddresses->where('is_billing_default',1)->first()->id}}"
                        type="submit">
                    Save
                </button>
            </div>
        </form>

    @else
        <form class="edit_prof_section" id="update_info">
            <div class="prof_img_name d_flex">
                <div class="edit_prof_img">
                    <img class="customer_img"
                         src="{{$user_info->avatar?showImage($user_info->avatar):showImage('frontend/default/img/avatar.jpg')}}"
                         alt="">
                </div>
                <div class="name_edit_name d_flex">
                    <span class="editmail">{{$user_info->email}}</span>
                    <div class="edit_prof_pic d_flex">
                        <input type="file" id="file" name="file">
                        <svg width="21" height="19" viewBox="0 0 21 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                  d="M3.38852 0.539732C2.11377 0.798862 1.16509 1.78561 0.96634 3.05914C0.933575 3.26903 0.925128 4.81359 0.933916 8.98699C0.946908 15.1413 0.930469 14.6697 1.15297 15.2644C1.40403 15.9354 2.14086 16.6723 2.81192 16.9233C3.39791 17.1426 3.11279 17.1288 7.32794 17.1415C10.3029 17.1505 11.2498 17.1423 11.3783 17.1069C11.8532 16.9757 12.0597 16.3566 11.7641 15.9504C11.706 15.8706 11.2381 15.2622 10.7242 14.5985C10.2105 13.9348 9.79011 13.3808 9.79019 13.3674C9.79027 13.3461 15.1631 6.49655 15.2402 6.41943C15.2565 6.40314 16.0611 7.33213 17.0281 8.48384L18.7864 10.5779L18.8053 11.3573C18.8239 12.1248 18.8258 12.1391 18.9259 12.2902C19.0824 12.5265 19.2676 12.6234 19.5629 12.6234C19.8583 12.6234 20.0434 12.5265 20.1999 12.29L20.3015 12.1365V7.58823C20.3015 3.45809 20.2957 3.01907 20.2383 2.8127C20.1268 2.41156 20.0175 2.15959 19.8321 1.87682C19.4012 1.2194 18.8446 0.818787 18.0104 0.565679C17.8186 0.507497 17.2207 0.501777 10.7181 0.495982C4.97762 0.490868 3.58806 0.499163 3.38852 0.539732ZM17.9402 2.15872C18.2849 2.3291 18.4996 2.54846 18.6665 2.90058L18.7864 3.15361L18.7972 5.19908C18.8031 6.32409 18.8031 7.46531 18.7972 7.73516L18.7864 8.22573L17.2635 6.40917C16.426 5.41003 15.6845 4.55877 15.6158 4.51745C15.4422 4.41305 15.0476 4.41317 14.8713 4.51768C14.7887 4.56658 13.7066 5.91234 11.7878 8.35251C10.1617 10.4202 8.82124 12.1111 8.80886 12.1099C8.79643 12.1087 8.32741 11.5117 7.76661 10.7832C7.11214 9.93313 6.69835 9.42851 6.6113 9.37438C6.42854 9.26074 6.04138 9.25653 5.85565 9.36619C5.71387 9.44991 4.03307 11.6012 3.89041 11.8816C3.56693 12.5173 4.25985 13.1855 4.90512 12.8601C5.04425 12.79 5.17459 12.643 5.64232 12.029C5.95482 11.6188 6.21903 11.2828 6.22944 11.2825C6.24391 11.282 9.18481 15.0684 9.53837 15.5427L9.6086 15.637L6.60274 15.6264L3.59685 15.6158L3.34382 15.4959C2.99166 15.3291 2.77234 15.1144 2.60196 14.7696L2.46048 14.4834V8.8185V3.15361L2.58037 2.90058C2.64628 2.76145 2.76984 2.57206 2.85488 2.47978C3.02219 2.29827 3.3659 2.09584 3.60132 2.0402C3.68829 2.01963 6.58888 2.00781 10.7011 2.01133L17.6539 2.01724L17.9402 2.15872ZM5.66891 3.28702C5.25429 3.39566 4.96072 3.56752 4.63724 3.89097C4.30913 4.21908 4.14098 4.50968 4.03151 4.93756C3.89485 5.472 3.96579 6.0531 4.22913 6.55568C4.39011 6.86296 4.84966 7.32251 5.15694 7.48349C5.65952 7.74683 6.24062 7.81777 6.77505 7.68111C7.20294 7.57164 7.49354 7.40349 7.82165 7.07538C8.14976 6.74727 8.31791 6.45667 8.42738 6.02879C8.56404 5.49435 8.4931 4.91325 8.22976 4.41067C8.06878 4.10339 7.60923 3.64384 7.30195 3.48286C6.80077 3.22028 6.1991 3.14812 5.66891 3.28702ZM6.64555 4.85332C6.87138 5.00275 6.96809 5.19158 6.96809 5.48318C6.96809 5.77856 6.87119 5.96371 6.63475 6.12019C6.42342 6.26004 6.0535 6.26549 5.83865 6.13193C5.5519 5.95367 5.41717 5.59973 5.50785 5.26298C5.55997 5.06938 5.79179 4.82139 5.97228 4.76616C6.17088 4.70537 6.48308 4.74578 6.64555 4.85332ZM16.4648 11.3571C16.2946 11.4092 16.0536 11.6595 16.0049 11.8349C15.981 11.9211 15.9647 12.418 15.9646 13.0664L15.9644 14.1534L14.7967 14.1649L13.6291 14.1764L13.4756 14.278C13.2392 14.4346 13.1424 14.6197 13.1424 14.9151C13.1424 15.2104 13.2392 15.3956 13.4756 15.5521L13.6291 15.6537L14.7947 15.6652L15.9603 15.6767L15.9718 16.8394C15.9831 17.9877 15.9844 18.004 16.0732 18.1468C16.1226 18.2263 16.2322 18.3371 16.3166 18.393C16.4446 18.4777 16.5122 18.4946 16.7219 18.4946C16.9316 18.4946 16.9993 18.4777 17.1272 18.393C17.2117 18.3371 17.3213 18.2263 17.3707 18.1468C17.4594 18.004 17.4607 17.9877 17.4721 16.8394L17.4835 15.6767L18.6463 15.6652C19.7945 15.6539 19.8109 15.6526 19.9537 15.5638C20.0332 15.5144 20.144 15.4048 20.1999 15.3204C20.2845 15.1924 20.3015 15.1248 20.3015 14.9151C20.3015 14.7054 20.2845 14.6377 20.1999 14.5098C20.144 14.4253 20.0332 14.3157 19.9537 14.2663C19.8109 14.1776 19.7945 14.1763 18.6463 14.1649L17.4835 14.1535L17.4721 12.9907C17.4607 11.8425 17.4594 11.8261 17.3707 11.6833C17.1921 11.396 16.7996 11.2547 16.4648 11.3571Z"
                                  fill="white"/>
                        </svg>
                        Ajouter une photo
                    </div>
                </div>
            </div>
            <div class="d_flex sto_ inp_cols">
                <div class="d_flex inps_labs">
                    <label for="name">{{__('common.first_name')}}</label>
                    <input type="text" id="name" placeholder="{{__('common.first_name')}}" value="" name="first_name">
                </div>
                <div class="d_flex inps_labs">
                    <label for="lastname">{{__('common.last_name')}}</label>
                    <input type="text" id="lastname" placeholder="{{__('common.last_name')}}" value=""
                           name="last_name">
                </div>
            </div>
            {{--                        <div class="d_flex sto_ inp_cols">--}}
            {{--                            <div class="d_flex inps_labs">--}}
            {{--                                <label for="address">Street adress</label>--}}
            {{--                                <input type="text" id="street" name="street" value="">--}}
            {{--                            </div>--}}
            {{--                            <div class="d_flex inps_labs">--}}
            {{--                                <label for="city">City</label>--}}
            {{--                                <input type="text" id="city" name="city" value="">--}}
            {{--                            </div>--}}
            {{--                        </div>--}}
            {{--                        <div class="d_flex sto_ inp_cols">--}}
            {{--                            <div class="d_flex inps_labs">--}}
            {{--                                <label for="state">State</label>--}}
            {{--                                <input type="text" id="state" name="state" value="">--}}
            {{--                            </div>--}}
            {{--                            <div class="d_flex inps_labs">--}}
            {{--                                <label for="zip">Zip code</label>--}}
            {{--                                <input type="text" id="zip_code" name="zip_code" value="">--}}
            {{--                            </div>--}}
            {{--                        </div>--}}
            <div class="d_flex sto_ inp_cols">
                <div class="d_flex inps_labs">
                    <label for="country">Country</label>
                    <input type="text" id="country" name="country" value="">
                </div>
                <div class="d_flex inps_labs">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="{{__('common.email_address')}}" value="">
                </div>
            </div>
            <div class="d_flex sto_ inp_cols">
                {{--                            <div class="d_flex inps_labs">--}}
                {{--                                <label for="phone">Phone number</label>--}}
                {{--                                <input type="text" id="phone"  name="phone" placeholder="{{__('common.phone_number')}}" value="">--}}
                {{--                            </div>--}}
                <button class="save_edits_btn save_btn d_flex" type="submit">Save</button>
            </div>
        </form>
    @endif

    @section('js')
        <script>
            const reader = new FileReader();
            const inputReader = document.querySelectorAll('.edit_prof_pic');
            const file_id_img = document.querySelectorAll('.customer_img');
            let result;

            function setImg(i) {
                file_id_img[i].src = result
            }

            reader.addEventListener("load", (e) => {
                result = e.target.result
            })
            for (let i = 0; i < inputReader.length; i++) {
                inputReader[i].addEventListener("change", (e) => {
                    reader.readAsDataURL(e.target.files[0]);
                    setTimeout(() => {
                        setImg(i);
                    }, 200);
                });
            }

            $('.save_btn').click(function () {

                let data = {
                    address_id: $(this).attr('data-ag') ?? 0,
                    name: $('#name').val(),
                    email: $('#email').val(),
                    lastname: $('#lastname').val(),
                    street: $('#street').val(),
                    phone: $('#phone').val(),
                    country: $('#country').val(),
                    state: $('#state').val(),
                    city: $('#city').val(),
                    postal_code: $('#zip_code').val(),
                }

                $.post("{{route('frontend.checkout.billing.address.store')}}", data, function (response) {

                }).fail(function (response) {
                    if (response?.responseJSON?.errors?.name) {
                        $('#name').css('border-color', 'rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
                    }
                    if (response?.responseJSON?.errors?.lastname) {
                        $('#lastname').css('border-color', 'rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
                    }
                    if (response?.responseJSON?.errors?.street) {
                        $('#street').css('border-color', 'rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
                    }
                    if (response?.responseJSON?.errors?.email) {
                        $('#email').css('border-color', 'rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
                    }
                    if (response?.responseJSON?.errors?.phone) {
                        $('#phone').css('border-color', 'rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
                    }
                    if (response?.responseJSON?.errors?.country) {
                        $('#country').css('border-color', 'rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
                    }
                    if (response?.responseJSON?.errors?.state) {
                        $('#state').css('border-color', 'rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
                    }
                    if (response?.responseJSON?.errors?.city) {
                        $('#city').css('border-color', 'rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
                    }
                    if (response?.responseJSON?.errors?.postal_code) {
                        $('#zip_code').css('border-color', 'rgb(206, 60, 92)').css('background', 'rgba(206, 60, 92, 0.1)')
                    }

                    return false;
                });
            })

        </script>
    @endsection

</div>