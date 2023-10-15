<?php

namespace Modules\Product\Repositories;

use App\Models\Cart;
use App\Models\MediaManager;
use App\Models\UsedMedia;
use App\Models\Wishlist;
use App\Traits\GenerateSlug;
use Illuminate\Support\Facades\Auth;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductGalaryImage;
use Modules\Product\Entities\DigitalFile;
use Modules\Product\Entities\ProductCrossSale;
use Modules\Product\Entities\ProductRelatedSale;
use Modules\Product\Entities\ProductSku;
use Modules\Product\Entities\ProductTag;
use Modules\Product\Entities\ProductUpSale;
use Modules\Product\Entities\ProductVariations;
use Modules\Seller\Entities\SellerProduct;
use Modules\Seller\Entities\SellerProductSKU;
use Modules\Shipping\Entities\ProductShipping;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Product\Imports\ProductImport;
use App\Traits\ImageStore;
use App\Traits\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Modules\GeneralSetting\Entities\EmailTemplateType;
use Modules\Product\Entities\CategoryProduct;
use Modules\Setup\Entities\Tag;

class ProductRepository
{
    use ImageStore, Notification ,GenerateSlug;

    public function getAll()
    {
        if(request()->has('id')){
            $user = Auth::user();
            if ($user->role->type == 'superadmin' || $user->role->type == 'admin' || $user->role->type == 'staff') {
                return Product::with('brand')->where('is_approved', 1)->where('discount', '!=',0)->latest();
            } else {
                return Product::with('brand')->where('created_by', $user->id)->where('discount', '!=',0)->latest();
            }
        }

            $user = Auth::user();
            if ($user->role->type == 'superadmin' || $user->role->type == 'admin' || $user->role->type == 'staff') {
                return Product::with('brand')->where('is_approved', 1)->latest();
            } else {
                return Product::with('brand')->where('created_by', $user->id)->latest();
            }



    }


    public function getProduct()
    {
        if(request()->has('id')){
            return  Product::with('brand')->where('is_approved', 1)->where('discount', '!=',0)->get();
        }
        return  Product::with('brand')->where('is_approved', 1)->get();
    }

    public function allbyPaginate()
    {
        $user = Auth::user();
        if ($user->role->type == 'superadmin' || $user->role->type == 'admin' || $user->role->type == 'staff') {
            return Product::where('is_approved', 1)->latest()->paginate(10);
        } else {
            return Product::where('created_by', $user->id)->latest()->paginate(10);
        }
    }

    public function getAllForEdit($id)
    {
        return Product::where('id', '!=', $id)->latest()->get();
    }

    public function getAllSKU()
    {
        return ProductSku::with('product', 'product.brand');
    }

