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
        $arrGroup = array(
            'name' => 'general',
            'display_name' => 'Общие настройки',
        );
        PermissionGroup::add($arrGroup);

        $arrGroup = array(
            'name' => 'user',
            'display_name' => 'Пользователи',
        );
        PermissionGroup::add($arrGroup);

        return true;
    }


    /**
     * Добавление общих прав
     */
    public function addPermGeneral()
    {
        $groupName = 'general';

        $arrPermission = array(
            'name' => 'manage_role',
            'display_name' => 'Управление ролями',
        );
        Permission::add($arrPermission, $groupName);
    }



    /**
     * Добавление прав для пользователей
     */
    public function addPermUser()
    {
        $groupName = 'user';

        $arrPermission = array(
            'name' => 'show_user',
            'display_name' => 'Просмотр',
        );
        Permission::add($arrPermission, $groupName);

        $arrPermission = array(
            'name' => 'add_user',
            'display_name' => 'Добавление',
        );
        Permission::add($arrPermission, $groupName);

        $arrPermission = array(
            'name' => 'edit_user',
            'display_name' => 'Редактирование',
        );
        Permission::add($arrPermission, $groupName);

        $arrPermission = array(
            'name' => 'change_role_user',
            'display_name' => 'Смена роли',
        );
        Permission::add($arrPermission, $groupName);

        $arrPermission = array(
            'name' => 'delete_user',
            'display_name' => 'Удаление',
        );
        Permission::add($arrPermission, $groupName);
    }



    /**
     * Добавление роли
     */
    public function addRole()
    {
        $arrRole = array(
            'name' => 'admin',
            'display_name' => 'Администратор',
        );
        $role = Role::add($arrRole);

        $role
            ->assignPermission('manage_role')
            ->assignPermission('show_user')
            ->assignPermission('add_user')
            ->assignPermission('edit_user')
            ->assignPermission('change_role_user')
            ->assignPermission('delete_user');
    }
}
