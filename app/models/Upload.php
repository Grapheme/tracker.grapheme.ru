<?php

class Upload extends \BaseModel {

	protected $table = 'uploads';
	protected $fillable = ['path','original_name','filesize','mimetype'];
}