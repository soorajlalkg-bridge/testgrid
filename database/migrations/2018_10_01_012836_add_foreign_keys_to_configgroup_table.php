<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToConfiggroupTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('configgroup', function(Blueprint $table)
		{
			$table->foreign('configid', 'fk_configfile_configoverview')->references('id')->on('config')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('configgroup', function(Blueprint $table)
		{
			$table->dropForeign('fk_configfile_configoverview');
		});
	}

}
