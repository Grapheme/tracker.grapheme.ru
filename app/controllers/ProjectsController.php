<?php

class ProjectsController extends \BaseController {

	public function __construct(){

	}

	public function index(){

		$projects['my'] = ProjectOwners::where('user_id',Auth::user()->id)->orderBy('updated_at','DESC')->with('projects','projects.client','projects.team','projects.tasks')->get();
		$projects['subscribe'] = ProjectTeam::where('user_id',Auth::user()->id)->with('projects','projects.client','projects.team','projects.tasks')->get();
        return View::make(Helper::acclayout('projects.list'),compact('projects'))->with('archive',0);
	}

    public function archive(){

		$projects['my'] = ProjectOwners::where('user_id',Auth::user()->id)->orderBy('updated_at','DESC')->with('projects','projects.client','projects.team','projects.tasks')->get();
		$projects['subscribe'] = ProjectTeam::where('user_id',Auth::user()->id)->with('projects','projects.client','projects.team','projects.tasks')->get();
        return View::make(Helper::acclayout('projects.list'),compact('projects'))->with('archive',1);
	}

	public function create(){

		$project_team = array();
		if($team = Team::where('superior_id',Auth::user()->id)->where('excluded', 0)->with('cooperator')->get()):
			foreach($team as $user):
                if (!empty($user->cooperator)):
                    $project_team[$user->cooperator->id]['fio'] = $user->cooperator->fio;
				    $project_team[$user->cooperator->id]['hour_price'] = $user->cooperator->hour_price;
                endif;
			endforeach;
		endif;
        $clients[0] = 'Без клиента';
        foreach(Clients::where('superior_id',Auth::user()->id)->orderBy('title')->select('id','title','short_title')->get() as $client):
            $clients[$client->id] = !empty($client->short_title) ? $client->short_title : $client->title;
        endforeach;
		return View::make(Helper::acclayout('projects.create'),compact('project_team','clients'));
	}

	public function store()	{

		$validator = Validator::make(Input::all(),Project::$rules);
		if($validator->passes()):
			if($project = Project::create(Input::except('_token','superior_hour_price','team','invite_team'))):
				ProjectOwners::create(['project_id'=>$project->id,'user_id'=>Auth::user()->id,'hour_price'=>Input::get('superior_hour_price')]);
                if (Input::has('favorite')):
                    Project::where('id',$project->id)->first()->favorites_projects()->sync([Auth::user()->id]);
                endif;
                if (Input::has('team')):
                    self::addTeam($project->id);
				endif;
                if (Input::has('invite_team')):
                    self::inviteTeam($project->id);
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

        $inFavorite = ProjectFavorite::where('project_id',$id)->where('user_id',Auth::user()->id)->count();
		if(ProjectOwners::where('project_id',$id)->where('user_id',Auth::user()->id)->exists()):
            $project = ProjectOwners::where('project_id',$id)->where('user_id',Auth::user()->id)->first()->project()->with('superior.avatar','client.logo','team.avatar')->first();
			$tasks = ProjectTask::where('project_id',$project->id)->with('cooperator','basecamp_task')->get();
            return View::make(Helper::acclayout('projects.show'),compact('project','tasks','inFavorite'))->with('access',TRUE);
		elseif(ProjectTeam::where('project_id',$id)->where('user_id',Auth::user()->id)->exists()):
            $project = ProjectTeam::where('project_id',$id)->where('user_id',Auth::user()->id)->first()->project()->with('superior.avatar','client.logo','team.avatar')->first();
            $tasks = ProjectTask::where('project_id',$project->id)->with('cooperator','basecamp_task')->get();
			return View::make(Helper::acclayout('projects.show'),compact('project','tasks','inFavorite'))->with('access',FALSE);
		else:
			App::abort(404);
		endif;
	}

	public function edit($id){

		if($project = ProjectOwners::where('project_id',$id)->where('user_id',Auth::user()->id)->first()->project()->with('team','owners','client')->first()):
            $setProjectValues[$project->superior_id]['hour_price'] = ProjectOwners::where('project_id',$id)->where('user_id',$project->superior_id)->pluck('hour_price');
            $project_team = $setProjectTeamIDs = [];
            if($team = Team::where('superior_id',Auth::user()->id)->where('excluded', 0)->with('cooperator')->get()):
                foreach($team as $user):
                    if (!empty($user->cooperator)):
                        $project_team[$user->cooperator->id] = $user->cooperator->fio;
                    endif;
                endforeach;
            endif;
            if (count($project->team)):
                foreach($project->team as $user):
                    $setProjectTeamIDs[] = $user->id;
                    $setProjectValues[$user->id]['hour_price'] = $user->hour_price;
                endforeach;
            endif;
            $clients[0] = 'Без клиента';
            foreach(Clients::where('superior_id',Auth::user()->id)->orderBy('title')->select('id','title','short_title')->get() as $client):
                $clients[$client->id] = !empty($client->short_title) ? $client->short_title : $client->title;
            endforeach;
			return View::make(Helper::acclayout('projects.edit'),compact('project','project_team','clients','setProjectTeamIDs','setProjectValues'));
		else:
			App::abort(404);
		endif;
	}

