<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUploadsTable extends Migration {

	public function up(){
		Schema::create('uploads', function(Blueprint $table){
			$table->increments('id');
			$table->string('path')->nullable();
			$table->string('original_name')->nullable();
			$table->string('filesize')->nullable();
			$table->string('mimetype')->nullable();
			$table->timestamps();
		});
	}

	public function down(){
		Schema::drop('uploads');
	}

}
