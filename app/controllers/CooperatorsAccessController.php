<?php

class CooperatorsAccessController extends \BaseController {

    public function __construct(){

    }

	public function index($cooperatorID){

        if(Team::where('superior_id',Auth::user()->id)->where('cooperator_id',$cooperatorID)->exists()):
            $user = Team::where('superior_id',Auth::user()->id)->where('cooperator_id',$cooperatorID)->first()->cooperator;
            $projects = ProjectOwners::where('user_id',Auth::user()->id)->with('project','project.client')->
                with(['project.team'=>function($query) use ($cooperatorID){
                    $query->where('users.id',$cooperatorID);
                }])->with(['project.owners'=>function($query) use ($cooperatorID){
                    $query->where('users.id',$cooperatorID);
                }])->get();
            return View::make(Helper::acclayout('cooperators.access'),compact('user','projects'));
        else:
            App::abort(404);
        endif;
	}

	public function store($cooperatorID){

        $validator = Validator::make(Input::all(),['superior_id'=>'required']);
        if($validator->passes()):
            $projectIDs = [];
            $projectHourPrices = [];
            if (Input::has('superior_hour_price')):
                foreach(Input::get('superior_hour_price') as $project_id => $price):
                    $projectHourPrices[$project_id] = $price;
                endforeach;
            endif;
            if (Input::has('owner')):
                foreach(Input::get('owner') as $project_id => $user_id):
                    $projectIDs[$project_id] = -1;
                endforeach;
            endif;
            if (Input::has('access')):
                foreach(Input::get('access') as $project_id => $access):
                    if (!isset($projectIDs[$project_id])):
                        $projectIDs[$project_id] = $access;
                    endif;
                endforeach;
            endif;
            if ($projectIDs):
                foreach($projectIDs as $project_id => $value):
                    switch ($value):
                        case -1 :
                            ProjectTeam::where('project_id',$project_id)->where('user_id',$cooperatorID)->delete();
                            if(ProjectOwners::where('project_id',$project_id)->where('user_id',$cooperatorID)->exists() === FALSE):
                                ProjectOwners::create(['project_id'=>$project_id,'user_id'=>$cooperatorID,'hour_price'=>@$projectHourPrices[$project_id]]);
                            endif;
                            break;
                        case 0 :
                            ProjectTeam::where('project_id',$project_id)->where('user_id',$cooperatorID)->delete();
                            ProjectOwners::where('project_id',$project_id)->where('user_id',$cooperatorID)->delete();
                            break;
                        case 1 :
                            ProjectOwners::where('project_id',$project_id)->where('user_id',$cooperatorID)->delete();
                            if(ProjectTeam::where('project_id',$project_id)->where('user_id',$cooperatorID)->exists() === FALSE):
                                ProjectTeam::create(['project_id'=>$project_id,'user_id'=>$cooperatorID,'hour_price'=>@$projectHourPrices[$project_id]]);
                            endif;
                            break;
                    endswitch;
                endforeach;
            endif;
            return Redirect::back()->with('message','Сохранено.');
        else:
            return Redirect::back()->withErrors($validator)->withInput(Input::all());
        endif;
	}
}