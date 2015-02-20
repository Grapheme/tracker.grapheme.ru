<?php

class BasecampController extends \BaseController {

	public function __construct(){ }

	protected function oauth_url(){

		$callback = URL::route('oauth.callback');
		$url = Config::get('basecamp.urlAuthorize').'?&client_id='.Config::get('basecamp.client_id').'&redirect_uri='.Config::get('basecamp.redirect_uri');
		return Redirect::to($url.'&type=web_server');
	}

	protected function oauth_callback(){

		try {
			$url = Config::get('basecamp.urlAccessToken').'?type=web_server&client_id='.Config::get('basecamp.client_id').'&redirect_uri='.Config::get('basecamp.redirect_uri').'&client_secret='.Config::get('basecamp.client_secret').'&code='.Input::get('code');
			$result = self::post_request($url);
			$json_result = json_decode($result,TRUE);
			if (isset($json_result['access_token'])):
				if($basecamp = BasecampAccounts::firstOrCreate(['user_id'=>Auth::user()->id])):
					$basecamp->user_id = Auth::user()->id;
					$basecamp->token = $json_result['access_token'];
					$basecamp->token_expires_at = date('Y-m-d H:i:s',time()+$json_result['expires_in']);
					$result = file_get_contents(Config::get('basecamp.urlUserDetails').'?access_token='.$basecamp->token);
					$json_result = json_decode($result,TRUE);
					if (isset($json_result['identity']['id'])):
						$basecamp->basecamp_identity_id = $json_result['identity']['id'];
						$basecamp->basecamp_accounts_list = json_encode($json_result['accounts']);
						$basecamp->save();
					endif;
					Session::set('basecamp_id',$basecamp->id);
					return Redirect::route('basecamp.accounts');
				else:
					App::abort(404);
				endif;
			endif;
		} catch (Exception $e) {
			return Redirect::route('dashboard')->with('message','Возникла ошибка при синхронизации с Basecamp.');
		}
	}

	public function userAccountsList(){

		if ($basecamp = BasecampAccounts::where('id',Session::get('basecamp_id'))->where('token_expires_at','>=',date('Y-m-d H:i:s'))->first()):
			if (!empty($basecamp['basecamp_accounts_list'])):
				$accounts = json_decode($basecamp['basecamp_accounts_list'],TRUE);
				return View::make(Helper::acclayout('basecamp.accounts'),compact('accounts'));
			endif;
		endif;
		return Redirect::route('dashboard')->with('message','Возникла ошибка при синхронизации с Basecamp.');
	}

	public function getUserAccountProjects(){

		try{
			$validator = Validator::make(Input::all(),['account_id'=>'required|integer','account_href'=>'required','account_product'=>'required','account_name'=>'required']);
			if($validator->passes()):
				if($basecamp_client = self::getBasecampClientHandler(Input::get('account_id'))):
					$projects = $basecamp_client->getProjects();
					$account = Input::except(['_token']);
					$already_uses = BasecampImportProject::where('user_id',Auth::user()->id)->get();
					$basecamp_peoples_count = self::userAccountPeopleCount($basecamp_client);

					return View::make(Helper::acclayout('basecamp.projects'),compact('projects','account','already_uses','basecamp_peoples_count'));
				endif;
			endif;
		} catch (Exception $error){
			# errors
		}
		return Redirect::route('dashboard')->with('message','Возникла ошибка при синхронизации с Basecamp.');
	}

	public function userAccountProjectImport(){

		try {
			if($basecamp_client = self::getBasecampClientHandler(Input::get('account_id'))):
				if (Input::has('sync_people')):
					self::userAccountPeopleImport($basecamp_client);
				endif;
                $BasecampImportProjectsIDs = BasecampImportProject::where('user_id',Auth::user()->id)->lists('basecamp_project_id','project_id');
                foreach(Input::get('projects') as $project_id):
					if (is_numeric($project_id)):
						$project = $basecamp_client->getProject(['id'=>(int)$project_id]);
                        $projectImportIndex = array_search($project['id'],$BasecampImportProjectsIDs);
                        if ($projectImportIndex === FALSE):
                            $new_project = Project::create(['superior_id'=>Auth::user()->id,'title'=>@$project['name'],'description'=>@$project['description']]);
                        else:
                            $new_project = Project::where('where',$projectImportIndex)->first();
                        endif;
						if ($new_project):
							ProjectOwners::create(['project_id'=>$new_project->id,'user_id'=>Auth::user()->id,'hour_price'=>0,'budget'=>0]);
							$project_data = ['account_id'=>Input::get('account_id'),'app_url'=>@$project['app_url'],'accesses'=>@$project['accesses']['count'],'attachments'=>@$project['attachments']['count'],'documents'=>@$project['documents']['count'],'todolists'=>@$project['todolists']['count']];
							BasecampImportProject::create(['user_id'=>Auth::user()->id,'project_id'=>$new_project->id,'basecamp_project_id'=>$project_id,'basecamp_project_data'=>json_encode($project_data)]);
							if (Input::has('sync_tasks')):
								self::userAccountProjectTasksImport($new_project->id, $project_id,$basecamp_client);
							endif;
						endif;
					endif;
				endforeach;
				return Redirect::route('dashboard')->with('message','Импорт с Basecamp успешно завершен.');
			endif;
		} catch (Exception $error){
			# errors
		}
		return Redirect::route('dashboard')->with('message','Возникла ошибка при синхронизации с Basecamp.');
	}

