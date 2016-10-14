<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUniqueFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->unique('name');
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->unique('name');
        });

        Schema::table('permission_groups', function (Blueprint $table) {
            $table->unique('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropUnique('roles_name_unique');
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->dropUnique('permissions_name_unique');
        });

        Schema::table('permission_groups', function (Blueprint $table) {
            $table->dropUnique('permission_groups_name_unique');
        });
    }
}
