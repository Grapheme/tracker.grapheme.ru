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

	protected function oauth_url(){

		$callback = URL::route('oauth.callback');
		$url = Config::get('basecamp.urlAuthorize').'?&client_id='.Config::get('basecamp.client_id').'&redirect_uri='.Config::get('basecamp.redirect_uri');
		return Redirect::to($url.'&type=web_server');
	}

	protected function oauth_callback(){

		try {
			$url = Config::get('basecamp.urlAccessToken').'?type=web_server&client_id='.Config::get('basecamp.client_id').'&redirect_uri='.Config::get('basecamp.redirect_uri').'&client_secret='.Config::get('basecamp.client_secret').'&code='.Input::get('code');
			$result = self::post_request($url);
			$json_result = json_decode($result,TRUE);
			if (isset($json_result['access_token'])):
				$result = file_get_contents(Config::get('basecamp.urlUserDetails').'?access_token='.$json_result['access_token']);
				$json_result = json_decode($result,TRUE);
				Helper::tad($json_result);
			endif;
		} catch (Exception $e) {
			if ($error = Input::get('error')):
				return Redirect::route('dashboard')->withErrors($error);
			endif;
			App::abort(404);
		}

	}

	protected function post_request($url){

		$opts = array('http' =>
			array(
				'method'  => 'POST',
				'header'  => "Content-type: application/x-www-form-urlencoded",
				'content' => '',
				'timeout' => 60
			)
		);
		$context  = stream_context_create($opts);
		return file_get_contents($url, false, $context, -1, 40000);
	}
}
