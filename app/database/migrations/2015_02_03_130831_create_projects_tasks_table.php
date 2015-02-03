<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProjectsTasksTable extends Migration {

	public function up(){
		Schema::create('projects_tasks', function(Blueprint $table){
			$table->increments('id');
			$table->integer('project_id')->nullable()->default(0)->unsigned();
			$table->integer('user_id')->nullable()->default(0)->unsigned();
			$table->text('note')->nullable();
			$table->tinyInteger('start_status')->nullable()->default(0)->unsigned();
			$table->tinyInteger('stop_status')->nullable()->default(0)->unsigned();
			$table->timestamp('start_date')->default('0000-00-00 00:00:00');
			$table->timestamp('stop_date')->default('0000-00-00 00:00:00');
			$table->timestamp('set_date')->default('0000-00-00 00:00:00');
			$table->integer('lead_time')->nullable()->default(0)->unsigned();
			$table->timestamps();
		});
	}

	public function down(){
		Schema::drop('projects_tasks');
	}

}
