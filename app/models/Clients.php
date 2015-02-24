<?php

class Clients extends \BaseModel {

	protected $table = 'clients';
	protected $fillable = ['superior_id','title','description','image_id','hour_price','budget','requisites'];
	public static $rules = ['title' => 'required'];

	public function icon(){

		return $this->hasOne('Upload', 'id', 'image_id');
	}

    public function projects(){

        return $this->hasMany('Project','client_id');
    }

}