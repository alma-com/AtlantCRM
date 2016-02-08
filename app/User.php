<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Hash;


/**
 * 
 * @method assignRole(int|string|object $role)
 * @method deleteRole(int|string|object $role)
 * 
 * @static @method add(array $arrData)
 * @static @method getModel(int|string|object $user)
 * @static @method checkArrayUser(array $arrData) 
 * 
 */
class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
	
	
	public function roles()
    {
       return $this->belongsToMany('App\Role', 'user_has_roles');
    }
	
	
	
	/**
	 * Assign role to the user
	 * 
	 * @param {int|string|object}
	 * 	@param {int} id role
	 * 	@param {string} name role
	 * 	@param {object} object role
	 *
	 * @returns {object}
	 */
	public function assignRole($role = '')
	{
		$role = Role::getModel($role);
		
		if(!is_null($role)){
			$this->roles()->save($role);
		}

		return $this;
	}
	
	
	
	/**
	 * Delete role to the user
	 * 
	 * @param {int|string|object}
	 * 	@param {int} id role
	 * 	@param {string} name role
	 * 	@param {object} object role
	 *
	 * @returns {object}
	 */
	public function deleteRole($role = '')
	{
		$role = Role::getModel($role);
		
		if(!is_null($role)){
			$this->roles()->detach($role);
		}
		
		return $this;
	}
	
	
	
	/**
	 * Save user
	 *
	 * @param {array} $arrData array with data to be added
	 * 	@param {string} $arrData['name'] name user
	 * 	@param {string} $arrData['email'] email user
	 * 	@param {string} $arrData['password'] password user
	 *
	 * @returns {object|null}
	 *
	 */
	public static function add($arrData = array())
	{
		if(self::checkArrayUser($arrData) === false){
			return null;
		}
		
		$arrDefault = array(
			'name' => '',
			'email' => '',
			'password' => '',
		);
		$res = array_merge($arrDefault, $arrData);
		
		$user = new User;
		$user->name = $res['name'];
		$user->email = $res['email'];
		if($res['password'] != ''){
			$user->password = Hash::make($res['password']);
		}
		$user->save();
		
		return $user;
	}
	
	
	
	/**
	 * Delete user
	 *
	 * @param {int|string|object}
	 * 	@param {int} id user
	 * 	@param {string} email user
	 * 	@param {object} object user
	 *
	 * @returns {true}
	 */
	public static function del($user = '')
	{
		$user = self::getModel($user);
		if(!is_null($user)){
			$user->delete();
		}
		
		return true;
	}
	
	
	
	
	/**
	 *  Getting the user by id or email or object
	 * 
	 * @param {int|string|object}
	 * 	@param {int} id user
	 * 	@param {string} email user
	 * 	@param {object} object user
	 *
	 * @returns {object|null}
	 */
	public static function getModel($user = '')
	{
		$userModel = null;
		if(is_string($user) === true){
			$userModel = self::where('email', $user);
		}
		if(is_int($user) === true){
			$userModel = self::find($user);
		}
		if(is_object($user) === true){
			$userModel = self::find($user->id);
		}
		
		return $userModel;
	}
	
	
	
	
	/**
	 * Checking to add to the array user
	 * 
	 * @returns {true|false}
	 */
	static function checkArrayUser($arrData = array())
	{
		if(
			is_array($arrData) === true 
			&& array_key_exists('name', $arrData) === true
			&& array_key_exists('email', $arrData) === true
		){
			return true;
		}
		
		return false;
	}	
}
