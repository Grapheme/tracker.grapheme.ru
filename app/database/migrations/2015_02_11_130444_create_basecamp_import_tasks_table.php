<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBasecampImportTasksTable extends Migration {

	public function up(){
		Schema::create('basecamp_import_tasks', function(Blueprint $table){
			$table->increments('id');
			$table->integer('cooperator_id')->nullable()->default(0)->unsigned();
			$table->integer('user_id')->nullable()->default(0)->unsigned();
			$table->integer('project_id')->nullable()->default(0)->unsigned();
			$table->integer('task_id')->nullable()->default(0)->unsigned();
			$table->integer('basecamp_project_id')->nullable()->default(0)->unsigned();
			$table->integer('basecamp_task_id')->nullable()->default(0)->unsigned();
			$table->string('basecamp_task_link',255)->nullable();
			$table->timestamps();
		});
	}

	public function down(){
		Schema::drop('basecamp_import_tasks');
	}

}
