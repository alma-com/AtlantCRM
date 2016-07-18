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
        $user = User::add(array(
            'name' => str_random(10),
            'email' => $this->faker->email,
        ));

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
        //Success
        $user = User::add(array(
            'name' => str_random(10),
            'email' => $this->faker->email,
        ));
        $roleOne = Role::add(str_random(10));
        $roleTwo = Role::add(str_random(10));
        $roleThree = Role::add(str_random(10));

        $user
            ->assignRole($roleOne->name)
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
            'email' => $this->faker->email,
        ));
        $userErr
            ->assignRole(str_random(12))
            ->assignRole();

        $this->assertTrue(count($userErr->roles()->get()) == 0);
    }


    public function testAccess()
    {
        //Success
        $user = User::add(array(
            'name' => str_random(10),
            'email' => $this->faker->email,
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


    public function testHasRole()
    {
        //Success
        $user = User::add(array(
            'name' => str_random(10),
            'email' => $this->faker->email,
        ));
        $roleOne = Role::add(str_random(10));
        $roleTwo = Role::add(str_random(10));
        $roleThree = Role::add(str_random(10));

        $user
            ->assignRole($roleOne->name)
            ->assignRole($roleTwo->id)
            ->assignRole($roleThree);

        $this->assertTrue($user->hasRole($roleOne));
        $this->assertTrue($user->hasRole($roleTwo->id));
        $this->assertTrue($user->hasRole($roleThree->name));

        //Error
        $user->deleteRole($roleOne);
        $this->assertFalse($user->hasRole($roleOne));
        $this->assertTrue($user->hasRole($roleTwo->id));
        $this->assertTrue($user->hasRole($roleThree->name));
        $this->assertFalse($user->hasRole(str_random(10)));
    }


    public function testDeleteRole()
    {
        //Success
        $user = User::add(array(
            'name' => str_random(10),
            'email' => $this->faker->email,
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


    public function testDelete()
    {
        //success
        $userModel = User::add(array(
            'name' => str_random(10),
            'email' => $this->faker->email,
        ));
        User::del($userModel);
        $this->assertTrue(is_null(User::find($userModel->id)));

        $userEmail = User::add(array(
            'name' => str_random(10),
            'email' => $this->faker->email,
        ));
        User::del($userEmail->email);
        $this->assertTrue(is_null(User::find($userEmail->id)));

        $userId = User::add(array(
            'name' => str_random(10),
            'email' => $this->faker->email,
        ));
        User::del($userId->id);
        $this->assertTrue(is_null(User::find($userId->id)));

        $user = User::add(array(
            'name' => str_random(10),
            'email' => $this->faker->email,
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