    public function create(array $data)
    {
//        dd($data);
        $data['images_alt'] = implode(',', $data['images_alt']);

        $product = new Product();
        $user = Auth::user();
        if ($user->role->type == 'superadmin' || $user->role->type == 'admin' || $user->role->type == 'staff') {
            $data['is_approved'] = 1;
            $data['requested_by'] = $user->role_id;
        } else {
            $data['is_approved'] = 0;
            $data['requested_by'] = $user->role_id;
        }
        if ($data['is_physical'] == 0) {
            $data['is_physical'] = 0;
            $data['shipping_type'] = 1;
            $data['shipping_cost'] = 0;
            $digital_product = new DigitalFile();
        }
        if($data['max_order_qty'] != null && $data['max_order_qty'] < 1){
            $data['max_order_qty'] = null;
        }
        if(isset($data['gst_group'])){
            $data['gst_group_id'] = $data['gst_group'];
        }
        $product->fill($data)->save();
        if(isset($data['meta_image'])){
            UsedMedia::create([
                'media_id' => $data['meta_image_id'],
                'usable_id' => $product->id,
                'usable_type' => get_class($product),
                'used_for' => 'meta_image'
            ]);
        }

        // send notification from seller request
        if(isModuleActive('MultiVendor') && $data['request_from'] == 'seller_product_form'){
            $notificationUrl = route('seller.product.index');
            $notificationUrl = str_replace(url('/'),'',$notificationUrl);
            $this->notificationUrl = $notificationUrl;
            $this->adminNotificationUrl = '/products';
            $this->routeCheck = 'product.index';
            $this->typeId = EmailTemplateType::where('type', 'product_approve_email_template')->first()->id;
            $this->notificationSend("Seller product create", $product->created_by);
        }

        $tags = [];
        $tags = explode(',', $data['tags']);

        foreach ($tags as $key => $tag) {
            $tag = Tag::where('name', $tag)->updateOrCreate([
                'name' => strtolower($tag)
            ]);
            ProductTag::create([
                'product_id' => $product->id,
                'tag_id' => $tag->id,
            ]);
        }

        if (isset($data['category_ids'])) {
            foreach ($data['category_ids'] as $category) {
                CategoryProduct::create([
                    'category_id' => $category,
                    'product_id' => $product->id
                ]);
            }
        }
        if ($data['galary_image'] != null) {
            $media_ids = explode(',',$data['media_ids']);
            foreach ($data['galary_image'] as $i => $image) {
                $product_galary_image = new ProductGalaryImage;
                $product_galary_image->product_id = $product->id;
                $product_galary_image->images_source = $image;
                $product_galary_image->media_id = $media_ids[$i];
                $product_galary_image->save();
            }
        }



        if ($data['product_type'] == 1) {
            $product_sku = new ProductSku;
            $product_sku->product_id = $product->id;
            $product_sku->sku = $data['sku'];
            $product_sku->weight = isset($data['weight'])?$data['weight']:0;
            $product_sku->length = isset($data['length'])?$data['length']:0;
            $product_sku->breadth = isset($data['breadth'])?$data['breadth']:0;
            $product_sku->height = isset($data['height'])?$data['height']:0;
            $product_sku->selling_price = $data['selling_price'];
            $stock = 0;
            if (!isModuleActive('MultiVendor')) {
                if ($data['stock_manage'] == 1) {
                    $stock = $data['single_stock'];
                }
            }
            $product_sku->additional_shipping = $data['additional_shipping'];
            $product_sku->status = ($user->role->type == 'superadmin' || $user->role->type == 'admin' || $user->role->type == 'staff') ? $data['status'] : 0;
            $product_sku->product_stock = $stock;
            $product_sku->save();
            if ($data['is_physical'] == 0 && isset($data['file_source'])) {
                $digital_product->create([
                    'product_sku_id' => $product_sku->id,
                    'file_source' => $data['file_source'],
                ]);
            }
        }
        if ($data['product_type'] == 2) {

            $words = explode(" ", $product->product_name);
            $acronym = "";
            foreach ($words as $w) {
                $acronym .= $w[0];
            }

            foreach ($data['track_sku'] as $key => $variant_sku) {
                $product_sku = new ProductSku;
                $product_sku->product_id = $product->id;
                $product_sku->sku = $data['sku'][$key];
                $product_sku->weight = isset($data['weight'])?$data['weight']:0;
                $product_sku->length = isset($data['length'])?$data['length']:0;
                $product_sku->breadth = isset($data['breadth'])?$data['breadth']:0;
                $product_sku->height = isset($data['height'])?$data['height']:0;

                $track_sku = explode("-", $data['track_sku'][$key]);
                $orginal_track_sku = $acronym;
                foreach($track_sku as $i => $t){
                    if($i > 0){
                        $orginal_track_sku .= '-'.$t;
                    }
                }

                $product_sku->track_sku = $orginal_track_sku;

                $product_sku->selling_price = $data['selling_price_sku'][$key];

                $product_sku->additional_shipping = $data['additional_shipping'];
                $image_increment = $key + 1;
                $media_img = null;
                if (isset($data['variant_image_' . $image_increment])) {
                    $media_img = MediaManager::find($data['variant_image_' . $image_increment]);
                    if($media_img){
                        if($media_img->storage == 'local'){
                            $file = asset_path($media_img->file_name);
                        }else{
                            $file = $media_img->file_name;
                        }
                        $variant_image = ImageStore::saveImage($file,600,545);
                        $product_sku->variant_image = $variant_image;
                    }
                } else {
                    $product_sku->variant_image = null;
                }
                $product_sku->status = ($user->role->type == 'admin' || $user->role->type == 'superadmin' || $user->role->type == 'staff') ? $data['status'] : 0;

                $stock = 0;
                if (!isModuleActive('MultiVendor')) {
                    if ($data['stock_manage'] == 1) {
                        $stock = $data['sku_stock'][$key];
                    }
                }

                $product_sku->product_stock = $stock;

                $product_sku->save();
                if (isset($data['variant_image_' . $image_increment])) {
                    UsedMedia::create([
                        'media_id' => $media_img->id,
                        'usable_id' => $product_sku->id,
                        'usable_type' => get_class($product_sku),
                        'used_for' => 'variant_image'
                    ]);
                }

                if ($data['is_physical'] == 0 && $data['file_source'][$key]) {
                    $digital_product->create([
                        'product_sku_id' => $product_sku->id,
                        'file_source' => $data['file_source'][$key],
                    ]);
                }
                $attribute_id = explode('-', $data['str_attribute_id'][0]);
                $attribute_value_id = explode('-', $data['str_id'][$key]);
                foreach ($attribute_value_id as $k => $value) {
                    $product_variation = new ProductVariations;
                    $product_variation->product_id = $product->id;
                    $product_variation->product_sku_id = $product_sku->id;
                    $product_variation->attribute_id = $attribute_id[$k];
                    $product_variation->attribute_value_id = $attribute_value_id[$k];
                    $product_variation->save();
                }
            }
        }

        if (isset($data['related_product'])) {

            foreach ($data['related_product'] as $key => $item) {
                ProductRelatedSale::create([
                    'product_id' => $product->id,
                    'related_sale_product_id' => $item
                ]);
            }
        }
        if (isset($data['up_sale'])) {
            foreach ($data['up_sale'] as $key => $item) {
                ProductUpSale::create([
                    'product_id' => $product->id,
                    'up_sale_product_id' => $item
                ]);
            }
        }
        if (isset($data['cross_sale'])) {
            foreach ($data['cross_sale'] as $key => $item) {
                ProductCrossSale::create([
                    'product_id' => $product->id,
                    'cross_sale_product_id' => $item
                ]);
            }
        }

        if (auth()->user()->role->type == 'superadmin' || auth()->user()->role->type == 'admin' || auth()->user()->role->type == 'staff') {
            $status = 0;
            if (isset($data['save_type'])) {
                if ($data['save_type'] == 'save_publish') {
                    $status = 1;
                }
            }
            $sellerProductName = $product->product_name;


            $sellerProduct = SellerProduct::create([
                'product_id' => $product->id,
                'product_name' => $product->product_name,
                'file_type' => isset($data['file_type'])?implode(',',$data['file_type']):null,
                'stock_manage' => (!isModuleActive('MultiVendor') && isset($data['stock_manage'])) ? $data['stock_manage'] : 0,
                'tax' => 0,
                'tax_type' => 0,
                'discount' => $product->discount,
                'discount_type' => $product->discount_type,
                'is_digital' => ($product->is_physical == 0) ? 0 : 1,
                'user_id' => 1,
                'slug' => $this->productSlug($sellerProductName),
                'is_approved' => 1,
                'status' => isModuleActive('MultiVendor') ? $status : $data['status']
            ]);

            $product_skus = ProductSku::where('product_id', $product->id)->get();
            foreach ($product_skus as $key => $item) {

                SellerProductSKU::create([
                    'product_id' => $sellerProduct->id,
                    'product_sku_id' => $item->id,
                    'product_stock' => $item->product_stock,
                    'selling_price' => $item->selling_price,
                    'status' => 1,
                    'user_id' => 1
                ]);

                $sellerProduct->update([
                    'min_sell_price' => $sellerProduct->skus->min('selling_price'),
                    'max_sell_price' => $sellerProduct->skus->max('selling_price')
                ]);
            }
        }
        return true;
    }

