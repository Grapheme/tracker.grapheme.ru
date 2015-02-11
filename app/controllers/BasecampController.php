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
						$basecamp->basecamp_user_id = $json_result['identity']['id'];
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
			if ($basecamp = BasecampAccounts::where('id',Session::get('basecamp_id'))->where('token_expires_at','>=',date('Y-m-d H:i:s'))->first()):
				$validator = Validator::make(Input::all(),['account_id'=>'required|integer','account_href'=>'required','account_product'=>'required','account_name'=>'required']);
				if($validator->passes()):
					$basecamp_client = \Basecamp\BasecampClient::factory(array(
						'auth'     		=> 'oauth',
						'token'    		=> $basecamp->token,
						'user_id'  		=> Input::get('account_id'),
						'app_name' 		=> Config::get('basecamp.app_name'),
						'app_contact' 	=> Config::get('basecamp.app_contact'),
					));
					$projects = $basecamp_client->getProjects();
					$account = Input::except(['_token']);
					$already_uses = BasecampImportProject::where('user_id',Auth::user()->id)->get();
					return View::make(Helper::acclayout('basecamp.projects'),compact('projects','account','already_uses'));
				endif;
			endif;
		} catch (Exception $error){
			# errors
		}
		return Redirect::route('dashboard')->with('message','Возникла ошибка при синхронизации с Basecamp.');
	}

	public function userAccountProjectImport(){

		try {
			if ($basecamp = BasecampAccounts::where('id',Session::get('basecamp_id'))->where('token_expires_at','>=',date('Y-m-d H:i:s'))->first()):
				$validator = Validator::make(Input::all(),['account_name'=>'required','account_id'=>'required|integer','projects'=>'required|min:1']);
				if($validator->passes()):
					$basecamp_client = \Basecamp\BasecampClient::factory(array(
						'auth'     		=> 'oauth',
						'token'    		=> $basecamp->token,
						'user_id'  		=> Input::get('account_id'),
						'app_name' 		=> Config::get('basecamp.app_name'),
						'app_contact' 	=> Config::get('basecamp.app_contact'),
					));
					foreach(Input::get('projects') as $project_id):
						if (is_numeric($project_id)):
							$project = $basecamp_client->getProject(['id'=>(int)$project_id]);
							if ($new_project = Project::create(['superior_id'=>Auth::user()->id,'title'=>@$project['name'],'description'=>@$project['description']])):
								ProjectOwners::create(['project_id'=>$new_project->id,'user_id'=>Auth::user()->id,'hour_price'=>0,'budget'=>0]);
								$project_data = ['account_id'=>Input::get('account_id'),'app_url'=>@$project['app_url'],'accesses'=>@$project['accesses']['count'],'attachments'=>@$project['attachments']['count'],'documents'=>@$project['documents']['count'],'todolists'=>@$project['todolists']['count']];
								BasecampImportProject::create(['user_id'=>Auth::user()->id,'project_id'=>$new_project->id,'basecamp_project_id'=>$project_id,'basecamp_project_data'=>json_encode($project_data)]);
							endif;
						endif;
					endforeach;
					return Redirect::route('dashboard')->with('message','Импорт с Basecamp успешно завершен.');
				endif;
			endif;
		} catch (Exception $error){
			# errors
		}
		return Redirect::route('dashboard')->with('message','Возникла ошибка при синхронизации с Basecamp.');
	}
}