	public function update($id){

		$validator = Validator::make(Input::all(),Project::$rules);
		if($validator->passes()):
            if (ProjectOwners::where('project_id',$id)->where('user_id',Auth::user()->id)->exists() === FALSE):
                return Redirect::route('projects.show',$id)->with('message','Проект редактировать запрещено.');
            endif;
			Project::where('id',$id)->update(Input::except('_method','_token','superior_hour_price','team','invite_team','favorite'));
            if (!Input::has('in_archive')):
                Project::where('id',$id)->update(['in_archive'=>0]);
            endif;
            ProjectOwners::where('project_id',$id)->where('user_id',Auth::user()->id)->update(['hour_price'=>Input::get('superior_hour_price')]);
            if (Input::has('favorite')):
                Project::where('id',$id)->first()->favorites_projects()->sync([Auth::user()->id]);
            else:
                Project::where('id',$id)->first()->favorites_projects()->detach([Auth::user()->id]);
            endif;
            if (Input::has('team')):
                self::addTeam($id);
            endif;
            if (Input::has('invite_team')):
                self::inviteTeam($id);
            endif;
			return Redirect::route('projects.show',$id)->with('message','Проект сохранен успешно.');
		else:
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		endif;
	}

    public function favorite($project_id){

        if(ProjectOwners::where('project_id',$project_id)->where('user_id',Auth::user()->id)->exists() === FALSE &&
            ProjectTeam::where('project_id',$project_id)->where('user_id',Auth::user()->id)->exists() === FALSE):
            return Redirect::back()->with('messages','Ошибка доступа.');
        else:
            if (Input::has('favorite')):
                if (Input::get('favorite') == 0):
                    Project::where('id',$project_id)->first()->favorites_projects()->sync([Auth::user()->id]);
                    return Redirect::back()->with('messages','Проект добавлен в избранные');
                else:
                    Project::where('id',$project_id)->first()->favorites_projects()->detach([Auth::user()->id]);
                    return Redirect::back()->with('messages','Проект удален из избранных');
                endif;
            endif;
        endif;
        return Redirect::back();
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

    private function addTeam($projectID){

        Project::where('id',$projectID)->first()->owners()->sync([Auth::user()->id]);
        $usersIDs = $users= array();
        foreach(Input::get('team') as $user):
            if (isset($user['user_id'])):
                $usersIDs[] = $user['user_id'];
                $users[$user['user_id']]['hour_price'] = $user['hour_price'] ? $user['hour_price'] : 0;
            endif;
        endforeach;
        if ($usersIDs):
            Project::where('id',$projectID)->first()->team()->sync($usersIDs);
            foreach($users as $user_id => $user):
                ProjectTeam::where('project_id',$projectID)->where('user_id',$user_id)->update(['hour_price'=>$user['hour_price']]);
            endforeach;
        endif;
    }

    private function inviteTeam($projectID){

        $InviteTeams = [];
        $invite_team_price = Input::get('invite_team.hour_price');
        foreach(Input::get('invite_team.email') as $index => $email):
            if (!empty($email)):
                $InviteTeams[$email] = isset($invite_team_price[$index]) && $invite_team_price[$index] ? $invite_team_price[$index] : 0;
            endif;
        endforeach;
        if (count($InviteTeams)):
            foreach($InviteTeams as $email => $hour_price):
                if(Auth::user()->email == $email):

                elseif(Invite::where('user_id',Auth::user()->id)->where('email',$email)->where('status',0)->exists()):

                elseif ($account = User::where('email',$email)->first()):
                    if (Team::where('superior_id',Auth::user()->id)->where('cooperator_id',$account->id)->exists() === FALSE):
                        $token = Str::random(48);
                        Invite::create(['user_id'=>Auth::user()->id,'email'=>$email,'token'=>$token,'note'=>json_encode(['redirect'=> FALSE,'params'=>['invitee_account'=>Auth::user()->id,'redirect'=>URL::route('dashboard'),'project'=>$projectID,'hour_price'=>$hour_price]])]);
                        Mail::send('emails.invite.exists',['token'=>$token],function($message) use ($account){
                            $message->from(Config::get('mail.from.address'),Config::get('mail.from.name'));
                            $message->to($account->email)->subject('Приглашение');
                        });
                    elseif(ProjectTeam::where('project_id',$projectID)->where('user_id',$account->id)->exists() === FALSE):
                        ProjectTeam::create(['project_id'=>$projectID,'user_id'=>$account->id,'hour_price'=>$hour_price]);
                        Mail::send('emails.invite.in_project',['project'=>Project::where('id',$projectID)->first()],function($message) use ($account){
                            $message->from(Config::get('mail.from.address'),Config::get('mail.from.name'));
                            $message->to($account->email)->subject('Приглашение в проект');
                        });
                    endif;
                else:
                    $token = Str::random(48);
                    Invite::create(['user_id'=>Auth::user()->id,'email'=>$email,'token'=>$token,'note'=>json_encode(['redirect'=>URL::route('register'),'params'=>['invitee_account'=>Auth::user()->id,'redirect'=>URL::route('invite',$token),'project'=>$projectID,'hour_price'=>$hour_price]])]);
                    Mail::send('emails.invite.register',['token'=>$token],function($message) use ($email){
                        $message->from(Config::get('mail.from.address'),Config::get('mail.from.name'));
                        $message->to($email)->subject('Приглашение');
                    });
                    return Redirect::back()->with('message','Приглашение отправлено.');
                endif;
            endforeach;
        endif;
    }
}