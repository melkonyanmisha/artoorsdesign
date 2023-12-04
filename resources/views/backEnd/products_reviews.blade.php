@extends('backEnd.master')
@section('styles')
    <link rel="stylesheet" href="{{asset(asset_path('backend/css/backend_page_css/products_reviews.css'))}}"/>
@endsection
@section('mainContent')

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="full-reviews-section">
        @if(request('full') == 0)
            <a class="primary-btn" href="?full=1">
                Full Reviews
            </a>
        @else
            <a class="primary-btn" href="?full=0">
                Partial Reviews
            </a>
        @endif
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
                        {title: 'Delete', data: 'remove_form', orderable: false},
                    ],

                    data: totalSales,
                    // "pageLength": 10,
                });

                salesTable.order([0, 'desc']).draw();
            }
        })
    </script>
@endpush