<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
	        $table->string('name')->nullable();
	        $table->string('address')->nullable();
	        $table->timestamp('created_at')->useCurrent();
	        $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
	        $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
}
