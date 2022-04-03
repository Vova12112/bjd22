<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFromToInAccidents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table(
		    'worker_accidents',
		    static function(Blueprint $table) {
			    $table->timestamp('sick_start')->after('hours_after_start_working')->nullable()->default(NULL);
			    $table->timestamp('sick_end')->after('sick_start')->nullable()->default(NULL);
		    }
	    );
    }
}
