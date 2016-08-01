<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\PermissionGroup;
use App\Permission;
use App\Role;
use App\User;

class PermissionUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->addGroup();
        $this->addPermGeneral();
        $this->addPermUser();

        $this->addRole();
    }



    /**
     * Группы прав
     */
    public function addGroup()
    {
        PermissionGroup::create([
            'name' => 'general',
            'display_name' => 'Общие настройки',
        ]);

        PermissionGroup::create([
            'name' => 'user',
            'display_name' => 'Пользователи',
        ]);
    }


    /**
     * Добавление общих прав
     */
    public function addPermGeneral()
    {
        $generalGroup = PermissionGroup::where('name', 'general')->first();

        $permission = Permission::create([
            'name' => 'manage_role',
            'display_name' => 'Управление ролями',
            'group_id' => $generalGroup->id,
        ]);
    }



    /**
     * Добавление прав для пользователей
     */
    public function addPermUser()
    {
        $userGroup = PermissionGroup::where('name', 'user')->first();

        $permShow = Permission::create([
            'name' => 'show_user',
            'display_name' => 'Просмотр',
            'group_id' => $userGroup->id,
        ]);

        $permAdd = Permission::create([
            'name' => 'add_user',
            'display_name' => 'Добавление',
            'group_id' => $userGroup->id,
        ]);

        $permEdit = Permission::create([
            'name' => 'edit_user',
            'display_name' => 'Редактирование',
            'group_id' => $userGroup->id,
        ]);

        $permRole = Permission::create([
            'name' => 'change_role_user',
            'display_name' => 'Смена роли',
            'group_id' => $userGroup->id,
        ]);

        $permDelete = Permission::create([
            'name' => 'delete_user',
            'display_name' => 'Удаление',
            'group_id' => $userGroup->id,
        ]);
    }



    /**
     * Добавление роли
     */
    public function addRole()
    {
        $role = Role::create([
            'name' => 'admin',
            'display_name' => 'Администратор',
        ]);

        $permManage = Permission::where('name', 'manage_role')->first();
        $permShow = Permission::where('name', 'show_user')->first();
        $permAdd = Permission::where('name', 'add_user')->first();
        $permEdit = Permission::where('name', 'edit_user')->first();
        $permRole = Permission::where('name', 'change_role_user')->first();
        $permDelete = Permission::where('name', 'delete_user')->first();

        $role->permissions()->sync([
            $permManage->id,
            $permShow->id,
            $permAdd->id,
            $permEdit->id,
            $permRole->id,
            $permDelete->id,
        ]);
    }
}
