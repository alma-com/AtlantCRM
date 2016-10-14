<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Slug;
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
    protected $signature = 'permission:update {model}';

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
        $class = $this->argument('model');
        $list = $class::setPermissions();

        $message = '';
        $message .= $this->keyExistMessage('group');
        $message .= $this->keyExistMessage('permissions');
        $message .= $this->groupMultipleMessage();

        if ($message !== '') {
            $this->info($message);
            return false;
        }

        //PermissionGroup::where('name', $groupKey);

        $group = PermissionGroup::create([
            'name' => Slug::make(key($list['group'])),
            'display_name' => array_shift($list['group']),
        ]);
        $groupId = $group->id;


        // $permissions = Permission::whereIn('name', array_keys($list['permissions']))->get();
        // if (count($permissions) > 0) {
        //     $permissionNames = implode(', ', $permissions->lists('name')->toArray());
        //     $this->info("permissions '".$permissionNames."' already exist");
        //     return false;
        // }

        // $group = PermissionGroup::create([
        //     'name' => $groupKey,
        //     'display_name' => $list['name'],
        // ]);
        // $groupId = $group->id;
        //
        // foreach ($list['permissions'] as $name => $displayName) {
        //     Permission::create([
        //         'name' => $name,
        //         'display_name' => $displayName,
        //         'group_id' => $groupId,
        //     ]);
        // }
    }

    /**
     * Key exist message
     * @param  string $key
     * @return string
     */
    public function keyExistMessage($key)
    {
        $class = $this->argument('model');
        $list = $class::setPermissions();
        if (!array_key_exists($key, $list)) {
            return "not found key '".$key."' in ".$class."::setPermissions()\n";
        }

        return '';
    }

    /**
     * if group multible return message
     * @param  string $key
     * @return string
     */
    public function groupMultipleMessage()
    {
        $class = $this->argument('model');
        $list = $class::setPermissions();
        $group = array_key_exists('group', $list) ? $list['group'] : [];

        if(count($list['group']) === 0 || count($list['group']) > 1) {
            return "group should be one. Now groups: ".count($list['group'])."\n";
        }

        return '';
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
