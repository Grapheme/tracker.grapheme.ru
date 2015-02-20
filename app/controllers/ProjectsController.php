<?php

class ProjectsController extends \BaseController {

	public function __construct(){

	}

	public function index(){

		$projects = Project::where('superior_id',Auth::user()->id)->orderBy('updated_at','DESC')->with('team','tasks')->get();
        return View::make(Helper::acclayout('projects.list'),compact('projects'));
	}

	public function create(){

		$project_team = array();
		if($team = Team::where('superior_id',Auth::user()->id)->with('cooperator')->get()):
			foreach($team as $user):
				$project_team[$user->cooperator->id] = $user->cooperator->fio;
			endforeach;
		endif;
		return View::make(Helper::acclayout('projects.create'),compact('project_team'));
	}

	public function store()	{

		$validator = Validator::make(Input::all(),Project::$rules);
		if($validator->passes()):
			if($project = Project::create(['superior_id' => Auth::user()->id, 'title' => Input::get('title'),'description' => Input::get('description')])):
				ProjectOwners::create(['project_id'=>$project->id,'user_id'=>Auth::user()->id,'hour_price'=>Input::get('hour_price'),'budget'=>Input::get('budget')]);
				if (Input::has('team')):
					Project::where('id',$project->id)->first()->owners()->sync([Auth::user()->id]);
					$usersIDs = $users= array();
					foreach(Input::get('team') as $user):
						if (isset($user['user_id'])):
							$usersIDs[] = $user['user_id'];
							$users[$user['user_id']]['hour_price'] = $user['hour_price'] ? $user['hour_price'] : 0;
							$users[$user['user_id']]['budget'] = $user['budget'] ? $user['budget'] : 0;
						endif;
					endforeach;
					if ($usersIDs):
						Project::where('id',$project->id)->first()->team()->sync($usersIDs);
						foreach($users as $user_id => $user):
							ProjectTeam::where('project_id',$project->id)->where('user_id',$user_id)->update(['hour_price'=>$user['hour_price'],'budget'=>$user['budget']]);
						endforeach;
					endif;
				endif;
				return Redirect::route('projects.show',$project->id)->with('message','Проект создан успешно.');
			else:
				return Redirect::back()->with('message','Возникла ошибка при записи в БД');
			endif;
		else:
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		endif;
	}

	public function show($id){

		if($project = Project::where('id',$id)->where('superior_id',Auth::user()->id)->with('icon','team')->first()):
			$tasks = ProjectTask::where('project_id',$project->id)->with('cooperator','basecamp_task')->get();
			return View::make(Helper::acclayout('projects.show'),compact('project','tasks'));
		else:
			App::abort(404);
		endif;
	}

	public function edit($id){

		if($project = Project::where('id',$id)->where('superior_id',Auth::user()->id)->with('icon','team','owners')->first()):
			$project_team = $setProjectTeamIDs = $setProjectValues = array();
			foreach($project->owners as $owner):
				$setProjectValues[$owner->id]['hour_price'] = ($owner->hour_price > 0) ? $owner->hour_price : '';
				$setProjectValues[$owner->id]['budget'] = ($owner->budget > 0) ? $owner->budget : '';
			endforeach;
			if($team = Team::where('superior_id',Auth::user()->id)->with('cooperator')->get()):
				foreach($team as $user):
					$project_team[$user->cooperator->id] = $user->cooperator->fio;
				endforeach;
			endif;
			if (count($project->team)):
				foreach($project->team as $user):
					$setProjectTeamIDs[] = $user->id;
					$setProjectValues[$user->id]['hour_price'] = ($user->hour_price > 0) ? $user->hour_price : '';
					$setProjectValues[$user->id]['budget'] = ($user->budget > 0) ? $user->budget : '';
				endforeach;
			endif;
			return View::make(Helper::acclayout('projects.edit'),compact('project','project_team','setProjectTeamIDs','setProjectValues'));
		else:
			App::abort(404);
		endif;
	}

	public function update($id){

		$validator = Validator::make(Input::all(),Project::$rules);
		if($validator->passes()):
			Project::where('id',$id)->where('superior_id',Auth::user()->id)->update(['title' => Input::get('title'),'description' => Input::get('description')]);
			ProjectOwners::where('project_id',$id)->where('user_id',Auth::user()->id)->update(['hour_price'=>Input::get('hour_price'),'budget'=>Input::get('budget')]);
			if (Input::has('team')):
				Project::where('id',$id)->first()->owners()->sync([Auth::user()->id]);
				$usersIDs = $users= array();
				foreach(Input::get('team') as $user):
					if (isset($user['user_id'])):
						$usersIDs[] = $user['user_id'];
						$users[$user['user_id']]['hour_price'] = $user['hour_price'] ? $user['hour_price'] : 0;
						$users[$user['user_id']]['budget'] = $user['budget'] ? $user['budget'] : 0;
					endif;
				endforeach;
				if ($usersIDs):
					Project::where('id',$id)->first()->team()->sync($usersIDs);
					foreach($users as $user_id => $user):
						ProjectTeam::where('project_id',$id)->where('user_id',$user_id)->update(['hour_price'=>$user['hour_price'],'budget'=>$user['budget']]);
					endforeach;
				endif;
			endif;
			return Redirect::route('projects.show',$id)->with('message','Проект сохранен успешно.');
		else:
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		endif;
	}

	public function destroy($id){

		if (Project::where('id',$id)->where('superior_id',Auth::user()->id)->first()):
			ProjectOwners::where('project_id',$id)->delete();
			ProjectTeam::where('project_id',$id)->delete();
			Project::where('id',$id)->delete();
			return Redirect::route('projects.index')->with('message','Проект удален успешно.');
		else:
			App::abort(404);
		endif;
	}
}