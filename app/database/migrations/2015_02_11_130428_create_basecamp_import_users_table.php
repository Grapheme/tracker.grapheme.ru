<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBasecampImportUsersTable extends Migration {

	public function up(){
		Schema::create('basecamp_import_users', function(Blueprint $table){
			$table->increments('id');
			$table->integer('import_user_id')->nullable()->default(0)->unsigned();
			$table->integer('user_id')->nullable()->default(0)->unsigned();
			$table->integer('basecamp_user_id')->nullable()->default(0)->unsigned();
			$table->integer('basecamp_identity_id')->nullable()->default(0)->unsigned();
			$table->timestamps();
		});
	}

	public function down(){
		Schema::drop('basecamp_import_users');
	}

}
