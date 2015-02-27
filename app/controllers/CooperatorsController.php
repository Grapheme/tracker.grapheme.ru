<?php

class CooperatorsController extends \BaseController {

	public function __construct(){

	}

	public function index(){

		$users = Team::where('superior_id',Auth::user()->id)->orWhere('cooperator_id',Auth::user()->id)->groupBy('superior_id')->groupBy('cooperator_id')->orderBy('updated_at','DESC')->with('cooperator.avatar','cooperator.tasks','superior.avatar','superior.tasks')->get();
		$invites = Invite::where('user_id',Auth::user()->id)->where('status',0)->get();
        return View::make(Helper::acclayout('cooperators.list'),compact('users','invites'));
	}

	public function create(){

		return View::make(Helper::acclayout('cooperators.create'));
	}

	public function store()	{

		$validator = Validator::make(Input::all(),User::$invite_rules);
		if($validator->passes()):
            if(Auth::user()->email == Input::get('email')):
                return Redirect::back()->with('message','Нельзя приглашать самого себя :)');
            elseif(Invite::where('user_id',Auth::user()->id)->where('email',Input::get('email'))->where('status',0)->exists()):
                return Redirect::back()->with('message','Заявка уже отправлена');
            elseif ($account = User::where('email',Input::get('email'))->first()):
                if (Team::where('superior_id',Auth::user()->id)->where('cooperator_id',$account->id)->exists()):
                    return Redirect::back()->with('message','Сотрудник уже в Вашей команде.');
                endif;
                $token = Str::random(48);
                Invite::create(['user_id'=>Auth::user()->id,'email'=>Input::get('email'),'token'=>$token,'note'=>json_encode(['redirect'=> FALSE,'params'=>['invitee_account'=>Auth::user()->id,'redirect'=>URL::route('dashboard')]])]);
                Mail::send('emails.invite.exists',['token'=>$token],function($message) use ($account){
                    $message->from(Config::get('mail.from.address'),Config::get('mail.from.name'));
                    $message->to($account->email)->subject('Приглашение');
                });
                return Redirect::back()->with('message','Приглашение отправлено.');
            else:
                $token = Str::random(48);
                Invite::create(['user_id'=>Auth::user()->id,'email'=>Input::get('email'),'token'=>$token,'note'=>json_encode(['redirect'=>URL::route('register'),'params'=>['invitee_account'=>Auth::user()->id,'redirect'=>URL::route('invite',$token)]])]);
                Mail::send('emails.invite.register',['token'=>$token],function($message) use ($account){
                    $message->from(Config::get('mail.from.address'),Config::get('mail.from.name'));
                    $message->to(Input::get('email'))->subject('Приглашение');
                });
                return Redirect::back()->with('message','Приглашение отправлено.');
            endif;
		else:
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		endif;
	}

	public function show($id){

		if (Auth::user()->id == $id):
			return Redirect::route('profile');
		elseif(Team::where('superior_id',Auth::user()->id)->where('cooperator_id',$id)->exists()):
            $user = Team::where('superior_id',Auth::user()->id)->where('cooperator_id',$id)->first()->cooperator;
			$tasks = ProjectTask::where('user_id',$id)->get();
			return View::make(Helper::acclayout('cooperators.show'),compact('user','tasks'))->with('access',TRUE);
        elseif(Team::where('superior_id',$id)->where('cooperator_id',Auth::user()->id)->exists()):
            $user = Team::where('superior_id',$id)->where('cooperator_id',Auth::user()->id)->first()->superior;
            $tasks = ProjectTask::where('user_id',$id)->get();
            return View::make(Helper::acclayout('cooperators.show'),compact('user','tasks'))->with('access',FALSE);
		else:
			App::abort(404);
		endif;
	}

	public function edit($id){

        App::abort(404);

		if($user = Team::where('superior_id',Auth::user()->id)->where('cooperator_id',$id)->first()->cooperator()->with('avatar')->first()):
			return View::make(Helper::acclayout('cooperators.edit'),compact('user'));
		else:
			App::abort(404);
		endif;
	}

	public function update($id){

		$validator = Validator::make(Input::all(),User::$update_rules);
		if($validator->passes()):
			if (Team::where('superior_id',Auth::user()->id)->where('cooperator_id',$id)->exists()):
				User::where('id',$id)->update(['fio' => Input::get('fio'),'position' => Input::get('position'),'hour_price' => Input::get('hour_price')]);
				return Redirect::route('cooperators.show',$id)->with('message','Профиль сотрудника сохранен успешно.');
			else:
				App::abort(404);
			endif;
		else:
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		endif;
	}

	public function destroy($id){

		if (Team::where('superior_id',Auth::user()->id)->where('cooperator_id',$id)->exists()):
			Team::where('cooperator_id',$id)->delete();
			ProjectTeam::where('user_id',$id)->delete();
			return Redirect::route('cooperators.index')->with('message','Сотрудник исключен успешно.');
		else:
			App::abort(404);
		endif;
	}

    public function inviteReject($invite_id){

        if (Invite::where('user_id',Auth::user()->id)->where('id',$invite_id)->where('status',0)->delete()):
            return Redirect::route('cooperators.index')->with('message','Заявка отклонена успешно.');
        else:
            App::abort(404);
        endif;
    }
}