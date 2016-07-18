<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Hash;


/**
 * @method access(int|string|object $perm)
 * @method hasRole(int|string|object $role)
 * @static @method getModel(int|string|object $user)
 */
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

    public function roles()
    {
       return $this->belongsToMany('App\Role', 'user_has_roles');
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    /**
     * Check access user
     *
     * @param {int|string|object}
     * @param {int} id perm
     * @param {string} name perm
     * @param {object} object perm
     *
     * @returns {true|false}
     */
    public function access($perm = '')
    {
        $roles = $this->roles()->get();
        $permission = Permission::getModel($perm);

        if(is_null($roles) || is_null($permission)){
            return false;
        }

        foreach($roles as $key => $item){
            $findPerm = $permission->roles()->find($item->id);
            if(count($findPerm) > 0){
                return true;
            }
        }

        return false;
    }

    /**
     * Has role
     *
     * @param {int|string|object}
     * @param {int} id role
     * @param {string} name role
     * @param {object} object role
     *
     * @returns {true|false}
     */
    public function hasRole($role = '')
    {
        $roles = Role::getModel($role);

        if(is_null($roles)){
            return false;
        }

        $findRole = $this->roles()->find($roles->id);
        if(count($findRole) > 0){
            return true;
        }

        return false;
    }

    /**
     *  Getting the user by id or email or object
     *
     * @param {int|string|object}
     * @param {int} id user
     * @param {string} email user
     * @param {object} object user
     *
     * @returns {object|null}
     */
    public static function getModel($user = '')
    {
        $userModel = null;
        if(is_string($user) === true){
            $userModel = self::where('email', $user);
        }
        if(is_numeric($user) === true){
            $userModel = self::find($user);
        }
        if(is_object($user) === true){
            $userModel = self::find($user->id);
        }

        return $userModel;
    }
}
