<?php
namespace Modules\Product\Repositories;

use App\Traits\ImageStore;
use Modules\Product\Entities\Category;
use Modules\Product\Entities\CategoryImage;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Product\Imports\CategoryImport;
use App\Models\MediaManager;
use Modules\Product\Export\CategoryExport;

class CategoryRepository
{
    use ImageStore;

    protected $category;
    protected $ids = [];

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function category()
    {
        return Category::with(['brands', 'categoryImage', 'groups.categories','subCategories'])->where("parent_id", 0)->get();
    }

    public function subcategory($category)
    {
        return Category::where("parent_id", $category)->get();
    }

    public function allSubCategory()
    {
        return Category::where("parent_id", "!=", 0)->get();
    }

    public function getAllSubSubCategoryID($category_id){

        $subcats = $this->subcategory($category_id);
        $this->unlimitedSubCategory($subcats);
        return $this->ids;
    }

    private function unlimitedSubCategory($subcats){

        foreach($subcats as $subcat){
            $this->ids[] = $subcat->id;
            $this_subcats = $this->subcategory($subcat->id);
            if(count($this_subcats) > 0){
                $this->unlimitedSubCategory($this_subcats);
            }
        }
    }

    public function getModel(){

        return $this->category;
    }

    public function getAll()
    {
        if(isModuleActive('Affiliate')){
            return Category::with(['parentCategory','categoryImage','brands','affiliateCategoryCommission'])->get();
        }else{
            return Category::with(['parentCategory','categoryImage','brands'])->get();
        }

    }

    public function getActiveAll(){
        return Category::with('categoryImage', 'parentCategory', 'subCategories')->where('status', 1)->latest()->get();
    }

    public function getCategoryByTop(){

        return Category::with('categoryImage', 'parentCategory', 'subCategories')->where('status', 1)->orderBy('total_sale', 'desc')->get();
    }

    public function save($data)
    {
        $depth_level = 1;
        if(isset($data['category_type'])){
            $parent_depth = Category::where('id', $data['parent_id'])->first();
            $depth_level = $parent_depth->depth_level + 1;
        }

        $category = Category::create([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'commission_rate' => isset($data['commission_rate'])?$data['commission_rate']:0,
            'icon' => $data['icon'],
            'status' => $data['status'],
            'searchable' => $data['searchable'],
            'parent_id' => isset($data['category_type'])?$data['parent_id']:0,
            'depth_level' => $depth_level,
            'title' => isset($data['title']) ? $data['title'] : null,
            'scheme_markup' => isset($data['scheme_markup']) ? $data['scheme_markup'] : null,
            'description' => isset($data['description']) ? $data['description'] : null,
            'meta_title' => isset($data['meta_title']) ? $data['meta_title'] : '',
            'meta_description' => isset($data['meta_description']) ? $data['meta_description'] : null,
            'media_id' => isset($data['media_id']) ? $data['media_id'] : null,
            'meta_image' => isset($data['meta_image']) ? $data['meta_image'] : null,
            'meta_image_alt' => isset($data['meta_image_alt']) ? $data['meta_image_alt'] : null,
            'meta_keyword' => isset($data['meta_keyword']) ? $data['meta_keyword'] : null
        ]);

        $data = [];
        if (isset($request['meta_image']) && $request['meta_image'] != $category->media_id) {
            if($category->meta_image != null){
                ImageStore::deleteImage($category->meta_image);
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
                $this->deleteImage($category->meta_image);
                $data['meta_image'] = null;
                $data['media_id'] = null;

            }else{
                $data['meta_image'] = $category->meta_image;
            }
        }

        Category::where('id', $category->id)->update([
            'media_id' => isset($data['media_id']) ? $data['media_id'] : $category->media_id,
            'meta_image' => isset($data['meta_image']) ? $data['meta_image'] : $category->meta_image
        ]);

        if(!empty($data['image'])){
            $data['image'] = ImageStore::saveImage($data['image'], 225, 225);

            CategoryImage::create([
                'category_id' => $category->id,
                'image' => $data['image']
            ]);

        }

        return true;
    }

