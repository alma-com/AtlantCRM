<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ConsoleUserAdminTest extends TestCase
{
    use DatabaseTransactions;

    public function test()
    {
        $faker = Faker\Factory::create();
        $email = $faker->email;

        Artisan::call('crm:create-admin', [
            'email' => $email,
            'password' => 'mypassword',
        ]);

        $admin = App\User::where('email', $email)->first();

        $this->assertTrue($admin->hasRole('admin'));
    }
}
