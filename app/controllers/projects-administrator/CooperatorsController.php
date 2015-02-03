<?php

class CooperatorsController extends \BaseController {

	public function __construct(){

	}

	public function index(){

		$users = Team::where('superior_id',Auth::user()->id)->orderBy('updated_at','DESC')->with('cooperator.avatar')->get();
		return View::make(Helper::acclayout('cooperators.list'),compact('users'));
	}

	public function create(){

		return View::make(Helper::acclayout('cooperators.create'));
	}

	public function store()	{

		$validator = Validator::make(Input::all(),User::$rules);
		if($validator->passes()):
			$password = Hash::make(Str::random(12));
			if($account = User::create(['group_id' => 4, 'email' => Input::get('email'),'fio' => Input::get('fio'),'position' => Input::get('position'),'active' => 1, 'password' => $password])):
				Team::create(['superior_id' => Auth::user()->id,'cooperator_id' => $account->id]);
				Mail::send('emails.auth.register',['fio'=>$account->fio,'login'=>$account->email,'password'=>$password],function($message) use ($account){
					$message->from(Config::get('mail.from.address'),Config::get('mail.from.name'));
					$message->to($account->email)->subject('Регистрация на Tracker Grapheme');
				});
				return Redirect::route('project_admin.cooperators.index')->with('message','Сотрудник добавлен успешно.');
			else:
				return Redirect::back()->with('message','Возникла ошибка при записи в БД');
			endif;
		else:
			return Redirect::route('project_admin.cooperators.create')->withErrors($validator)->withInput(Input::all());
		endif;
	}

	public function show($id){

		if (Auth::user()->id == $id):
			return Redirect::route('profile');
		elseif($user = Team::where('superior_id',Auth::user()->id)->where('cooperator_id',$id)->first()->cooperator):
			return View::make(Helper::acclayout('cooperators.show'),compact('user'));
		else:
			App::abort(404);
		endif;
	}

	public function edit($id){

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
				User::where('id',$id)->update(['fio' => Input::get('fio'),'position' => Input::get('position')]);
				return Redirect::route('project_admin.cooperators.show',$id)->with('message','Профиль сотрудника сохранен успешно.');
			else:
				App::abort(404);
			endif;
		else:
			return Redirect::route('project_admin.cooperators.edit',$id)->withErrors($validator)->withInput(Input::all());
		endif;
	}

	public function destroy($id){

		if (Team::where('superior_id',Auth::user()->id)->where('cooperator_id',$id)->exists()):
			User::where('id',$id)->delete();
			Team::where('cooperator_id',$id)->delete();
			ProjectTeam::where('user_id',$id)->delete();
			return Redirect::route('project_admin.cooperators.index')->with('message','Профиль сотрудника удален успешно.');
		else:
			App::abort(404);
		endif;
	}

}