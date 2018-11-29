<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToNodestatusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('nodestatus', function(Blueprint $table)
		{
			$table->foreign('nodeid', 'fk_nodestatus_node')->references('id')->on('node')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('nodestatus', function(Blueprint $table)
		{
			$table->dropForeign('fk_nodestatus_node');
		});
	}

}
