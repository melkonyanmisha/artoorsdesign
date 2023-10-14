@extends('frontend.default.layouts.newApp')

@php $message = App\Models\ContactUsSeo::first(); @endphp

@section('title'){{$message->title}}@endsection
@section('share_meta')
    <meta name="title" content="{{$message->meta_title}}"/>
    <meta name="description" content="{{$message->meta_description}}"/>
    <meta property="og:title" content="{{$message->meta_title}}"/>
    <meta property="og:description" content="{{$message->meta_description}}"/>
    <meta property="og:url" content="{{URL::full()}}" />
    <meta property="og:image" content="{{showImage($message->meta_image)}}" />
    <meta property="og:image:width" content="400"/>
    <meta property="og:image:height" content="300"/>
    <meta property="og:image:alt" content="{{$message->meta_image_alt}}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:locale" content="en_EN"/>
    <meta name ="keywords" content="{{$message->meta_keyword}}">
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
                        @if(request()->has('id'))
                            <script>
                                new aaa('{{ request()->id }}')
                                    $('.messages_from_user').removeClass('messages_from_user_active');
                                    // $(this).addClass('messages_from_user_active');
                            </script>
                        @endif

                        @foreach ($users as $user)
                            @if(isset($user))
                            <div class="messages_from_user sto_ d_flex @if(request()->has('id')) @if($user->id == request()->id)) messages_from_user_active @endif @endif " onclick="aaa('{{ $user->id }}')" data-id="{{$user->id}}">
                                <div class="user_img_mess">
                                    <img src="{{$user->avatar?showImage($user->avatar):showImage('frontend/default/img/avatar.jpg')}}" alt="">
                                </div>
                                <div class="d_flex name_message sto_">

                                    <div class="d_flex sto_ username_online_off">


                                        <span class="name_from_">{{ $user->first_name?$user->first_name.' '. $user->last_name:$user->email }}</span>


                                        @if($user->active_user)
                                            <span class="online d_flex">
                                                <span class="online_green"></span>
                                                ONLINE @if(isset(\App\Models\Block_user::where('user_id',6)->where('second_user',$user->id)->first()->id)) Blocked @endif
                                            </span>
                                        @else
                                            <span class="date_message">

                                                {{
                                                    Carbon\Carbon::parse($user->when_not_active)->format('H:i')
                                                }} @if(isset(\App\Models\Block_user::where('user_id',6)->where('second_user',$user->id)->first()->id)) Blocked @endif
                                            </span>
                                        @endif
                                    </div>

                                    @php
                                        $pop = \App\Models\Message::where([['from_id' ,'=', auth()->id()],['to_id',"=",$user->id]])->orwhere([['to_id' ,'=', auth()->id()],['from_id',"=",$user->id]])->latest()->first();
                                    @endphp
                                    <p class="under_name_mess">

                                        {{Illuminate\Support\Str::substr($pop->messages, 0, 60)}}
                                        {{--                                            Lorem Ipsum is simply dummy text of the printing and typesetting.....--}}
                                    </p>
                                </div>
                            </div>
                            @endif
                        @endforeach


                    </div>
                    @if(!empty($users[0]))
                        <div class="message_block_">
                            @include('mainInclude')
                        </div>
                    @endif



                </div>
            </div>
        </section>
    </main>
@endsection
