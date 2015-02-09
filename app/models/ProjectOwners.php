<?php

class ProjectOwners extends \BaseModel {

	protected $table = 'projects_owners';
	protected $fillable = ['project_id','user_id','hour_price','budget'];

}