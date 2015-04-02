<?php

class UploadsController extends BaseController {

	public function __construct(){

    }

    /****************************************************************************/

    public static function getUploadedFile($input = 'file'){

        if (Input::hasFile($input)):
            $fileName = time()."_".Auth::user()->id."_".rand(1000, 1999).'.'.Input::file($input)->getClientOriginalExtension();
            Input::file($input)->move(Config::get('site.uploads_full_path').'/', $fileName);
            return Config::get('site.uploads_path').'/'.$fileName;
        endif;
        return null;
    }

    public static function getUploadedImageManipulationFile($thumb = TRUE,$input = 'file'){

        if(Input::hasFile($input)):
            $fileName = time()."_".Auth::user()->id."_".rand(1000, 1999).'.'.Input::file($input)->getClientOriginalExtension();
            if(!File::exists(Config::get('site.images_thumbs_full_path'))):
                File::makeDirectory(Config::get('site.images_thumbs_full_path'),0777,TRUE);
            endif;
            ImageManipulation::make(Input::file($input)->getRealPath())->save(Config::get('site.images_path').'/'.$fileName);
            if ($thumb):
                ImageManipulation::make(Input::file($input)->getRealPath())->resize(200, 200)->save(Config::get('site.images_thumbs_full_path').'/thumb_'.$fileName);
                return array('main'=>Config::get('site.images_path').'/'.$fileName,'thumb'=>Config::get('site.images_thumbs_path').'/thumb_'.$fileName);
            endif;
            return Config::get('site.images_path').'/'.$fileName;
        endif;
        return FALSE;
    }
}


