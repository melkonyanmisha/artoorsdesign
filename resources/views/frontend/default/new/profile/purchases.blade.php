@php
    use App\Models\Order;
@endphp

<div class="for_my_purchases">
    <h2>My Purchases</h2>
    <div class="purchases_section">
        @php
            $orders = Order::where('customer_id',auth()->id())->latest()->paginate(request()->paginate_id??20);
        @endphp

        <table class="purchases_block_">
            <tr>
                <th>Model Name</th>
                <th>Download Total</th>
                <th>Grand total</th>
                <th>{{__('common.order_id')}}</th>
                <th>{{__('defaultTheme.order_date')}}</th>
                <th>Status</th>
                <th>Discount total</th>
                <th>Generate Invoice</th>
            </tr>

            @foreach($orders as $order)
                @foreach ($order->packages[0]->products as $product)
                    <tr>
                        <td>
                            @if(!empty($product->seller_product_sku))

                                <a style="color: #00AAAD"
                                   href="{{singleProductURL($product->seller_product_sku->product->seller->slug, $product->seller_product_sku->product->slug, $product->seller_product_sku->product->product->categories[0]->slug)}}"
                                   class="for_descrip descrip_text">
                                    @if($product->seller_product_sku->product->product_name != null)
                                        {{$product->seller_product_sku->product->product_name}}
                                    @else
                                        {{ $product->seller_product_sku->product->product->product_name}}
                                    @endif
                                </a>
                            @else
                                <a style="color: rgba(113, 113, 113, 0.7)" disabled class="for_descrip descrip_text">
                                    Product removed
                                </a>
                            @endif
                        </td>

                        <td>
                            {{--                            todo@@@ need to change in the future --}}
                            Test
                        </td>

                        <td>
                            <span class="item_img for_item">
                                {{$order->grand_total}}$
                            </span>
                        </td>

                        <td>
                            <span class="for_model">{{ $order->order_number }}</span>
                        </td>

                        <td>
                            <span class="for_date">{{$order->created_at->toDateString()}}</span>
                        </td>

                        <td>
                            <span class="for_model">{{ $order->status() }}</span>
                        </td>
                        <td>
                            <span class="for_amount"> {{$order->discount_total }}$</span>
                        </td>

                        <td>
                            <a href="{{ route('order_manage.print_order_details', $order->id) }}"
                               target="_blank"
                               class="for_amount" style="color: #00AAAD">Generate </a>
                        </td>
                    </tr>
                @endforeach
            @endforeach

        </table>

        @php
            drawProfilePagination($orders, 'purchases');
        @endphp
    </div>
</div>