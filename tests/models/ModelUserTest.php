<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ModelUserTest extends TestCase
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
        $user = factory(App\User::class)->create();
        $role = factory(App\Role::class)->create();
        $permOne = factory(App\Permission::class)->create();
        $permTwo = factory(App\Permission::class)->create();

        $role->permissions()->attach([$permOne->id, $permTwo->id]);
        $user->roles()->attach([$role->id]);

        $this->assertTrue($user->hasAccess($permOne->id));
        $this->assertTrue($user->hasAccess($permTwo->name));
    }

    public function testHasRole()
    {
        $user = factory(App\User::class)->create();
        $roleOne = factory(App\Role::class)->create();
        $roleTwo = factory(App\Role::class)->create();

        $user->roles()->attach([$roleOne->id, $roleTwo->id]);

        $this->assertTrue($user->hasRole($roleOne->id));
        $this->assertTrue($user->hasRole($roleTwo->name));
    }

    public function testDeleteRole()
    {
        $user = factory(App\User::class)->create();
        $roleOne = factory(App\Role::class)->create();
        $roleTwo = factory(App\Role::class)->create();
        
        $user->roles()->attach([$roleOne->id, $roleTwo->id]);
        $user->roles()->detach($roleTwo->id);

        $this->assertFalse($user->hasRole($roleTwo->id));
    }
}
