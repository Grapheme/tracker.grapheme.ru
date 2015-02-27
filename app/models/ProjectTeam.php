<?php

class ProjectTeam extends \BaseModel {

	protected $table = 'projects_team';
	protected $fillable = ['project_id','user_id','hour_price'];

    public function project(){

        return $this->belongsTo('Project','project_id');
    }

    public function projects(){

        return $this->belongsTo('Project','project_id','id');
    }

    public function user(){

        return $this->belongsTo('User','user_id');
    }

    public function users(){

        return $this->belongsTo('User','user_id','id');
    }
}