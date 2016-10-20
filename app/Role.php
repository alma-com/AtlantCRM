<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->get();
    }

    public static function findAdmin()
    {
        return self::whereName('admin')->first();
    }

    public static function findUser()
    {
        return self::whereName('user')->first();
    }

    public static function findByIdOrName($value)
    {
        if (is_numeric($value)) {
            return self::whereId($value);
        }

        return self::whereName($value);
    }
    
    /**
     * Check access
     * @param  string|int $perm id or name permission
     * @return boolean
     */
    public function hasAccess($perm = '')
    {
        $permission = Permission::findByIdOrName($perm)->with('roles')->first();

        if (count($permission)) {
            $findPerm = $permission->roles()->find($this->id);
            if (count($findPerm)) {
                return true;
            }
        }

        return false;
    }
}
