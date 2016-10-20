<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\User;
use App\Role;

class UserAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crm:create-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create admin';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $email = $this->ask('What is your email?');
        $password = $this->secret('What is the password?');

        $user = User::create([
            'name' => 'Admin',
            'email' => $email,
            'password' => $password,
        ]);
        $user->roles()->sync([Role::findAdmin()->id, Role::findUser()->id]);
    }
}
