<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Wallet\Entities\WalletBalance;
use Modules\Customer\Entities\CustomerAddress;
use Modules\Marketing\Entities\CouponUse;
use Modules\GST\Entities\OrderPackageGST;
use Modules\GiftCard\Entities\GiftCardUse;
use Modules\OrderManage\Entities\CancelReason;
use Carbon\Carbon;

class ComplainSuggestionSeo extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'complain_suggestion_seo';
    public $timestamps = false;



}
