<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
	 * Привязывание права к роли
	 */
	public function assignPermission($name = '')
	{
		$permission = null;
		if(is_string($name) === true){
			$permission = Permission::getByName($name);
		}
		if(is_int($name) === true){
			$permission = Permission::find($name);
		}
		if(is_object($name) === true){
			$permission = $name;
		}
		
		
		if(!is_null($permission)){
			$this->permissions()->save($permission);
		}

		return $this;
	}
	
	
	
	/**
	 * Self function
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
