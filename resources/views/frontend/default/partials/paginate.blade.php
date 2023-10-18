@if($paginator->lastPage() > 1)

    <div class='choose_pagination_quantity'>
        <span> Per page: </span>
        <a class="per_page per_page_active" data-id="12">12</a>
        <a class="per_page" data-id="24">24</a>
        <a class="per_page" data-id="36">36</a>
        <a class="per_page" data-id="72">72</a>
        <a class="per_page" data-id="144">144</a>
    </div>
    <div class="d_flex pagination">
        @php
            $offset = 4;

            if($paginator->currentPage() > $paginator->lastPage() - $offset ){
               $startPageToDisplay = $paginator->lastPage() - $offset;
               $endPageToDisplay = $paginator->lastPage();
            }else{
               if($paginator->currentPage() < $offset  ){
               $startPageToDisplay = 1;
               $endPageToDisplay = $offset +1;
               }else{
                    $startPageToDisplay =  $paginator->currentPage() - floor( $offset / 2);
                    $endPageToDisplay = $paginator->currentPage() + floor( $offset / 2);
               }
            }

            if($startPageToDisplay < 0){
                $startPageToDisplay = 1;
            }
        @endphp

        @if($paginator->currentPage() != 1)
            <a href="#" data-id="{{$paginator->currentPage()-1}}" class="prev_next_page pagination_sp">
                <svg width="9" height="15" viewBox="0 0 9 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 1.25L2 7.25L8 13.25" stroke="#282828" stroke-width="2.25"/>
                </svg>
                Previous
            </a>
        @endif

        @for($i =$startPageToDisplay; $i <= $endPageToDisplay; $i++)
            @if($i < $paginator->lastPage())
                <a href="#" data-id="{{$i}}"
                   class="{{ $paginator->currentPage() == $i ? 'pagination_sp pagination_sp_active' : 'pagination_sp' }}">
                    {{$i}}
                </a>
            @endif
        @endfor

        @if( $endPageToDisplay < $paginator->lastPage() - 1)
            <a>...</a>
        @endif

        <a href="#" data-id="{{$paginator->lastPage()}}"
           class="pagination_sp  @if($paginator->currentPage() == $paginator->lastPage()) pagination_sp_active @endif">
            {{ $paginator->lastPage() }}
        </a>

        @if($paginator->currentPage() != $paginator->lastPage())
            <a href="#" data-id="{{$paginator->currentPage()+1}}" class="prev_next_page pagination_sp">
                <span>Next</span>
                <svg width="9" height="15" viewBox="0 0 9 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1.25 1.25L7.25 7.25L1.25 13.25" stroke="#282828" stroke-width="2.25"/>
                </svg>
            </a>
        @endif

        <div class="jump_to_section">
            <div class="vl"></div>
            <label for="jump_to_page" style="cursor: pointer">Jump to page:</label>
            <input type="number" id="jump_to_page" placeholder="Fill and press Enter">
        </div>
        <script>
            $(document).ready(function ($) {
                $('#jump_to_page').on('keyup', function (e) {
                    const pageNumber = $(this).val();

                    if (!pageNumber) {
                        return false;
                    }

                    // Get the current URL
                    let currentUrl = window.location.href;
                    // Create a URL object to parse the URL
                    let url = new URL(currentUrl);
                    // Update the 'page' parameter to pageNumber
                    url.searchParams.set('page', pageNumber);

                    window.location.href = url.href;
                });
            });
        </script>
    </div>
@endif