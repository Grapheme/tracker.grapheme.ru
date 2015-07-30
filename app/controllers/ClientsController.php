<?php

class ClientsController extends \BaseController {

    public function __construct(){

    }

    public function index(){

        $clients = Clients::where('superior_id',Auth::user()->id)->orderBy('updated_at','DESC')->with('projects','logo')->get();
        return View::make(Helper::acclayout('clients.list'),compact('clients'));
    }

	public function create(){

        return View::make(Helper::acclayout('clients.create'));
	}

	public function store(){

        $validator = Validator::make(Input::all(),Clients::$rules);
        if($validator->passes()):
            $post = Input::all();
            if ($image_path = UploadsController::getUploadedFile('logo', TRUE)):
                $upload = new Upload();
                $upload->path = $image_path;
                $upload->original_name = 'logo.jpg';
                $upload->filesize = 0;
                $upload->mimetype = '';
                $upload->save();
                $post['image_id'] = $upload->id;
            endif;
            if($client = Clients::create($post)):
                if (Auth::user()->clients()->count() == 1):
                    return Redirect::route('clients.show',$client->id)->with('message','Клиент добавлен успешно. Для выставления счета заполните реквизиты в Вашем '.HTML::link(URL::route('profile'),'профиле.'));
                else:
                    return Redirect::route('clients.show',$client->id)->with('message','Клиент добавлен успешно.');
                endif;
            else:
                return Redirect::back()->with('message','Возникла ошибка при записи в БД');
            endif;
        else:
            return Redirect::back()->withErrors($validator)->withInput(Input::all());
        endif;
	}

	public function show($id){

        if($client = Clients::where('id',$id)->where('superior_id',Auth::user()->id)->with('logo')->first()):
            $projects = Project::where('client_id',$client->id)->with('basecamp_projects','logo')->get();
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

        if($client = Clients::where('id',$id)->where('superior_id',Auth::user()->id)->first()):
            return View::make(Helper::acclayout('clients.edit'),compact('client'));
        else:
            App::abort(404);
        endif;
	}

    public function update($id){

        $validator = Validator::make(Input::all(),Clients::$rules);
        if($validator->passes()):
            $post = Input::except(['_method','_token','logo']);
            $client = Clients::where('id',$id)->where('superior_id',Auth::user()->id)->first();
            if ($image_path = UploadsController::getUploadedFile('logo', TRUE)):
                if($old_image_path = Upload::where('id', $client->image_id)->pluck('path')):
                    if(File::exists(public_path($old_image_path))):
                        File::delete(public_path($old_image_path));
                        Upload::where('id', Auth::user()->image_id)->delete();
                    endif;
                endif;
                $upload = new Upload();
                $upload->path = $image_path;
                $upload->original_name = 'logo.jpg';
                $upload->filesize = 0;
                $upload->mimetype = '';
                $upload->save();
                $post['image_id'] = $upload->id;
            endif;
            Clients::where('id',$id)->where('superior_id',Auth::user()->id)->update($post);
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