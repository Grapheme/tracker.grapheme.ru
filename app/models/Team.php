<?php

class Team extends \BaseModel {

	protected $table = 'teams';
	protected $fillable = ['superior_id','cooperator_id','excluded'];

	public function superior(){

		return $this->hasOne('User','id','superior_id');
	}

	public function cooperator(){

		return $this->hasOne('User','id','cooperator_id');
	}

	public function cooperators(){

		return $this->hasMany('User','id','cooperator_id');
	}
}