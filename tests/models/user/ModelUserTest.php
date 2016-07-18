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

    protected $faker;

    protected function setUp()
    {
        parent::setUp();
        $this->faker = Faker\Factory::create();
    }


    public function testAdd()
    {
        $user = User::create([
            'name' => str_random(10),
            'email' => $this->faker->email,
        ]);

        $this->assertFalse(is_null($user));

        $this->seeInDatabase('users', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email
        ]);
    }


    public function testUpdate()
    {
        $user = User::create([
            'name' => str_random(10),
            'email' => $this->faker->email,
        ]);

        $newName = str_random(10);
        $user->name = $newName;
        $user->save();

        $this->seeInDatabase('users', [
            'id' => $user->id,
            'name' => $newName
        ]);
    }


    public function testAssignRole()
    {
        $user = User::create([
            'name' => str_random(10),
            'email' => $this->faker->email,
        ]);
        $role = Role::add(str_random(10));

        $user->roles()->sync([$role->id]);

        $this->seeInDatabase('user_has_roles', ['role_id' => $role->id, 'user_id' => $user->id]);
        $this->assertTrue(count($user->roles()->get()) === 1);
    }


    public function testAccess()
    {
        $user = User::create([
            'name' => str_random(10),
            'email' => $this->faker->email,
        ]);
        $role = Role::add(str_random(10));
        $permOne = Permission::add(str_random(10));
        $permTwo = Permission::add(str_random(10));
        $permThree = Permission::add(str_random(10));

        $role
            ->assignPermission($permOne)
            ->assignPermission($permTwo)
            ->assignPermission($permThree);

        $user->roles()->sync([$role->id]);

        $this->assertTrue($user->access($permOne));
        $this->assertTrue($user->access($permTwo->name));
        $this->assertTrue($user->access($permThree->id));
    }


    public function testHasRole()
    {
        $user = User::create([
            'name' => str_random(10),
            'email' => $this->faker->email,
        ]);
        $role = Role::add(str_random(10));
        $user->roles()->sync([$role->id]);

        $this->assertTrue($user->hasRole($role));
    }


    public function testDeleteRole()
    {
        $user = User::create([
            'name' => str_random(10),
            'email' => $this->faker->email,
        ]);
        $role = Role::add(str_random(10));
        $user->roles()->sync([$role->id]);

        $user->roles()->sync([]);

        $this->assertFalse($user->hasRole($role));
    }


    public function testDelete()
    {
        $user = User::create([
            'name' => str_random(10),
            'email' => $this->faker->email,
        ]);
        $role = Role::add(str_random(10));
        $user->roles()->sync([$role->id]);
        $user->delete();

        $this->assertTrue(is_null(User::find($user->id)));
        $this->assertTrue(count($user->roles()->get()) === 0);
    }
}
