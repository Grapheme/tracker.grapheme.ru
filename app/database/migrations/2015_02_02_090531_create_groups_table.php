<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGroupsTable extends Migration {

	public function up(){
		Schema::create('groups', function(Blueprint $table){
			$table->increments('id');
			$table->string('slug',20)->unique();
			$table->string('title',100)->unique();
			$table->string('dashboard',30);
			$table->timestamps();
		});
	}

	public function down(){
		Schema::drop('groups');
	}

}
