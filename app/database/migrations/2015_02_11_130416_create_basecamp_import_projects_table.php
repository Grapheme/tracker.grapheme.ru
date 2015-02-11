<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBasecampImportProjectsTable extends Migration {

	public function up(){
		Schema::create('basecamp_import_projects', function(Blueprint $table){
			$table->increments('id');
			$table->integer('user_id')->nullable()->default(0)->unsigned();
			$table->integer('project_id')->nullable()->default(0)->unsigned();
			$table->integer('basecamp_project_id')->nullable()->default(0)->unsigned();
			$table->text('basecamp_project_data')->nullable();
			$table->timestamps();
		});
	}

	public function down(){
		Schema::drop('basecamp_import_projects');
	}

}
