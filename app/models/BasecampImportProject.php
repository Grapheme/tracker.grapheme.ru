<?php

class BasecampImportProject extends \BaseModel {

	protected $table = 'basecamp_import_projects';
	protected $fillable = ['user_id','project_id','basecamp_project_id','basecamp_project_data'];
}