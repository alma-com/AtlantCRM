<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
			$table->string('name');
			$table->text('description');
			$table->timestamp('date_start');
			$table->timestamp('deadline');
			$table->integer('planned_duration');
            $table->timestamps();
        });

		Schema::table('tasks', function (Blueprint $table) {
			$table->integer('project_id')->unsigned();
			$table->foreign('project_id')
			   ->references('id')
			   ->on('projects')
			   ->onDelete('cascade');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('tasks', function ($table) {
			$table->dropForeign('tasks_project_id_foreign');
		});
        Schema::drop('tasks');
    }
}
