<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;
use App\Role;

class UserHasRoleTest extends TestCase
{
	use DatabaseTransactions; 

    /**
     * Привязывание/отвязывание роли к пользователю
     */
    public function testExample()
    {
		$roleModel = new Role;
        $testUser = factory(User::class)->create();
		$this->seeInDatabase('users', ['id' => $testUser->id]);
		
		
		//Добавление ролей
		$role1 = Role::addRole(array('name' => 'testRole1'));
		$role2 = Role::addRole(array('name' => 'testRole2'));
		$this->assertTrue(!is_null($role1));
		$this->assertTrue(!is_null($role2));
		
		
		//Привязывание ролей к пользователю
		$testUser
			->assignRole($role1->name)
			->assignRole($role2->name)
			->assignRole('noExistRole');
		$this->seeInDatabase('user_has_roles', ['user_id' => $testUser->id, 'role_id' => $role1->id]);
		$this->seeInDatabase('user_has_roles', ['user_id' => $testUser->id, 'role_id' => $role2->id]);
		
		$this->assertTrue(!is_null($roleModel->getByName($role1->name)));
		$this->assertTrue(!is_null($roleModel->getByName($role2->name)));
		$this->assertTrue(is_null($roleModel->getByName('noExistRole')));
		
		
		//Отвязывание роли 1
		$testUser
			->deleteRole($role1->name)
			->deleteRole('noExistRole');
		$this->seeInDatabase('users', ['id' => $testUser->id]);
		$this->assertTrue(is_null($testUser->roles()->find($role1->id)));
		$this->assertTrue(!is_null($testUser->roles()->find($role2->id)));
		
		
		//Отвязывание роли 2
		$testUser->deleteRole($role2->name);
		$this->assertTrue(is_null($testUser->roles()->find($role1->id)));
		$this->assertTrue(is_null($testUser->roles()->find($role2->id)));
		
    }

}
