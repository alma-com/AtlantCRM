<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


/**
 * 
 * @method assignPermission(int|string|object $perm)
 * @method deletePermission(int|string|object $perm)
 *
 * @static @method add(array $arrData)
 * @static @method del(int|string|object $group)
 * @static @method getByName(string $name)
 * @static @method getModel(int|string|object $role)
 * @static @method checkArrayRole(array $arrData)
 *
 */
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
	 * Assign permission to the role
	 * 
	 * @param {int|string|object}
	 * 	@param {int} id permission
	 * 	@param {string} name permission
	 * 	@param {object} object permission
	 *
	 * @returns {object}
	 */
	public function assignPermission($perm = '')
	{
		$permission = Permission::getModel($perm);
		
		if(!is_null($permission)){
			$this->permissions()->save($permission);
		}

		return $this;
	}
	
	
	
	/**
	 * Delete permission to the role
	 * 
	 * @param {int|string|object}
	 * 	@param {int} id permission
	 * 	@param {string} name permission
	 * 	@param {object} object permission
	 *
	 * @returns {object}
	 */
	public function deletePermission($perm = '')
	{
		$permission = Permission::getModel($perm);
		
		if(!is_null($permission)){
			$this->permissions()->detach($permission);
		}
		
		return $this;
	}
	
	
	
	/**
	 * Adding role
	 * 
	 * @param {array} $arrData array with data to be added
	 * 	@param {string} $arrData['name'] name role
	 * 	@param {string} $arrData['display_name'] display name of the role
	 * 	@param {string} $arrData['description'] role description
	 * 	@param {string} $arrData['sort_order'] sorting order
	 * 	
	 * @returns {object|null} - return object models or null
	 */
	public static function add($arrData = array())
	{
		if(self::checkArrayRole($arrData) === false){return null;}
		
		if(is_string($arrData) === true && $arrData !== ''){
			$arrData = array('name' => $arrData);
		}
		
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
	 * Delete role
	 *
	 * @param {int|string|object}
	 * 	@param {int} id role
	 * 	@param {string} name role
	 * 	@param {object} object role
	 *
	 * @returns {true}
	 */
	public static function del($role = '')
	{
		$role = self::getModel($role);
		if(!is_null($role)){			
			$role->delete();
		}
		
		return true;
	}
	
	
	
	/**
	 * Getting a role by name
	 * 
	 * @param {string} name role
	 *
	 * @returns {object|null}
	 */
	public static function getByName($name = ''){
		$role =null;
		if($name != ''){
			$role = self::where('name', $name)->first();
		}
		
		return $role;
	}
	
	
	
	/**
	 *  Getting the role by id or name or object
	 * 
	 * @param {int|string|object}
	 * 	@param {int} id role
	 * 	@param {string} name role
	 * 	@param {object} object role
	 *
	 * @returns {object|null}
	 */
	public static function getModel($role = '')
	{
		$roleModel = null;
		if(is_string($role) === true){
			$roleModel = self::getByName($role);
		}
		if(is_int($role) === true){
			$roleModel = self::find($role);
		}
		if(is_object($role) === true){
			$roleModel = self::find($role->id);
		}
		
		return $roleModel;
	}
	
	
	
	/**
	 * Checking to add to the array role
	 * 
	 * @returns {true|false}
	 */
	static function checkArrayRole($arrData = array())
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
