<?php

class ProjectTeam extends \BaseModel {

	protected $table = 'projects_team';
	protected $fillable = ['project_id','user_id','hour_price','budget'];

}