<?php

class ProjectTask extends \BaseModel {

	protected $table = 'projects_tasks';
	protected $fillable = ['project_id','user_id','note','start_date','set_date','lead_time','start_status'];
	public static $rules = ['project'=>'required|integer','performer'=>'required|integer','note' => 'required'];

	public function cooperator(){

		return $this->hasOne('User','id','user_id');
	}

	public function project(){

		return $this->hasOne('Project','id','project_id');
	}
}