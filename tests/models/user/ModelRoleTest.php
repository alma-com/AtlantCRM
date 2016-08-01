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
        $role = Role::create(['name' => str_random(10)]);

        $this->assertFalse(is_null($role));
        $this->seeInDatabase('roles', ['id' => $role->id, 'name' => $role->name]);
    }


    public function testAssignPermission()
    {
        $role = Role::create(['name' => str_random(10)]);
        $permOne = Permission::add(str_random(10));
        $permTwo = Permission::add(str_random(10));
        $permThree = Permission::add(str_random(10));

        $role->permissions()->sync([$permOne->id, $permTwo->id, $permThree->id]);

        $this->seeInDatabase('role_has_permissions', ['role_id' => $role->id, 'permission_id' => $permOne->id]);
        $this->seeInDatabase('role_has_permissions', ['role_id' => $role->id, 'permission_id' => $permTwo->id]);
        $this->seeInDatabase('role_has_permissions', ['role_id' => $role->id, 'permission_id' => $permThree->id]);

        $this->assertTrue(count($role->permissions()->get()) == 3);
    }


    public function testAccess()
    {
        $role = Role::create(['name' => str_random(10)]);
        $permOne = Permission::add(str_random(10));
        $permTwo = Permission::add(str_random(10));
        $permThree = Permission::add(str_random(10));

        $role->permissions()->sync([$permOne->id, $permTwo->id, $permThree->id]);

        $this->assertTrue($role->access($permOne));
        $this->assertTrue($role->access($permTwo->name));
        $this->assertTrue($role->access($permThree->id));
    }


    public function testDeletePermission()
    {
        //Success
        $role = Role::create(['name' => str_random(10)]);
        $permOne = Permission::add(str_random(10));
        $permTwo = Permission::add(str_random(10));
        $permThree = Permission::add(str_random(10));

        $role->permissions()->sync([$permOne->id, $permTwo->id, $permThree->id]);


        $role->permissions()->sync([$permTwo->id, $permThree->id]);
        $this->assertTrue(count($role->permissions()->get()) == 2);

        $role->permissions()->sync([$permThree->id]);
        $this->assertTrue(count($role->permissions()->get()) == 1);

        $role->permissions()->sync([]);
        $this->assertTrue(count($role->permissions()->get()) == 0);
    }


    public function testDelete()
    {
        $role = Role::create(['name' => str_random(10)]);
        $perm = Permission::add(str_random(10));
        $role->permissions()->sync([$perm->id]);

        $role->delete();

        $this->assertTrue(is_null(Role::find($role->id)));
        $this->assertTrue(count($role->permissions()->get()) === 0);
    }
}
