<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePermissionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name');
            $table->integer('sort_order');
            $table->timestamps();
        });

        Schema::create('permission_groups', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name');
            $table->integer('sort_order');
            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name');
            $table->integer('group_id')->nullable()->unsigned();
            $table->integer('sort_order');
            $table->timestamps();

            $table->foreign('group_id')
               ->references('id')
               ->on('permission_groups')
               ->onDelete('cascade');
        });

        Schema::create('user_has_permissions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('user_id')->unsigned();
            $table->integer('permission_id')->unsigned();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('permission_id')
                ->references('id')
                ->on('permissions')
                ->onDelete('cascade');

            $table->primary(['user_id', 'permission_id']);
        });

        Schema::create('user_has_roles', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('role_id')->unsigned();
            $table->integer('user_id')->unsigned();

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->primary(['role_id', 'user_id']);
        });

        Schema::create('role_has_permissions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('permission_id')->unsigned();
            $table->integer('role_id')->unsigned();

            $table->foreign('permission_id')
                ->references('id')
                ->on('permissions')
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('role_has_permissions');
        Schema::drop('user_has_roles');
        Schema::drop('user_has_permissions');
        Schema::drop('roles');
        Schema::drop('permission_groups');
        Schema::drop('permissions');
    }
}
