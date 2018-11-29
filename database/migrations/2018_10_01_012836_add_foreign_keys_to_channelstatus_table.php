<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToChannelstatusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('channelstatus', function(Blueprint $table)
		{
			$table->foreign('channelid', 'fk_channelstatus_channel')->references('id')->on('channel')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('channelstatus', function(Blueprint $table)
		{
			$table->dropForeign('fk_channelstatus_channel');
		});
	}

}
