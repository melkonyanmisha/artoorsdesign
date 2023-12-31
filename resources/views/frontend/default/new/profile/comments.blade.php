@php
    use App\Models\Comment;
    use Modules\Seller\Entities\SellerProduct;
@endphp

<div class="for_my_comments">
    <h3>My Comments</h3>

    @php
        $products = Comment::where('user_id',auth()->id())->latest()->paginate(request()->comment_id??20);
    @endphp
    <div class="d_flex sto_ my_comm_block">
        @foreach($products as $product)
            <div class="comment_block sto_ d_flex">
                <div class="d_flex img_and_com">
                    <div class="item_img">
                        <img src="{{auth()->user()->avatar?showImage(auth()->user()->avatar):showImage('frontend/default/img/avatar.jpg')}}"
                             alt="">
                    </div>
                    <div class="d_flex rows_ max_comm_view">
                        <span class="com_text">{{$product->text}}</span>
                        @php
                            $a = SellerProduct::where('id',$product->product_id)->first()
                        @endphp
                        <span class="comented_">
                            Commented on
                            <a href="{{singleProductURL($a->seller->slug, $a->slug, $a->product->categories[0]->slug)}}"
                               class="prod_name_">{{$a->product->product_name}}
                            </a>
                        </span>
                    </div>
                </div>
                <div class="d_flex loc_delete">
                    <div class="clock_ d_flex">
                        <svg width="13" height="14" viewBox="0 0 13 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6.65885 0.858948C3.26953 0.858948 0.523438 3.60504 0.523438 6.99436C0.523438 10.3837 3.26953 13.1298 6.65885 13.1298C10.0482 13.1298 12.7943 10.3837 12.7943 6.99436C12.7943 3.60504 10.0482 0.858948 6.65885 0.858948ZM6.65885 11.9423C3.91276 11.9423 1.71094 9.74046 1.71094 6.99436C1.71094 4.27301 3.91276 2.04645 6.65885 2.04645C9.38021 2.04645 11.6068 4.27301 11.6068 6.99436C11.6068 9.74046 9.38021 11.9423 6.65885 11.9423ZM8.16797 9.36936C8.31641 9.46832 8.48958 9.44358 8.58854 9.29515L9.05859 8.67666C9.15755 8.52822 9.13281 8.35504 8.98438 8.25608L7.35156 7.04384V3.53082C7.35156 3.38239 7.20312 3.23395 7.05469 3.23395H6.26302C6.08984 3.23395 5.96615 3.38239 5.96615 3.53082V7.61285C5.96615 7.68707 5.99089 7.78603 6.0651 7.83551L8.16797 9.36936Z"
                                  fill="#717171"/>
                        </svg>
                        <span>{{$product->created_at->toDateString()}}</span>
                    </div>
                    <span class="remove_comment" onclick="comment_delete('{{$product->id}}')">
                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.728516 0.577698L16.9922 16.8407M16.9922 0.577698L0.728516 16.8414"
                                      stroke="#717171"/>
                            </svg>
                    </span>
                </div>
            </div>
        @endforeach
    </div>

    @php
        drawProfilePagination($products, 'comments');
    @endphp
</div>