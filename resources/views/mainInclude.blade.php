@php
    use App\Models\Block_user;
    use App\Models\User;
    use App\Models\Message;
@endphp

@if(!empty($to_user))

    @php
        // userId can be the id of current or a specified user
        $userId = session()->has('userId') ? session('userId') : auth()->id();
        $user= User::with('role')->where('id', $userId)->first();
    @endphp

    <script>
        function block_user(user_id, second_user) {
            let data = {user_id, second_user}
            $.post("/block/user", data)
                .done(function (data) {
                    location.reload()
                })
                .fail(function (xhr, textStatus, errorThrown) {
                });
        }

        function un_block_user(id) {
            let data = {id}
            $.post("/un/block/user", data)
                .done(function (data) {
                    location.reload()
                })
                .fail(function (xhr, textStatus, errorThrown) {
                });
        }

        $('.sett_tochk').click(function () {
            $('.setting_del_block').toggleClass('setting_del_block_active');
        });
    </script>
    <script>
        function delete_chat() {
            $.post("{{ route('delete_chat') }}", {'id': {{$to_user->id}}}, function (data) {
                location.reload()
            });
        }
    </script>
    <div class="message_head d_flex">
        <a href="" class="shop_name">
            <div class="shop_img">
                <img src="{{$to_user->avatar?showImage($to_user->avatar):showImage('frontend/default/img/avatar.jpg')}}"
                     alt="">
            </div>
            <div class="d_flex name_on_off">
                <span class="name_us_onof">{{ $to_user->name }} </span>
{{--                @if($to_user->active_user)--}}
{{--                    <span class="online d_flex">--}}
{{--                    <span class="online_green"></span>--}}
{{--                        ONLINE--}}
{{--                    </span>--}}
{{--                @else--}}
{{--                    <span class="offline d_flex">--}}
{{--                    <span class="offline_gray"></span>--}}
{{--                        OFFLINE {{ Carbon\Carbon::parse($to_user->when_not_active)->format('H:i') }}--}}
{{--                    </span>--}}
{{--                @endif--}}
            </div>
        </a>
        <div class="settings_mess">
            <div class="sett_tochk">
                <svg width="3" height="20" viewBox="0 0 3 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="1.5" cy="1.5" r="1.5" fill="#282828"></circle>
                    <circle cx="1.5" cy="9.99902" r="1.5" fill="#282828"></circle>
                    <circle cx="1.5" cy="18.5" r="1.5" fill="#282828"></circle>
                </svg>
            </div>
            <div class="setting_del_block">
                <span class="delete_mess" onclick="delete_chat()">Delete</span>
                @if($user->role->type === 'superadmin' || $user->role->type === 'admin')
                    <span class="block_user"
                          onclick="
                                @if(isset(\App\Models\Block_user::where('user_id',$userId)->where('second_user',$to_user->id)->first()->id))
                                    un_block_user('{{\App\Models\Block_user::where('user_id', $userId)->where('second_user',$to_user->id)->first()->id}}')
                                @else
                                    block_user('{{$userId}}','{{$to_user->id}}')
                                @endif
                                ">

                        @if(isset(Block_user::where('user_id', $userId)->where('second_user',$to_user->id)->first()->id))
                            Unblock
                        @else
                            Block
                        @endif

                    </span>
                @endif
            </div>
        </div>
    </div>
    @php
        $pop = Message::where([['from_id' ,'=', $userId],['to_id',"=",$to_user->id]])->orwhere([['to_id' ,'=', $userId],['from_id',"=",$to_user->id]])->get();
    @endphp
    @foreach ($pop as $m)
        @if ($m->to_id == $userId && $m->view == 0)
            @php
                $m->view = 1;
                $m->save();
            @endphp

            <script>
                $.post("{{ route('ggg') }}", function (data) {
                    $(".messageneri_tiv").text(data);
                });
            </script>
        @endif
    @endforeach

    <div id="sms_area">
        @include('include')
    </div>
    <div class="text_here_block">
        <span class="file_svg">
            <input type="file" id="file">
           <svg width="16" height="12" viewBox="0 0 16 12" fill="none"
                xmlns="http://www.w3.org/2000/svg">
           <path d="M14.5 0H1.5C0.65625 0 0 0.6875 0 1.5V10.5C0 11.3438 0.65625 12 1.5 12H14.5C15.3125 12 16 11.3438 16 10.5V1.5C16 0.6875 15.3125 0 14.5 0ZM14.3125 10.5H1.6875C1.5625 10.5 1.5 10.4375 1.5 10.3125V1.6875C1.5 1.59375 1.5625 1.5 1.6875 1.5H14.3125C14.4062 1.5 14.5 1.59375 14.5 1.6875V10.3125C14.5 10.4375 14.4062 10.5 14.3125 10.5ZM4 2.75C3.28125 2.75 2.75 3.3125 2.75 4C2.75 4.71875 3.28125 5.25 4 5.25C4.6875 5.25 5.25 4.71875 5.25 4C5.25 3.3125 4.6875 2.75 4 2.75ZM3 9H13V6.5L10.25 3.78125C10.0938 3.625 9.875 3.625 9.71875 3.78125L6 7.5L4.75 6.28125C4.59375 6.125 4.375 6.125 4.21875 6.28125L3 7.5V9Z"
                 fill="#323232"/>
           </svg>
        </span>
        <textarea name="messege_text" id="sms" class="messege_text" placeholder="Comment..." rows="1"
                  cols="1"></textarea>
        <button class="send_sms" type="submit"
                @if(!(\App\Models\Block_user::where('user_id', $userId)->where('second_user',$to_user->id)->first())) onclick="sendMessage({{$userId}}, {{$to_user->id }})" @endif>
            <svg width="31" height="27" viewBox="0 0 31 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M29.8878 12.5832C30.6888 12.932 30.6888 14.068 29.8878 14.4168L2.39243 26.3906C1.73192 26.6783 0.993165 26.1942 0.993165 25.4738L0.993165 16.2622C0.993165 15.7793 1.3382 15.3654 1.81318 15.2785L7.48706 14.2403C8.51796 14.0517 8.60121 12.6059 7.59877 12.3002L1.70147 10.5017C1.28067 10.3734 0.993166 9.98514 0.993166 9.54521L0.993166 1.52619C0.993166 0.805771 1.73193 0.321716 2.39243 0.609357L29.8878 12.5832Z"
                      fill="#00AAAD"/>
            </svg>
        </button>
    </div>
    <script>
        $('#sms').on('keydown', function (event) {
            if (event.which === 13 && !event.shiftKey) { // Check for "Enter" key with keycode 13
                event.preventDefault(); // Prevent the default behavior of Enter (newline)

                sendMessage({{$userId}}, {{$to_user->id }})
            }
        });
    </script>
@endif