    public function find($id)
    {
        return Product::findOrFail($id);
    }

    public function findProductSkuById($id)
    {
        return ProductSku::findOrFail($id);
    }

    public function update(array $data, $id)
    {
        $data['images_alt'] = implode(',', $data['images_alt']);

        $product = Product::findOrFail($id);
        if($data['max_order_qty'] != null && $data['max_order_qty'] < 1){
            $data['max_order_qty'] = null;
        }
        if(isset($data['gst_group'])){
            $data['gst_group_id'] = $data['gst_group'];
        }
        $prev_isphysical = $product->is_physical;
        $product->update($data);

        if (!isModuleActive('MultiVendor')) {
            $product->sellerProducts->where('user_id', 1)->first()->update([
                'product_name' => $product->product_name,
                'status' => $product->status,
                'discount' => $product->discount,
                'discount_type' => $product->discount_type,
                'tax' => $product->tax,
                'tax_type' => $product->tax_type,
                'slug' => $this->productSlug($product->product_name),
//                'min_sell_price' => $data['selling_price'],
//                'max_sell_price' => $data['selling_price']
            ]);
        }

        //for tag start
        $tags = [];
        $tags = explode(',', $data['tags']);
        $oldtags = ProductTag::where('product_id', $id)->whereHas('tag', function ($q) use ($tags) {
            $q->whereNotIn('name', $tags);
        })->pluck('id');
        ProductTag::destroy($oldtags);

        foreach ($tags as $key => $tag) {
            $tag = Tag::where('name', $tag)->updateOrCreate([
                'name' => strtolower($tag)
            ]);
            ProductTag::where('product_id', $product->id)->where('tag_id', $tag->id)->updateOrCreate([
                'product_id' => $product->id,
                'tag_id' => $tag->id,
            ]);
        }
        // for tag end

        if (isset($data['category_ids'])) {
            $deleted_cats = CategoryProduct::where('product_id', $id)->whereNotIn('category_id', $data['category_ids'])->pluck('id');
            CategoryProduct::destroy($deleted_cats);
            foreach ($data['category_ids'] as $category) {
                CategoryProduct::where('product_id', $id)->updateOrCreate([
                    'product_id' => $id,
                    'category_id' => $category
                ]);
            }
        }

        if(!empty($data['file_type'])){
            $a = implode(',',$data['file_type']);
            $product->sellerProducts[0]->file_type = $a;
            $product->sellerProducts[0]->save();
        }



        if ($product->product_type == 1) {
            $product_sku = $product->skus->first();
            $product_sku->product_id = $product->id;
            $product_sku->sku = $data['sku'];
            $product_sku->weight = isset($data['weight'])?$data['weight']:0;
            $product_sku->length = isset($data['length'])?$data['length']:0;
            $product_sku->breadth = isset($data['breadth'])?$data['breadth']:0;
            $product_sku->height = isset($data['height'])?$data['height']:0;

            if($product_sku->selling_price != $data['selling_price']){
                $ids = \App\Models\Paymant_products::where('product_id1',$product->id)->where('user_id','')->pluck('id');
                \App\Models\Paymant_products::destroy($ids);
            }

            $product_sku->selling_price = $data['selling_price'];
            $product_sku->product_stock = isset($data['single_stock'])?$data['single_stock']:0;


            $product_sku->additional_shipping = isset($data['additional_shipping']) ? $data['additional_shipping'] : 0;

            $product_sku->status = $data['status'];
            $product_sku->save();
            if (!isModuleActive('MultiVendor')) {
                $product->sellerProducts->where('user_id', 1)->first()->skus->first()->update([
                    'selling_price' => $data['selling_price'],
                    'product_stock' => isset($data['single_stock'])?$data['single_stock']:0
                ]);
            }



            if ($data['is_physical'] == 0 && !empty($data['digital_file'])) {
                $name = uniqid() . $data['digital_file']->getClientOriginalName();
                $data['digital_file']->move(public_path() . '/uploads/digital_file/', $name);
                $data['file_source'] = '/uploads/digital_file/' . $name;

                DigitalFile::where('product_sku_id', $product_sku->id)->updateOrCreate([
                    'product_sku_id' => $product_sku->id,
                    'file_source' => $data['file_source']
                ]);
            }




        } else {


            if(product_attribute_editable($product->id) === false){
                foreach ($data['track_sku'] as $key => $variant_sku) {

                    $sku_exist = ProductSku::where('sku', $data['sku'][$key])->orWhere('track_sku', $data['track_sku'][$key])->first();
                    if ($sku_exist == null) {
                        $product_sku = new ProductSku;
                        $product_sku->product_id = $product->id;
                        $product_sku->sku = $data['sku'][$key];
                        $product_sku->weight = isset($data['weight'])?$data['weight']:0;
                        $product_sku->length = isset($data['length'])?$data['length']:0;
                        $product_sku->breadth = isset($data['breadth'])?$data['breadth']:0;
                        $product_sku->height = isset($data['height'])?$data['height']:0;
                        $product_sku->track_sku = $data['track_sku'][$key];

                        $product_sku->selling_price = $data['selling_price_sku'][$key];


                        $product_sku->additional_shipping = isset($data['additional_shipping']) ? $data['additional_shipping'] : 0;


                        $image_increment = $key + 1;
                        $media_img = null;
                        if (isset($data['variant_image_' . $image_increment])) {

                            $media_img = MediaManager::find($data['variant_image_' . $image_increment]);
                            if($media_img){
                                if($media_img->storage == 'local'){
                                    $file = asset_path($media_img->file_name);
                                }else{
                                    $file = $media_img->file_name;
                                }
                                $variant_image = ImageStore::saveImage($file,600,545);
                                $product_sku->variant_image = $variant_image;
                            }
                        }

                        $product_sku->status = $data['status'];

                        $stock = 0;
                        if (!isModuleActive('MultiVendor')) {
                            if ($data['stock_manage'] == 1) {
                                if ($data['product_type'] == 1) {
                                    $stock = isset($data['single_stock'])?$data['single_stock']:0;
                                } else {
                                    $stock = isset($data['sku_stock'])?$data['sku_stock'][$key]:0;
                                }
                            }
                        }
                        $product_sku->product_stock = $stock;

                        $product_sku->save();

                        if (isset($data['variant_image_' . $image_increment])) {
                            UsedMedia::create([
                                'media_id' => $media_img->id,
                                'usable_id' => $product_sku->id,
                                'usable_type' => get_class($product_sku),
                                'used_for' => 'variant_image'
                            ]);
                        }

                        if(!isModuleActive("MultiVendor")){
                            $sellerProduct = $product->sellerProducts->where('user_id', 1)->first();
                            if($sellerProduct){
                                SellerProductSKU::create([
                                    'product_id' => $sellerProduct->id,
                                    'product_sku_id' => $product_sku->id,
                                    'product_stock' => $product_sku->product_stock,
                                    'selling_price' => $product_sku->selling_price,
                                    'status' => 1,
                                    'user_id' => 1
                                ]);
                            }

                        }

                        if ($data['is_physical'] == 0 && !empty($data['digital_file']) && isset($data['digital_file'][$key])) {
                            $name = uniqid() . $data['digital_file'][$key]->getClientOriginalName();
                            $data['digital_file'][$key]->move(public_path() . '/uploads/digital_file/', $name);
                            $file_source = '/uploads/digital_file/' . $name;

                            DigitalFile::where('product_sku_id', $product_sku->id)->updateOrCreate([
                                'file_source' => $file_source,
                                'product_sku_id' => $product_sku->id
                            ]);
                        }

                        $attribute_id = explode('-', $data['str_attribute_id'][0]);
                        $attribute_value_id = explode('-', $data['str_id'][$key]);
                        foreach ($attribute_value_id as $k => $value) {
                            $product_variation = new ProductVariations;
                            $product_variation->product_id = $product->id;
                            $product_variation->product_sku_id = $product_sku->id;
                            $product_variation->attribute_id = $attribute_id[$k];
                            $product_variation->attribute_value_id = $attribute_value_id[$k];
                            $product_variation->save();
                        }
                    } else {
                        $sku_exist->sku = $data['sku'][$key];

                        $words = explode(" ", $product->product_name);
                        $acronym = "";

                        foreach ($words as $w) {
                            $acronym .= $w[0];
                        }
                        $track_sku = explode("-", $data['track_sku'][$key]);
                        $orginal_track_sku = $acronym;
                        foreach($track_sku as $i => $t){
                            if($i > 0){
                                $orginal_track_sku .= '-'.$t;
                            }
                        }

                        $sku_exist->track_sku = $orginal_track_sku;

                        $sku_exist->selling_price = $data['selling_price_sku'][$key];


                        $sku_exist->additional_shipping = isset($data['additional_shipping']) ? $data['additional_shipping'] : 0;

                        $image_increment = $key + 1;
                        if (isset($data['variant_image_' . $image_increment]) && @$sku_exist->variant_image_media->media_id != $data['variant_image_' . $image_increment]) {

                            $media_img = MediaManager::find($data['variant_image_' . $image_increment]);
                            if($media_img){
                                if($media_img->storage == 'local'){
                                    $file = asset_path($media_img->file_name);
                                }else{
                                    $file = $media_img->file_name;
                                }
                                ImageStore::deleteImage($sku_exist->variant_image);
                                $variant_image = ImageStore::saveImage($file,600,545);
                                $sku_exist->variant_image = $variant_image;
                            }

                            if(@$sku_exist->variant_image_media){
                                $sku_exist->variant_image_media->update([
                                    'media_id' => $media_img->id
                                ]);
                            }else{
                                UsedMedia::create([
                                    'media_id' => $media_img->id,
                                    'usable_id' => $sku_exist->id,
                                    'usable_type' => get_class($sku_exist),
                                    'used_for' => 'variant_image'
                                ]);
                            }

                        }else{
                            if($sku_exist->variant_image_media != null && !isset($data['variant_image_' . $image_increment])){
                                $this->deleteImage($sku_exist->variant_image);
                                $sku_exist->variant_image_media->delete();
                            }
                        }

                        $sku_exist->status = $data['status'];

                        $stock = 0;
                        if (!isModuleActive('MultiVendor')) {
                            if ($data['stock_manage'] == 1) {
                                if ($data['product_type'] == 1) {
                                    $stock = isset($data['single_stock'])?$data['single_stock']:0;
                                } else {
                                    $stock = isset($data['sku_stock'])? $data['sku_stock'][$key]:0;
                                }
                            }
                        }
                        $sku_exist->product_stock = $stock;
                        $sku_exist->save();

                        if (!isModuleActive('MultiVendor')) {
                            $product->sellerProducts->where('user_id', 1)->first()->skus->where('product_sku_id', $sku_exist->id)->first()->update([
                                'product_stock' => $sku_exist->product_stock,
                                'selling_price' => $sku_exist->selling_price
                            ]);
                        }
                        // for upload digital file
                        if ($data['is_physical'] == 0 && !empty($data['digital_file']) && isset($data['digital_file'][$key])) {

                            $name = uniqid() . $data['digital_file'][$key]->getClientOriginalName();
                            $data['digital_file'][$key]->move(public_path() . '/uploads/digital_file/', $name);
                            $file_source = '/uploads/digital_file/' . $name;
                            if(@$sku_exist->digital_file && File::exists(asset_path(@$sku_exist->digital_file->file_source))){
                                File::delete(asset_path(@$sku_exist->digital_file->file_source));
                            }
                            DigitalFile::where('product_sku_id', $sku_exist->id)->updateOrCreate([
                                'file_source' => $file_source,
                                'product_sku_id' => $sku_exist->id
                            ]);
                        }
                    }
                }
            }else{
                $old_skus = $product->skus;
                $old_sku_ids = [];
                foreach($old_skus as $sku){
                    if($sku->variant_image_media != null){
                        $sku->variant_image_media->delete();
                    }
                    if($sku->variant_image){
                        $this->deleteImage($sku->variant_image);
                    }
                    $old_sku_ids[] = $sku->id;
                }
                ProductSku::destroy($old_sku_ids);
                $product_variations = $product->variations->pluck('id');
                ProductVariations::destroy($product_variations);
                $sellerProduct = $product->sellerProducts->where('user_id', 1)->first();
                if($sellerProduct){
                    $old_frontend_sku = $sellerProduct->skus->pluck('id');
                    SellerProductSKU::destroy($old_frontend_sku);
                }


                foreach ($data['track_sku'] as $key => $variant_sku) {

                    $words = explode(" ", $product->product_name);
                    $acronym = "";

                    foreach ($words as $w) {
                        $acronym .= $w[0];
                    }
                    $track_sku = explode("-", $data['track_sku'][$key]);
                    $orginal_track_sku = $acronym;
                    foreach($track_sku as $i => $t){
                        if($i > 0){
                            $orginal_track_sku .= '-'.$t;
                        }
                    }

                    $product_sku = new ProductSku;
                    $product_sku->product_id = $product->id;
                    $product_sku->sku = $data['sku'][$key];
                    $product_sku->weight = isset($data['weight'])?$data['weight']:0;
                    $product_sku->length = isset($data['length'])?$data['length']:0;
                    $product_sku->breadth = isset($data['breadth'])?$data['breadth']:0;
                    $product_sku->height = isset($data['height'])?$data['height']:0;
                    $product_sku->track_sku = $orginal_track_sku;

                    $product_sku->selling_price = $data['selling_price_sku'][$key];


                    $product_sku->additional_shipping = isset($data['additional_shipping']) ? $data['additional_shipping'] : 0;


                    $image_increment = $key + 1;
                    $media_img = null;
                    if (isset($data['variant_image_' . $image_increment])) {
                        // $variant_image = ImageStore::saveImage($data['variant_image_' . $image_increment], 600,545);
                        // $product_sku->variant_image = $variant_image;
                        $media_img = MediaManager::find($data['variant_image_' . $image_increment]);
                        if($media_img){
                            if($media_img->storage == 'local'){
                                $file = asset_path($media_img->file_name);
                            }else{
                                $file = $media_img->file_name;
                            }
                            $variant_image = ImageStore::saveImage($file,600,545);
                            $product_sku->variant_image = $variant_image;
                        }
                    }

                    $product_sku->status = $data['status'];

                    $stock = 0;
                    if (!isModuleActive('MultiVendor')) {
                        if ($data['stock_manage'] == 1) {
                            if ($data['product_type'] == 1) {
                                $stock = isset($data['single_stock'])?$data['single_stock']:0;
                            } else {
                                $stock = isset($data['sku_stock'])?$data['sku_stock'][$key]:0;
                            }
                        }
                    }
                    $product_sku->product_stock = $stock;

                    $product_sku->save();

                    if (isset($data['variant_image_' . $image_increment])) {
                        UsedMedia::create([
                            'media_id' => $media_img->id,
                            'usable_id' => $product_sku->id,
                            'usable_type' => get_class($product_sku),
                            'used_for' => 'variant_image'
                        ]);
                    }

                    if($sellerProduct){
                        SellerProductSKU::create([
                            'product_id' => $sellerProduct->id,
                            'product_sku_id' => $product_sku->id,
                            'product_stock' => $product_sku->product_stock,
                            'selling_price' => $product_sku->selling_price,
                            'status' => 1,
                            'user_id' => 1
                        ]);
                    }

                    if ($data['is_physical'] == 0 && !empty($data['digital_file']) && isset($data['digital_file'][$key])) {
                        $name = uniqid() . $data['digital_file'][$key]->getClientOriginalName();
                        $data['digital_file'][$key]->move(public_path() . '/uploads/digital_file/', $name);
                        $file_source = '/uploads/digital_file/' . $name;

                        DigitalFile::where('product_sku_id', $product_sku->id)->updateOrCreate([
                            'file_source' => $file_source,
                            'product_sku_id' => $product_sku->id
                        ]);
                    }

                    $attribute_id = explode('-', $data['str_attribute_id'][0]);
                    $attribute_value_id = explode('-', $data['str_id'][$key]);
                    foreach ($attribute_value_id as $k => $value) {
                        $product_variation = new ProductVariations;
                        $product_variation->product_id = $product->id;
                        $product_variation->product_sku_id = $product_sku->id;
                        $product_variation->attribute_id = $attribute_id[$k];
                        $product_variation->attribute_value_id = $attribute_value_id[$k];
                        $product_variation->save();
                    }
                }
            }



        }
        if (isset($data['related_product'])) {
            $oldproduct = ProductRelatedSale::where('product_id', $id)->whereNotIn('related_sale_product_id', $data['related_product'])->pluck('id');
            if (count($oldproduct) > 0) {
                ProductRelatedSale::destroy($oldproduct);
            }
            foreach ($data['related_product'] as $key => $item) {
                ProductRelatedSale::where('product_id', $id)->updateOrCreate([
                    'product_id' => $id,
                    'related_sale_product_id' => $item
                ]);
            }
        }
        if (isset($data['up_sale'])) {
            $oldproduct = ProductUpSale::where('product_id', $id)->whereNotIn('up_sale_product_id', $data['up_sale'])->pluck('id');
            if (count($oldproduct) > 0) {
                ProductUpSale::destroy($oldproduct);
            }
            foreach ($data['up_sale'] as $key => $item) {
                ProductUpSale::where('product_id', $id)->updateOrCreate([
                    'product_id' => $id,
                    'up_sale_product_id' => $item
                ]);
            }
        }
        if (isset($data['cross_sale'])) {
            $oldproduct = ProductCrossSale::where('product_id', $id)->whereNotIn('cross_sale_product_id', $data['cross_sale'])->pluck('id');
            if (count($oldproduct) > 0) {
                ProductCrossSale::destroy($oldproduct);
            }
            foreach ($data['cross_sale'] as $key => $item) {
                ProductCrossSale::where('product_id', $id)->updateOrCreate([
                    'product_id' => $id,
                    'cross_sale_product_id' => $item
                ]);
            }
        }

        if (!isModuleActive('MultiVendor')) {
            $frontend_product = $product->sellerProducts->where('user_id', 1)->first();
            $frontend_product->update([
                'stock_manage' => $data['stock_manage']
            ]);
        }

        if(auth()->user()->role->type == 'seller' && isModuleActive('MultiVendor')){
            $notificationUrl = route('seller.product.index');
            $notificationUrl = str_replace(url('/'),'',$notificationUrl);
            $this->notificationUrl = $notificationUrl;
            $this->adminNotificationUrl = '/products';
            $this->routeCheck = 'product.index';
            $this->typeId = EmailTemplateType::where('type', 'product_update_email_template')->first()->id;
            $this->notificationSend("Seller product update", $product->created_by);
        }

        return true;
    }

