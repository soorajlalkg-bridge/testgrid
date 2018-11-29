<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->text('username', 65535)->nullable();
			$table->text('pwhash', 65535)->nullable();
			$table->text('firstname', 65535)->nullable();
			$table->text('lastname', 65535)->nullable();
			$table->text('email', 65535)->nullable();
			$table->integer('loginlimit')->nullable();
			$table->integer('autoplay')->nullable()->default(0)->comment('0=>autoplay off, 1=>autoplay on');
			$table->string('remember_token', 100)->nullable();
			$table->timestamps();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user');
	}

}
