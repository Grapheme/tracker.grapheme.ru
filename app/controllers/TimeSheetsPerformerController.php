<?php

class TimeSheetsPerformerController extends \BaseController {

	public function __construct(){

	}

	public function index(){

		$tasks = $weekTasks = array();
		$dt_request = Request::has('date') ? Request::get('date') : $dt_request = date('Y-m-d');
		$startOfDay = (new \Carbon\Carbon($dt_request))->hour(0)->minute(0)->second(0);
		$endOfDay = (new \Carbon\Carbon($dt_request))->hour(23)->minute(59)->second(59);
		$startOfWeek = (new \Carbon\Carbon($startOfDay))->startOfWeek()->hour(0)->minute(0)->second(0);
		$endOfWeek = (new \Carbon\Carbon($startOfDay))->endOfWeek()->hour(23)->minute(59)->second(59);
		for($day = 0; $day < 7; $day++):
			$index = (new \Carbon\Carbon($dt_request))->startOfWeek()->AddDays($day);
			$weekTasks[$index->format('Y-m-d')] = ['label'=>$index->format('d.m'),'lead_time'=>'0:00','tasks_count'=>0];
		endfor;
		$projectsIDs = array();
		foreach(User::where('id',Auth::user()->id)->first()->cooperator_projects()->get() as $project):
			$projectsIDs[] = $project->id;
		endforeach;
		if($projectsIDs):
			$tasks = ProjectTask::where('user_id',Auth::user()->id)->whereBetween('set_date',[$startOfDay,$endOfDay])->with('project')->get();
			foreach(ProjectTask::where('user_id',Auth::user()->id)->whereBetween('set_date',[$startOfWeek,$endOfWeek])->get() as $task):
				$index = (new myDateTime())->setDateString($task->set_date);
				$weekTasks[$index->format('Y-m-d')]['lead_time'] += (getLeadTimeMinutes($task)+floor($task->lead_time/60));
				$weekTasks[$index->format('Y-m-d')]['tasks_count'] += 1;
			endforeach;
		endif;
		return View::make(Helper::acclayout('timesheets.list'),compact('tasks','weekTasks','dt_request','startOfWeek','endOfWeek'));
	}

	public function create(){

		return View::make(Helper::acclayout('timesheets.create'));
	}

	public function store()	{

		$validator = Validator::make(Input::all(),ProjectTask::$rules);
		$set_date = Input::get('set_date') ? Input::get('set_date') : date('Y-m-d');
		if($validator->passes()):
			if (ProjectTask::where('user_id',Auth::user()->id)->where('start_status',1)->where('stop_status',0)->exists()):
				$start_status = 0;
				$start_date = '0000-00-00 00:00:00';
			else:
				$start_status = 1;
				$start_date = date('Y-m-d H:i:s');
			endif;
			if($task = ProjectTask::create(['project_id' => Input::get('project'), 'user_id' => Input::get('performer'),'note' => Input::get('note'),'start_status' => $start_status,'start_date' => $start_date,'set_date'=>$set_date,'lead_time'=>str2secLeadTime(Input::get('lead_time'))])):
				return Redirect::route('timesheets.index',['date'=>$set_date])->with('message','Задача создана успешно.');
			else:
				return Redirect::back()->with('message','Возникла ошибка при записи в БД');
			endif;
		else:
			return Redirect::route('timesheets.create',['date'=>$set_date])->withErrors($validator)->withInput(Input::all());
		endif;
	}

	public function edit($id){

		if($task = ProjectTask::where('id',$id)->where('user_id',Auth::user()->id)->first()):
			return View::make(Helper::acclayout('timesheets.edit'),compact('task'));
		else:
			App::abort(404);
		endif;
	}

	public function update($id){

		$set_date = Input::get('set_date') ? Input::get('set_date') : date('Y-m-d');
		$validator = Validator::make(Input::all(),ProjectTask::$update_rules);
		if($validator->passes()):
			ProjectTask::where('id',$id)->where('user_id',Auth::user()->id)->update(['note'=>Input::get('note'),'updated_at'=>date('Y-m-d H:i:s')]);
			return Redirect::route('timesheets.index',['data'=>$set_date])->with('message','Задача сохранена успешно.');
		else:
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		endif;
	}

	public function destroy($id){

		if ($task = ProjectTask::where('id',$id)->where('user_id',Auth::user()->id)->first()):
			$task->delete();
			return Redirect::back()->with('message','Задача удалена успешно.');
		endif;
		App::abort(404);
	}

	public function RunningTimer(){

		$validator = Validator::make(Input::all(),['task'=>'integer|required','run'=>'integer|required']);
		if($validator->passes()):
			if ($task = ProjectTask::where('id',Input::get('task'))->where('user_id',Auth::user()->id)->first()):
				if (Input::get('run') == 0):
					$task->stop_status = 1;
					$task->stop_date = date('Y-m-d H:i:s');
				elseif(Input::get('run') == 1):
					$dt = myDateTime::getDiffTimeStamp($task->stop_date,$task->start_date);
					$task->lead_time += $dt > 60 ? $dt : 0;
					$task->start_status = 1;
					$task->start_date = date('Y-m-d H:i:s');
					$task->stop_status = 0;
					$task->stop_date = '0000-00-00 00:00:00';
					ProjectTask::where('user_id',Auth::user()->id)->where('stop_status',0)->where('id','!=',$task->id)->update(['stop_status'=>1,'stop_date'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')]);
				endif;
				$task->save();
				$task->touch();
				return Redirect::back();
			endif;
		endif;
		App::abort(404);
	}
}