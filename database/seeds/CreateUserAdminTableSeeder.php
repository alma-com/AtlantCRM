<?php

use Illuminate\Database\Seeder;

class CreateUserAdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Create User Admin
         */
        $user_id = DB::table('users')->insertGetId([
            'email' => 'You@email.com',
            'name' => 'My name',
            'password' => Hash::make('password'),
        ]);


        /**
         * Create Roles and Permission
         */
        $role_id = DB::table('roles')->insertGetId([
            'name' => 'admin',
            'display_name' => 'Администратор',
            'description' => '',
            'sort_order' => 0,
        ]);

        $group_id = DB::table('permission_groups')->insertGetId([
            'name' => 'general',
            'display_name' => 'Общие настройки',
            'description' => '',
            'sort_order' => 0,
        ]);

        $permission_id = DB::table('permissions')->insertGetId([
            'name' => 'manage_roles',
            'display_name' => 'Управление ролями',
            'description' => '',
            'sort_order' => 0,
            'group_id' => $group_id,
        ]);


        /**
         * Sign tables
         */
        DB::table('user_has_roles')->insert([
            'role_id' => $role_id,
            'user_id' => $user_id,
        ]);

        DB::table('role_has_permissions')->insert([
            'permission_id' => $permission_id,
            'role_id' => $role_id,
        ]);
        
    }
}
