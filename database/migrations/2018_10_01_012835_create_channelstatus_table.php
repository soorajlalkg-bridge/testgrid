<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateChannelstatusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('channelstatus', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('channelid')->nullable()->index('idx_channelstatus_channelid');
			$table->dateTime('timestamp')->nullable();
			$table->text('config', 65535)->nullable();
			$table->integer('inputsignalstatus')->nullable();
			$table->text('source', 65535)->nullable();
			$table->time('uptime')->nullable();
			$table->integer('resyncs')->nullable();
			$table->integer('state')->nullable();
			$table->text('videourl', 65535)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('channelstatus');
	}

}
