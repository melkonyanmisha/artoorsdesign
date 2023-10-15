<?php

namespace Modules\FrontendCMS\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use \Modules\FrontendCMS\Services\AboutUsService;
use Exception;
use Modules\FrontendCMS\Http\Requests\AboutUsRequest;
use Modules\UserActivityLog\Traits\LogActivity;
use App\Models\HomeSeo;
use App\Models\ContactUsSeo;
use App\Models\ComplainSuggestionSeo;
use App\Models\ThermSeo;
use App\Models\BlogSeo;
use App\Models\PrivacySeo;
use App\Models\MediaManager;
use App\Models\UsedMedia;
use App\Traits\ImageStore;

class AboutUsController extends Controller
{
    use ImageStore;

    protected $aboutusService;

    public function __construct(AboutUsService $aboutusService)
    {
        $this->middleware('maintenance_mode');
        $this->aboutusService = $aboutusService;
    }
    public function index()
    {

        try {
            $aboutus = $this->aboutusService->getAll();

            return view('frontendcms::aboutus.index',compact('aboutus'));
        } catch (Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return response()->json([
                'status'    =>  false,
                'message'   =>  $e
            ]);
        }

    }


    public function update(AboutUsRequest $request, $id)
    {

        try {
            $this->aboutusService->update($request->only('mainTitle', 'subTitle', 'mainDescription','sec1_image','sec2_image',
             'benifitTitle', 'benifitDescription','sellingTitle','sellingDescription','slug','price', 'home_page_title', 'home_page_description'), $id);

            Toastr::success(__('common.updated_successfully'),__('common.success'));
            LogActivity::successLog('About us updated.');
            return redirect()->route('frontendcms.about-us.index');
        } catch (Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return response()->json([
                'status'    =>  false,
                'message'   =>  $e->getMessage().$e->getLine()
            ]);
        }
    }

