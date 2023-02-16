<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectMailSchedulerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_mail_scheduler', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('project_id');
			$table->integer('creator_id')->nullable();
			$table->string('sender_id')->nullable();
			$table->string('mail_type')->nullable();
			$table->longText('description')->nullable();
			$table->string('receiver_id')->nullable();
			$table->string('cc_id')->nullable();
			$table->boolean('status')->default(0);
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
        Schema::dropIfExists('project_mail_scheduler');
    }
}
