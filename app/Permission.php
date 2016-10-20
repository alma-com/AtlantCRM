<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = [
        'name', 'display_name', 'description', 'sort_order', 'group_id',
    ];

    public function permissionGroups()
    {
        return $this->belongsTo('App\PermissionGroup', 'group_id');
    }

    public function roles()
    {
       return $this->belongsToMany('App\Role', 'role_has_permissions');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    public static function findByIdOrName($value)
    {
        if (is_numeric($value)) {
            return self::whereId($value);
        }

        return self::whereName($value);
    }
}
