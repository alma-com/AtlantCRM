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
	 *
	 */
	public function getIdByName($name = ''){
		$group = array();
		if($name != ''){
			$group = $this->where('name', $name)->first();
		}
		
		if(count($group) == 0){
			$group = $this->where('name', $this->defaultName)->first();
		}
		
		return $group->id;
	}
	
	
}
