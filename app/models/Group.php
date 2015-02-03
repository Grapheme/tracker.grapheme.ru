<?php

class Group extends \BaseModel {

	protected $fillable = ['slug','title','dashboard'];
	public static $rules = array(
		'slug' => 'required|unique:groups',
		'title' => 'required',
		'dashboard' => 'required'
	);
}