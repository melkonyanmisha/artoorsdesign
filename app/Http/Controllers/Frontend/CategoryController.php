<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\SearchTerm;
use Illuminate\Http\Request;
use Modules\FrontendCMS\Entities\HomepageCustomProduct;
use Modules\FrontendCMS\Entities\HomePageSection;
use Modules\Product\Entities\Brand;
use Modules\Product\Entities\Category;
use Modules\Product\Entities\ProductTag;
use Modules\Seller\Entities\SellerProduct;
use Modules\Product\Repositories\CategoryRepository;
use Modules\Product\Repositories\BrandRepository;
use \Modules\Product\Repositories\AttributeRepository;
use App\Services\FilterService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Modules\GeneralSetting\Entities\Currency;
use Modules\GiftCard\Entities\GiftCard;
use Modules\Product\Entities\Attribute;
use Modules\Product\Entities\CategoryProduct;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductVariations;
use Modules\Setup\Entities\Tag;
use Str;

class CategoryController extends Controller
{
    protected $filterService;
    public function __construct(FilterService $filterService)
    {
        $this->filterService = $filterService;
        $this->middleware('maintenance_mode');
    }
    public function index()
    {
        $paginate = isset(\request()->paginate_id) && \request()->paginate_id != null ? \request()->paginate_id : 12;
        $data['products'] = $this->filterService->getAllActiveProduct(null, $paginate);
        $catRepo = new CategoryRepository(new Category());
        $data['CategoryList'] = $catRepo->category();
        $attributeRepo = new AttributeRepository;
        $data['attributeLists'] = $attributeRepo->getActiveAllWithoutColor();
        $data['color'] = $attributeRepo->getColorAttr();
        if (session()->has('filterDataFromCat')) {
            session()->forget('filterDataFromCat');
        }
        $product_ids = $this->filterService->getAllActiveProductId();

        $product_min_price = $this->filterService->filterProductMinPrice($product_ids);
        $product_max_price = $this->filterService->filterProductMaxPrice($product_ids);
        $product_min_price = $this->filterService->getConvertedMin($product_min_price);
        $product_max_price = $this->filterService->getConvertedMax($product_max_price);
        $data['min_price_lowest'] = $product_min_price;
        $data['max_price_highest'] = $product_max_price;

        return view(theme('new.category'), $data);
    }

    public function fetchPagenateData(Request $request)
    {
        $sort_by = null;
        $paginate = null;
        if ($request->has('sort_by')) {
            $sort_by = $request->sort_by;
            $data['sort_by'] = $request->sort_by;
        }
        if ($request->has('paginate')) {
            $paginate = $request->paginate;
            $data['paginate'] = $request->paginate;
        }

        if ($request->has('slug1')) {
            $data['products'] = $this->filterService->getAllActiveProductt($sort_by, $paginate,$request->slug1);
        }else{
            $data['products'] = $this->filterService->getAllActiveProduct($sort_by, $paginate);
        }

        return view(theme('partials.category_paginate_data'), $data);
    }

    public function filterIndex(Request $request)
    {
        $data['products'] = $this->filterService->filterProductFromCategoryBlade($request->except("_token"), null, null);
        return view(theme('partials.category_paginate_data'), $data);
    }

    public function fetchFilterPagenateData(Request $request)
    {
        $sort_by = null;
        $paginate = null;
        if ($request->has('sort_by')) {
            $sort_by = $request->sort_by;
            $data['sort_by'] = $request->sort_by;
        }
        if ($request->has('paginate')) {
            $paginate = $request->paginate;
            $data['paginate'] = $request->paginate;
        }

        $data['products'] = $this->filterService->filterProductFromCategoryBlade(session()->get('filterDataFromCat'), $sort_by, $paginate);
        return view(theme('partials.category_paginate_data'), $data);
    }

