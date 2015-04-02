<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserRequisitesTable extends Migration {

	public function up(){
		Schema::create('user_requisites', function(Blueprint $table){
			$table->increments('id');
            $table->integer('superior_id')->nullable()->default(0)->unsigned();

            $table->string('title',255)->nullable();
            $table->string('short_title',100)->nullable();
            $table->text('address')->nullable();
            $table->string('phone',100)->nullable();
            $table->string('logo',50)->nullable();
            $table->string('bank',255)->nullable();
            $table->string('bik',20)->nullable();
            $table->string('kor_account',25)->nullable();
            $table->string('inn',15)->nullable();
            $table->string('kpp',15)->nullable();
            $table->string('payment_account',25)->nullable();
			$table->timestamps();
		});
	}

    public function down(){
		Schema::drop('user_requisites');
	}

}
