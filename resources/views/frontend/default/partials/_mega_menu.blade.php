@php
    $headerSliderSection = $headers->where('type','slider')->first();
    $headerCategorySection = $headers->where('type','category')->first();
    $headerProductSection = $headers->where('type','product')->first();
    $headerNewUserZoneSection = $headers->where('type','new_user_zone')->first();
@endphp

@php
    $sliders = $headerSliderSection->sliders();
@endphp
{{--@dd($sliders)--}}{{--src="{{asset(asset_path($slider->slider_image))}}--}}

@foreach($sliders as $key => $slider)
    @if($slider->status)
        <div class="slide_full_img">
            <a href="{{route('frontend.category_slug', ['slug' => 'all-products'])}}">
                <img src="{{asset(asset_path($slider->slider_image))}}" alt="{{$slider->image_alt}}">
                {{--            {{asset(asset_path('new/img/slide.jpg'))}}--}}
            </a>
        </div>
    @endif
@endforeach