    public function getGalleryImage($id)
    {
        return ProductGalaryImage::where('product_id', $id)->get();
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);
        if (count($product->sellerProducts) > 0) {
            if (count($product->sellerProducts) < 2 && $product->sellerProducts->first()->seller->role->type == 'superadmin') {

                if (
                    count($product->sellerProducts->first()->flashDealProducts) < 1 && count($product->sellerProducts->first()->newUserZoneProducts) < 1 &&
                    count($product->sellerProducts->first()->MenuElements) < 1 && $product->sellerProducts->first()->headerProductPanel == null && count($product->sellerProducts->first()->Silders) < 1 &&
                    count($product->sellerProducts->first()->homepageCustomProducts) < 1 //&& count($product->sellerProducts->first()->Orders) < 1
                ) {
                    $seller_product_skus = $product->sellerProducts->first()->skus->pluck('id')->toArray();
                    $seller_product = $product->sellerProducts->first()->id;
                    $cart_list = Cart::where('product_type', 'product')->whereIn('product_id',$seller_product_skus)->pluck('id')->toArray();

                    $wush_list = Wishlist::where('seller_product_id',$seller_product)->pluck('id')->toArray();
                    $astxs = \App\Models\Astx::where('product_id',$seller_product)->pluck('id')->toArray();

                    Cart::destroy($cart_list);
                    \App\Models\Astx::destroy($astxs);
                    Wishlist::destroy($wush_list);

                    $comments = \App\Models\Comment::where('product_id',$product->sellerProducts->first()->id)->pluck('id')->toArray();
                    \App\Models\Comment::destroy($comments);

                    ImageStore::deleteImage($product->thumbnail_image_source);
                    ImageStore::deleteImage($product->meta_image);
                    $images = $this->getGalleryImage($id);
                    foreach($images as $image){
                        ImageStore::deleteImage($image->images_source);

                    }
                    ProductGalaryImage::where("product_id", $id)->delete();
                    ProductTag::where("product_id", $id)->delete();
                    CategoryProduct::where("product_id", $id)->delete();
                    ProductSku::where("product_id", $id)->delete();
                    ProductVariations::where("product_id", $id)->delete();
                    $methods = ProductShipping::where('product_id', $id)->pluck('id');
                    ProductShipping::destroy($methods);
                    Product::findOrFail($id)->delete();
                    return 'possible';
                }
                return "not_possible";
            }
            return "not_possible";
        }

