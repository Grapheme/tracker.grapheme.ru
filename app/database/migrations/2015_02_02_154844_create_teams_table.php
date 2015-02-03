<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTeamsTable extends Migration {

	public function up(){
		Schema::create('teams', function(Blueprint $table){
			$table->increments('id');
			$table->integer('superior_id')->nullable()->default(0)->unsigned();
			$table->integer('cooperator_id')->nullable()->default(0)->unsigned();
			$table->timestamps();
		});
	}

	public function down(){
		Schema::drop('teams');
	}

}
