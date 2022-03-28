<?php

use App\ValuesObject\Genders;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkersTable extends Migration
{
	/**
	 * Run the migrations.
	 * @return void
	 */
	public function up()
	{
		Schema::create('workers', function(Blueprint $table) {
			$table->id();
			$table->string('first_name')->nullable();
			$table->string('last_name')->nullable();
			$table->string('sub_name')->nullable();
			$table->integer('sex')->default(Genders::UNKNOWN)->nullable();
			$table->boolean('married')->default(FALSE);
			$table->timestamp('birth_at')->nullable();
			$table->unsignedBigInteger('structure_segment_id');
			$table->unsignedBigInteger('profession_id');
			$table->timestamp('body_check_at')->nullable();
			$table->timestamp('instructed_at')->nullable();
			$table->string('description')->nullable();
			$table->timestamp('fired_at')->nullable();
			$table->timestamp('created_at')->useCurrent();
			$table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
		});
	}

	/**
	 * Reverse the migrations.
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('workers');
	}
}