    public function filterIndexByType(Request $request)
    {
        $sort_by = null;
        $paginate = null;
        if ($request->has('sort_by')) {
            $data['sort_by'] = $request->sort_by;
        }
        if ($request->has('paginate')) {
            $data['paginate'] = $request->paginate;
        }
        $data['products'] = $this->filterService->filterProductBlade($request->except("_token"), $sort_by, $paginate);
        if ($request->requestItemType == "category") {
            $data['category_id'] = $request->requestItem;
            $data['item'] = "category";
        }
        if ($request->requestItemType == "brand") {
            $data['brand_id'] = $request->requestItem;
            $data['item'] = "brand";
        }
        if ($request->requestItemType == "search") {
            $data['keyword'] = $request->requestItem;
            $data['item'] = "search";
        }
        if ($request->requestItemType == "tag") {
            $data['tag_id'] = $request->requestItem;
            $data['item'] = "tag";
        }
        if($request->requestItemType == "product"){
            $data['section_name'] = $request->requestItem;
            $data['item'] = "product";
        }

        return view(theme('partials.listing_paginate_data'),$data);
    }

    public function fetchFilterPagenateDataByType(Request $request)
    {
        $sort_by = null;
        $paginate = null;
        if ($request->has('sort_by')) {
            $sort_by = $request->sort_by;
            $data['sort_by'] = $request->sort_by;
        }
        if ($request->has('paginate')) {
            $paginate = $request->paginate;
            $data['paginate'] = $request->paginate;
        }
        $data['products'] = $this->filterService->filterProductBlade(session()->get('filterDataFromCat'), $sort_by, $paginate);
        if ($request->requestItemType == "category") {
            $data['category_id'] = $request->requestItem;
            $data['item'] = "category";
        }
        if ($request->requestItemType == "brand") {
            $data['brand_id'] = $request->requestItem;
            $data['item'] = "brand";
        }
        if ($request->requestItemType == "search") {
            $data['keyword'] = $request->requestItem;
            $data['item'] = "search";
        }
        if ($request->requestItemType == "tag") {
            $data['tag_id'] = $request->requestItem;
            $data['item'] = "tag";
        }
        return view(theme('partials.listing_paginate_data'), $data);
    }

    public function sortFilterIndexByType(Request $request)
    {

        if (session()->has('filterDataFromCat')) {
            $data['products'] = $this->filterService->filterSortProductBlade($request->except("_token"),session()->get('filterDataFromCat'));
        }
        else {
            $data['products'] = $this->filterService->productSortByCategory($request->requestItemType,$request->requestItem, $request->sort_by, $request->paginate);
        }
        if ($request->requestItemType == "category") {
            $data['category_id'] = $request->requestItem;
            $data['item'] = "category";
        }
        if ($request->requestItemType == "brand") {
            $data['brand_id'] = $request->requestItem;
            $data['item'] = "brand";
        }
        if ($request->requestItemType == "tag") {
            $data['tag_id'] = $request->requestItem;
            $data['item'] = "tag";
        }
        if ($request->requestItemType == 'product') {
            $data['item'] = 'product';
            $data['section_name'] = $request->requestItem;
        }
        if ($request->requestItemType == "search") {
            $data['keyword'] = $request->requestItem;
            $data['item'] = "search";
        }
        if ($request->has('sort_by')) {
            $data['sort_by'] = $request->sort_by;
        }
        if ($request->has('paginate')) {
            $data['paginate'] = $request->paginate;
        }
        return view(theme('partials.listing_paginate_data'), $data);
    }

