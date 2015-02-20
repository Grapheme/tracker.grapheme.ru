<?php

class BasecampImportUser extends \BaseModel {

	protected $table = 'basecamp_import_users';
	protected $fillable = ['import_user_id','user_id','basecamp_user_id','basecamp_identity_id'];
}