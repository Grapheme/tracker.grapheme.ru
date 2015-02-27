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
			$password = Str::random(12);
			if($account = User::create(['group_id' => 3, 'fio' => Input::get('fio'), 'position' => '','email' => Input::get('email'),'active' => TRUE, 'password' => Hash::make($password), 'remember_token' => ''])):
				Mail::send('emails.auth.register',['fio'=>$account->fio,'login'=>$account->email,'password'=>$password],function($message) use ($account){
					$message->from(Config::get('mail.from.address'),Config::get('mail.from.name'));
					$message->to($account->email)->subject('Регистрация на Tracker Grapheme');
				});
				Auth::login($account,TRUE);
				if (Auth::check()):
                    if (Input::has('invite_id') && Input::has('invitee_user_id') && Input::has('invite_redirect')):
                        if (Invite::where('id',Input::get('invite_id'))->exists()):
                            Invite::where('id',Input::get('invite_id'))->update(['note'=>json_encode(['redirect'=> FALSE,'params'=>['invitee_account'=>Input::get('invitee_user_id'),'redirect'=>URL::to(AuthAccount::getGroupStartUrl())]])]);
                            return Redirect::to(Input::get('invite_redirect'));
                        endif;
                    endif;
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

	public static function inviteRegister($data){

		if (!isset($data['password'])):
			$data['password'] = Config::get('site.default_password');
		endif;
		if($account = User::create(['group_id' => 4, 'fio' => $data['fio'], 'position' => $data['position'],'email' => $data['email'],'active' => TRUE, 'password' => Hash::make($data['password']), 'remember_token' => ''])):
			Mail::send('emails.auth.register',['fio'=>$account->fio,'login'=>$account->email,'password'=>$data['password']],function($message) use ($account){
				$message->from(Config::get('mail.from.address'),Config::get('mail.from.name'));
				$message->to($account->email)->subject('Регистрация на Tracker Grapheme');
			});
			return $account;
		endif;
		return FALSE;
	}

    public function invite($token){

        if ($invite = Invite::where('token',$token)->first()):
            if ($invite->status == 1):
                return Redirect::route('home')->with('message','Проверочный код устарел.');
            endif;
            $note = json_decode($invite->note,TRUE);
            if ($note['redirect']):
                if (Auth::check()):
                    self::logout();
                endif;
                return Redirect::to($note['redirect'])->withInput(['email'=>$invite->email])->with('invite_id',$invite->id)->with('invitee_user_id',$note['params']['invitee_account'])->with('invite_redirect',$note['params']['redirect']);
            else:
                if($invitee = User::where('id',$note['params']['invitee_account'])->first()):
                    if($accountID = User::where('email',$invite->email)->pluck('id')):
                        Auth::loginUsingId($accountID);
                        $invite->note = $note;
                        if (Team::where('superior_id',$invite->user_id)->where('cooperator_id',$accountID)->exists()):
                            return Redirect::route('dashboard');
                        else:
                            Team::create(['superior_id'=>$invite->user_id,'cooperator_id'=>$accountID]);
                            Invite::where('id',$invite->id)->update(['status'=>1,'updated_at'=>date("Y-m-d H:i:s")]);
                            return View::make(Helper::layout('invite'),compact('invitee'))->with('redirect',URL::route('dashboard'));
                        endif;
                    endif;
                endif;
            endif;
        endif;
        App::abort(404);
    }
}
