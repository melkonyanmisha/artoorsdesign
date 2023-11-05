@if($type === "superadmin" || $type === "admin" || $type === "staff")
    <label class="switch_toggle" for="checkbox_available_only_single_user_{{ $products->id }}">
        <input type="checkbox" id="checkbox_available_only_single_user_{{ $products->id }}"
               @if ($products->available_only_single_user == 1) checked @endif
               data-id="{{ $products->id }}"
               class="product_available_only_single_user_change">
        <div class="slider round"></div>
    </label>
@else
    @if($products->available_only_single_user === 1)
        <span class="badge_1">On</span>
    @else
        <span class="badge_2">Off</span>
    @endif
@endif