@php
    /** @var Modules\Product\Entities\ $product */
@endphp

@php
    $link = route('artoors.files',$product->id);
@endphp

<a href="{{$link}}" class="download_product_btn" target="_blank">
    Download
    <svg width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
        <g clip-path="url(#clip0_794_15016)">
            <path d="M16.5 9H13.5938L15.0625 7.5625C16 6.625 15.3125 5 14 5H12V1.5C12 0.6875 11.3125 0 10.5 0H7.5C6.65625 0 6 0.6875 6 1.5V5H4C2.65625 5 1.96875 6.625 2.9375 7.5625L4.375 9H1.5C0.65625 9 0 9.6875 0 10.5V14.5C0 15.3438 0.65625 16 1.5 16H16.5C17.3125 16 18 15.3438 18 14.5V10.5C18 9.6875 17.3125 9 16.5 9ZM4 6.5H7.5V1.5H10.5V6.5H14L9 11.5L4 6.5ZM16.5 14.5H1.5V10.5H5.875L7.9375 12.5625C8.5 13.1562 9.46875 13.1562 10.0312 12.5625L12.0938 10.5H16.5V14.5ZM13.75 12.5C13.75 12.9375 14.0625 13.25 14.5 13.25C14.9062 13.25 15.25 12.9375 15.25 12.5C15.25 12.0938 14.9062 11.75 14.5 11.75C14.0625 11.75 13.75 12.0938 13.75 12.5Z"
                  fill="white"/>
        </g>
        <defs>
            <clipPath id="clip0_794_15016">
                <rect width="18" height="16" fill="white"/>
            </clipPath>
        </defs>
    </svg>
</a>