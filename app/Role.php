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
			$role->name = $res['name'];
			$role->display_name = $res['display_name'];
			$role->description = $res['description'];
			$role->sort_order = $res['sort_order'];
			$role->save();
		}
		
		return $role;		
	}
	
	
	
	 /**
	 * Привязывание права к роли
	 */
	public function assignPermission($namePermission = '')
	{
		if($namePermission != ''){
			$permission = new Permission;
			$permission = $permission->getByName($namePermission);
			
			if(count($permission) > 0){
				$check = $this->permissions()->find($permission->id);
				if(count($check) == 0){
					$this->permissions()->save($permission);
				}
			}
			
		}
		return $this;
	}
	
	
	
	/**
	 * Получение id роли по названию
	 */
	public function getByName($name = ''){
		$role = array();
		if($name != ''){
			$role = $this->where('name', $name)->first();
		}
		
		return $role;
	}
	
	
}
