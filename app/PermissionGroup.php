<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * add - добавление группы
 * del - удаление группы
 * assignPermission - Привязывание права к группе
 * deletePermission - Отвязывание права от группы
 * getByName - Получение группы по названию
 * getModel - Получение модели группы по id или по названию или по моделе
 * getModelDefault - Получение модели по умолчанию
 */
class PermissionGroup extends Model
{
	public static $defaultName = 'general';
	
	
	protected $fillable = [
        'name', 'display_name', 'description', 'sort_order',
    ];
	
	public function permissions()
    {
        return $this->hasMany('App\Permission', 'group_id');
    }
	
	
	
	/**
	 * Добавление группы прав
	 */
	public static function add($arrData = '')
	{
		if(self::checkArrAdd($arrData) === false){return null;}
		
		if(is_string($arrData) === true && $arrData !== ''){
			$arrData = array('name' => $arrData);
		}
		$sort_order = PermissionGroup::max('sort_order')+10;
		$arrDefault = array(
			'name' => '',
            'display_name' => '',
            'description' => '',
            'sort_order' => $sort_order,
		);
		$res = array_merge($arrDefault, $arrData);
		
		$group = PermissionGroup::where('name', $res['name'])->first();
		if(is_null($group)){
			$group = new PermissionGroup;
			$group->name = $res['name'];
			$group->display_name = $res['display_name'];
			$group->description = $res['description'];
			$group->sort_order = $res['sort_order'];
			$group->save();
			return $group;	
		}
				
		return null;		
	}
	
	
	
	/**
	 * Удаление группы прав
	 */
	public static function del($name = '')
	{
		$group = self::getModel($name);
		$permission = $group->permissions()->get();
		if(count($permission) > 0){
			foreach($permission as $key => $item){
				$group->deletePermission($item);
			}
		}
		
		$group->delete();
		return true;
	}
	
	
	
	/**
	 * Привязывание права к группе
	 */
	public function assignPermission($name = '')
	{
		$permission = Permission::getModel($name);
		
		if(!is_null($permission)){
			$this->permissions()->save($permission);
		}

		return $this;
	}
	
	
	
	/**
	 * Отвязывание права от группы
	 */
	public function deletePermission($name = '')
	{	
		$permission = Permission::getModel($name);
		$groupDefault = self::getModelDefault();
		
		if(!is_null($permission)){
			//$this->permissions()->where('id', $permission->id)->delete();
			$groupDefault->assignPermission($permission);
		}
		
		return $this;
	}
	
	
	
	/**
	 * Получение группы по названию
	 */
	public static function getByName($name = ''){
		$group = null;
		$id_group = '';
		if($name != ''){
			$group = self::where('name', $name)->first();
		}
		
		if(is_null($group)){
			$group = self::where('name', self::$defaultName)->first();
			if(is_null($group)){
				$group = self::add(array('name' => self::$defaultName));
			}
		}
		
		return $group;
	}
	
	
	
	/**
	 * Получение модели группы по id или по названию или по моделе
	 */
	public static function getModel($name = '')
	{
		$group = null;
		if(is_string($name) === true){
			$group = self::getByName($name);
		}
		if(is_int($name) === true){
			$group = self::find($name);
		}
		if(is_object($name) === true){
			$group = self::find($name->id);
		}
		
		return $group;
	}
	
	
	
	/**
	 * Получение модели по умолчанию
	 */
	public static function getModelDefault()
	{
		return self::getModel(self::$defaultName);
	}
	
	
	
	/**
	 * Self function --------------------------------------------------------------------------------
	 */
	
	
	/**
	 * Проверка массива для добавления группы
	 */
	static function checkArrAdd($arrData = '')
	{
		if(is_string($arrData) === true && $arrData !== ''){
			return true;
		}
		if(is_array($arrData) === true && array_key_exists('name', $arrData) === true){
			return true;
		}
		
		return false;
	}	
}
