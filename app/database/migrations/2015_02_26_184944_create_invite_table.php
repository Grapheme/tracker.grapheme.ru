<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInviteTable extends Migration {

	public function up(){
		Schema::create('invite', function(Blueprint $table){
			$table->increments('id');
            $table->integer('user_id')->nullable()->default(0)->unsigned();
            $table->string('email',100)->nullable();
            $table->text('token')->nullable();
            $table->text('note')->nullable();
            $table->boolean('status')->nullable()->default(0)->unsigned();
			$table->timestamps();
		});
	}

	public function down(){
		Schema::drop('invite');
	}

}
