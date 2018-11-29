<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToConfigchannelTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('configchannel', function(Blueprint $table)
		{
			$table->foreign('channelid', 'fk_id_channel')->references('id')->on('channel')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('configgroupid', 'fk_id_configgroup')->references('id')->on('configgroup')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('configchannel', function(Blueprint $table)
		{
			$table->dropForeign('fk_id_channel');
			$table->dropForeign('fk_id_configgroup');
		});
	}

}
