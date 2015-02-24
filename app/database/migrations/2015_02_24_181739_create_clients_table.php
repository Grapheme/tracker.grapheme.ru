<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateClientsTable extends Migration {

	public function up(){
		Schema::create('clients', function(Blueprint $table){
			$table->increments('id');
            $table->integer('superior_id')->nullable()->default(0)->unsigned();
            $table->string('title',100)->nullable();
            $table->string('description',255)->nullable();
            $table->integer('image_id')->nullable()->default(0)->unsigned();
            $table->integer('hour_price')->nullable()->default(0)->unsigned();
            $table->integer('budget')->nullable()->default(0)->unsigned();
            $table->text('requisites')->nullable();
			$table->timestamps();
		});
	}

	public function down(){
		Schema::drop('clients');
	}

}
