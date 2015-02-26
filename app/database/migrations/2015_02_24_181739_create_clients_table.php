<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateClientsTable extends Migration {

	public function up(){
		Schema::create('clients', function(Blueprint $table){
			$table->increments('id');
            $table->integer('superior_id')->nullable()->default(0)->unsigned();
            $table->string('title',255)->nullable();
            $table->string('short_title',100)->nullable();
            $table->text('address')->nullable();
            $table->string('phone',100)->nullable();
            $table->string('email',100)->nullable();
            $table->integer('group_id')->nullable()->default(0)->unsigned();
            $table->integer('hour_price')->nullable()->default(0)->unsigned();
            $table->string('bank',255)->nullable();
            $table->string('payment_account',100)->nullable();
            $table->string('fio_signature',100)->nullable();
            $table->string('contact_person',100)->nullable();
            $table->string('fio',100)->nullable();
            $table->string('fio_rod',100)->nullable();
            $table->string('inn',50)->nullable();
            $table->string('kpp',50)->nullable();
            $table->string('ogrn',50)->nullable();
            $table->string('okpo',50)->nullable();
			$table->timestamps();
		});
	}

	public function down(){
		Schema::drop('clients');
	}

}
