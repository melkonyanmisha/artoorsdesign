@component('mail::message')

{{--<h1>{{ app('general_setting')->mail_header }}</h1>--}}
<h4>{{ $data['text'] }}</h4>
Link - <a href="{{$data['description']}}">{{$data['description']}}</a>
<p>Password @php echo $data['password'] @endphp</p>

{{--<p>{{  app('general_setting')->mail_footer }}</p>--}}
@endcomponent
