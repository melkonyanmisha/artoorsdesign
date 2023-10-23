@php
    /* @var App\Models\User $to_user */

    $user_id = session()->has('user_id') ? session('user_id') : auth()->id();
    $pop = \App\Models\Message::where([['from_id' ,'=', $user_id],['to_id',"=",$to_user->id]])->orwhere([['to_id' ,'=', $user_id],['from_id',"=",$to_user->id]])->get();
@endphp

<div class="sms_block">
    @foreach ($pop as $m)

        @if($m->from_id == $user_id)
            <div class="sms_from_user">
                @if($m->image)
                    <span>{{$m->created_at->toDateString()}}</span>
                    <img src="{{ asset('images/message/'.$m->image) }}" alt="">
                    @if($m->messages)
                        <div class="white_sms_block">
                            <p class='sms_mini'>
                                {{ $m->messages }}
                            </p>
                        </div>
                    @endif
                @elseif($m->messages)
                    <span>{{$m->created_at->toDateString()}}</span>
                    <div class="white_sms_block">
                        <p class='sms_mini'>
                            {{ $m->messages }}
                        </p>
                    </div>
                @endif
                @if($m->messages)
                    @if($m->view && (auth()->user()->role_id == 1 || auth()->user()->role_id == 2))
                        <span>
                        <svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M10 5C10 2.23858 7.76142 0 5 0C2.23858 0 0 2.23858 0 5C0 7.76142 2.23858 10 5 10C7.76142 10 10 7.76142 10 5ZM6.51178 3.14945L4.17867 5.48255L3.25418 4.55806L3.1953 4.50606C2.95012 4.31542 2.5956 4.33276 2.37029 4.55806C2.12622 4.80214 2.12622 5.19786 2.37029 5.44194L3.73673 6.80838L3.79561 6.86037C4.04079 7.05101 4.39531 7.03368 4.62062 6.80838L7.39566 4.03333L7.44766 3.97445C7.6383 3.72927 7.62097 3.37475 7.39566 3.14945C7.15159 2.90537 6.75586 2.90537 6.51178 3.14945Z"
                              fill="#00AAAD"/>
                        </svg>
                    </span>
                    @elseif((auth()->user()->role_id == 1 || auth()->user()->role_id == 2))
                        <span>
                            <svg width="10" height="10" viewBox="0 0 10 10" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                  d="M5 0C7.76142 0 10 2.23858 10 5C10 7.76142 7.76142 10 5 10C2.23858 10 0 7.76142 0 5C0 2.23858 2.23858 0 5 0ZM5 1.25C2.92893 1.25 1.25 2.92893 1.25 5C1.25 7.07107 2.92893 8.75 5 8.75C7.07107 8.75 8.75 7.07107 8.75 5C8.75 2.92893 7.07107 1.25 5 1.25ZM6.51178 3.14945L4.17867 5.48255L3.25418 4.55806L3.1953 4.50606C2.95012 4.31542 2.5956 4.33276 2.37029 4.55806C2.12622 4.80214 2.12622 5.19786 2.37029 5.44194L3.73673 6.80838L3.79561 6.86037C4.04079 7.05101 4.39531 7.03368 4.62062 6.80838L7.39566 4.03333L7.44766 3.97445C7.6383 3.72927 7.62097 3.37475 7.39566 3.14945C7.15159 2.90537 6.75586 2.90537 6.51178 3.14945Z"
                                  fill="#C4C4C4"/>
                            </svg>
                        </span>
                    @endif
                @endif
            </div>

        @else
            <div class="sms_from_shop">
                @if($m->image)
                    <span>{{$m->created_at->toDateString()}}</span>
                    <img src="{{ asset('images/message/'.$m->image) }}" alt="">
                    @if($m->messages)
                        <div class="blue_sms_block">
                            {{ $m->messages }}
                        </div>
                    @endif
                @elseif($m->messages)
                    <span>{{$m->created_at->toDateString()}}</span>

                    <div class="blue_sms_block">
                        {{ $m->messages }}
                    </div>
                @endif

            </div>
        @endif
    @endforeach
</div>