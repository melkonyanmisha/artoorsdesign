<table class="table" id="mainProductTable">
    <thead>
        @php
            $user = auth()->user();
            $type = $user->role->type;
        @endphp
    <tr>
{{--        <th scope="col">All</th>--}}
        <th class="sorting_disabled select-checkbox"><input type="checkbox" name="select_all" value="1" id="example-select-all"></th>
{{--        <th scope="col">{{ __('common.sl') }}</th>--}}
        <th scope="col" class="1 @if(session()->get('name') && session()->get('name') == 'Name') sorting_desc @endif">Name</th>
        <th scope="col" class="2 @if(session()->get('name') && session()->get('name') == 'PRICE') sorting_desc @endif">PRICE</th>
{{--        <th scope="col">{{ __('common.product_type') }}</th>--}}
{{--        <th scope="col">{{ __('product.brand') }}</th>--}}
        <th scope="col" class="3 sorting_disabled Image">Image</th>
        <th scope="col" class="4 Дата @if(session()->get('name') && session()->get('name') == 'Дата') sorting_desc @endif">Дата</th>
{{--        @if(!isModuleActive('MultiVendor'))--}}
{{--        <th scope="col">{{ __('product.stock') }}</th>--}}
{{--        @endif--}}
        @if($type == "superadmin" || $type == "admin" || $type == "staff")
        <th scope="col" class="sorting_disabled">{{ __('common.status') }}</th>
        @else
        <th scope="col" class="sorting_disabled">{{ __('common.approval') }}</th>
        @endif
        <th scope="col" class="sorting_disabled">{{ __('common.action') }}</th>
    </tr>
    </thead>

</table>
<script>

    $(document).ready(function (){

        var table = $('#example').DataTable(
            {
                "order": [[ 4, 'asc' ]]
            }
        );

        // var data = table
        //     .order([[ 3, 'asc' ]]);


        if('{{session()->get('name')}}' && '{{session()->get('name')}}' !== 'Name'){
            console.log('{{session()->get('name')}}')
            console.log($('.{{session()->get('name')}}'))
            $('.{{session()->get('name')}}').click()
            $('.{{session()->get('name')}}').click()
            $('.{{session()->get('name')}}').click()
            $('.{{session()->get('name')}}').click()
            $('.{{session()->get('name')}}').click()
            $('.{{session()->get('name')}}').click()
            $('.{{session()->get('name')}}').click()
            $('.{{session()->get('name')}}').click()
            $('.{{session()->get('name')}}').click()
            $('.{{session()->get('name')}}').click()
            $('.{{session()->get('name')}}').click()
            $('.{{session()->get('name')}}').click()

            {{--setTimeout(() => {--}}
            {{--    $('.{{session()->get('name')}}').click()--}}
            {{--    $('.{{session()->get('name')}}').click()--}}
            {{--    $('.{{session()->get('name')}}').click()--}}
            {{--    $('.{{session()->get('name')}}').click()--}}
            {{--    $('.{{session()->get('name')}}').click()--}}
            {{--    $('.{{session()->get('name')}}').click()--}}
            {{--    $('.{{session()->get('name')}}').click()--}}
            {{--    $('.{{session()->get('name')}}').click()--}}
            {{--    $('.{{session()->get('name')}}').click()--}}
            {{--    $('.{{session()->get('name')}}').click()--}}
            {{--    $('.{{session()->get('name')}}').click()--}}
            {{--    $('.{{session()->get('name')}}').click()--}}
            {{--}, "1000")--}}
        }

        $('th').click(function (){
            $.get('/post/set/get',{'type':$(this).text()})
        })
    })


</script>
