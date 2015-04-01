<?php

class ProjectFavorite extends \BaseModel {

    protected $table = 'projects_favorites';
    protected $fillable = ['user_id','project_id'];

    public function project(){

        return $this->belongsTo('Project','project_id');
    }
    public function user(){

        return $this->belongsTo('User','user_id');
    }
}