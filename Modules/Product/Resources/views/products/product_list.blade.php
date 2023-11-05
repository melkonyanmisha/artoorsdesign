<table class="table" id="mainProductTable">
    <thead>
    @php
        $user = auth()->user();
        $type = $user->role->type;
    @endphp
    <tr>
        <th class="select-checkbox">
            <input type="checkbox" name="select_all" value="1" id="example-select-all">
        </th>
        {{--        <th scope="col">{{ __('common.sl') }}</th>--}}
        <th>Name</th>
        <th>Image</th>
        <th>Date Added</th>
        <th>Date Modified</th>
        <th>PRICE</th>
        <th>Total Earnings</th>
        <th>Single User</th>
        <th>Views</th>

        {{--        <th scope="col">{{ __('common.product_type') }}</th>--}}
        {{--        <th scope="col">{{ __('product.brand') }}</th>--}}
        {{--        @if(!isModuleActive('MultiVendor'))--}}
        {{--        <th scope="col">{{ __('product.stock') }}</th>--}}
        {{--        @endif--}}
        @if($type == "superadmin" || $type == "admin" || $type == "staff")
            <th>{{ __('common.status') }}</th>
        @else
            <th>{{ __('common.approval') }}</th>
        @endif
        <th>{{ __('common.action') }}</th>
    </tr>
    </thead>
</table>