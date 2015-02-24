<?php

class ClientsController extends \BaseController {

    public function __construct(){

    }

    public function index(){

        $clients = Clients::where('superior_id',Auth::user()->id)->orderBy('updated_at','DESC')->with('projects','icon')->get();
        return View::make(Helper::acclayout('clients.list'),compact('clients'));
    }

	public function create(){

        return View::make(Helper::acclayout('clients.create'));
	}

	public function store(){
        $validator = Validator::make(Input::all(),Clients::$rules);
        if($validator->passes()):
            if($client = Clients::create(['superior_id' => Auth::user()->id, 'title' => Input::get('title'),'description' => Input::get('description'),'hour_price' => Input::get('hour_price'),'budget' => Input::get('budget'),'requisites' => Input::get('requisites')])):
                return Redirect::route('clients.show',$client->id)->with('message','Клиент добавлен успешно.');
            else:
                return Redirect::back()->with('message','Возникла ошибка при записи в БД');
            endif;
        else:
            return Redirect::back()->withErrors($validator)->withInput(Input::all());
        endif;
	}

	public function show($id){

        if($client = Clients::where('id',$id)->where('superior_id',Auth::user()->id)->with('icon')->first()):
            $projects = Project::where('client_id',$client->id)->with('icon','basecamp_projects')->get();
            $tasks = array();
            if($projectIDs = Project::where('client_id',$client->id)->lists('id')):
                $tasks = ProjectTask::whereIn('project_id',$projectIDs)->with('cooperator','basecamp_task')->get();
            endif;
            return View::make(Helper::acclayout('clients.show'),compact('client','projects','tasks'));
        else:
            App::abort(404);
        endif;
	}

	public function edit($id){

        if($client = Clients::where('id',$id)->where('superior_id',Auth::user()->id)->with('icon')->first()):
            return View::make(Helper::acclayout('clients.edit'),compact('client'));
        else:
            App::abort(404);
        endif;
	}

    public function update($id){

        $validator = Validator::make(Input::all(),Clients::$rules);
        if($validator->passes()):
            Clients::where('id',$id)->where('superior_id',Auth::user()->id)->update(['title' => Input::get('title'),'description' => Input::get('description'),'hour_price' => Input::get('hour_price'),'budget' => Input::get('budget'),'requisites' => Input::get('requisites')]);
            return Redirect::route('clients.show',$id)->with('message','Клиент сохранен успешно.');
        else:
            return Redirect::back()->withErrors($validator)->withInput(Input::all());
        endif;
	}

    public function destroy($id){
        if (Clients::where('id',$id)->where('superior_id',Auth::user()->id)->first()):
            Project::where('client_id',$id)->update(['client_id'=>0]);
            Clients::where('id',$id)->delete();
            return Redirect::route('clients.index')->with('message','Клиент удален успешно.');
        else:
            App::abort(404);
        endif;
	}

}