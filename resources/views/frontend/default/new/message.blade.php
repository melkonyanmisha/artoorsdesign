@php
    /* @var int $userId */

use App\Models\Message;
use App\Models\Block_user;
use Carbon\Carbon;

$userId = $userId ?? auth()->id();
@endphp

@extends('frontend.default.layouts.newApp')

@php $message = App\Models\ContactUsSeo::first(); @endphp

@section('title')
    {{$message->title}}
@endsection
@section('share_meta')
    <meta name="title" content="{{$message->meta_title}}"/>
    <meta name="description" content="{{$message->meta_description}}"/>
    <meta property="og:title" content="{{$message->meta_title}}"/>
    <meta property="og:description" content="{{$message->meta_description}}"/>
    <meta property="og:url" content="{{URL::full()}}"/>
    <meta property="og:image" content="{{showImage($message->meta_image)}}"/>
    <meta property="og:image:width" content="400"/>
    <meta property="og:image:height" content="300"/>
    <meta property="og:image:alt" content="{{$message->meta_image_alt}}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:locale" content="en_EN"/>
    <meta name="keywords" content="{{$message->meta_keyword}}">
@endsection

@section('content')
    <main>
        @include('frontend.default.includes.mainInclude')
        <section class="wrapper">
            @section('breadcrumb')
                Messages
            @endsection
            @include('frontend.default.partials._breadcrumb')
            <div class="message_section">
                <h1>Messages</h1>

                <div class="d_flex message_block">
                    <div class="message_users_block d_flex">
                        @foreach ($users as $user)
                            @if(isset($user))
                                <div class="messages_from_user sto_ d_flex"
                                     onclick="fill_message_block('{{ $user->id }}')"
                                     data-id="{{$user->id}}">
                                    <div class="user_img_mess">
                                        <img src="{{$user->avatar?showImage($user->avatar):showImage('frontend/default/img/avatar.jpg')}}"
                                             alt="">
                                    </div>
                                    <div class="d_flex name_message sto_">
                                        <div class="d_flex sto_ username_online_off">
                                            <span class="name_from_">{{ $user->first_name?$user->first_name.' '. $user->last_name:$user->email }}</span>
                                            @if($user->active_user)
                                                <span class="online d_flex">
                                                <span class="online_green"></span>
                                                ONLINE @if(isset(Block_user::where('user_id',6)->where('second_user',$user->id)->first()->id))
                                                        Blocked
                                                    @endif
                                            </span>
                                            @else
                                                <span class="date_message">

                                                {{
                                                    Carbon::parse($user->when_not_active)->format('H:i')
                                                }} @if(isset(Block_user::where('user_id',6)->where('second_user',$user->id)->first()->id))
                                                        Blocked
                                                    @endif
                                            </span>
                                            @endif
                                        </div>
                                        @php
                                            $pop = Message::where([['from_id' ,'=', $userId],['to_id',"=",$user->id]])->orwhere([['to_id' ,'=', $userId],['from_id',"=",$user->id]])->latest()->first();
                                        @endphp
                                        <p class="under_name_mess">
                                            {{Illuminate\Support\Str::substr($pop->messages, 0, 60)}}
                                        </p>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    @if(!empty($users[0]))
                        @php
                            // userId can be the id of current or a specified user
                            session(['userId' => $userId]);
                        @endphp
                        <div class="message_block_">
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </main>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            @if(auth()->user()->role->type === 'customer')
            const firstMessageBlock = $('.messages_from_user:first-child');
            $(firstMessageBlock).click();
            $(firstMessageBlock).addClass('messages_from_user_active');
            @endif
        })
    </script>
@endsection