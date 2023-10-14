<?php


use App\Http\Controllers\Frontend\WelcomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\ContactUsController;
use App\Http\Controllers\Frontend\AboutUsController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Frontend\CareerController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\MerchantController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\ReturnExchangeController;
use App\Http\Controllers\Frontend\LanguageController;
use Modules\OrderManage\Http\Controllers\OrderManageController;
use App\Http\Controllers\Auth\MerchantRegisterController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Frontend\CompareController;
use App\Http\Controllers\Frontend\CouponController;
use App\Http\Controllers\Frontend\FlashDealController;
use App\Http\Controllers\Frontend\GiftCardController;
use App\Http\Controllers\Frontend\NewUserZoneController;
use App\Http\Controllers\Frontend\NotificationController;
use App\Http\Controllers\Frontend\OrderController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\ProductReviewController;
use App\Http\Controllers\Frontend\ReferralController;
use App\Http\Controllers\Frontend\SellerController;
use App\Http\Controllers\Frontend\SupportTicketController;
use App\Http\Controllers\Frontend\WishlistController;
use App\Http\Controllers\MediaManagerController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;
use Modules\FrontendCMS\Entities\DynamicPage;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\UploadFileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\SidebarManager\Entities\Sidebar;
use Illuminate\Http\Request;
use App\Mail\SendMail;
use  App\Models\HomeSeo;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//for language switcher
Route::get('/admin/home-page/seo',function (){
    $info = HomeSeo::first();
  return view('frontendcms::seo.home', compact('info'));
})->name('admin.home-page');

Route::get('/clear-cache', function() {

    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
    $exitCode = Artisan::call('config:clear');


    return 'DONE'; //Return anything
});
Route::post('/parol/reset', function() {
    \session()->flash('reset',1);
});

Route::get('/test-mail', function(){
    $reset_link = 'sadas';
    $message = (string) view('emails.reset_mail', compact('reset_link'));
    $headers = "From: " . app('general_setting')->email . " \r\n";
    $headers .= "Reply-To: " . app('general_setting')->email . " \r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=utf-8\r\n";
    $status =  mail('sedagalstyann@gmail.com', 'asd', $message, $headers);

    dd($status);
});

Route::get('/session/set', function(Request  $request) {
    \session()->put('dash',$request->type);
});

Route::get('/post/set/get', function(Request  $request) {
    \session()->put('name',$request->type);
});

Route::post('/parol/reset1', function() {
    \session()->flash('arcaMessage',1);
    return \session()->get('arcaMessage');
});

Route::post('/pictures/uploadImage', function() {
    return 'aaaaaaa';
});

Route::get('/artoors/files/{id}', function($id) {

    if($b = \App\Models\Paymant_products::where('product_id1',$id)->where('user_id','')->first()){
        $product = \Modules\Product\Entities\Product::find($id);
        if($product->video_link){
            return redirect($product->video_link);
        }else{
            return redirect('/');
        }
    }

    if($a = \App\Models\Paymant_products::where('product_id1',$id)->where('user_id',\auth()->id())->first()){
//        if($a->user_id){
//            if (\auth()->id() == $a->user_id){
                $product = \Modules\Product\Entities\Product::find($id);
                if($product->video_link){
                    return redirect($product->video_link);
                }else{
                    return redirect('/');
                }
//            }else{
//
//            }

//        }else{
//            $product = \Modules\Product\Entities\Product::find($id);
//            if($product->video_link){
//                return redirect($product->video_link);
//            }else{
//                return redirect('/');
//            }
//        }


    }else{
        return redirect('/');
    }


})->name('artoors.files');

Route::post('/parol/reset/1', function(Request $request) {
        foreach (\App\Models\Subscription::where('status',1)->get() as $user){
            \Mail::to($user->email)->send(new SendMail($request->text));
        }

        return 1;
});

