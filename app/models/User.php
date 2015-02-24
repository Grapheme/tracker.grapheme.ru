<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends BaseModel implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	protected $table = 'users';
	protected $hidden = array('password', 'remember_token');
	protected $fillable = ['group_id','fio','email','password','active','remember_token','position','hour_price'];

	public static $rules = array(
		'fio' => 'required',
		'email' => 'required|unique:users|email'
	);

	public static $update_rules = array(
		'fio' => 'required'
	);

	public function avatar(){

		return $this->hasOne('Upload', 'id', 'image_id');
	}

	public function group(){

		return $this->belongsTo('Group');
	}

	public function projects(){

		return $this->belongsToMany('Project','projects_team','project_id','user_id');
	}

	public function tasks(){

		return $this->hasMany('ProjectTask','user_id');
	}

	public function cooperator_projects(){

		return $this->belongsToMany('Project','projects_team','user_id','project_id');
	}
}
