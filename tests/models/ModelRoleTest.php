<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ModelRoleTest extends TestCase
{
    use DatabaseTransactions;

    protected $faker;

    protected function setUp()
    {
        parent::setUp();
        $this->faker = Faker\Factory::create();
    }

    public function testAccess()
    {
        $role = factory(App\Role::class)->create();
        $permOne = factory(App\Permission::class)->create();
        $permTwo = factory(App\Permission::class)->create();

        $role->permissions()->attach([$permOne->id, $permTwo->id]);

        $this->assertTrue($role->hasAccess($permOne->id));
        $this->assertTrue($role->hasAccess($permTwo->name));
    }

    public function testDeletePermission()
    {
        $role = factory(App\Role::class)->create();
        $permOne = factory(App\Permission::class)->create();
        $permTwo = factory(App\Permission::class)->create();

        $role->permissions()->attach([$permOne->id, $permTwo->id]);
        $role->permissions()->detach($permOne->id);
        $this->assertTrue(count($role->permissions()->get()) == 1);

        $role->permissions()->detach($permTwo->id);
        $this->assertTrue(count($role->permissions()->get()) == 0);
    }
}