    public function update($data, $id)
    {
        $category = $this->category::where('id',$id)->first();
        $depth_level = 1;
        if(isset($data['category_type'])){
            $parent_depth = Category::where('id', $data['parent_id'])->first();
            $depth_level = $parent_depth->depth_level + 1;
        }
        if (isset($data['meta_image']) && $data['meta_image'] != $category->media_id) {
            if($category->meta_image != null){
                ImageStore::deleteImage($category->meta_image);
            }
            $media_img = MediaManager::find($data['meta_image']);
            if($media_img->storage == 'local'){
                $file = asset_path($media_img->file_name);
            }else{
                $file = $media_img->file_name;
            }
            $data['media_id'] = $data['meta_image'];
            $meta_image = ImageStore::saveImage($file,300,300);
            $data['meta_image'] = $meta_image;
        }
        else{
            if(!isset($data['meta_image'])){
                $this->deleteImage($category->meta_image);
                $data['meta_image'] = null;
                $data['media_id'] = null;

            }else{
                $data['meta_image'] = $category->meta_image;
            }
        };
        $category->update([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'commission_rate' => isset($data['commission_rate'])?$data['commission_rate']:0,
            'icon' => $data['icon'],
            'status' => $data['status'],
            'searchable' => $data['searchable'],
            'parent_id' => isset($data['category_type'])?$data['parent_id']:0,
            'depth_level' => $depth_level,
            'title' => isset($data['title']) ? $data['title'] : null,
            'scheme_markup' => isset($data['scheme_markup']) ? $data['scheme_markup'] : null,
            'description' => isset($data['description']) ? $data['description'] : null,
            'meta_title' => isset($data['meta_title']) ? $data['meta_title'] : null,
            'meta_description' => isset($data['meta_description']) ? $data['meta_description'] : null,
            'media_id' => isset($data['media_id']) ? $data['media_id'] : null,
            'meta_image' => isset($data['meta_image']) ? $data['meta_image'] : null,
            'meta_keyword' => isset($data['meta_keyword']) ? $data['meta_keyword'] : null,
            'meta_image_alt' => isset($data['meta_image_alt']) ? $data['meta_image_alt'] : null
        ]);


        if(!empty($data['image'])){

            $data['image'] = ImageStore::saveImage($data['image'], 225, 225);

            if(@$category->categoryImage->image){
                ImageStore::deleteImage(@$category->categoryImage->image);
                @$category->categoryImage->update([
                    'image' => $data['image']
                ]);

            }else{
                CategoryImage::create([
                    'category_id' => $id,
                    'image' => $data['image']
                ]);
            }
        }

        return true;
    }

    public function delete($id)
    {

        $category = $this->category->findOrFail($id);

        if (count($category->products) > 0 || count($category->subCategories) > 0
        || count($category->newUserZoneCategories) > 0 || count($category->newUserZoneCouponCategories) > 0 ||
         count($category->MenuElements) > 0 || count($category->MenuRightPanel) > 0 || count($category->Silders) > 0 || count($category->headerCategoryPanel) > 0 ||
          count($category->homepageCustomCategories) > 0) {
            return "not_possible";
        }

        if(@$category->categoryImage){
            ImageStore::deleteImage(@$category->categoryImage->image);
        }
        $category->delete();

        return 'possible';
    }

    public function checkParentId($id){
        $categories = Category::where('parent_id',$id)->get();
    }

    public function show($id)
    {
        $category = $this->category->with('categoryImage', 'parentCategory', 'subCategories.subCategories')->where('id', $id)->first();
        return $category;
    }

    public function edit($id){
        $category = $this->category->findOrFail($id);
        return $category;
    }

    public function findBySlug($slug)
    {
        return $this->category->where('slug', $slug)->first();
    }

    public function csvUploadCategory($data)
    {
        Excel::import(new CategoryImport, $data['file']->store('temp'));
    }

    public function csvDownloadCategory()
    {
        if (file_exists(storage_path("app/category_list.xlsx"))) {
          unlink(storage_path("app/category_list.xlsx"));
        }
        return Excel::store(new CategoryExport, 'category_list.xlsx');
    }
}
