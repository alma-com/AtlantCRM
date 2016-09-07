<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


/**
 * @method access(int|string|object $perm)
 * @static @method getModel(int|string|object $role)
 */
class Role extends Model
{

    protected $fillable = [
        'name', 'display_name', 'description', 'sort_order',
    ];


    public function permissions()
    {
       return $this->belongsToMany('App\Permission', 'role_has_permissions');
    }

    public function users()
    {
       return $this->belongsToMany('App\User', 'user_has_roles');
    }

    /**
     * scopeOrdered
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->get();
    }

    /**
     * Check access role
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
        $permission = Permission::getModel($perm);
        if(is_null($permission)){
            return false;
        }

        $findPerm = $permission->roles()->find($this->id);
        if(count($findPerm) > 0){
            return true;
        }

        return false;
    }

    /**
     *  Getting the role by id or name or object
     *
     * @param {int|string|object}
     * @param {int} id role
     * @param {string} name role
     * @param {object} object role
     *
     * @returns {object|null}
     */
    public static function getModel($role = '')
    {
        $roleModel = null;
        if(is_string($role) === true){
            $roleModel = self::where('name', $role);
        }
        if(is_numeric($role) === true){
            $roleModel = self::find($role);
        }
        if(is_object($role) === true){
            $roleModel = self::find($role->id);
        }

        return $roleModel;
    }
}
