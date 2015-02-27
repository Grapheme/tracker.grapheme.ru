<?php

class Invite extends \BaseModel {

    protected $table = 'invite';
    protected $guarded = ['id','_method','_token'];
    protected $fillable = ['user_id','email','token','note'];
    public static $rules = ['title' => 'required'];
}