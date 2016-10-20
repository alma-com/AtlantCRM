<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_id = DB::table('roles')->insertGetId([
            'name' => 'admin',
            'display_name' => 'Администратор',
            'sort_order' => 0,
        ]);

        $role_user_id = DB::table('roles')->insertGetId([
            'name' => 'user',
            'display_name' => 'Обычный пользователь',
            'sort_order' => 1,
        ]);
    }
}