Route::post('/locale',[LanguageController::class,'locale'])->name('frontend.locale');
Route::post('/change_password',[\App\Http\Controllers\Controller::class,'change_password'])->name('change_password');
Route::post('/change_email',[\App\Http\Controllers\Controller::class,'change_email'])->name('change_email');
Route::post('/change_notification',[\App\Http\Controllers\Controller::class,'change_notification'])->name('change_notification');
Auth::routes(['verify' => true]);
Route::get('/admin/login', [LoginController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('/admin/login', [LoginController::class, 'adminLogin'])->name('admin.login_submit');
Route::get('/admin',function(){
    return redirect(url('/admin/login'));
});

Route::get('/',[WelcomeController::class,'index'])->name('frontend.welcome');
Route::get('/get-more-products',[WelcomeController::class,'get_more_products'])->name('frontend.get_more_products');
Route::post('/ajax-search-product',[WelcomeController::class,'ajax_search_for_product'])->name('frontend.ajax_search_for_product');
Route::get('/search',[WelcomeController::class,'searchPage'])->name('frontend.searchPage');

Route::get('/secret-logout',[WelcomeController::class,'secret_logout'])->name('secret_logout');
Route::get('/uploads/digital_file/{slug}',[OrderManageController::class,'download'])->name('digital_file_download');

Route::middleware(['admin'])->group(function () {
    Route::get('/admin-dashboard', [ProfileController::class, 'dashboard'])->name('admin.dashboard')->middleware('permission');
    Route::get('/dashboard-cards-info/{type}', [ProfileController::class, 'dashboardCards'])->name('dashboard.card.info');
});

Route::get('sitemap.xml', function (){
    header('Content-type: text/xml');

    $sitemap = Sitemap::create()
        ->add(Url::create('/'))
        ->add(Url::create('/blog'))
        ->add(Url::create('/terms/conditions'))
        ->add(Url::create('/privacy/policy'));

    $blog_posts = \Modules\Blog\Entities\BlogPost::get();

    foreach ($blog_posts as $blog_post) {
        $sitemap->add(Url::create("/blog/{$blog_post->title}"));
    }

    $products = \Modules\Product\Entities\Product::with('categories')->get();

    foreach ($products as $product) {
        $sitemap->add(Url::create("/product/{$product->categories[0]->slug}/{$product->slug}"));
    }

    $categories = \Modules\Product\Entities\Category::get();

    foreach ($categories as $category) {
        if($category->slug == 'all-designs'){
            $sitemap->add(Url::create("/category/all-products"));
        }else{
            $sitemap->add(Url::create("/category/{$category->slug}"));
        }
    }

    $sitemap->writeToFile(public_path('sitemap.xml'));

    $xml = simplexml_load_file(public_path('sitemap.xml'));

    echo $xml->asXML();
})->name('sitemap');

Route::post('search',[SearchController::class,'search'])->name('routeSearch');
Route::get('/my-wishlist',[WishlistController::class,'index'])->name('frontend.my-wishlist')->middleware(['auth','customer']);

//for category page
Route::get('category/',[CategoryController::class,'productByCategory'])->name('frontend.category');
Route::get('category/fetch_data', [CategoryController::class,'fetchPagenateData'])->name('frontend.category.fetch-data');
Route::post('/category-filter-product',[CategoryController::class,'filterIndex'])->name('frontend.category_page_product_filter');
Route::get('/category-filter-product-page',[CategoryController::class,'fetchFilterPagenateData'])->name('frontend.category_page_product_filter_page');
Route::post('/filter-product-by-type',[CategoryController::class,'filterIndexByType'])->name('frontend.product_filter_by_type');
Route::get('/filter-product-page-by-type',[CategoryController::class,'fetchFilterPagenateDataByType'])->name('frontend.product_filter_page_by_type');
Route::get('/filter-sort-product-by-type',[CategoryController::class,'sortFilterIndexByType'])->name('frontend.sort_product_filter_by_type');
Route::post('/get-color-by-type', [CategoryController::class,'get_colors_by_type'])->name('frontend.get_colors_by_type');
Route::post('/get-attribute-by-type', [CategoryController::class,'get_attribute_by_type'])->name('frontend.get_attribute_by_type');
Route::post('/get-brand-by-type', [CategoryController::class,'get_brand_by_type'])->name('frontend.get_brands_by_type');


Route::get('/contact-us',[ContactUsController::class,'index'])->name('frontend.contact-us');
Route::get('/about-us',[AboutUsController::class,'index'])->name('frontend.about-us');
Route::get('/merchant',[MerchantController::class,'index'])->name('frontend.merchant');
Route::get('/return-exchange',[ReturnExchangeController::class,'index'])->name('frontend.return-exchange');

//cart
Route::get('/cart',[CartController::class,'index'])->name('frontend.cart')->middleware('customer');
Route::post('/cart/store',[CartController::class,'store'])->name('frontend.cart.store');
Route::post('/cart/update',[CartController::class,'update'])->name('frontend.cart.update');
Route::post('/cart/delete-all',[CartController::class,'destroyAll'])->name('frontend.cart.delete-all');
Route::post('/cart/delete',[CartController::class,'destroy'])->name('frontend.cart.delete');
Route::post('/cart/update-qty',[CartController::class,'updateQty'])->name('frontend.cart.update-qty');
Route::post('/cart/select-all',[CartController::class,'selectAll'])->name('frontend.cart.select-all');
Route::post('/cart/select-all-seller',[CartController::class,'selectAllSeller'])->name('frontend.cart.select-all-seller');
Route::post('/cart/select-item',[CartController::class,'selectItem'])->name('frontend.cart.select-item');
Route::post('/cart/shipping-info-update',[CartController::class,'updateCartShippingInfo'])->name('frontend.cart.update_shipping_info');

//Wishlist
Route::post('/wishlist/store',[WishlistController::class,'store'])->name('frontend.wishlist.store')->middleware('auth');
Route::post('/wishlist/remove',[WishlistController::class,'remove'])->name('frontend.wishlist.remove')->middleware('auth');
Route::get('/my-wishlist/paginate-data',[WishlistController::class,'my_wish_list'])->name('frontend.my-wishlist.paginate-data')->middleware(['auth','customer']);

//compare
Route::get('/compare', [CompareController::class, 'index'])->name('frontend.compare.index');
Route::post('/compare', [CompareController::class, 'store'])->name('frontend.compare.store');
Route::post('/compare/remove', [CompareController::class, 'removeItem'])->name('frontend.compare.remove');
Route::post('/compare/reset', [CompareController::class, 'reset'])->name('frontend.compare.reset');


Route::middleware(['guestCheckout', 'customer'])->group(function () {
    Route::get('/checkout',[CheckoutController::class,'index'])->name('frontend.checkout');
    Route::post('/checkout/billing-address/store',[CheckoutController::class,'billingAddressStore'])->name('frontend.checkout.billing.address.store');
});
//checkout
Route::group(['middleware' => ['auth']], function () {
    Route::post('/checkout/item/delete',[CheckoutController::class,'destroy'])->name('frontend.checkout.item.delete');
    Route::post('/checkout/address/store',[CheckoutController::class,'addressStore'])->name('frontend.checkout.address.store');
    Route::post('/checkout/address/shipping',[CheckoutController::class,'shippingAddressChange'])->name('frontend.checkout.address.shipping');
    Route::post('/checkout/address/billing',[CheckoutController::class,'billingAddressChange'])->name('frontend.checkout.address.billing');
    Route::post('/checkout/coupon-apply',[CheckoutController::class,'couponApply'])->name('frontend.checkout.coupon-apply');
    Route::get('/checkout/coupon-delete',[CheckoutController::class,'couponDelete'])->name('frontend.checkout.coupon-delete');

});

Route::post('/change-shipping-method',[CheckoutController::class,'changeShippingMethod'])->name('frontend.change_shipping_method');

//order
Route::group(['middleware' => ['auth','customer']], function () {
    Route::get('/my-purchase-orders',[OrderController::class,'my_purchase_order_index'])->name('frontend.my_purchase_order_list');
    Route::get('/my-purchase-order-pdf/{id}',[OrderController::class,'my_purchase_order_pdf'])->name('frontend.my_purchase_order_pdf');
    Route::post('/my-purchase-order-cancell',[OrderController::class,'my_purchase_order_cancel'])->name('frontend.order_cancel_by_customer');
    Route::post('/my-purchase-package-order-cancell',[OrderController::class,'my_purchase_order_package_cancel'])->name('frontend.my_purchase_order_package_cancel');
});
    Route::get('/my-purchase-order-details/{id}',[OrderController::class,'my_purchase_order_detail'])->name('frontend.my_purchase_order_detail');

    Route::get('/track-order',[OrderController::class,'track_order'])->name('frontend.order.track');
    Route::post('/order/store',[OrderController::class,'store'])->name('frontend.order.store');
    Route::get('/order/summary/{id}',[OrderController::class,'order_summary'])->name('frontend.order.summary_after_checkout');
    Route::post('/order/payment',[OrderController::class,'payment'])->name('frontend.order_payment');
    Route::post('/track-order',[OrderController::class,'track_order_find'])->name('frontend.order.track_find');


//seller profile
Route::get('/seller-profile/{seller_id}',[SellerController::class,'index'])->name('frontend.seller');
Route::get('seller-profile/{seller_id}/fetch_data', [SellerController::class,'fetchPagenateData'])->name('frontend.seller.fetch-data');
Route::post('seller-profile/get-color-by-type', [SellerController::class,'get_colors_by_type'])->name('frontend.seller.get_colors_by_type');
Route::post('seller-profile/get-attribute-by-type', [SellerController::class,'get_attribute_by_type'])->name('frontend.seller.get_attribute_by_type');
Route::post('/seller-filter-product-by-type',[SellerController::class,'filterIndexByType'])->name('frontend.seller.product_filter_by_type');
Route::get('/seller-filter-product-page-by-type',[SellerController::class,'fetchFilterPagenateDataByType'])->name('frontend.seller.product_filter_page_by_type');
Route::get('/seller-filter-sort-product-by-type',[SellerController::class,'sortFilterIndexByType'])->name('frontend.seller.sort_product_filter_by_type');

//Route::get('/{category}/{seller}/{slug?}',[ProductController::class,'show'])->name('frontend.item.show');
Route::get('/product/{category}/{seller}/{slug?}',[ProductController::class,'show'])->name('frontend.item.show');
Route::post('/item-details-for-get-modal',[ProductController::class,'show_in_modal'])->name('frontend.item.show_in_modal');
Route::get('/item/reviews/get-data',[ProductController::class,'getReviewByPage'])->name('frontend.product.reviews.get-data');
Route::get('/giftcard/reviews/get-data',[GiftCardController::class,'getReviewByPage'])->name('frontend.giftcard.reviews.get-data');

Route::group(['middleware' => ['auth','seller'],'prefix' => 'media-manager'], function () {
    // for media manager
    Route::get('/upload-files', [MediaManagerController::class,'index'])->name('media-manager.upload_files')->middleware('permission');
    Route::get('/new-upload', [MediaManagerController::class,'add_new'])->name('media-manager.new-upload')->middleware('permission');
    Route::post('/new-upload-store', [MediaManagerController::class,'store'])->middleware('prohibited_demo_mode');
    Route::get('/delete-media-file/{id}', [MediaManagerController::class,'destroy'])->name('media-manager.delete_media_file')->middleware(['prohibited_demo_mode', 'permission']);
    Route::get('/get-files-modal', [MediaManagerController::class,'getfilesForModal'])->name('media-manager.get_files_for_modal');
    Route::post('/get-modal', [MediaManagerController::class,'getModal'])->name('media-manager.get_media_modal');
    Route::post('/get_media_by_id', [MediaManagerController::class,'getMediaById'])->name('media-manager.get_media_by_id');
});


//merchant register
Route::get('/merchant-register-step-1',[MerchantRegisterController::class,'showRegisterFormStepFirst'])->name('frontend.merchant-register-step-first');
Route::get('/merchant-register-step-2/{id}',[MerchantRegisterController::class,'showRegisterForm'])->name('frontend.merchant-register');
Route::get('/merchant-register-step-3',[MerchantRegisterController::class,'showRegisterForm2'])->name('frontend.merchant-register-subscription-type');
Route::post('/merchant-register',[MerchantRegisterController::class,'register'])->name('frontend.merchant.store');
Route::get('/user-email-verify',[WelcomeController::class,'emailVerify'])->name('frontend.mail-verify');
Route::get('/verify',[\App\Http\Controllers\Auth\EmailVerificationController::class,'emailVerify'])->name('frontend.mail-verify-link');
Route::post('/resend-link',[\App\Http\Controllers\Auth\EmailVerificationController::class,'resendMail'])->name('frontend.resend-link');

//flash deal
Route::get('/flash-deal/{slug}',[FlashDealController::class,'show'])->name('frontend.flash-deal');
Route::get('/flash-deal/{slug}/fetch-data',[FlashDealController::class,'fetchData'])->name('frontend.flash-deal.fetch-data');

Route::get('/shopping-recent-viewed',[WelcomeController::class,'shopping_from_recent_viewed'])->name('frontend.shopping_from_recent_viewed');


// new user zone
Route::get('/new-user-zone/{slug}',[NewUserZoneController::class,'show'])->name('frontend.new-user-zone');
Route::get('/new-user-zone/{slug}/fetch-product-data',[NewUserZoneController::class,'fetchProductData'])->name('frontend.new-user-zone.fetch-product-data');
Route::get('/new-user-zone/{slug}/fetch-category-data',[NewUserZoneController::class,'fetchCategoryData'])->name('frontend.new-user-zone.fetch-category-data');
Route::get('/new-user-zone/{slug}/fetch-coupon-category-data',[NewUserZoneController::class,'fetchCouponCategoryData'])->name('frontend.new-user-zone.fetch-coupon-category-data');
Route::get('/new-user-zone/{slug}/fetch-all-category-data',[NewUserZoneController::class,'fetchAllCategoryData'])->name('frontend.new-user-zone.fetch-all-category-data');
Route::get('/new-user-zone/{slug}/fetch-all-coupon-category-data',[NewUserZoneController::class,'fetchAllCouponCategoryData'])->name('frontend.new-user-zone.fetch-all-coupon-category-data');

Route::post('/new-user-zone/{slug}/coupon-store',[NewUserZoneController::class,'couponStore'])->name('frontend.new-user-zone.coupon-store');


//gift cards
Route::get('/gift-cards',[GiftCardController::class,'index'])->name('frontend.gift-card.index');
Route::get('/gift-cards/fetch-data',[GiftCardController::class,'fetchData'])->name('frontend.gift-card.fetch-data');
Route::get('/gift-cards/fetch-data-by-filter',[GiftCardController::class,'fetchDataByFilter'])->name('frontend.gift-card.fetch-data-by-filter');
Route::get('/gift-cards/{slug}',[GiftCardController::class,'show'])->name('frontend.gift-card.show');
Route::post('/gift-cards/filter-by-type',[GiftCardController::class,'filterByType'])->name('frontend.gift-card.filter_by_type');
Route::get('/gift-cards/filter/page-by-type',[GiftCardController::class,'filterPaginateDataByType'])->name('frontend.gift-card.filter_page_by_type');

Route::group(['middleware' => ['auth','customer']], function(){
    Route::get('/purchased-gift-cards',[GiftCardController::class,'purchased_gift_card'])->name('frontend.purchased-gift-card');
    Route::post('/purchased-gift-cards-redeem',[GiftCardController::class,'gift_card_redeem'])->name('frontend.gift_card_redeem');
    Route::post('/wallet-recharge-via-gift-cards',[GiftCardController::class,'recharge_via_gift_card'])->name('frontend.wallet.recharge_via_gift_card');

    Route::get('digital-products', [OrderController::class,'digital_product_index'])->name('frontend.digital_product');
    //support ticket
    Route::get('/support-ticket',[SupportTicketController::class,'index'])->name('frontend.support-ticket.index');
    Route::get('/support-ticket/paginate',[SupportTicketController::class,'dataWithPaginate'])->name('frontend.support-ticket.paginate');
    Route::get('/support-ticket/create',[SupportTicketController::class,'create'])->name('frontend.support-ticket.create');
    Route::get('/support-ticket/{id}/show',[SupportTicketController::class,'show'])->name('frontend.support-ticket.show');
    Route::post('/support-ticket/store',[SupportTicketController::class,'store'])->name('frontend.support-ticket.store')->middleware('prohibited_demo_mode');;
});



//social login
Route::get('login/{provider}', [App\Http\Controllers\Auth\LoginController::class, 'redirectToProvider']);
Route::get('login/{provider}/callback',[App\Http\Controllers\Auth\LoginController::class,'handleProviderCallback']);
Route::post('social-login',[App\Http\Controllers\Auth\LoginController::class,'social_login'])->name('social.login');
Route::post('social-connect',[App\Http\Controllers\Auth\LoginController::class,'social_connect'])->name('social.connect');
Route::post('social-delete/{providerId}',[App\Http\Controllers\Auth\LoginController::class,'social_delete'])->name('social.delete');

//subscription route
Route::post('/subscription/store',[WelcomeController::class,'subscription'])->name('subscription.store');
Route::post('/contact/store',[WelcomeController::class,'contactForm'])->name('contact.store');


Route::get('/change/time',function (Request $request){
    Modules\Appearance\Entities\Header::find(1)->update([
        'time' => 0,
        'ka' => false,
    ]);

    foreach (\Modules\Seller\Entities\SellerProduct::where('discount','!=',0)->get() as $product){
        $product->discount = 0;
        $product->save();

        $product1 = $product->product;
        $product1->discount = 0;
        $product1->save();
    }

})->name('change_time');

//staff
Route::group(['middleware' => ['auth','admin']], function(){

     Route::group(['prefix' => 'iceDrive','name'=>'iceDrive.'], function() {
         Route::get('/user/data',[App\Http\Controllers\IceDriveController::class,'user_data'])->name('user_data');
         Route::post('/login',[App\Http\Controllers\IceDriveController::class,'login'])->name('login');

     });



     Route::view('/admin/comments','backEnd.pages.comments');
     Route::view('/comment/admin','backEnd.pages.comment')->name('admin.comment');
     Route::view('/popap','backEnd.popap')->name('admin.popap');

     Route::post('/change/tokos',function (Request $request){
         Modules\Appearance\Entities\Header::find(1)->update([
             'ka' => $request->ka
         ]);
     })->name('change_tokos');



     Route::post('/tokos',function (Request $request){
         Modules\Appearance\Entities\Header::find(1)->update([
             'tokos' => $request->tokos,
             'text' => $request->text,
             'time' => $request->time,
             'link' => $request->link,
         ]);

         return redirect()->route('admin.dashboard');
     })->name('tokos');

     Route::middleware('permission')->prefix('hr')->group(function(){
        Route::resource('staffs', '\App\Http\Controllers\StaffController');
        Route::post('/staff-status-update',[StaffController::class,'status_update'])->name('staffs.update_active_status')->middleware('prohibited_demo_mode');
        Route::get('/staff/view/{id}', [StaffController::class,'show'])->name('staffs.view');
        Route::get('/staff/destroy/{id}',[StaffController::class,'destroy'])->name('staffs.destroy')->middleware('prohibited_demo_mode');
     });

    Route::post('/staff-document/store', [StaffController::class,'document_store'])->name('staff_document.store')->middleware('prohibited_demo_mode');
    Route::get('/staff-document/destroy/{id}', [StaffController::class,'document_destroy'])->name('staff_document.destroy')->middleware('prohibited_demo_mode');
    Route::get('/profile-view', [StaffController::class,'profile_view'])->name('profile_view');
    Route::post('/profile-edit', [StaffController::class,'profile_edit'])->name('profile_edit_modal');
    Route::post('/profile-update/{id}', [StaffController::class,'profile_update'])->name('profile.update')->middleware('prohibited_demo_mode');
    Route::post('/staff-profile/img-delete', [StaffController::class,'profileImgDelete'])->name('staff.img.delete')->middleware('prohibited_demo_mode');

    Route::post('/upload/start', [UploadFileController::class, 'start'])->name('upload.start');
    Route::post('/upload/chunk', [UploadFileController::class, 'uploadChunk'])->name('upload.chunk');
    Route::post('/upload/complete', [UploadFileController::class, 'complete'])->name('upload.complete');
});

 //for profile
 Route::group(['middleware' => ['auth','customer'],'prefix' => 'profile'], function () {

     Route::get('/mark-as-read', [NotificationController::class, 'mark_as_read'])->name('frontend.mark_as_read');
     Route::get('/notifications', [NotificationController::class, 'notifications'])->name('frontend.notifications');
     Route::get('/notificationsData', [NotificationController::class, 'notificationsData'])->name('frontend.notificationsData');
     Route::get('/notification_setting', [NotificationController::class, 'notification_setting'])->name('frontend.notification_setting');
     Route::post('/notification_setting/{id}', [NotificationController::class, 'notification_setting_update'])->name('frontend.notification_setting.update')->middleware('prohibited_demo_mode');

     Route::get('/', [ProfileController::class, 'index'])->name('frontend.customer_profile');
     Route::get('/dashboard', [ProfileController::class, 'dashboard'])->name('frontend.dashboard');
     Route::get('/coupons', [CouponController::class, 'index'])->name('customer_panel.coupon');
     Route::post('/coupons/store', [CouponController::class, 'store'])->name('frontend.profile.coupon.store')->middleware('prohibited_demo_mode');
     Route::post('/coupons/delete', [CouponController::class, 'destroy'])->name('frontend.profile.coupon.delete')->middleware('prohibited_demo_mode');
     Route::get('/orders', [ProfileController::class, 'order']);
     Route::get('/refunds', [ProfileController::class, 'refund']);
     Route::get('/referral', [ReferralController::class, 'referral'])->name('customer_panel.referral');
     Route::get('/product-review', [ProductReviewController::class, 'index']);
     Route::post('/product-review', [ProductReviewController::class, 'store'])->name('frontend.profile.review.store');

     Route::post('/user-notification-read', [NotificationController::class,'read'])->name('user_notification_read');
 });



 //for summernote image upload
 Route::post('summer-note-file-upload', [UploadFileController::class, 'upload_image'])->name('summerNoteFileUpload');

 Route::fallback(function($slug){
     $pageData = DynamicPage::where('is_static', 0)->where('status', 1)->where('slug', $slug)->first();
     if($pageData)
     {
         if($pageData->module == 'Lead' && isModuleActive('Lead') ){
             return view('lead::index',compact('pageData'));
         }else{
             return view(theme('pages.dynamic_page'),compact('pageData'));
         }
     }else{
         return abort(404);
     }
 });

Route::get('/message', [\App\Http\Controllers\MessageController::class,'index'])->name('message.index')->middleware('auth');
Route::post('/delete/notif', [\App\Http\Controllers\Controller::class,'delete_notif'])->name('delete.notFif');
Route::post('/block/user', [\App\Http\Controllers\Controller::class,'block_user'])->name('block/user');
Route::post('/un/block/user', [\App\Http\Controllers\Controller::class,'un_block_user'])->name('un.block/user');
Route::get('/download/files', [\App\Http\Controllers\Controller::class,'download_files'])->name('download.files');
Route::get('/minchev/download', [\App\Http\Controllers\Controller::class,'minchev_download'])->name('minchev_download');

Route::get('/clear', function() {
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
    $exitCode = Artisan::call('config:clear');


    return 'done'; //Return anything
});

Route::post('/astx', function (Request $request){
    if (!empty($a = \App\Models\Astx::where('user_id', \auth()->id())->where('product_id',$request->id)->first())){
        $a->astx = $request->astx;
        $a->save();
    }else{
        $a = \App\Models\Astx::create([
            'user_id' => \auth()->id(),
            'product_id' => $request->id,
            'astx' => $request->astx
        ]);
    }
    $controller = new \App\Http\Controllers\Controller();
    return $controller->send_notid($a);

})->name('astx');

Route::post('/like', function (Request $request){
    if (!empty(\App\Models\Like::where('user_id', \auth()->id())->where('product_id',$request->id)->first())){
//        \App\Models\Like::where('user_id', \auth()->id())->where('product_id',$request->id)->first()->delete();

            $data = count(\App\Models\Like::where('product_id',\request()->id)->get());

        return json_encode($data);
    }else{
        $astx = \App\Models\Like::create([
            'user_id' => \auth()->id(),
            'product_id' => $request->id,
            'type' => $request->type
        ]);
    }
    $data = count(\App\Models\Like::where('product_id',\request()->id)->get());
    return json_encode($data);
})->name('like');

Route::get('/download', function (Request $request){
    $filename = $request->filename;
//    $path = explode('/',$filename);
//    $file_path = public_path() .'/uploads/product_pdf/'. $path[3];
//    dd($file_path);
//    dd(public_path($filename) );
    if (file_exists(public_path($filename))) {

        $product = \Modules\Product\Entities\Product::where('pdf',$filename)->first();
        $product->downloads++;
        $product->save();

        return response()->download(public_path($filename), $filename, [
//            'Content-Length: ' . filesize($file_path)
        ]);
    } else {
        // Error
//        throw Throwable();
        return 'error';
    }

})->name('download');

Route::get('/messages/{id}', function (Request $request){
    \App\Models\Message::create([
        'from_id' => auth()->id(),
        'to_id' => \App\Models\User::find($request->id)->id,
        'messages' => $request->message??'',
        'image' => $imageName??null
    ]);

    $conversations =  \App\Models\Message::where('from_id', auth()->id())
        ->orWhere('to_id', auth()->id())
        ->orderBy('created_at','desc')
        ->get();
    $users = $conversations->map(function($conversation){
        if($conversation->from_id == auth()->id()) {
            return \App\Models\User::find($conversation->to_id);//$conversation->uxarkox;
        }

        return \App\Models\User::find($conversation->from_id);
    })->unique();

    return view(theme('new.message'), compact('users'));
})->name('message.second')->middleware('auth');

Route::post('/find_user', [App\Http\Controllers\Controller::class, 'find_user'])->name('find_user');
Route::post('/delete_chat', [App\Http\Controllers\Controller::class, 'delete_chat'])->name('delete_chat');
Route::post('/cat',function(Request $request){
    return count(\App\Models\Message::where('to_id',auth()->id())->where([['messages' ,'!=', '']])->where('view','0')->get());
})->middleware('auth')->name('ggg');

Route::post('/bbb',function(Request $request){
    $to_user = \App\Models\User::find($request->id);

    if(!empty(\App\Models\Block_user::where([['user_id' ,'=', auth()->id()],['second_user',"=",$request->id]])->orwhere([['second_user' ,'=', auth()->id()],['user_id',"=",$request->id]])->first())){
        return view('include',compact('to_user'));
    }

    $imageName = null;
    if($request->file){
        $imageName   = time() . '.' . $request->file->getClientOriginalExtension();
        $request->file->move('images/message', $imageName);
    }

    $data = \App\Models\Message::create([
        'from_id' => auth()->id(),
        'to_id' => $request->id,
        'messages' => $request->message??'',
        'image' => $imageName??null
    ]);

    event(new \App\Events\FormSubmited($to_user));

    return view('include',compact('to_user'));

})->name('bbb');

Route::post('/aabb',function(Request $request){
    $to_user = \App\Models\User::find($request->id);

    return view('include',compact('to_user'));
})->name('aabb');

//ameria
Route::group(['prefix' => '/ameria', 'middleware' => ['auth']], function() {
    Route::post('/create', [\App\Http\Controllers\Controller::class,'arcaCreate'])->name('arca.step1');
    Route::get('/info', [\App\Http\Controllers\Controller::class,'arcaInfo'])->name('arca.result');
    Route::get('/fail', [\App\Http\Controllers\Controller::class,'idramFail'])->name('idram.fail');
});

//comment
Route::post('/store_comment',[\App\Http\Controllers\Controller::class,'store_comment'])->name('store_comment');
Route::post('/delete_comment',[\App\Http\Controllers\Controller::class,'delete_comment'])->name('delete_comment');
Route::post('/store/notif',[\App\Http\Controllers\Controller::class,'store_notif'])->name('store_notif');

Route::get('category/{slug}',[CategoryController::class,'productByCategory'])->name('frontend.category-product');
Route::get('category/{slug}',[CategoryController::class,'productByCategory'])->name('frontend.category_slug');
