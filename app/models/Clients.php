<?php

class Clients extends \BaseModel {

	protected $table = 'clients';
    protected $guarded = ['id','_method','_token'];
	protected $fillable = ['superior_id','title','short_title','address','phone','email','group_id','hour_price','bank','payment_account','fio_signature','contact_person','fio','fio_rod','inn','kpp','ogrn','okpo'];
	public static $rules = ['title' => 'required'];

    public function projects(){

        return $this->hasMany('Project','client_id');
    }

}