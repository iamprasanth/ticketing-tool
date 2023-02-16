<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_tasks', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('task_group_id');
			$table->string('task_name');
			$table->text('description')->nullable();
			$table->string('assignee')->nullable();
			$table->string('subscribers')->nullable();
			$table->integer('label')->nullable();
			$table->boolean('priority')->default(0);
			$table->string('project_file')->nullable();
			$table->date('due_date');
			$table->integer('created_by')->nullable();
			$table->boolean('is_completed')->default(1);
			$table->string('estimate')->nullable();
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
        Schema::dropIfExists('project_tasks');
    }
}