    public function productByCategory($slug = null)
    {
        if($slug == null){
            return redirect()->to('category/all-products');
        }

        $paginate = 12;
        $data = [];

        if (request()->has('sort_by')) {
            $sort_by = request()->sort_by;
            $data['sort_by'] = request()->sort_by;
        }
        if (request()->has('paginate_id')) {
            $paginate = request()->paginate_id;
            $data['paginate_id'] = request()->paginate_id;
        }

        $item = isset(request()->item) ? request()->item : '';

        if (isset($slug) && $slug != null && $item == '' && $slug != 'all-products') {
            $catRepo = new CategoryRepository(new Category());
            $category = $catRepo->findBySlug($slug);
            if ($category) {
                $category_id = $category->id;

                $data['CategoryList'] = $catRepo->subcategory($category_id);
                $data['filter_name'] = $catRepo->show($category_id);
                $category_ids = $catRepo->getAllSubSubCategoryID($category_id);


                $category_ids[] = $category_id;

                $product_sellers = SellerProduct::with('skus', 'product')->where('seller_products.status', 1)->select("seller_products.*")
                    ->join('products', function ($query) use ($category_id) {
                        return $query->on('products.id', '=', 'seller_products.product_id')->where(['products.status' => 1, 'products.available_only_single_user' => 0])
                            ->join('category_product', function ($q) use ($category_id) {
                                return $q->on('products.id', '=', 'category_product.product_id')->where('category_product.category_id', $category_id)->join('categories', function ($q2) use ($category_id) {
                                    return $q2->on('category_product.category_id', '=', 'categories.id')->orOn('category_product.category_id', '=', 'categories.parent_id');
                                });
                            });
                    })->distinct('seller_products.id');

                $sort = 'desc';
                $column = 'created_at';
                if (in_array(request()->get('sort_by'), ['old', 'alpha_asc', 'low_to_high'])) {
                    $sort = 'asc';
                }
                if (in_array(request()->get('sort_by'), ['alpha_asc', 'alpha_desc'])) {
                    $column = 'product_name';
                } elseif (request()->get('sort_by') == "low_to_high") {
                    $column = 'min_sell_price';
                } elseif (request()->get('sort_by') == "high_to_low") {
                    $column = 'max_sell_price';
                }
                if (get_class($product_sellers) == \Illuminate\Database\Eloquent\Builder::class) {
                    $product_sellers = $product_sellers->orderBy($column, $sort);
                } else {
                    if ($sort == 'asc') {
                        $product_sellers = $product_sellers->sortBy($column);
                    } else {
                        $product_sellers = $product_sellers->sortByDesc($column);
                    }

                }

                $data['sort_by'] = request()->has('sort_by') ? request()->get('sort_by') : 'new';

                $product_min_price = $this->filterService->filterProductMinPrice($product_sellers->pluck('id'));
                $product_max_price = $this->filterService->filterProductMaxPrice($product_sellers->pluck('id'));
                $product_min_price = $this->filterService->getConvertedMin($product_min_price);
                $product_max_price = $this->filterService->getConvertedMax($product_max_price);
                $data['min_price_lowest'] = $product_min_price;
                $data['max_price_highest'] = $product_max_price;

                $attributeRepo = new AttributeRepository;
                $data['attributeLists'] = $attributeRepo->getAttributeForSpecificCategory($category_id, $category_ids);
                $data['category_id'] = $category_id;
                $data['color'] = $attributeRepo->getColorAttributeForSpecificCategory($category_id, $category_ids);
                $data['products'] = $product_sellers->paginate(($paginate != null) ? $paginate : 12);

                if(isset($_GET['page']) && $_GET['page'] > $data['products']->lastPage()) {
                    $last_page = $data['products']->lastPage();

                    \Illuminate\Pagination\Paginator::currentPageResolver(function () use ($last_page) {
                        return $last_page;
                    });

                    $data['products'] = $product_sellers->paginate($paginate);
                }
            }
        } else if (isset($slug) && $slug != null && $item == '' && $slug == 'all-products') {
            $product_sellers = SellerProduct::with('skus', 'product')->where('seller_products.status', 1)->select("seller_products.*")
                ->join('products', function ($query) {
                    return $query->on('products.id', '=', 'seller_products.product_id')->where(['products.status' => 1, 'products.available_only_single_user' => 0]);
                })->distinct('seller_products.id');

            $sort = 'desc';
            $column = 'created_at';
            if (in_array(request()->get('sort_by'), ['old', 'alpha_asc', 'low_to_high'])) {
                $sort = 'asc';
            }
            if (in_array(request()->get('sort_by'), ['alpha_asc', 'alpha_desc'])) {
                $column = 'product_name';
            } elseif (request()->get('sort_by') == "low_to_high") {
                $column = 'min_sell_price';
            } elseif (request()->get('sort_by') == "high_to_low") {
                $column = 'max_sell_price';
            }
            if (get_class($product_sellers) == \Illuminate\Database\Eloquent\Builder::class) {
                $product_sellers = $product_sellers->orderBy($column, $sort);
            } else {
                if ($sort == 'asc') {
                    $product_sellers = $product_sellers->sortBy($column);
                } else {
                    $product_sellers = $product_sellers->sortByDesc($column);
                }

            }

            $data['sort_by'] = request()->has('sort_by') ? request()->get('sort_by') : 'new';

            $product_min_price = $this->filterService->filterProductMinPrice($product_sellers->pluck('id'));
            $product_max_price = $this->filterService->filterProductMaxPrice($product_sellers->pluck('id'));
            $product_min_price = $this->filterService->getConvertedMin($product_min_price);
            $product_max_price = $this->filterService->getConvertedMax($product_max_price);
            $data['min_price_lowest'] = $product_min_price;
            $data['max_price_highest'] = $product_max_price;

            $data['products'] = $product_sellers->paginate(($paginate != null) ? $paginate : 12);

            if(isset($_GET['page']) && $_GET['page'] > $data['products']->lastPage()) {
                $last_page = $data['products']->lastPage();

                \Illuminate\Pagination\Paginator::currentPageResolver(function () use ($last_page) {
                    return $last_page;
                });
                $data['products'] = $product_sellers->paginate($paginate);
            }
        }

        if($item != ''){
            if ($item == 'search') {
                $searchTerm = SearchTerm::where('keyword', $slug)->first();
                if ($searchTerm) {
                    $count = $searchTerm->count;
                    $searchTerm->count = 1 + $count;

                    $searchTerm->save();
                } else {
                    SearchTerm::create(['keyword' => $slug, 'count' => 1]);
                }

                $data['filter_name'] = "Search Query : " . "\" " . $slug . " \" ";

                $slugs = explode(' ', $slug);

                $mainProducts = Product::whereHas('tags', function ($q) use ($slugs) {

                    return $q->where(function ($q) use ($slugs) {
                        foreach ($slugs as $slug) {
                            $q = $q->orWhere('name', 'LIKE', "%{$slug}%");
                        }
                        return $q;
                    });

                })->pluck('id')->toArray();

                $giftCards = GiftCard::where('status', 1)->whereHas('tags', function ($q) use ($slugs) {
                    return $q->where(function ($q) use ($slugs) {
                        foreach ($slugs as $slug) {
                            $q = $q->orWhere('name', 'LIKE', "%{$slug}%");
                        }
                        return $q;
                    });

                })->select(['*', 'name as product_name', 'sku as slug'])->get();


                $category_ids = CategoryProduct::whereIn('product_id', $mainProducts)->distinct()->pluck('category_id');
                $data['CategoryList'] = Category::whereIn('id', $category_ids)->get();
                $data['slug'] = $slug;


                $products = SellerProduct::where('status', 1)->whereHas('product', function ($query) use ($mainProducts, $slug) {
                    return $query->whereIn('id', $mainProducts)->orWhere('product_name', 'LIKE', "%{$slug}%");
                })->orWhere('product_name', 'LIKE', "%{$slug}%")->activeSeller()->get();

                $sort = 'desc';
                $column = 'created_at';
                if (in_array(request()->get('sort_by'), ['old', 'alpha_asc', 'low_to_high'])) {
                    $sort = 'asc';
                }
                if (in_array(request()->get('sort_by'), ['alpha_asc', 'alpha_desc'])) {
                    $column = 'product_name';
                } elseif (request()->get('sort_by') == "low_to_high") {
                    $column = 'min_sell_price';
                } elseif (request()->get('sort_by') == "high_to_low") {
                    $column = 'max_sell_price';
                } elseif (request()->get('sort_by') == "popularity") {
                    $column = 'position';
                }
                if (get_class($products) == \Illuminate\Database\Eloquent\Builder::class) {
                    $products = $products->orderBy($column, $sort);
                } else {
                    if ($sort == 'asc') {
                        $products = $products->sortBy($column);
                    } else {
                        $products = $products->sortByDesc($column);
                    }

                }


                $brandIds = Product::whereIn('id', $mainProducts)->distinct()->pluck('brand_id');
                $data['brandList'] = Brand::whereIn('id', $brandIds)->get();
                $attribute_ids = ProductVariations::whereIn('product_id', $mainProducts)->distinct()->pluck('attribute_id');
                $data['attributeLists'] = Attribute::with('values')->whereIn('id', $attribute_ids)->where('id', '>', 1)->get();
                $data['color'] = Attribute::with('values')->whereIn('id', $attribute_ids)->first();

                $product_min_price = $this->filterService->filterProductMinPrice($products->pluck('id'));
                $product_max_price = $this->filterService->filterProductMaxPrice($products->pluck('id'));
                $giftcard_min_price = $giftCards->min('selling_price');
                $giftcard_max_price = $giftCards->max('selling_price');


                $min_price = $this->filterService->getConvertedMin(min($product_min_price, $giftcard_min_price));
                $max_price = $this->filterService->getConvertedMax(max($product_max_price, $giftcard_max_price));

                $data['min_price_lowest'] = $min_price;
                $data['max_price_highest'] = $max_price;

                $products = $products->merge($giftCards);

                $data['keyword'] = $slug;
//            $data['products'] = $this->filterService->sortAndPaginate($products, $sort_by, $paginate);

                $data['products'] = $products->paginate(($paginate != null) ? $paginate : 12);

                if(isset($_GET['page']) && $_GET['page'] > $data['products']->lastPage()) {
                    $last_page = $data['products']->lastPage();

                    \Illuminate\Pagination\Paginator::currentPageResolver(function () use ($last_page) {
                        return $last_page;
                    });
                    $data['products'] = $products->paginate($paginate);
                }
            }else{
                abort(404);
            }
        }

        $data['slug'] = $slug;

        if (!request()->has('brandd') && !request()->has('sort_by') &&  !request()->has('paginate_id')  && !request()->has('discount') && !request()->has('attribute') && !request()->has('color') && !request()->has('min_price') && !request()->has('max_price')  && !request()->has('max_price')) {
            return view(theme('new.category'), $data);
        } else {
            $listing = view(theme('partials.category_paginate_data'), $data)->render();

            if (isset(request()->type) && request()->type == 'ajax') {
                return response()->json(['search' => $listing]);
            }else{
                return view(theme('new.category'), $data);
            }
        }

//        if (!request()->has('page')) {
//            $data['products']->appends($request->except('page'));
//            if (session()->has('filterDataFromCat')) {
//                session()->forget('filterDataFromCat');
//            }
//            return view(theme('new.category'),$data);
////            return view(theme('pages.listing'),$data);
//        }
//        else {
//            return view(theme('new.category'),$data);
////            return  view(theme('partials.listing_paginate_data'),$data);
//        }
////        return view(theme('new.category'),$data);
    }

