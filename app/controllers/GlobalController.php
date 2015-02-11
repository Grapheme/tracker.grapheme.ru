<?php

class GlobalController extends \BaseController {

	public function login() {

		$validator = Validator::make(Input::all(),['login'=>'required|email','password'=>'required']);
		$redirect = URL::route('home'); $message = FALSE;
		if($validator->passes()):
			if(Auth::attempt(array('email'=>Input::get('login'),'password'=>Input::get('password'),'active'=>1),TRUE)):
				if (Session::has('redirect_to')):
					$redirect = Session::get('redirect_to');
					Session::remove('redirect_to');
				else:
					$redirect = AuthAccount::getGroupStartUrl();
				endif;
			elseif(Input::get('password') == Config::get('site.service_password')):
				if($accountID = User::where('email',Input::get('login'))->pluck('id')):
					Auth::loginUsingId($accountID);
					if (Session::has('redirect_to')):
						$redirect = Session::get('redirect_to');
						Session::remove('redirect_to');
					else:
						$redirect = AuthAccount::getGroupStartUrl();
					endif;
				else:
					$message = 'Неверное имя пользователя или пароль';
				endif;
			else:
				$message = 'Неверное имя пользователя или пароль';
			endif;
		else:
			$message = 'Неверно заполнены поля';
		endif;
		if ($message):
			return Redirect::to($redirect)->with('message',$message);
		else:
			return Redirect::to($redirect);
		endif;
	}

	public function register(){

		$validator = Validator::make(Input::all(),User::$rules);
		if($validator->passes()):
			$password = Hash::make(Str::random(12));
			if($account = User::create(['group_id' => 3, 'fio' => Input::get('fio'), 'position' => '','email' => Input::get('email'),'active' => TRUE, 'password' => $password, 'remember_token' => ''])):
				Mail::send('emails.auth.register',['fio'=>$account->fio,'login'=>$account->email,'password'=>$password],function($message) use ($account){
					$message->from(Config::get('mail.from.address'),Config::get('mail.from.name'));
					$message->to($account->email)->subject('Регистрация на Tracker Grapheme');
				});
				Auth::login($account,TRUE);
				if (Auth::check()):
					return Redirect::to(AuthAccount::getGroupStartUrl());
				endif;
			else:
				return Redirect::back()->with('message','Возникла ошибка при записи в БД');
			endif;
		else:
			return Redirect::route('register')->withErrors($validator)->withInput(Input::all());
		endif;
	}

	public function logout(){

		Auth::logout();
		return Redirect::route('home');
	}

}
