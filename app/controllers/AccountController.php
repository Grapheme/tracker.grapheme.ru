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

        $profile = Auth::user();
		return View::make(Helper::acclayout('profile'),compact('profile'));
	}

    public function profileUpdate(){

        $validator = Validator::make(Input::all(),['hour_price'=>'required']);
        if($validator->passes()):
            $user = Auth::user();
            $user->hour_price = Input::get('hour_price');
            $user->save();
            $user->touch();
            return Redirect::back()->with('message','Профиль сохранен');
        else:
            return Redirect::back()->withErrors($validator)->withInput(Input::all());
        endif;
	}
    /*********************************************************************************************/
}