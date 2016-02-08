<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\PermissionGroup;
use App\Permission;

class ModelPermissionTest extends TestCase
{
	use DatabaseTransactions; 

    public function testExample()
    {
		$arr = Array(
			'faker' => Faker\Factory::create(),
		);
		
		$this->addTest($arr);
        $this->deleteTest($arr);
    }
	
	
	
	public function addTest($arr)
	{	
		//Success
		$groupDefault = PermissionGroup::getModelDefault();
		$permString = Permission::add(str_random(10));
		$this->assertFalse(is_null($permString));
		$this->seeInDatabase('permissions', [
			'id' => $permString->id, 
			'name' => $permString->name,
			'display_name' => $permString->display_name,
			'description' => $permString->description,
			'sort_order' => $permString->sort_order,
			'group_id' => $groupDefault->id,
		]);
		
		
		$permArrayMin = Permission::add(array('name' => str_random(10)));
		$this->assertFalse(is_null($permArrayMin));
		$this->seeInDatabase('permissions', ['id' => $permArrayMin->id]);
		
		
		$group= PermissionGroup::add(str_random(10));
		$permArrayFull = Permission::add(
			array(
				'name' => str_random(10),
				'display_name' => str_random(10),
				'description' => str_random(10),
				'sort_order' => $arr['faker']->randomNumber,
				'group_id' => $group->id,
			)
		);
		$this->assertFalse(is_null($permArrayFull));
		$this->seeInDatabase('permissions', [
			'id' => $permArrayFull->id,
			'group_id' => $group->id,
		]);
		
		
		$group = PermissionGroup::add(str_random(10));
		$permission = Permission::add(str_random(10), $group->name);
		$this->seeInDatabase('permissions', [
			'id' => $permission->id,
			'group_id' => $group->id,
		]);
		
		
		
		//Error
		$permNull = Permission::add();
		$permInt = Permission::add($arr['faker']->randomNumber);
		$permErrArray = Permission::add(array('display_name' => str_random(10)));
		
		$this->assertTrue(is_null($permNull));
		$this->assertTrue(is_null($permInt));
		$this->assertTrue(is_null($permErrArray));
		
		$group = PermissionGroup::add(str_random(10));
		$groupDefault = PermissionGroup::getModelDefault();
		$permission = Permission::add(str_random(10), $group->id);
		$this->seeInDatabase('permissions', [
			'id' => $permission->id,
			'group_id' => $groupDefault->id,
		]);
	}
	
	
	
	public function deleteTest($arr)
	{	
		//Success
		$permModel = Permission::add(str_random(10));
		Permission::del($permModel);
		$this->assertTrue(is_null(Permission::getModel($permModel->id)));
		
		$permName = Permission::add(str_random(10));
		Permission::del($permName->name);
		$this->assertTrue(is_null(Permission::getModel($permName->id)));
		
		$permId = Permission::add(str_random(10));
		Permission::del($permId->id);
		$this->assertTrue(is_null(Permission::getModel($permId->id)));
		
		
		//Error
		$groupDefault = PermissionGroup::getModelDefault();
		$count = count($groupDefault->permissions()->get());
		
		Permission::del();
		Permission::del(str_random(12));
		
		$this->assertTrue(count($groupDefault->permissions()->get()) == $count);
	}
}
