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

        $profile = Auth::user();
		return View::make(Helper::acclayout('profile'),compact('profile'));
	}

    public function profileUpdate(){

        $validator = Validator::make(Input::all(),['hour_price'=>'required']);
        if($validator->passes()):
            $user = Auth::user();
            $user->hour_price = Input::get('hour_price');
            $user->save();
            $user->touch();
            return Redirect::back()->with('message','Профиль сохранен');
        else:
            return Redirect::back()->withErrors($validator)->withInput(Input::all());
        endif;
	}

    public function report(){

        $startOfDay = Input::has('begin_date') ? \Carbon\Carbon::createFromFormat('Y-m-d',Input::get('begin_date'))->format('Y-m-d 00:00:00') : \Carbon\Carbon::createFromDate(2000,1,1,'GMT')->format('Y-m-d 00:00:00');
        $endOfDay = Input::has('end_date') ? \Carbon\Carbon::createFromFormat('Y-m-d',Input::get('end_date'))->format('Y-m-d 00:00:00') : \Carbon\Carbon::now()->format('Y-m-d 00:00:00');
        $tasks = [];
        if (Input::has('project')):
            if(ProjectOwners::where('project_id',Input::get('project'))->where('user_id',Auth::user()->id)->exists()):
                $tasks = ProjectTask::where('project_id',Input::get('project'))->where('stop_status',1)->whereBetween('set_date',[$startOfDay,$endOfDay])->with('project','project.client')->get();
            endif;
        elseif(Input::has('client')):
            if($ProjectIDs = Clients::where('id',Input::get('client'))->first()->projects()->lists('id')):
                $tasks = ProjectTask::whereIn('project_id',$ProjectIDs)->where('stop_status',1)->whereBetween('set_date',[$startOfDay,$endOfDay])->with('project','project.client')->get();
            endif;
        elseif(Input::has('user')):
            if (Input::get('user') == Auth::user()->id):
                $tasks = ProjectTask::where('user_id',Auth::user()->id)->where('stop_status',1)->whereBetween('set_date',[$startOfDay,$endOfDay])->with('project','project.client')->get();
            elseif(Team::where('superior_id',Input::get('user'))->where('cooperator_id',Auth::user()->id)->exists()):
                $tasks = ProjectTask::where('user_id',Input::get('user'))->where('stop_status',1)->whereBetween('set_date',[$startOfDay,$endOfDay])->with('project','project.client')->get();
            elseif(Team::where('superior_id',Auth::user()->id)->where('cooperator_id',Input::get('user'))->exists()):
                $tasks = ProjectTask::where('user_id',Input::get('user'))->where('stop_status',1)->whereBetween('set_date',[$startOfDay,$endOfDay])->with('project','project.client')->get();
            endif;
        else:
            $tasks = ProjectTask::where('user_id',Auth::user()->id)->where('stop_status',1)->whereBetween('set_date',[$startOfDay,$endOfDay])->with('project','project.client')->get();
        endif;
        return View::make(Helper::acclayout('report'),compact('tasks'));
    }
}