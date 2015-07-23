<?php

class AccountController extends \BaseController {

	public function __construct(){

	}

	public function dashboard(){

		return View::make(Helper::acclayout('dashboard'));
	}

	public function settings(){

		return View::make(Helper::acclayout('settings'));
	}

	public function profile(){

        $profile = User::where('id',Auth::user()->id)->with('avatar')->first();
        $requisites = Auth::user()->getRequisites;
		return View::make(Helper::acclayout('profile'),compact('profile','requisites'));
	}

    public function profileUpdate(){

        $validator = Validator::make(Input::all(),['hour_price'=>'required']);
        if($validator->passes()):
            $user = Auth::user();
            $user->hour_price = Input::get('hour_price');
            if ($image_path = UploadsController::getUploadedFile('avatar', TRUE)):
                if($old_image_path = Upload::where('id', Auth::user()->image_id)->pluck('path')):
                    if(File::exists(public_path($old_image_path))):
                        File::delete(public_path($old_image_path));
                        Upload::where('id', Auth::user()->image_id)->delete();
                    endif;
                endif;
                $upload = new Upload();
                $upload->path = $image_path;
                $upload->original_name = 'avatar.jpg';
                $upload->filesize = 0;
                $upload->mimetype = '';
                $upload->save();
                $user->image_id = $upload->id;
            endif;
            $user->save();
            $user->touch();
            return Redirect::back()->with('message','Профиль сохранен');
        else:
            return Redirect::back()->withErrors($validator)->withInput(Input::all());
        endif;
	}

    public function profileRequisitesUpdate(){

        $validator = Validator::make(Input::all(),UserRequisites::$rules);
        if($validator->passes()):
            if (Input::has('id')):
                UserRequisites::where('superior_id',Auth::user()->id)->where('id',Input::get('id'))->update(Input::except('_method','_token','id','logo'));
            else:
                $requisites = new UserRequisites(Input::except('_method','_token','id','logo'));
                Auth::user()->requisites()->save($requisites);
            endif;
            if (Input::hasFile('logo')):
                $image = UserRequisites::where('superior_id',Auth::user()->id)->pluck('logo');
                if (!empty($image) && File::exists(public_path($image))):
                    File::delete(public_path($image));
                endif;
            endif;
            if($image = UploadsController::getUploadedImageManipulationFile(FALSE,'logo')):
                UserRequisites::where('superior_id',Auth::user()->id)->update(['logo'=>$image]);
            endif;
            return Redirect::back()->with('message','Реквизиты сохранены');
        else:
            return Redirect::back()->withErrors($validator)->withInput(Input::all());
        endif;
	}
    /*********************************************************************************************/
}