    public function get_colors_by_type(Request $request)
    {
        if ($request->type == "cat") {
            $catRepo = new CategoryRepository(new Category());
            $category_ids = $catRepo->getAllSubSubCategoryID($request->id);
            $attributeRepo = new AttributeRepository;
            $data['color'] = $attributeRepo->getColorAttributeForSpecificCategory($request->id, $category_ids);
        }
        if ($request->type == "brand") {
            $attributeRepo = new AttributeRepository;
            $data['color'] = $attributeRepo->getColorAttributeForSpecificBrand($request->id);
        }
        return view(theme('partials.color_attribute'), $data);
    }

    public function get_brand_by_type(Request $request)
    {
        if ($request->type == "cat") {
            $catRepo = new CategoryRepository(new Category());
            $category_ids = $catRepo->getAllSubSubCategoryID($request->id);
            $brandRepo = new BrandRepository;
            $data['brandList'] = $brandRepo->getBrandForSpecificCategory($request->id, $category_ids);
        }
        return view(theme('partials.brand'), $data);
    }

    public function get_attribute_by_type(Request $request)
    {
        if ($request->type == "cat") {
            $catRepo = new CategoryRepository(new Category());
            $category_ids = $catRepo->getAllSubSubCategoryID($request->id);
            $attributeRepo = new AttributeRepository;
            $data['attributeLists'] = $attributeRepo->getAttributeForSpecificCategory($request->id, $category_ids);
        }
        if ($request->type == "brand") {
            $attributeRepo = new AttributeRepository;
            $data['attributeLists'] = $attributeRepo->getAttributeForSpecificBrand($request->id);
        }
        return view(theme('partials.attributes'), $data);
    }
}
