<?php

class Report extends \BaseModel {

    protected $table = 'reports';
    protected $guarded = ['id','_method','_token'];
    protected $fillable = ['superior_id','user_id','client_id','project_id','title','path'];
    public static $rules = ['title' => 'required'];

    public function superior(){

        return $this->hasOne('User','id','superior_id');
    }

    public function user(){

        return $this->hasOne('User','id','user_id');
    }

    public function client(){

        return $this->hasOne('Clients','id','client_id');
    }

    public function project(){

        return $this->hasOne('Project','id','project_id');
    }

}