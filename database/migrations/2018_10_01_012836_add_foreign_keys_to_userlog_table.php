<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToUserlogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('userlog', function(Blueprint $table)
		{
			$table->foreign('sessionid', 'fk_userlog_session')->references('id')->on('session')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('userlog', function(Blueprint $table)
		{
			$table->dropForeign('fk_userlog_session');
		});
	}

}
