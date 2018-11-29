<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToChannelTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('channel', function(Blueprint $table)
		{
			$table->foreign('nodeid', 'fk_channel_node')->references('id')->on('node')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('channel', function(Blueprint $table)
		{
			$table->dropForeign('fk_channel_node');
		});
	}

}
