<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProjectsTable extends Migration {

	public function up(){
		Schema::create('projects', function(Blueprint $table){
			$table->increments('id');
			$table->integer('superior_id')->nullable()->default(0)->unsigned();
			$table->integer('client_id')->nullable()->default(0)->unsigned();
			$table->string('title',100)->nullable();
			$table->string('description',255)->nullable();
			$table->integer('image_id')->nullable()->default(0)->unsigned();
			$table->timestamps();
		});
	}

	public function down(){
		Schema::drop('projects');
	}

}
