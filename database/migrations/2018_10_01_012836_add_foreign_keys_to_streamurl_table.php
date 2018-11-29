<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToStreamurlTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('streamurl', function(Blueprint $table)
		{
			$table->foreign('channelid', 'fk_streamurl_channel_0')->references('id')->on('channel')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('streamurl', function(Blueprint $table)
		{
			$table->dropForeign('fk_streamurl_channel_0');
		});
	}

}
