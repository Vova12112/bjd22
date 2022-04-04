<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultOrganizationToOrganizationTable extends Migration
{
	/**
	 * Run the migrations.
	 * @return void
	 */
	public function up()
	{
		DB::table('organizations')->insert(['id' => 1, 'name' => '', 'address' => '']);
	}

	/**
	 * Reverse the migrations.
	 * @return void
	 */
	public function down()
	{
	}
}
