<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateConfiggroupTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('configgroup', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('configid')->nullable()->index('idx_configfile_configid');
			$table->integer('groupindex')->nullable();
			$table->integer('numrows')->nullable();
			$table->integer('numcols')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('configgroup');
	}

}
