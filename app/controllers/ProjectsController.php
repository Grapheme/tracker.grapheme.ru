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
			if($project = Project::create(['superior_id' => Auth::user()->id, 'title' => Input::get('title'),'description' => Input::get('description'),'hour_price' => Input::get('hour_price')])):
				if (Input::has('team')):
					Project::where('id',$project->id)->first()->team()->sync(Input::get('team'));
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
			$tasks = ProjectTask::where('project_id',$project->id)->with('cooperator')->get();
			return View::make(Helper::acclayout('projects.show'),compact('project','tasks'));
		else:
			App::abort(404);
		endif;
	}

	public function edit($id){

		if($project = Project::where('id',$id)->where('superior_id',Auth::user()->id)->with('icon','team')->first()):
			$project_team = array();
			$set_project_team = array();
			if($team = Team::where('superior_id',Auth::user()->id)->with('cooperator')->get()):
				foreach($team as $user):
					$project_team[$user->cooperator->id] = $user->cooperator->fio;
				endforeach;
			endif;
			if (count($project->team)):
				foreach($project->team as $user):
					$set_project_team[] = $user->id;
				endforeach;
			endif;
			return View::make(Helper::acclayout('projects.edit'),compact('project','project_team','set_project_team'));
		else:
			App::abort(404);
		endif;
	}

	public function update($id){

		$validator = Validator::make(Input::all(),Project::$rules);
		if($validator->passes()):
			Project::where('id',$id)->where('superior_id',Auth::user()->id)->update(['title' => Input::get('title'),'description' => Input::get('description'),'hour_price' => Input::get('hour_price')]);
			if (Input::has('team')):
				Project::where('id',$id)->first()->team()->sync(Input::get('team'));
			endif;
			return Redirect::route('projects.show',$id)->with('message','Проект сохранен успешно.');
		else:
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		endif;
	}

	public function destroy($id){

		if (Project::where('id',$id)->where('superior_id',Auth::user()->id)->first()):
			ProjectTeam::where('project_id',$id)->delete();
			Project::where('id',$id)->delete();
			return Redirect::route('projects.index')->with('message','Проект удален успешно.');
		else:
			App::abort(404);
		endif;
	}
}