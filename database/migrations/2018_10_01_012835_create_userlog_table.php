<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserlogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('userlog', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('sessionid')->nullable()->index('idx_userlog_sessionid');
			$table->dateTime('timestamp')->nullable();
			$table->integer('action')->nullable();
			$table->integer('result')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('userlog');
	}

}
