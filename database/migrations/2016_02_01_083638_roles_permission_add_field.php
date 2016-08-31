<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RolesPermissionAddField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission_groups', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('display_name');
            $table->string('description');
            $table->integer('sort_order');
            $table->timestamps();
        });

        Schema::table('roles', function ($table) {
            $table->string('display_name');
            $table->string('description');
            $table->integer('sort_order');
        });

        Schema::table('permissions', function ($table) {
            $table->string('display_name');
            $table->string('description');
            $table->integer('sort_order');
            $table->integer('group_id')->unsigned();

             $table->foreign('group_id')
                ->references('id')
                ->on('permission_groups')
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

        Schema::table('roles', function ($table) {
            $table->dropColumn(['display_name', 'description', 'sort_order']);
        });

        Schema::table('permissions', function ($table) {
            $table->dropForeign('permissions_group_id_foreign');
            $table->dropColumn(['display_name', 'description', 'sort_order', 'group_id']);
        });

        Schema::drop('permission_groups');

    }
}
