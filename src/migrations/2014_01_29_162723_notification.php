<?php

use Illuminate\Database\Migrations\Migration;

class Notification extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		Schema::create('notification', function($table)
		{
			$table->increments('id');
			$table->integer('object_id')->unsigned()->nullable();
			$table->integer('actor_id')->unsigned()->nullable();
			$table->integer('created');
		});

		Schema::create('notification_object', function($table)
		{
			$table->increments('id');
			$table->string('object');
			$table->integer('object_id')->nullable();
		});

		Schema::create('notification_actor', function($table)
		{
			$table->increments('id');
			$table->string('actor');
			$table->integer('actor_id');
		});

		Schema::create('notification_user', function($table)
		{
			$table->increments('id');
			$table->integer('notification_id');
			$table->integer('user_id');
			$table->boolean('viewed')->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('notification_object');
		Schema::drop('notification_actor');
		Schema::drop('notification');
		Schema::drop('notification_user');
	}

}
