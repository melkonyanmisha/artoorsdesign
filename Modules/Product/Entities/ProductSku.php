<?php

namespace Modules\Product\Entities;
use App\Models\UsedMedia;

use Illuminate\Database\Eloquent\Model;

class ProductSku extends Model
{
    protected $table = "product_sku";
    protected $guarded = ["id"];

    public function product()
    {
        return $this->belongsTo(Product::class, "product_id");
    }

    public function product_variations()
    {
        return $this->hasMany(ProductVariations::class);
    }

    public function digital_file()
    {
        return $this->hasOne(DigitalFile::class);
    }
    public function variant_image_media(){
        return $this->morphOne(UsedMedia::class, 'usable')->where('used_for', 'variant_image');
    }
}
