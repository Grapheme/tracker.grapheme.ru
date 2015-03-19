<?php

class RemindersController extends Controller {

	public function getRemind(){

        return Redirect::back();
		#return View::make('password.remind');
	}

	public function postRemind(){

        $validator = Validator::make(Input::all(),array('email'=>'required|email'));
        if($validator->passes()):
            $response = Password::remind(Input::only('email'),function($message, $user){
                $message->from(Config::get('mail.from.address'),Config::get('mail.from.name'));
                $message->subject('Tracker - Новый пароль');
            });
            switch ($response):
                case Password::REMINDER_SENT:
                    return Redirect::back()->with('message', Lang::get($response));
                case Password::INVALID_USER:
                    return Redirect::back()->with('message', Lang::get($response));
            endswitch;
        else:
            return Redirect::back()->with('message', 'Возникла ошибка.');
        endif;

	}

	public function getReset($token = null){

		if (is_null($token)) App::abort(404);
        if (Auth::check()):
            return View::make(Helper::acclayout('profile'))->with('token', $token)->with('status','password-remind');
        else:
            $email = DB::table('password_reminders')->where('token',$token)->pluck('email');
            return View::make(Helper::layout('password-remind'))->with('token', $token)->with('email',$email);
        endif;
	}

	public function postReset(){

		$credentials = Input::only('email','password','password_confirmation','token');
		$response = Password::reset($credentials, function($user,$password){
			$user->password = Hash::make($password);
			$user->save();
		});
		switch ($response):
			case Password::INVALID_PASSWORD:
			case Password::INVALID_TOKEN:
			case Password::INVALID_USER:
				return Redirect::back()->with('message', Lang::get($response));
			case Password::PASSWORD_RESET:
                Auth::attempt(array('email'=>Input::get('email'),'password'=>Input::get('password'),'active'=>1),TRUE);
                if (Auth::check()):
                    return Redirect::to(AuthAccount::getStartPage('profile'))->with('message', Lang::get($response));
                else:
                    return Redirect::to('/')->with('message', Lang::get($response));
                endif;
		endswitch;
	}
}
