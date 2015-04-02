<?php

class UserRequisites extends \BaseModel {

    protected $table = 'user_requisites';
    protected $guarded = ['id','_method','_token'];
    protected $fillable = ['superior_id','title','short_title','address','phone','bank','bik','kor_account','inn','kpp','payment_account'];
    public static $rules = ['title' => 'required','short_title'=>'required'];

    public function user(){

        return $this->belongsTo('User','superior_id');
    }
}