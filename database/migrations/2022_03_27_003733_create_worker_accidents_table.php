<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkerAccidentsTable extends Migration
{
	/**
	 * Run the migrations.
	 * @return void
	 */
	public function up()
	{
		Schema::create('worker_accidents', function(Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('worker_id');
			$table->unsignedBigInteger('accident_type_id');
			$table->timestamp('accident_at');
			$table->integer('hours_after_start_working');
			$table->timestamp('created_at')->useCurrent();
			$table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
			$table->timestamp('deleted_at')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('worker_accidents');
	}
}
