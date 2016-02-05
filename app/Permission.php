<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\PermissionGroup;

class Permission extends Model
{
    protected $fillable = [
        'name', 'display_name', 'description', 'sort_order', 'group_id',
    ];
	
	
	public function permissionGroups()
    {
        return $this->belongsTo('App\PermissionGroup', 'group_id');
    }
	
	
	/**
	 * Получение id права по названию
	 */
	public function getByName($name = ''){
		$permission = array();
		if($name != ''){
			$permission = $this->where('name', $name)->first();
		}
		
		return $permission;
	}
	
	
	/**
	 * Добавление права доступа
	 */
	public static function addPermission($arrData, $groupName = '')
	{
		if(is_array($arrData) === false){
			return null;
		}
		
		$sort_order = Permission::max('sort_order')+10;
		$permissionGroup = new PermissionGroup;
		$group_id =  $permissionGroup->getIdByName($groupName);
		
		$arrDefault = array(
			'name' => '',
            'display_name' => '',
            'description' => '',
            'sort_order' => $sort_order,
            'group_id' => $group_id,
		);
		$res = array_merge($arrDefault, $arrData);
		

		$permission = Permission::where('name', $res['name'])->first();
		if(count($permission) == 0){
			$permission = new Permission;
			$permission->name = $res['name'];
			$permission->display_name = $res['display_name'];
			$permission->description = $res['description'];
			$permission->sort_order = $res['sort_order'];
			$permission->group_id = $res['group_id'];
			$permission->save();
		}
		
		return $permission;		
	}
}
