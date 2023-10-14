@extends('backEnd.master')
@section('styles')

    <link rel="stylesheet" href="{{asset(asset_path('modules/review/css/style.css'))}}" />


@endsection
@section('mainContent')
    <section class="admin-visitor-area up_st_admin_visitor">

        <div class="container-fluid p-0">
            <div class="row justify-content-center">

                <div class="col-lg-12">
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active show" id="all_review">
                            <div class="col-12">
                                <div class="box_header common_table_header">
                                    <div class="main-title d-md-flex">
                                        <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">Comments</h3>

                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="QA_section QA_section_heading_custom check_box_table">
                                    <div class="QA_table">
                                        <div class="" id="all_item_table">

                                            <div class="">
                                                <table class="table" id="allReviewTable">
                                                    <thead>
                                                    <tr>
                                                        <th scope="col" width="10%">{{ __('common.sl') }}</th>
                                                        <th scope="col" width="35%">{{ __('review.customer_feedback') }}</th>
                                                        <th scope="col" width="35%">Text</th>
                                                        <th scope="col" width="35%">Purchased</th>

                                                        <th scope="col" width="35%">Product name:</th>
                                                        <th scope="col" width="15%">Created Time</th>

                                                        </th>
                                                    </tr>
                                                    @foreach(\App\Models\Comment::all() as $i => $astx)
                                                        <tr>
                                                            <th scope="col" width="10%">{{ ++$i }}</th>
                                                            @php
                                                                $user = \App\Models\User::find($astx->user_id);
                                                                $product = \Modules\Seller\Entities\SellerProduct::find($astx->product_id);

                                                                if(!$product)continue
                                                            @endphp
                                                            <th scope="col" width="35%">{{ $user->email }}</th>
                                                            <th scope="col" width="35%">{!! \Illuminate\Support\Str::limit(html_entity_decode($astx->text), 20, $end='...') !!}</th>
                                                            @if(\App\Models\Paymant_products::where('user_id',$user->id)->where('product_id',$product->id)->first())
                                                            <th scope="col" width="35%">Yes</th>
                                                            @else
                                                            <th scope="col" width="35%">No</th>
                                                            @endif
                                                            <th scope="col" width="35%">{{ $product->product->product_name }}</th>
                                                            {{--                seller_product--}}
                                                            <th scope="col" width="15%">{{ $astx->created_at }}</th>
                                                            <th scope="col" width="15%"><a href="{{route('admin.comment',['id' => $astx->id])}}">Comment</a></th>
                                                            <th scope="col" width="20%" style="background-color: red; color: white;" class="delete" data-id="{{$astx->id}}">delete<br></th>
                                                        </tr>
                                                    @endforeach
                                                    </thead>
                                                    <script>
                                                        $('.delete').click(function (){
                                                            $.post( "{{route('delete_comment')}}", { _token : '{{csrf_token()}}',id : $(this).attr('data-id') } )
                                                                .done(function() {
                                                                    alert( "second success" );
                                                                    location.reload()
                                                                })
                                                                .fail(function(e) {
                                                                    alert( e );
                                                                })
                                                        })

                                                    </script>
                                                </table>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection



