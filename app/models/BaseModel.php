<?php

class BaseModel extends Eloquent {
	
	public static $errors = array();

    public function rules() {

        return static::$rules;
    }

}