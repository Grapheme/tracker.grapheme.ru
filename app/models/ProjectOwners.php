<?php

class ProjectOwners extends \BaseModel {

	protected $table = 'projects_owners';
	protected $fillable = ['project_id','user_id','hour_price','budget'];

    public function project(){

        return $this->hasOne('Project','id','project_id');
    }

    public function projects(){

        return $this->belongsTo('Project','project_id','id');
    }
}