<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('project_manager')->nullable();
			$table->string('project_name');
			$table->string('description')->nullable();
			$table->integer('label')->nullable();
			$table->integer('category')->nullable();
			$table->string('client_company')->nullable();
			$table->integer('created_by');
			$table->boolean('is_completed')->default(0);
			$table->boolean('is_active')->default(1);
			$table->boolean('is_deleted')->default(0);
			$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
