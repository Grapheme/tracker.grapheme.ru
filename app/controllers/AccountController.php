<?php

class AccountController extends \BaseController {

	public function __construct(){

	}

	public function dashboard(){

		return View::make(Helper::acclayout('dashboard'));
	}

	public function settings(){

		return View::make(Helper::acclayout('settings'));
	}

	public function profile(){

		return View::make(Helper::acclayout('profile'));
	}

    public function report(){

        $startOfDay = Input::has('begin_date') ? \Carbon\Carbon::createFromFormat('Y-m-d',Input::get('begin_date'))->format('Y-m-d 00:00:00') : \Carbon\Carbon::createFromDate(2000,1,1,'GMT')->format('Y-m-d 00:00:00');
        $endOfDay = Input::has('end_date') ? \Carbon\Carbon::createFromFormat('Y-m-d',Input::get('end_date'))->format('Y-m-d 00:00:00') : \Carbon\Carbon::now()->format('Y-m-d 00:00:00');
        $tasks = $tasksIDs = [];
        if($tasksList = ProjectTask::where('user_id',Auth::user()->id)->where('stop_status',1)->whereBetween('set_date',[$startOfDay,$endOfDay])->with('project','project.client')->get()):
            if (Input::has('client')):
                foreach($tasksList as $task):
                    if (!empty($task->project) && isset($task->project->client) && !empty($task->project->client)):
                        if ($task->project->client->id == Input::get('client')):
                            $tasksIDs[$task->id] = $task->id;
                        endif;
                    endif;
                endforeach;
            endif;
            if (Input::has('project')):
                foreach($tasksList as $task):
                    if (!empty($task->project)):
                        if ($task->project->id == Input::get('project')):
                            $tasksIDs[$task->id] = $task->id;
                        endif;
                    endif;
                endforeach;
            endif;
            if (!empty($tasksIDs)):
                $tasks = ProjectTask::whereIn('id',$tasksIDs)->get();
            endif;
        endif;
        return View::make(Helper::acclayout('report'),compact('tasks'));
    }
}