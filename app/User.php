<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

use Hash;

class User extends Authenticatable
{
	use HasRoles;
	
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
	
	
	
	
	/**
	 * Изменение пользователя
	 */
	public static function updateData($arrParam)
	{
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
