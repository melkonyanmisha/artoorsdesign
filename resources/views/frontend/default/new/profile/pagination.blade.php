@php

    function drawProfilePagination(Illuminate\Pagination\LengthAwarePaginator $model, string $fieldToAppend)
    {

    $paginateID = intval(request('paginate_id'));
    $offset = 4;
    $currentPage = $model->currentPage();
    $lastPage = $model->lastPage();

    if($currentPage > $lastPage - $offset ){
       $startPageToDisplay = $lastPage - $offset;
       $endPageToDisplay = $lastPage;
    }else{
       if($currentPage < $offset  ){
       $startPageToDisplay = 1;
       $endPageToDisplay = $offset +1;
       }else{
            $startPageToDisplay =  $currentPage - floor( $offset / 2);
            $endPageToDisplay = $currentPage + floor( $offset / 2);
       }
    }

    if($startPageToDisplay <= 0){
        $startPageToDisplay = 1;
    }

@endphp

<div class="pagination-section">
    <div class='choose_pagination_quantity'>
        <span> Per page: </span>
        <a class="<?= !$paginateID || $paginateID === 12 ? 'per_page per_page_active' : 'per_page'; ?>"
           href="{{route('frontend.customer_profile',['paginate_id'=>12,'a' => $fieldToAppend])}}">
            12
        </a>

        <a class="<?= !$paginateID || $paginateID === 24 ? 'per_page per_page_active' : 'per_page'; ?>"
           href="{{route('frontend.customer_profile',['paginate_id'=>24,'a' => $fieldToAppend])}}">
            24
        </a>
        <a class="<?= !$paginateID || $paginateID === 36 ? 'per_page per_page_active' : 'per_page'; ?>"
           href="{{route('frontend.customer_profile',['paginate_id'=>36,'a' => $fieldToAppend])}}">
            36
        </a>
        <a class="<?= !$paginateID || $paginateID === 72 ? 'per_page per_page_active' : 'per_page'; ?>"
           href="{{route('frontend.customer_profile',['paginate_id'=>72,'a' => $fieldToAppend])}}">
            72
        </a>
        <a class="<?= !$paginateID || $paginateID === 144 ? 'per_page per_page_active' : 'per_page'; ?>"
           href="{{route('frontend.customer_profile',['paginate_id'=>144,'a' => $fieldToAppend])}}">
            144
        </a>
    </div>

    @if ($lastPage > 1)
        <div class="pagination">
            <div class="pagination_numbers">
                @if($currentPage != 1)
                    <a href="{{ $model->appends(['a' => $fieldToAppend])->previousPageUrl() }}"
                       data-id="{{$currentPage-1}}" class="prev_next_page pagination_sp">
                        <svg width="9" height="15" viewBox="0 0 9 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8 1.25L2 7.25L8 13.25" stroke="#282828" stroke-width="2.25"/>
                        </svg>
                        Prev
                    </a>
                @endif

                @for($i =$startPageToDisplay; $i <= $endPageToDisplay; $i++)
                    @if($i < $lastPage)
                        <a href="{{ $model->appends(['a' => $fieldToAppend])->url($i) }}" data-id="{{$i}}"
                           class="{{ $currentPage == $i ? 'pagination_sp pagination_sp_active' : 'pagination_sp' }}">
                            {{$i}}
                        </a>
                    @endif
                @endfor

                @if( $endPageToDisplay < $lastPage - 1)
                    <span class="pagination-dots">...</span>
                @endif

                <a href="{{ $model->appends(['a' => $fieldToAppend])->url($model->appends(['a' => $fieldToAppend])->lastPage()) }}"
                   data-id="{{$lastPage}}"
                   class="pagination_sp  @if($currentPage === $lastPage) pagination_sp_active @endif">
                    {{ $lastPage }}
                </a>

                @if($currentPage != $lastPage)
                    <a href="{{ $model->appends(['a' => $fieldToAppend])->nextPageUrl() }}" data-id="{{++$currentPage}}"
                       class="prev_next_page pagination_sp">
                        <span>Next</span>
                        <svg width="9" height="15" viewBox="0 0 9 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1.25 1.25L7.25 7.25L1.25 13.25" stroke="#282828" stroke-width="2.25"/>
                        </svg>
                    </a>
                @endif
            </div>
        </div>
    @endif

</div>

@php
    }
@endphp