	protected function userAccountProjectTasksImport($projectID,$basecampProjectID, $basecamp_client = NULL){

		if (is_null($basecamp_client)):
			$basecamp_client = self::getBasecampClientHandler(Input::get('account_id'));
		endif;
		try{
            if($responseTodoLists = $basecamp_client->getTodolistsByProject(['projectId' =>(int)$basecampProjectID])):
                $TeamUserIDs = Team::where('superior_id',Auth::user()->id)->lists('cooperator_id');
                $BasecampImportUserIDs = BasecampImportUser::lists('user_id','basecamp_user_id');
                $BasecampImportTasksIDs = BasecampImportProjectTask::where('basecamp_project_id',$basecampProjectID)->lists('basecamp_task_id');
                foreach($responseTodoLists as $todo):
                    if ($responseTodo = $basecamp_client->getTodolist(['projectId'=>(int)$basecampProjectID,'todolistId'=>(int)$todo['id']])):
                        if ($responseTodo['remaining_count'] && isset($responseTodo['todos']['remaining'])):
                            foreach($responseTodo['todos']['remaining'] as $task):
                                $performerID = Auth::user()->id;
                                $set_date = empty($task['due_on']) ? date("Y-m-d") : $task['due_on'];
                                if (isset($task['assignee']['id']) && isset($BasecampImportUserIDs[$task['assignee']['id']])):
                                    $performerID = $BasecampImportUserIDs[$task['assignee']['id']];
                                endif;
                                if (in_array($task['id'],$BasecampImportTasksIDs) === FALSE):
                                    if($new_task = ProjectTask::create(['project_id'=>$projectID,'user_id'=>$performerID,'note'=>$task['content'],'set_date'=>$set_date,'lead_time'=>0])):
                                        BasecampImportProjectTask::create(['user_id'=>$performerID,'project_id'=>$projectID,'task_id'=>$new_task->id,'basecamp_project_id'=>$basecampProjectID,'basecamp_task_id'=>$task['id'],'basecamp_task_link'=>$task['app_url']]);
                                    endif;
                                endif;
                            endforeach;
                        endif;
                    endif;
                endforeach;
            endif;
            return TRUE;
		} catch(Exception $error){
           # errors
		}
        return Redirect::route('dashboard')->with('message','Возникла ошибка при синхронизации с Basecamp.');
	}

	protected function userAccountPeopleImport($basecamp_client = NULL){

		if (is_null($basecamp_client)):
			$basecamp_client = self::getBasecampClientHandler(Input::get('account_id'));
		endif;
		try{
			if($peoples = $basecamp_client->getPeople()):
				$TeamUserIDs = Team::where('superior_id',Auth::user()->id)->lists('cooperator_id');
				$BasecampImportUserIDs = BasecampImportUser::where('import_user_id',Auth::user()->id)->lists('basecamp_identity_id');
				foreach($peoples as $people):
					if($people['email_address'] != Auth::user()->email):
						$user = User::where('email',$people['email_address'])->first();
						if (!$user):
							$invite = ['fio'=>$people['name'],'position'=>'','email'=>$people['email_address']];
							if($account = GlobalController::inviteRegister($invite)):
								BasecampImportUser::create(['import_user_id'=>Auth::user()->id,'user_id'=>$account->id,'basecamp_user_id'=>$people['id'],'basecamp_identity_id'=>$people['identity_id']]);
								Team::create(['superior_id'=>Auth::user()->id,'cooperator_id'=>$account->id]);
							endif;
						else:
							if(in_array($user->id,$TeamUserIDs) === FALSE):
								Team::create(['superior_id'=>Auth::user()->id,'cooperator_id'=>$user->id]);
							endif;
							if(in_array($people['identity_id'],$BasecampImportUserIDs) === FALSE):
								BasecampImportUser::create(['user_id'=>Auth::user()->id,'basecamp_user_id'=>$people['identity_id']]);
							endif;
						endif;
					endif;
				endforeach;
			endif;
			return TRUE;
		} catch (Exception $error){
			# errors
		}
		return Redirect::route('dashboard')->with('message','Возникла ошибка при синхронизации с Basecamp.');
	}

	private function getBasecampClientHandler($user_id = NULL){

		if ($basecamp = BasecampAccounts::where('id',Session::get('basecamp_id'))->where('token_expires_at','>=',date('Y-m-d H:i:s'))->first()):
			$basecamp_client = \Basecamp\BasecampClient::factory(array(
				'auth'     		=> 'oauth',
				'token'    		=> $basecamp->token,
				'user_id'  		=> $user_id,
				'app_name' 		=> Config::get('basecamp.app_name'),
				'app_contact' 	=> Config::get('basecamp.app_contact')
			));
			return $basecamp_client;
		else:
			return FALSE;
		endif;
	}

	private function userAccountPeopleCount($basecamp_client = NULL){

		if (is_null($basecamp_client)):
			$basecamp_client = self::getBasecampClientHandler(Input::get('account_id'));
		endif;
		try{
			if($peoples = $basecamp_client->getPeople()):
				$countPeople = 0;
				foreach($peoples as $people):
					if ($people['email_address'] != Auth::user()->email):
						$countPeople++;
					endif;
				endforeach;
				return $countPeople;
			endif;
		} catch (Exception $error){
			# errors
		}
		return Redirect::route('dashboard')->with('message','Возникла ошибка при синхронизации с Basecamp.');
	}
}