    public function editPrivacyPolicyPage(){
        $info =PrivacySeo::first();
        return view('frontendcms::seo.privacy', compact('info'));
    }
    public function editTermsConditionsPage(){
        $info =ThermSeo::first();
        return view('frontendcms::seo.therms', compact('info'));
    }
    public function editContactUsPage(){
        $info = ContactUsSeo::first();
        return view('frontendcms::seo.contact_us', compact('info'));
    }
    public function editComplainAndSuggestionsPage(){
        $info = ComplainSuggestionSeo::first();
        return view('frontendcms::seo.complain_suggestion', compact('info'));
    }
    public function editBlogPage(){
        $info = BlogSeo::first();
        return view('frontendcms::seo.blog', compact('info'));
    }
    public function updateHomeSeo(Request $request , $id){
        $home = HomeSeo::find($id);

        $data = [];
        if (isset($request['meta_image']) && $request['meta_image'] != $home->media_id) {
            if(@$home->meta_image != null){
                ImageStore::deleteImage($home->meta_image);
            }

            $media_img = MediaManager::find($request['meta_image']);
//            dd($media_img);
            if($media_img->storage == 'local'){
                $file = asset_path($media_img->file_name);
            }else{
                $file = $media_img->file_name;
            }
            $data['media_id'] = $request['meta_image'];
            $meta_image = ImageStore::saveImage($file,300,300);
            $data['meta_image'] = $meta_image;
        }
        else{
            if(!isset($data['meta_image'])){
                $this->deleteImage($home->meta_image);
                $data['meta_image'] = null;
                $data['media_id'] = null;

            }else{
                $data['meta_image'] = $home->meta_image;
            }
        };
        $data['title']  = $request->title;
        $data['meta_title']  = $request->meta_title;
        $data['meta_keyword']  = $request->meta_keyword;
        $data['meta_description']  = $request->meta_description;
        $data['scheme_markup']  = $request->scheme_markup;
        $data['product_slider_title']  = $request->product_slider_title;
        $data['product_slider_descr']  = $request->product_slider_descr;
        $data['meta_image_alt']  = $request->meta_image_alt;
        HomeSeo::where('id',$id)->update($data);
        return redirect()->route('admin.home-page');
    }
    public function updatePrivacySeo(Request $request , $id){
        $home = PrivacySeo::find($id);
        $data = [];
        if (isset($request['meta_image']) && $request['meta_image'] != $home->media_id) {
            if(@$home->meta_image != null){
                ImageStore::deleteImage($home->meta_image);
            }
            $media_img = MediaManager::find($request['meta_image']);
            if($media_img->storage == 'local'){
                $file = asset_path($media_img->file_name);
            }else{
                $file = $media_img->file_name;
            }
            $data['media_id'] = $request['meta_image'];
            $meta_image = ImageStore::saveImage($file,300,300);
            $data['meta_image'] = $meta_image;
        }
        else{
            if(!isset($data['meta_image'])){
                $this->deleteImage($home->meta_image);
                $data['meta_image'] = null;
                $data['media_id'] = null;

            }else{
                $data['meta_image'] = $home->meta_image;
            }
        };
        $data['title']  = $request->title;
        $data['meta_title']  = $request->meta_title;
        $data['meta_keyword']  = $request->meta_keyword;
        $data['meta_description']  = $request->meta_description;
        $data['meta_image_alt']  = $request->meta_image_alt;
        $data['scheme_markup']  = $request->scheme_markup;
        PrivacySeo::where('id',$id)->update($data);
        return redirect()->route('admin.home-page');
    }
    public function updateThermsSeo(Request $request , $id){
        $home = ThermSeo::find($id);
        $data = [];
        if (isset($request['meta_image']) && $request['meta_image'] != $home->media_id) {
            if(@$home->meta_image != null){
                ImageStore::deleteImage($home->meta_image);
            }
            $media_img = MediaManager::find($request['meta_image']);
            if($media_img->storage == 'local'){
                $file = asset_path($media_img->file_name);
            }else{
                $file = $media_img->file_name;
            }
            $data['media_id'] = $request['meta_image'];
            $meta_image = ImageStore::saveImage($file,300,300);
            $data['meta_image'] = $meta_image;
        }
        else{
            if(!isset($data['meta_image'])){
                $this->deleteImage($home->meta_image);
                $data['meta_image'] = null;
                $data['media_id'] = null;

            }else{
                $data['meta_image'] = $home->meta_image;
            }
        };
        $data['title']  = $request->title;
        $data['meta_title']  = $request->meta_title;
        $data['meta_keyword']  = $request->meta_keyword;
        $data['meta_description']  = $request->meta_description;
        $data['meta_image_alt']  = $request->meta_image_alt;
        $data['scheme_markup']  = $request->scheme_markup;
        ThermSeo::where('id',$id)->update($data);
        return redirect()->route('admin.home-page');
    }
    public function updateContactUsSeo(Request $request , $id){
        $contact_us = ContactUsSeo::find($id);
        $data = [];
        if (isset($request['meta_image']) && $request['meta_image'] != $contact_us->media_id) {
            if(@$contact_us->meta_image != null){
                ImageStore::deleteImage($contact_us->meta_image);
            }
            $media_img = MediaManager::find($request['meta_image']);
            if($media_img->storage == 'local'){
                $file = asset_path($media_img->file_name);
            }else{
                $file = $media_img->file_name;
            }
            $data['media_id'] = $request['meta_image'];
            $meta_image = ImageStore::saveImage($file,300,300);
            $data['meta_image'] = $meta_image;
        }
        else{
            if(!isset($data['meta_image'])){
                $this->deleteImage($contact_us->meta_image);
                $data['meta_image'] = null;
                $data['media_id'] = null;

            }else{
                $data['meta_image'] = $contact_us->meta_image;
            }
        };
        $data['title']  = $request->title;
        $data['meta_title']  = $request->meta_title;
        $data['meta_keyword']  = $request->meta_keyword;
        $data['meta_description']  = $request->meta_description;
        $data['meta_image_alt']  = $request->meta_image_alt;
        ContactUsSeo::where('id',$id)->update($data);
        return redirect()->route('front.contact-us-page');
    }
    public function updateComplainAndSuggestionsSeo(Request $request , $id){
        $complain_suggestion = ComplainSuggestionSeo::find($id);
        $data = [];
        if (isset($request['meta_image']) && $request['meta_image'] != $complain_suggestion->media_id) {
            if(@$complain_suggestion->meta_image != null){
                ImageStore::deleteImage($complain_suggestion->meta_image);
            }
            $media_img = MediaManager::find($request['meta_image']);
            if($media_img->storage == 'local'){
                $file = asset_path($media_img->file_name);
            }else{
                $file = $media_img->file_name;
            }
            $data['media_id'] = $request['meta_image'];
            $meta_image = ImageStore::saveImage($file,300,300);
            $data['meta_image'] = $meta_image;
        }
        else{
            if(!isset($data['meta_image'])){
                $this->deleteImage($complain_suggestion->meta_image);
                $data['meta_image'] = null;
                $data['media_id'] = null;

            }else{
                $data['meta_image'] = $complain_suggestion->meta_image;
            }
        };
        $data['title']  = $request->title;
        $data['meta_title']  = $request->meta_title;
        $data['meta_keyword']  = $request->meta_keyword;
        $data['meta_description']  = $request->meta_description;
        $data['meta_image_alt']  = $request->meta_image_alt;
        ComplainSuggestionSeo::where('id',$id)->update($data);
        return redirect()->route('front.complain-suggestion-page');
    }
    public function updateBlogSeo(Request $request , $id){
        $blog = BlogSeo::find($id);
        $data = [];
        if (isset($request['meta_image']) && $request['meta_image'] != $blog->media_id) {
            if($blog->meta_image != null){
                ImageStore::deleteImage($blog->meta_image);
            }
            $media_img = MediaManager::find($request['meta_image']);
            if($media_img->storage == 'local'){
                $file = asset_path($media_img->file_name);
            }else{
                $file = $media_img->file_name;
            }
            $data['media_id'] = $request['meta_image'];
            $meta_image = ImageStore::saveImage($file,300,300);
            $data['meta_image'] = $meta_image;
        }
        else{
            if(!isset($data['meta_image'])){
                $this->deleteImage($blog->meta_image);
                $data['meta_image'] = null;
                $data['media_id'] = null;

            }else{
                $data['meta_image'] = $blog->meta_image;
            }
        }
        $data['title']  = $request->title;
        $data['meta_title']  = $request->meta_title;
        $data['meta_keyword']  = $request->meta_keyword;
        $data['scheme_markup']  = $request->scheme_markup;
        $data['meta_description']  = $request->meta_description;
        $data['meta_image_alt']  = $request->meta_image_alt;
        BlogSeo::where('id',$id)->update($data);
        return redirect()->route('front.blog-page');
    }

}
