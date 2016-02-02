<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
	
	protected $fillable = [
        'name', 'display_name', 'description', 'sort_order',
    ];
	
	
	
    /**
	 * Добавление роли
	 */
	public static function addRole($arrData)
	{
		$sort_order = Role::max('sort_order')+10;
		$arrDefault = array(
			'name' => '',
            'display_name' => '',
            'description' => '',
            'sort_order' => $sort_order,
		);
		$res = array_merge($arrDefault, $arrData);
		
		$role = Role::where('name', $res['name'])->first();
		if(count($role) == 0){
			$role = new Role;
		}
		
		$role->name = $res['name'];
		$role->display_name = $res['display_name'];
		$role->description = $res['description'];
		$role->sort_order = $res['sort_order'];
		$role->save();
		
		return $role;		
	}
	
	
}
