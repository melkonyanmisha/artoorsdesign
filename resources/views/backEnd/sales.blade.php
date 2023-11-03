@extends('backEnd.master')
@section('styles')
    <link rel="stylesheet" href="{{asset(asset_path('backend/css/backend_page_css/sales.css'))}}"/>
@endsection

@section('mainContent')
    <table id="sales" class="table">
    </table>
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            salesDataTable();

            function salesDataTable() {
                let totalSales = {!! json_encode($totalSales) !!};
                const salesTable = $('#sales').DataTable({
                    "columns": [
                        {title: 'Order ID', data: 'order_id'},
                        {title: 'Product ID', data: 'product_id'},
                        {title: 'Purchased', data: 'purchase_date'},
                        {title: 'Product', data: 'product_name'},
                        {title: 'Buyer', data: 'customer_full_name'},
                        {title: 'Country', data: 'shipping_country'},
                        {title: 'Original Price', data: 'sub_total'},
                        {title: 'Sold Price', data: 'grand_total'},
                        {title: 'Downloads', data: 'downloads'},
                        {title: 'Refund', data: 'refund_link'},
                    ],

                    data: totalSales,
                    // "pageLength": 10,
                });

                salesTable.order([0, 'desc']).draw();
            }
        })
    </script>
@endpush