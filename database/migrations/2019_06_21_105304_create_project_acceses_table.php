<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectAccesesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_access', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('task_group');
            $table->integer('project_id');
            $table->string('git_url')->nullable();
            $table->text('server')->nullable();
            $table->text('backend')->nullable();
            $table->text('database')->nullable();
            $table->text('domains')->nullable();
            $table->text('additional_info')->nullable();
            $table->integer('is_active')->default(1);
            $table->integer('is_deleted')->default(0);
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
        Schema::dropIfExists('project_access');
    }
}
