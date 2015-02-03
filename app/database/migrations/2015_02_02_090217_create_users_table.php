<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	public function up(){
		Schema::create('users', function(Blueprint $table){
			$table->increments('id');
			$table->smallInteger('group_id')->default(0)->nullable()->unsigned();
			$table->string('fio',100)->nullable();
			$table->string('position',100)->nullable();
			$table->string('email')->unique()->nullable();
			$table->smallInteger('active')->default(0)->nullable()->unsigned();
			$table->string('password',60)->nullable();
			$table->string('remember_token',100)->nullable();
			$table->integer('image_id')->nullable()->default(0)->unsigned();
			$table->timestamps();
		});
	}

	public function down(){
		Schema::drop('users');
	}

}
