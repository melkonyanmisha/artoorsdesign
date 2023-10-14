<div class="d_flex pagination ">
    <div class="d_flex pagination_block  ">
        @if($paginator->currentPage() != 1)
        <a class="prev_next_page category_paginate @if($paginator->currentPage() != 1) getFilterUpdateByIndex @endif "
           data-id="{{$paginator->currentPage() - 1}}"
        >
            <svg width="9" height="15" viewBox="0 0 9 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M8 1.25L2 7.25L8 13.25" stroke="#282828" stroke-width="2.25"/>
            </svg>
        </a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <a class="pagination_sp d_flex">{{ $element }}</a>
            @endif

            @if (is_array($element))

                @foreach ($element as $page => $url)
                    <a data-id="{{$page}}"
                       class="category_paginate getFilterUpdateByIndex pagination_sp
@if($paginator->currentPage() == $page) pagination_sp_active @endif">{{$page}}</a>
                @endforeach
            @endif
        @endforeach
        @if($paginator->currentPage() != $paginator->lastPage())
        <a data-id="{{$paginator->currentPage() + 1}}"
           class="category_paginate @if($paginator->currentPage() != $paginator->lastPage()) getFilterUpdateByIndex @endif
                    prev_next_page">
            <svg width="9" height="15" viewBox="0 0 9 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M1.25 1.25L7.25 7.25L1.25 13.25" stroke="#282828" stroke-width="2.25"/>
            </svg>
        </a>
        @endif
    </div>
</div>
