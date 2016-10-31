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
    protected $signature = 'crm:create-admin {email?} {password?}';

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
        $argEmail = $this->argument('email');
        $argPass = $this->argument('password');

        $email = ($argEmail !== null) ? $argEmail : $this->ask('What is your email?');
        $password = ($argPass !== null) ? $argPass : $this->secret('What is the password?');

        $user = User::create([
            'name' => 'Admin',
            'email' => $email,
            'password' => $password,
        ]);
        $user->roles()->sync([Role::findAdmin()->id, Role::findUser()->id]);
    }
}
