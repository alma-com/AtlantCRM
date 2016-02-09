<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;
use App\Role;
use App\Permission;

class ModelUserTest extends TestCase
{
	use DatabaseTransactions; 
	
    public function testExample()
    {
		$arr = Array(
			'faker' => Faker\Factory::create(),
		);
		
		$this->addTest($arr);
		$this->updateTest($arr);
		$this->assignRoleTest($arr);
		$this->accessTest($arr);
		$this->deleteRoleTest($arr);
        $this->deleteTest($arr);
    }
	
	
	/**
	 * Add user
	 */
	public function addTest($arr)
	{
		//Success
		$userArrayMin = User::add(array(
			'name' => str_random(10),
			'email' => $arr['faker']->email,
		));
		$this->assertFalse(is_null($userArrayMin));
		$this->seeInDatabase('users', [
			'id' => $userArrayMin->id, 
			'name' => $userArrayMin->name,
			'email' => $userArrayMin->email
		]);
		
		$userArrayFull = User::add(array(
			'name' => str_random(10),
            'email' => $arr['faker']->email,
            'password' => Hash::make(str_random(10)),
		));
		$this->assertFalse(is_null($userArrayFull));
		$this->seeInDatabase('users', [
			'id' => $userArrayFull->id,
			'name' => $userArrayFull->name,
			'email' => $userArrayFull->email,
			'password' => $userArrayFull->password
		]);
		
		
		//Error
		$userNull = User::add();
		$userString = User::add(str_random(10));
		$userInt = User::add($arr['faker']->randomNumber);
		$userErrArray = User::add(array('name' => str_random(10)));
		
		$this->assertTrue(is_null($userNull));
		$this->assertTrue(is_null($userInt));
		$this->assertTrue(is_null($userErrArray));
	}
	
	
	
	/**
	 * Update user
	 */
	public function updateTest($arr)
	{
		$user = User::add(array(
			'name' => str_random(10),
			'email' => $arr['faker']->email,
		));
		
		$newName = str_random(10);
		$user->name = $newName;
		$user->save();
		
		$this->seeInDatabase('users', [
			'id' => $user->id,
			'name' => $newName
		]);
	}
	
	
	
	/**
	 * Assign role to the user
	 */
	public function assignRoleTest($arr)
	{
		//Success
		$user = User::add(array(
			'name' => str_random(10),
			'email' => $arr['faker']->email,
		));
		$roleOne = Role::add(str_random(10));
		$roleTwo = Role::add(str_random(10));
		$roleThree = Role::add(str_random(10));
		
		$user
			->assignRole($roleOne->name)
			->assignRole($roleTwo)
			->assignRole($roleThree->id);
			
		$this->seeInDatabase('user_has_roles', ['role_id' => $roleOne->id, 'user_id' => $user->id]);
		$this->seeInDatabase('user_has_roles', ['role_id' => $roleTwo->id, 'user_id' => $user->id]);
		$this->seeInDatabase('user_has_roles', ['role_id' => $roleThree->id, 'user_id' => $user->id]);
		
		$this->assertTrue(count($user->roles()->get()) == 3);
		
		
		
		//Error
		$userErr = User::add(array(
			'name' => str_random(10),
			'email' => $arr['faker']->email,
		));
		$userErr
			->assignRole(str_random(12))
			->assignRole();
		
		$this->assertTrue(count($userErr->roles()->get()) == 0);
	}
	
	
	
	/**
	 * Check access user
	 */
	public function accessTest($arr)
	{
		//Success
		$user = User::add(array(
			'name' => str_random(10),
			'email' => $arr['faker']->email,
		));
		$role = Role::add(str_random(10));
		$permOne = Permission::add(str_random(10));
		$permTwo = Permission::add(str_random(10));
		$permThree = Permission::add(str_random(10));
		
		$role
			->assignPermission($permOne)
			->assignPermission($permTwo)
			->assignPermission($permThree);
			
		$user->assignRole($role);
		
		$this->assertTrue($user->access($permOne));
		$this->assertTrue($user->access($permTwo->name));
		$this->assertTrue($user->access($permThree->id));
		
		
		//Error
		$role->deletePermission($permOne);
		$this->assertFalse($user->access($permOne->id));
		$this->assertTrue($user->access($permTwo->name));
		$this->assertTrue($user->access($permThree));
		$this->assertFalse($user->access(str_random(10)));
		
	}
	
	
	
	/**
	 * Delete role to the user
	 */
	public function deleteRoleTest($arr)
	{
		//Success
		$user = User::add(array(
			'name' => str_random(10),
			'email' => $arr['faker']->email,
		));
		$roleOne = Role::add(str_random(10));
		$roleTwo = Role::add(str_random(10));
		$roleThree = Role::add(str_random(10));
		
		$user
			->assignRole($roleOne->name)
			->assignRole($roleTwo)
			->assignRole($roleThree->id);
		

		$user->deleteRole($roleOne->name);
		$this->assertTrue(count($user->roles()->get()) == 2);
		
		$user->deleteRole($roleTwo->id);
		$this->assertTrue(count($user->roles()->get()) == 1);
		
		$user->deleteRole($roleThree);
		$this->assertTrue(count($user->roles()->get()) == 0);

		
		//Error
		$user->assignRole($roleThree);
		$user->deleteRole(str_random(12));
		$this->assertTrue(count($user->roles()->get()) == 1);
	}
	
	
	
	/**
	 * Delete user
	 */
	public function deleteTest($arr)
	{
		//success
		$userModel = User::add(array(
			'name' => str_random(10),
			'email' => $arr['faker']->email,
		));
		User::del($userModel);
		$this->assertTrue(is_null(User::find($userModel->id)));
		
		$userEmail = User::add(array(
			'name' => str_random(10),
			'email' => $arr['faker']->email,
		));
		User::del($userEmail->email);
		$this->assertTrue(is_null(User::find($userEmail->id)));
		
		$userId = User::add(array(
			'name' => str_random(10),
			'email' => $arr['faker']->email,
		));
		User::del($userId->id);
		$this->assertTrue(is_null(User::find($userId->id)));
		
		
		
		$user = User::add(array(
			'name' => str_random(10),
			'email' => $arr['faker']->email,
		));
		$roleOne = Role::add(str_random(10));
		$roleTwo = Role::add(str_random(10));
		$roleThree = Role::add(str_random(10));
		
		$user
			->assignRole($roleOne->name)
			->assignRole($roleTwo)
			->assignRole($roleThree->id);
			
		
		User::del($user);
		
		$this->assertTrue(count($user->roles()->get()) == 0);

		
		
		//error
		User::del();	
		User::del(str_random(12));	
	}
}
