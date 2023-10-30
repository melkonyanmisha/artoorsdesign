@php

    function drawProfilePagination($model, string $fieldToAppend)
    {

    $paginate_id = intval(request('paginate_id'));
//        var_dump($paginate_id); exit;
@endphp

<div class="pagination-section">
    <div class='choose_pagination_quantity'>
        <span> Per page: </span>
        <a class="<?= !$paginate_id || $paginate_id === 12 ? 'per_page per_page_active' : 'per_page'; ?>"
           href="{{route('frontend.customer_profile',['paginate_id'=>12,'a' => $fieldToAppend])}}">
            12
        </a>

        <a class="<?= !$paginate_id || $paginate_id === 24 ? 'per_page per_page_active' : 'per_page'; ?>"
           href="{{route('frontend.customer_profile',['paginate_id'=>24,'a' => $fieldToAppend])}}">
            24
        </a>
        <a class="<?= !$paginate_id || $paginate_id === 36 ? 'per_page per_page_active' : 'per_page'; ?>"
           href="{{route('frontend.customer_profile',['paginate_id'=>36,'a' => $fieldToAppend])}}">
            36
        </a>
        <a class="<?= !$paginate_id || $paginate_id === 72 ? 'per_page per_page_active' : 'per_page'; ?>"
           href="{{route('frontend.customer_profile',['paginate_id'=>72,'a' => $fieldToAppend])}}">
            72
        </a>
        <a class="<?= !$paginate_id || $paginate_id === 144 ? 'per_page per_page_active' : 'per_page'; ?>"
           href="{{route('frontend.customer_profile',['paginate_id'=>144,'a' => $fieldToAppend])}}">
            144
        </a>
    </div>

{{--    todo@@@@ need to continue like as resources/views/frontend/default/partials/paginate.blade.php --}}

    @if ($model->appends(['a' => $fieldToAppend])->lastPage() > 1)
        <div class="pagination">
            <div class="pagination_numbers">
                <a href="{{ $model->appends(['a' => $fieldToAppend])->previousPageUrl() }}"
                   class="prev_next_page">
                    <svg width="9" height="15" viewBox="0 0 9 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8 1.25L2 7.25L8 13.25" stroke="#282828" stroke-width="2.25"/>
                    </svg>
                </a>
                @if($model->appends(['a' => $fieldToAppend])->lastPage() > 1)
                    <a href="{{ $model->appends(['a' => $fieldToAppend])->url(1) }}"
                       class="pagination_sp @if($model->appends(['a' => $fieldToAppend])->currentPage() == 1) pagination_sp_active @endif">
                        1
                    </a>
                @endif
                @if($model->lastPage() > 2)
                    <a href="{{ $model->appends(['a' => $fieldToAppend])->url(2) }}"
                       class="pagination_sp @if($model->appends(['a' => $fieldToAppend])->currentPage() == 2) pagination_sp_active @endif">
                        2
                    </a>
                @endif
                @if($model->appends(['a' => $fieldToAppend])->lastPage() > 3)
                    <a href="{{ $model->appends(['a' => $fieldToAppend])->url(3) }}"
                       class="pagination_sp @if($model->appends(['a' => $fieldToAppend])->currentPage() == 3) pagination_sp_active @endif">
                        3
                    </a>
                @endif
                @if( $model->appends(['a' => $fieldToAppend])->lastPage() > 4)
                    <a>...</a>
                @endif
                <a href="{{ $model->appends(['a' => $fieldToAppend])->url($model->appends(['a' => $fieldToAppend])->lastPage()) }}"
                   class="pagination_sp  @if($model->appends(['a' => $fieldToAppend])->currentPage() == $model->appends(['a' => $fieldToAppend])->lastPage()) pagination_sp_active @endif">{{ $model->appends(['a' => $fieldToAppend])->lastPage() }}</a>
                <a href="{{ $model->appends(['a' => $fieldToAppend])->nextPageUrl()  }}" class="prev_next_page">
                    <svg width="9" height="15" viewBox="0 0 9 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1.25 1.25L7.25 7.25L1.25 13.25" stroke="#282828" stroke-width="2.25"/>
                    </svg>
                </a>
            </div>
        </div>
    @endif

</div>

@php

    }

@endphp