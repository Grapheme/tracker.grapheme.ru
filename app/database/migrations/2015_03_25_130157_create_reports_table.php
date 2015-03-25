<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReportsTable extends Migration {

	public function up(){
		Schema::create('reports', function(Blueprint $table){
			$table->increments('id');
            $table->integer('superior_id')->nullable()->default(0)->unsigned();
            $table->integer('user_id')->nullable()->default(0)->unsigned();
            $table->integer('client_id')->nullable()->default(0)->unsigned();
            $table->integer('project_id')->nullable()->default(0)->unsigned();
            $table->string('title',255)->nullable();
            $table->string('path',255)->nullable();
			$table->timestamps();
		});
	}

	public function down(){
		Schema::drop('reports');
	}

}