        if($product->sellerProducts->first()){

            $seller_product_skus = $product->sellerProducts->first()->skus->pluck('id')->toArray();
            $cart_list = Cart::where('product_type', 'product')->whereIn('product_id',$seller_product_skus)->pluck('id')->toArray();
            Cart::destroy($cart_list);

            $comments = \App\Models\Comment::where('product_id',$product->id)->pluck('id')->toArray();
            \App\Models\Comment::destroy($comments);
        }
        ImageStore::deleteImage($product->thumbnail_image_source);
        ImageStore::deleteImage($product->meta_image);
        if($product->meta_image_media != null){
            $product->meta_image_media->delete();
        }
        $images = $this->getGalleryImage($id);
        foreach($images as $image){
            ImageStore::deleteImage($image->images_source);

        }

        ProductGalaryImage::where("product_id", $id)->delete();
        ProductTag::where("product_id", $id)->delete();
        CategoryProduct::where("product_id", $id)->delete();
        // $media_used = UsedMedia::where('')
        ProductSku::where("product_id", $id)->delete();
        ProductVariations::where("product_id", $id)->delete();
        $methods = ProductShipping::where('product_id', $id)->pluck('id');
        ProductShipping::destroy($methods);
        Product::findOrFail($id)->delete();
        return 'possible';
    }
    public function getRequestProduct()
    {
        if(request()->has('id')){
            return Product::with('brand')->where('is_approved', 0)->where('discount', '!=',0);
        }
        return Product::with('brand')->where('is_approved', 0);
    }

    public function productApproved($data)
    {
        $product = Product::where('id', $data['id'])->firstOrFail();
        $product->update([
            'is_approved' => $data['is_approved']
        ]);

        $productName =  $product->product_name;

        $sellerProduct = SellerProduct::create([
            'product_id' => $product->id,
            'product_name' => $product->product_name,
            'stock_manage' => 0,
            'tax' => $product->tax,
            'tax_type' => $product->tax_type,
            'discount' => $product->discount,
            'discount_type' => $product->discount_type,
            'is_digital' => ($product->is_physical == 0) ? 0 : 1,
            'user_id' => $product->created_by,
            'slug' => $this->productSlug($productName),
            'is_approved' => 1
        ]);

        $product_skus = ProductSku::where('product_id', $data['id'])->get();
        foreach ($product_skus as $item) {
            $item->update([
                'status' => $data['is_approved']
            ]);
            SellerProductSKU::create([
                'product_id' => $sellerProduct->id,
                'product_sku_id' => $item->id,
                'product_stock' => 0,
                'purchase_price' => $item->purchase_price,
                'selling_price' => $item->selling_price,
                'status' => 1,
                'user_id' => $product->created_by
            ]);

            $sellerProduct->update([
                'min_sell_price' => $sellerProduct->skus->min('selling_price'),
                'max_sell_price' => $sellerProduct->skus->max('selling_price')
            ]);
        }

        // Send Notification
        $notificationUrl = route('seller.product.index');
        $notificationUrl = str_replace(url('/'),'',$notificationUrl);
        $this->notificationUrl = $notificationUrl;
        $this->adminNotificationUrl = '/products';
        $this->routeCheck = 'product.index';
        $this->typeId = EmailTemplateType::where('type', 'product_approve_email_template')->first()->id;
        $this->notificationSend("Seller product approval", $product->created_by);

        return 1;
    }

    public function findProductSkuBySKU($sku)
    {
        return ProductSku::where('sku', $sku)->firstOrFail();
    }

    public function updateRecentViewedConfig($data)
    {
        $previousRouteServiceProvier = base_path('Modules/Product/Resources/views/recently_views/config.json');
        $newRouteServiceProvier      = base_path('Modules/Product/Resources/views/recently_views/config.txt');
        copy($newRouteServiceProvier, $previousRouteServiceProvier);
        $jsonString = file_get_contents(base_path('Modules/Product/Resources/views/recently_views/config.json'));
        $config = json_decode($jsonString, true);
        $config['max_limit'] = (!empty($data['max_limit'])) ? $data['max_limit'] : "0";
        $config['number_of_days'] = (!empty($data['number_of_days'])) ? $data['number_of_days'] : "0";
        $newJsonString = json_encode($config, JSON_PRETTY_PRINT);
        file_put_contents($previousRouteServiceProvier, stripslashes($newJsonString));
    }

    public function csvUploadCategory($data)
    {
        Excel::import(new ProductImport, $data['file']->store('temp'));
    }

    public function updateSkuByID($data)
    {
        return ProductSku::where('id', $data['id'])->update([
            'selling_price' => $data['selling_price'],
            'variant_image' => isset($data['variant_image']) ? $data['variant_image'] : null
        ]);
    }

    public function getFilterdProduct($table)
    {
        $product = Product::query();
        if(request()->has('id')){
            if ($table == 'alert') {
                return $product->where('stock_manage', 1)->whereHas('skus', function ($query) {
                    return $query->select(DB::raw('SUM(product_stock) as sum_colum'))->having('sum_colum', '<=', 10);
                });
            }
            if ($table == 'stockout') {
                return $product->where('stock_manage', 1)->whereHas('skus', function ($query) {
                    return $query->select(DB::raw('SUM(product_stock) as sum_colum'))->having('sum_colum', '<', 1);
                });
            }
            if ($table == 'disable') {
                return $product->where('status', 0);
            }
        }
        if ($table == 'alert') {
            return $product->where('stock_manage', 1)->whereHas('skus', function ($query) {
                return $query->select(DB::raw('SUM(product_stock) as sum_colum'))->having('sum_colum', '<=', 10);
            });
        }
        if ($table == 'stockout') {
            return $product->where('stock_manage', 1)->whereHas('skus', function ($query) {
                return $query->select(DB::raw('SUM(product_stock) as sum_colum'))->having('sum_colum', '<', 1);
            });
        }
        if ($table == 'disable') {
            return $product->where('status', 0);
        }
    }

    public function getSellerProduct(){
        return Product::where('created_by', auth()->id())->orWhere('created_by', auth()->user()->sub_seller->seller_id);
    }
}
