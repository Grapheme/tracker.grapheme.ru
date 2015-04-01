<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFavoriteProjectTable extends Migration {

	public function up(){
		Schema::create('projects_favorites', function(Blueprint $table){
			$table->increments('id');
            $table->integer('user_id')->nullable()->default(0)->unsigned();
            $table->integer('project_id')->nullable()->default(0)->unsigned();
			$table->timestamps();
		});
	}

	public function down(){
		Schema::drop('projects_favorites');
	}

}
