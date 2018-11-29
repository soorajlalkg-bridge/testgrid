<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateConfigchannelTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('configchannel', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('configgroupid')->nullable()->index('idx_id_configid');
			$table->integer('groupindex')->nullable();
			$table->integer('position')->nullable();
			$table->integer('column_position')->nullable();
			$table->text('label', 65535)->nullable();
			$table->integer('channelid')->nullable()->index('idx_id_channelid');
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
		Schema::drop('configchannel');
	}

}
