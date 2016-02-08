<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\PermissionGroup;
use App\Permission;

class ModelPermissionGroupTest extends TestCase
{
	use DatabaseTransactions; 
	

    public function testExample()
    {
		$arr = Array(
			'faker' => Faker\Factory::create(),
		);
		
        $this->addTest($arr);
        $this->assignPermissionTest($arr);
        $this->deletePermissionTest($arr);
        $this->deleteTest($arr);
    }
	
	
	

	public function addTest($arr)
	{	
		//Success
		$groupString = PermissionGroup::add(str_random(10));
		$groupArrayMin = PermissionGroup::add(array('name' => str_random(10)));
		$groupArrayFull = PermissionGroup::add(array(
			'name' => str_random(10),
            'display_name' => str_random(10),
            'description' => str_random(10),
            'sort_order' => $arr['faker']->randomNumber,
		));
		
		$this->assertFalse(is_null($groupString));
		$this->assertFalse(is_null($groupArrayMin));
		$this->assertFalse(is_null($groupArrayFull));
		
		$this->seeInDatabase('permission_groups', ['id' => $groupString->id, 'name' => $groupString->name]);
		$this->seeInDatabase('permission_groups', ['id' => $groupArrayMin->id, 'name' => $groupArrayMin->name]);
		$this->seeInDatabase('permission_groups', [
			'id' => $groupArrayFull->id,
			'name' => $groupArrayFull->name,
			'display_name' => $groupArrayFull->display_name,
			'description' => $groupArrayFull->description,
			'sort_order' => $groupArrayFull->sort_order
		]);
		
		
		
		//Error
		$groupNull = PermissionGroup::add();
		$groupInt = PermissionGroup::add($arr['faker']->randomNumber);
		$groupErrArray = PermissionGroup::add(array('display_name' => str_random(10)));
		
		$this->assertTrue(is_null($groupNull));
		$this->assertTrue(is_null($groupInt));
		$this->assertTrue(is_null($groupErrArray));
	}
	
	
	
	public function assignPermissionTest($arr)
	{
		//Success
		$group = PermissionGroup::add(str_random(10));
		$permissionOne = Permission::add(str_random(10));
		$permissionTwo = Permission::add(str_random(10));
		$permissionThree = Permission::add(str_random(10));
		
		$group
			->assignPermission($permissionOne)
			->assignPermission($permissionTwo->name)
			->assignPermission($permissionThree->id);
			
		$this->seeInDatabase('permissions', ['id' => $permissionOne->id, 'group_id' => $group->id]);
		$this->seeInDatabase('permissions', ['id' => $permissionTwo->id, 'group_id' => $group->id]);
		$this->seeInDatabase('permissions', ['id' => $permissionThree->id, 'group_id' => $group->id]);
		
		$this->assertTrue(count($group->permissions()->get()) == 3);
		
		
		
		//Error
		$groupErr = PermissionGroup::add(str_random(10));
		$groupErr
			->assignPermission(str_random(12))
			->assignPermission();
		
		$this->assertTrue(count($groupErr->permissions()->get()) == 0);
	}
	
	
	
	public function deletePermissionTest($arr)
	{
		//success
		$group = PermissionGroup::add(str_random(10));
		$permissionOne = Permission::add(str_random(10));
		$permissionTwo = Permission::add(str_random(10));
		$permissionThree = Permission::add(str_random(10));
		
		$group
			->assignPermission($permissionOne)
			->assignPermission($permissionTwo->name)
			->assignPermission($permissionThree->id);
		
		
		$group->deletePermission($permissionOne->id);
		$this->assertTrue(count($group->permissions()->get()) == 2);
		
		$group->deletePermission($permissionTwo);
		$this->assertTrue(count($group->permissions()->get()) == 1);

		$group->deletePermission($permissionThree->name);
		$this->assertTrue(count($group->permissions()->get()) == 0);
		
		
		$groupDefault = PermissionGroup::getModelDefault();
		$this->assertFalse(is_null($groupDefault->permissions()->find($permissionOne->id)));
		$this->assertFalse(is_null($groupDefault->permissions()->find($permissionTwo->id)));
		$this->assertFalse(is_null($groupDefault->permissions()->find($permissionThree->id)));
		
		
		//error
		$group->assignPermission($permissionThree->id);
		$group->deletePermission(str_random(12));
		$this->assertTrue(count($group->permissions()->get()) == 1);
	}
	
	
	
	public function deleteTest($arr)
	{
		//success
		$groupModel = PermissionGroup::add(str_random(10));
		PermissionGroup::del($groupModel);
		$this->assertTrue(is_null(PermissionGroup::getModel($groupModel->id)));
		
		$groupName = PermissionGroup::add(str_random(10));
		PermissionGroup::del($groupName->name);
		$this->assertTrue(is_null(PermissionGroup::getModel($groupName->id)));
		
		$groupId = PermissionGroup::add(str_random(10));
		PermissionGroup::del($groupId->id);
		$this->assertTrue(is_null(PermissionGroup::getModel($groupId->id)));
		
		
		
		$group = PermissionGroup::add(str_random(10));
		$permissionOne = Permission::add(str_random(10));
		$permissionTwo = Permission::add(str_random(10));
		$permissionThree = Permission::add(str_random(10));
		$group
			->assignPermission($permissionOne)
			->assignPermission($permissionTwo->name)
			->assignPermission($permissionThree->id);
			
			
		PermissionGroup::del($group);
		
		$this->assertTrue(count($group->permissions()->get()) == 0);
		
		$groupDefault = PermissionGroup::getModelDefault();
		$this->assertFalse(is_null($groupDefault->permissions()->find($permissionOne->id)));
		$this->assertFalse(is_null($groupDefault->permissions()->find($permissionTwo->id)));
		$this->assertFalse(is_null($groupDefault->permissions()->find($permissionThree->id)));
		
		
		//error
		PermissionGroup::del();	
		PermissionGroup::del(str_random(12));	
	}

}








