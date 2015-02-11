<?php

class BasecampImportUser extends \BaseModel {

	protected $table = 'basecamp_import_users';
	protected $fillable = ['user_id','basecamp_user_id','token','token_expires_at'];
}