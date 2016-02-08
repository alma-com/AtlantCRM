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
	 * Добавление права доступа
	 */
	public static function add($arrData, $groupName = '')
	{
		if(is_string($arrData) === true && $arrData !== ''){
			$arrData = array('name' => $arrData);
		}
		if(is_array($arrData) === false){
			return null;
		}
		
		$sort_order = Permission::max('sort_order')+10;
		$group =  PermissionGroup::getByName($groupName);
		
		$arrDefault = array(
			'name' => '',
            'display_name' => '',
            'description' => '',
            'sort_order' => $sort_order,
            'group_id' => $group->id,
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
	
	
	
	
	/**
	 * Получение права по названию
	 */
	public static function getByName($name = ''){
		$permission = null;
		if($name != ''){
			$permission = self::where('name', $name)->first();
		}
		
		return $permission;
	}
	
	
	
	/**
	 * Получение модели права по id или по названию или по моделе
	 */
	public static function getModel($name = '')
	{
		$permission = null;
		if(is_string($name) === true){
			$permission = self::getByName($name);
		}
		if(is_int($name) === true){
			$permission = self::find($name);
		}
		if(is_object($name) === true){
			$permission = self::find($name->id);
		}
		
		return $permission;
	}
}
