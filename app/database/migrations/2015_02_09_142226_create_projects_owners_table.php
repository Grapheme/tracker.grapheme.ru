<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProjectsOwnersTable extends Migration {

	public function up(){
		Schema::create('projects_owners', function(Blueprint $table){
			$table->increments('id');
			$table->integer('project_id')->nullable()->default(0)->unsigned();
			$table->integer('user_id')->nullable()->default(0)->unsigned();
			$table->integer('hour_price')->nullable()->default(0)->unsigned();
			$table->timestamps();
		});
	}

	public function down(){
		Schema::drop('projects_owners');
	}
}