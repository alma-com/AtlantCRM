<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;
use App\Role;
use App\Permission;

class RoleHasPermissionTest extends TestCase
{
	use DatabaseTransactions; 
	
   /**
     * Привязывание/отвязывание права доступа к роли
     */
    public function testExample()
    {
		$permissionModel = new Permission;
		$testRole = Role::addRole(array('name' => 'testRole111'));
		$this->assertTrue(!is_null($testRole));
	   
	   
		//Добавление прав доступа
		$permission1 = Permission::addPermission(array('name' => 'testPermission1'));
		$permission2 = Permission::addPermission(array('name' => 'testPermission2'));
		$this->assertTrue(!is_null($permission1));
		$this->assertTrue(!is_null($permission2));
		
		
		//Привязывание прав к роли
		$testRole
			->assignPermission($permission1->name)
			->assignPermission($permission2->name)
			->assignPermission('noExistPermission');
		$this->seeInDatabase('role_has_permissions', ['role_id' => $testRole->id, 'permission_id' => $permission1->id]);
		$this->seeInDatabase('role_has_permissions', ['role_id' => $testRole->id, 'permission_id' => $permission2->id]);
		
		$this->assertTrue(!is_null($permissionModel->getByName($permission1->name)));
		$this->assertTrue(!is_null($permissionModel->getByName($permission2->name)));
		$this->assertTrue(is_null($permissionModel->getByName('noExistPermission')));
		
		
		//Отвязывание права 1
		$testRole
			->deletePermission($permission1->name)
			->deletePermission('noExistRole');
		$this->assertTrue(is_null($testRole->permissions()->find($permission1->id)));
		$this->assertTrue(!is_null($testRole->permissions()->find($permission2->id)));
		
		
		//Отвязывание роли 2
		$testRole->deletePermission($permission2->name);
		$this->assertTrue(is_null($testRole->permissions()->find($permission1->id)));
		$this->assertTrue(is_null($testRole->permissions()->find($permission2->id)));
    }
	
}
