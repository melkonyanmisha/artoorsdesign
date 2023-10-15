<div class="">

    <table class="table table-hover" id="allReviewTable">
        <thead>
            <tr>
                <th scope="col" width="10%">{{ __('common.sl') }}</th>
                <th scope="col" width="10%">{{ __('review.rating') }}</th>
                <th scope="col" width="35%">{{ __('review.customer_feedback') }}</th>
                <th scope="col" width="35%">User Name</th>
                <th scope="col" width="35%">Product name:</th>
                <th scope="col" width="15%">Created Time</th>
                <th scope="col" width="15%">Updated Time</th>
                <th scope="col" width="15%">Delete</th>

                </th>
            </tr>
            @foreach(\App\Models\Astx::all() as $i => $astx)
                @php
                    $user = \App\Models\User::find($astx->user_id);
                    $product = \Modules\Seller\Entities\SellerProduct::find($astx->product_id);

                    if(!$product)continue
                @endphp
            <tr>
                <th scope="col" width="10%">{{ ++$i }}</th>
                <th scope="col" width="10%">{{ $astx->astx }}</th>

                <th scope="col" width="35%">{{ $user->email }}</th>
                <th scope="col" width="35%">{{ $user->customerBillingAddress->name }}</th>
                <th scope="col" width="35%">{{ $product?\Illuminate\Support\Str::limit($product->product->product_name, 25, $end='...'):'' }}</th>
{{--                seller_product--}}
                <th scope="col" width="15%">{{ $astx->created_at }}</th>
                <th scope="col" width="15%">{{ $astx->updated_at }}</th>
                <th scope="col" width="20%" style="background-color: red; color: white;" class="delete" data-id="{{$astx->id}}">delete<br></th>
            </tr>
            @endforeach
        </thead>
<script>
$('.delete').click(function (){
    $.post( "{{route('review.product.delete')}}", { _token : '{{csrf_token()}}',id : $(this).attr('data-id') } )
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
