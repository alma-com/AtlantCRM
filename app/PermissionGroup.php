<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermissionGroup extends Model
{
	public $defaultName;
	
	
	protected $fillable = [
        'name', 'display_name', 'description', 'sort_order',
    ];
	
	public function __construct(){
		$this->defaultName = 'general';
	}
	
	public function permissions()
    {
        return $this->hasMany('App\Permission', 'group_id');
    }
	
	
		
	/**
	 * Получение id группы по названию
	 */
	public function getIdByName($name = ''){
		$group = array();
		$id_group = '';
		if($name != ''){
			$group = $this->where('name', $name)->first();
		}
		
		if(count($group) == 0){
			$group = $this->where('name', $this->defaultName)->first();
		}
		$id_group = $group->id;
		
		return $id_group;
	}
	
	
	
	/**
	 * Добавление группы прав
	 */
	public static function addGroup($arrData)
	{
		$sort_order = PermissionGroup::max('sort_order')+10;
		$arrDefault = array(
			'name' => '',
            'display_name' => '',
            'description' => '',
            'sort_order' => $sort_order,
		);
		$res = array_merge($arrDefault, $arrData);
		
		$group = PermissionGroup::where('name', $res['name'])->first();
		if(count($group) == 0){
			$group = new PermissionGroup;
			$group->name = $res['name'];
			$group->display_name = $res['display_name'];
			$group->description = $res['description'];
			$group->sort_order = $res['sort_order'];
			$group->save();
		}
				
		return $group;		
	}
	
	
}
