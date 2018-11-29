<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNodestatusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('nodestatus', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('nodeid')->nullable()->unique('idx_nodestatus_nodeid');
			$table->dateTime('time')->nullable();
			$table->integer('status')->nullable();
			$table->text('version', 65535)->nullable();
			$table->time('uptime')->nullable();
			$table->integer('load')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('nodestatus');
	}

}
