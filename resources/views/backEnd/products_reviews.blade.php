@extends('backEnd.master')
@section('styles')
    <link rel="stylesheet" href="{{asset(asset_path('backend/css/backend_page_css/products_reviews.css'))}}"/>
@endsection
@section('mainContent')
    <div class="col-12">
        <div class="box_header common_table_header">
            <div class="main-title d-md-flex">
                <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">Products Review List</h3>

            </div>
        </div>
    </div>

    <table id="products-reviews" class="table">
    </table>
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            salesDataTable();

            function salesDataTable() {
                let totalSales = {!! json_encode($totalSales) !!};
                const salesTable = $('#products-reviews').DataTable({
                    "columns": [
                        {title: 'ID', data: 'id'},
                        {title: 'User Id', data: 'user_id'},
                        {title: 'Product Id', data: 'product_id'},
                        {title: 'Is Positive Like', data: 'is_positive_like'},
                        {title: 'Created At', data: 'created_at'},
                        {title: 'Text', data: 'text'},
                    ],

                    data: totalSales,
                    // "pageLength": 10,
                });

                salesTable.order([0, 'desc']).draw();
            }
        })
    </script>
@endpush