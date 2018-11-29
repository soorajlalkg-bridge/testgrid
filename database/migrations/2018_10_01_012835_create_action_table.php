<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateActionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('action', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->text('action_type', 65535)->nullable();
		});

		// Insert some stuff
		$data = [
		    ['action_type' => 'login'],
		    ['action_type' => 'logout'],
		    ['action_type' => 'change password'],
		    ['action_type' => 'save config'],
		    ['action_type' => 'import config'],
		    ['action_type' => 'export config'],
		    ['action_type' => 'delete config'],
		];
		DB::table('action')->insert($data);
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('action');
	}

}
