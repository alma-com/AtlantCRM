<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermissionGroup extends Model
{
    protected $fillable = [
        'name', 'display_name', 'description', 'sort_order',
    ];

    public function permissions()
    {
        return $this->hasMany('App\Permission', 'group_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }
}
