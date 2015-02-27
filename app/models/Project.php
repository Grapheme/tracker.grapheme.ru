<?php

class Project extends \BaseModel {

	protected $table = 'projects';
    protected $guarded = ['id','_method','_token','superior_hour_price'];
	protected $fillable = ['superior_id','client_id','title','description','budget','visible'];
	public static $rules = ['title' => 'required'];

	public function superior(){

		return $this->hasOne('User','id','superior_id');
	}

    public function client(){

		return $this->hasOne('Clients','id','client_id');
	}

	public function team(){

		return $this->belongsToMany('User','projects_team','project_id','user_id')->select(['users.*','projects_team.hour_price']);
	}

	public function owners(){

		return $this->belongsToMany('User','projects_owners','project_id','user_id')->select(['users.*','projects_owners.hour_price']);
	}

	public function tasks(){

		return $this->hasMany('ProjectTask','project_id');
	}

    public function basecamp_projects(){

		return $this->hasMany('BasecampImportProject','project_id');
	}
}