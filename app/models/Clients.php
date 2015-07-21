<?php

class Clients extends \BaseModel {

	protected $table = 'clients';
    protected $guarded = ['id','_method','_token','logo'];
	protected $fillable = ['superior_id','title','short_title','address','phone','email','group_id','hour_price','bank','payment_account','fio_signature','contact_person','fio','fio_rod','inn','kpp','ogrn','okpo','image_id'];
	public static $rules = ['title' => 'required'];

    public function projects(){

        return $this->hasMany('Project','client_id');
    }

    public function logo(){

        return $this->hasOne('Upload', 'id', 'image_id');
    }
}