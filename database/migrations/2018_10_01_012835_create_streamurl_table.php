<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStreamurlTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('streamurl', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('channelid')->nullable()->index('fk_streamurl_channel_0');
			$table->text('url', 65535)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('streamurl');
	}

}
