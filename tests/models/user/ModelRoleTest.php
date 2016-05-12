<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Role;
use App\Permission;

class ModelRoleTest extends TestCase
{
    use DatabaseTransactions;

	protected $faker;


	protected function setUp()
	{
		parent::setUp();
		$this->faker = Faker\Factory::create();
	}


	public function testAdd()
    {
		//Success
		$roleString = Role::add(str_random(10));
		$roleArrayMin = Role::add(array('name' => str_random(10)));
		$roleArrayFull = Role::add(array(
			'name' => str_random(10),
            'display_name' => str_random(10),
            'description' => str_random(10),
            'sort_order' => $this->faker->randomNumber,
		));

		$this->assertFalse(is_null($roleString));
		$this->assertFalse(is_null($roleArrayMin));
		$this->assertFalse(is_null($roleArrayFull));

		$this->seeInDatabase('roles', ['id' => $roleString->id, 'name' => $roleString->name]);
		$this->seeInDatabase('roles', ['id' => $roleArrayMin->id, 'name' => $roleArrayMin->name]);
		$this->seeInDatabase('roles', [
			'id' => $roleArrayFull->id,
			'name' => $roleArrayFull->name,
			'display_name' => $roleArrayFull->display_name,
			'description' => $roleArrayFull->description,
			'sort_order' => $roleArrayFull->sort_order
		]);

		//Error
		$roleNull = Role::add();
		$roleInt = Role::add($this->faker->randomNumber);
		$roleErrArray = Role::add(array('display_name' => str_random(10)));

		$this->assertTrue(is_null($roleNull));
		$this->assertTrue(is_null($roleInt));
		$this->assertTrue(is_null($roleErrArray));
	}


	public function testAssignPermission()
    {
		//Success
		$role = Role::add(str_random(10));
		$permOne = Permission::add(str_random(10));
		$permTwo = Permission::add(str_random(10));
		$permThree = Permission::add(str_random(10));

		$role
			->assignPermission($permOne)
			->assignPermission($permTwo->name)
			->assignPermission($permThree->id);

		$this->seeInDatabase('role_has_permissions', ['role_id' => $role->id, 'permission_id' => $permOne->id]);
		$this->seeInDatabase('role_has_permissions', ['role_id' => $role->id, 'permission_id' => $permTwo->id]);
		$this->seeInDatabase('role_has_permissions', ['role_id' => $role->id, 'permission_id' => $permThree->id]);

		$this->assertTrue(count($role->permissions()->get()) == 3);

		//Error
		$roleErr = Role::add(str_random(10));
		$roleErr
			->assignPermission(str_random(12))
			->assignPermission();

		$this->assertTrue(count($roleErr->permissions()->get()) == 0);
	}


	public function testAccess()
	{
		//Success
		$role = Role::add(str_random(10));
		$permOne = Permission::add(str_random(10));
		$permTwo = Permission::add(str_random(10));
		$permThree = Permission::add(str_random(10));

		$role
			->assignPermission($permOne)
			->assignPermission($permOne)
			->assignPermission($permTwo)
			->assignPermission($permThree);

		$this->assertTrue($role->access($permOne));
		$this->assertTrue($role->access($permTwo->name));
		$this->assertTrue($role->access($permThree->id));

		//Error
		$role->deletePermission($permOne);
		$this->assertFalse($role->access($permOne->id));
		$this->assertTrue($role->access($permTwo->name));
		$this->assertTrue($role->access($permThree));
		$this->assertFalse($role->access(str_random(10)));

	}


	public function testDeletePermission()
    {
		//Success
		$role = Role::add(str_random(10));
		$permOne = Permission::add(str_random(10));
		$permTwo = Permission::add(str_random(10));
		$permThree = Permission::add(str_random(10));
		$permThree2 = Permission::add(str_random(10));

		$role
			->assignPermission($permOne)
			->assignPermission($permTwo->name)
			->assignPermission($permThree->id);


		$role->deletePermission($permOne->id);
		$this->assertTrue(count($role->permissions()->get()) == 2);

		$role->deletePermission($permTwo->name);
		$this->assertTrue(count($role->permissions()->get()) == 1);

		$role->deletePermission($permThree);
		$this->assertTrue(count($role->permissions()->get()) == 0);

		//Error
		$role->assignPermission($permThree);
		$role->deletePermission(str_random(12));
		$this->assertTrue(count($role->permissions()->get()) == 1);
	}


	public function testDelete()
	{
		//success
		$roleModel = Role::add(str_random(10));
		Role::del($roleModel);
		$this->assertTrue(is_null(Role::getModel($roleModel->id)));

		$roleName = Role::add(str_random(10));
		Role::del($roleName->name);
		$this->assertTrue(is_null(Role::getModel($roleName->id)));

		$roleId = Role::add(str_random(10));
		Role::del($roleId->id);
		$this->assertTrue(is_null(Role::getModel($roleId->id)));

		$role = Role::add(str_random(10));
		$permOne = Permission::add(str_random(10));
		$permTwo = Permission::add(str_random(10));
		$permThree = Permission::add(str_random(10));
		$permThree2 = Permission::add(str_random(10));

		$role
			->assignPermission($permOne)
			->assignPermission($permTwo->name)
			->assignPermission($permThree->id);

		Role::del($role);

		$this->assertTrue(count($role->permissions()->get()) == 0);

		//error
		Role::del();
		Role::del(str_random(12));
	}
}
