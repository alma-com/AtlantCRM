<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FormAddUserTest extends TestCase
{

    /**
     * Checking form add new user
     * Rules:
     * - required fields: name, email, password, password_confirmation
     * - email unique
     * - email must be format e-mail
     * - password must be 6 or more symbols
     * - password and password_confirmation must be same
     */

    use DatabaseTransactions;

    protected $faker;
    protected $user;
    protected $url;
    protected $url_err;
    protected $url_ok;
    protected $press;


    protected function setUp()
    {
        parent::setUp();

        $roleAdmin = App\Role::where('name', 'admin')->first();
        $user = factory(App\User::class)->create();
        $user->roles()->sync([$roleAdmin->id]);

        $this->faker = Faker\Factory::create();
        $this->user = $user;
        $this->url = '/users/create';
        $this->url_err = '/users/create';
        $this->url_ok = '/users';
        $this->press = 'create_user';
    }


    public function testValid()
    {
        $name = $this->faker->name;
        $email = $this->faker->email;
        $password = $this->faker->lexify('??????');

        $this->actingAs($this->user)
            ->visit($this->url)
                ->type($name, 'name')
                ->type($email, 'email')
                ->type($password, 'password')
                ->type($password, 'password_confirmation')
                ->press($this->press)
                ->seePageIs($this->url_ok);

        $this->seeInDatabase('users', ['email' => $email, 'name' => $name]);
    }


    public function testRequired()
    {
        $this->actingAs($this->user)
            ->visit($this->url)
                ->press($this->press)
                ->see('Поле "Имя" обязательно для заполнения')
                ->see('Поле "E-mail" обязательно для заполнения')
                ->see('Поле "Пароль" обязательно для заполнения')
                ->see('Поле "Еще раз пароль" обязательно для заполнения')
                ->seePageIs($this->url_err);
    }


    public function testUnique()
    {
        $email = $this->faker->email;
        $password = $this->faker->lexify('??????');

        $this->actingAs($this->user)
            ->visit($this->url)
                ->type($this->faker->name, 'name')
                ->type($email, 'email')
                ->type($password, 'password')
                ->type($password, 'password_confirmation')
                ->press($this->press)
                ->seePageIs($this->url_ok);

        $this->actingAs($this->user)
            ->visit($this->url)
                ->type($this->faker->name, 'name')
                ->type($email, 'email')
                ->type($password, 'password')
                ->type($password, 'password_confirmation')
                ->press($this->press)
                ->see('Такое значение поля "E-mail" уже существует')
                ->seePageIs($this->url_err);
    }


    public function testFormatEmail()
    {
        $arrField = array('name', 'password', 'address', 'domainName');
        foreach($arrField as $field){
            $email = $this->faker->$field;
            $this->actingAs($this->user)
                ->visit($this->url)
                    ->type($email, 'email')
                    ->press($this->press)
                    ->see('Поле "E-mail" имеет неверный формат')
                    ->seePageIs($this->url_err);
        }
    }


    public function testLengthPassword()
    {
        $password = $this->faker->lexify('?????');

        $this->actingAs($this->user)
            ->visit($this->url)
                ->type($this->faker->name, 'name')
                ->type($this->faker->email, 'email')
                ->type($password, 'password')
                ->type($password, 'password_confirmation')
                ->press($this->press)
                ->see('Поле "Пароль" должно быть не менее 6 символов')
                ->seePageIs($this->url_err);
    }


    public function testPasswordIsSameConfirmation()
    {
        $password = $this->faker->lexify('??????');
        $password2 = $this->faker->lexify('?????????');

        $this->actingAs($this->user)
            ->visit($this->url)
                ->type($this->faker->name, 'name')
                ->type($this->faker->email, 'email')
                ->type($password, 'password')
                ->type($password2, 'password_confirmation')
                ->press($this->press)
                ->see('Значение "Еще раз пароль" должно совпадать с "Пароль"')
                ->seePageIs($this->url_err);
    }


}
