<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBasecampTable extends Migration {

	public function up(){
		Schema::create('basecamp_accounts', function(Blueprint $table){
			$table->increments('id');
			$table->integer('user_id')->nullable()->default(0)->unsigned();
			$table->integer('basecamp_user_id')->nullable()->default(0)->unsigned();
			$table->text('token')->nullable();
			$table->timestamp('token_expires_at')->default('0000-00-00 00:00:00');
			$table->text('basecamp_accounts_list')->nullable();
			$table->timestamps();
		});
	}

	public function down(){
		Schema::drop('basecamp_accounts');
	}

}
