<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_comments', function (Blueprint $table) {
			$table->increments('id');
			$table->bigInteger('commented_by')->unsigned()->index();
			$table->integer('task_group_id')->nullable();
			$table->integer('task_id');
			$table->text('description');
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
        Schema::dropIfExists('task_comments');
    }
}
