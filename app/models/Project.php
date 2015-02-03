<?php

class Project extends \BaseModel {

	protected $table = 'projects';
	protected $fillable = ['superior_id','title','description','hour_price','image_id'];
	public static $rules = ['title' => 'required'];

	public function icon(){

		return $this->hasOne('Upload', 'id', 'image_id');
	}

	public function superior(){

		return $this->hasOne('User','id','superior_id');
	}

	public function team(){

		return $this->belongsToMany('User','projects_team','project_id','user_id');
	}
}