<?php

class BasecampImportProjectTask extends \BaseModel {

	protected $table = 'basecamp_import_tasks';
	protected $fillable = ['cooperator_id','user_id','project_id','task_id','basecamp_project_id','basecamp_task_id','basecamp_task_link'];
}