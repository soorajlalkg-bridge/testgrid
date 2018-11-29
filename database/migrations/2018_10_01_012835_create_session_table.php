<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSessionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('session', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('userid')->nullable()->index('idx_session_userid');
			$table->text('session_token', 65535)->nullable();
			$table->dateTime('start')->nullable();
			$table->dateTime('end')->nullable();
			$table->dateTime('last_activity')->nullable();
			$table->string('ip', 20)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('session');
	}

}
