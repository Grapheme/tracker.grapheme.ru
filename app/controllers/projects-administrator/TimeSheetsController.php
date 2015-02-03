<?php

class TimeSheetsController extends \BaseController {

	public function __construct(){

	}

	public function index(){

		$tasks = array();
		if($projectsIDs = Project::where('superior_id',Auth::user()->id)->lists('id')):
			if (Request::has('date')):
				$start = (new \Carbon\Carbon(Request::get('date')))->hour(0)->minute(0)->second(0);
				$end = (new \Carbon\Carbon(Request::get('date')))->hour(23)->minute(59)->second(59);
			else:
				$start = (new \Carbon\Carbon('now'))->hour(0)->minute(0)->second(0);
				$end = (new \Carbon\Carbon('now'))->hour(23)->minute(59)->second(59);
			endif;
			$tasks = ProjectTask::whereIn('project_id',$projectsIDs)->whereBetween('set_date',[$start,$end])->with('cooperator','project')->get();
		endif;
		return View::make(Helper::acclayout('timesheets.list'),compact('tasks'));
	}

	public function create(){

		$project_team[Auth::user()->id] = Auth::user()->fio;
		if($team = Team::where('superior_id',Auth::user()->id)->with('cooperator')->get()):
			foreach($team as $user):
				$project_team[$user->cooperator->id] = $user->cooperator->fio;
			endforeach;
		endif;
		return View::make(Helper::acclayout('timesheets.create'),compact('project_team'));
	}

	public function store()	{

		$validator = Validator::make(Input::all(),ProjectTask::$rules);
		if($validator->passes()):
			$set_date = Input::get('set_date') ? Input::get('set_date') : date('Y-m-d');
			if($task = ProjectTask::create(['project_id' => Input::get('project'), 'user_id' => Input::get('performer'),'note' => Input::get('note'),'start_status' => 1,'start_date' => date('Y-m-d H:i:s'),'set_date'=>$set_date,'lead_time'=>str2secLeadTime(Input::get('lead_time'))])):
				return Redirect::route('project_admin.timesheets.index',['date'=>$set_date])->with('message','Задача создана успешно.');
			else:
				return Redirect::back()->with('message','Возникла ошибка при записи в БД');
			endif;
		else:
			return Redirect::route('project_admin.timesheets.create')->withErrors($validator)->withInput(Input::all());
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
					$project_team[] = $user->id;
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
			return Redirect::route('project_admin.projects.show',$id)->with('message','Проект сохранен успешно.');
		else:
			return Redirect::route('project_admin.projects.edit',$id)->withErrors($validator)->withInput(Input::all());
		endif;
	}

	public function destroy($id){

		if ($task = ProjectTask::where('id',$id)->first()):
			if(Project::where('id',$task->project_id)->where('superior_id',Auth::user()->id)->first()):
				$task->delete();
				return Redirect::route('project_admin.timesheets.index')->with('message','Задача удалена успешно.');
			endif;
		endif;
		App::abort(404);
	}
}