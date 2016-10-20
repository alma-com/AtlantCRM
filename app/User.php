<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Hash;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Set permission
     * Command: php artisan crm:permission-update 'App\User'
     */
    public static function setPermissions()
    {
        return [
            'group' => ['users' => 'Пользователи'],
            'permissions' => [
                'show users' => 'Просмотр',
                'manage role users' => 'Управление ролями',
                'add users' => 'Добавление',
                'edit users' => 'Редактирование',
                'change role users' => 'Смена роли',
                'delete users' => 'Удаление',
            ],
        ];
    }

    public function roles()
    {
       return $this->belongsToMany('App\Role', 'user_has_roles');
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    /**
     * Check hasAccess user
     * @param  string|int $perm id or name permission
     * @return boolean
     */
    public function hasAccess($perm = '')
    {
        $roles = $this->roles()->get();
        $permission = Permission::findByIdOrName($perm)->with('roles')->first();

        foreach($roles as $key => $item){
            $findPerm = $permission->roles()->find($item->id);
            if (count($findPerm)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Has role
     * @param  string|int  $role name or id
     * @return boolean
     */
    public function hasRole($role = '')
    {
        $roles = Role::findByIdOrName($role)->first();
        if (count($roles)) {
            $findRole = $this->roles()->find($roles->id);
            if (count($findRole) > 0) {
                return true;
            }
        }

        return false;
    }
}
