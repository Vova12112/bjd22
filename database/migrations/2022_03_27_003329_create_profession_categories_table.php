<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfessionCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profession_categories', function (Blueprint $table) {
            $table->id();
	        $table->string('name');
	        $table->timestamp('created_at')->useCurrent();
	        $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
	        $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profession_categories');
    }
}
