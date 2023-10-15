<?php

namespace Modules\Appearance\Repositories;

use App\Traits\ImageStore;
use Modules\GeneralSetting\Entities\GeneralSetting;

class PreloaderRepository
{
    use ImageStore;

    public function updatePreloader($data){
        if(!empty($data['preloader_image'])){
            $this->deleteImage(app('general_setting')->preloader_image);
            $img = $this->saveSettingsImage($data['preloader_image']);
        }else{
            $img = app('general_setting')->preloader_image;
        }

        GeneralSetting::first()->update([
            'preloader_status' => $data['preloader_status'],
            'preloader_type' => $data['preloader_type'],
            'preloader_image' => $img,
            'preloader_style' => $data['preloader_style']
        ]);

        return true;
    }
}
