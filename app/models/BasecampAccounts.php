<?php

class BasecampAccounts extends \BaseModel {

	protected $table = 'basecamp_accounts';
	protected $fillable = ['user_id','basecamp_user_id','token','token_expires_at','basecamp_accounts_list'];
}