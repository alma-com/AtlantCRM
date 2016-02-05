<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Hash;

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
	 * Привязывание роли к пользователю
	 */
	public function assignRole($nameRole = '')
	{
		if($nameRole != ''){
			$roleModel = new Role;
			$role = $roleModel->getByName($nameRole);
			
			if(count($role) > 0){
				$check = $this->roles()->find($role->id);
				if(count($check) == 0){
					$this->roles()->save($role);
				}
			}
			
		}
		return $this;
	}
	
	
	
	/**
	 * Отвязывание роли к пользователю
	 */
	public function deleteRole($nameRole = '')
	{
		if($nameRole != ''){
			$roleModel = new Role;
			$role = $roleModel->getByName($nameRole);
			
			if(!is_null($role)){
				$this->roles()->where('id', $role->id)->delete();
			}
			
		}
		return $this;
	}
	
	
	
	/**
	 * Изменение пользователя
	 */
	public static function updateData($arrParam)
	{
		if(is_array($arrParam) === false){
			return false;
		}
		
		$arrDefault = array(
			'id' => '',
			'name' => '',
			'email' => '',
			'password' => '',
		);
		$res = array_merge($arrDefault, $arrParam);
		
		$user = new User;
		if($res['id'] != ''){$user = User::find($res['id']);}
		$user->name = $res['name'];
		$user->email = $res['email'];
		if($res['password'] != ''){
			$user->password = Hash::make($res['password']);
		}
		$user->save();
		
		return $user;
	}
	
	
	
	/**
	 * Удаление пользователя
	 */
	public static function deleteData($id)
	{
		$user = User::find($id);
		$user->delete();
		
		return true;
	}
			
}
