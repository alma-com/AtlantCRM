<?php
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Checking form add new role
 * Rules:
 * - required fields: display_name, name
 * - name unique
 */
class FormAddRoleTest extends TestCase
{
    use DatabaseTransactions;

    protected $faker;
    protected $admin;
    protected $url;
    protected $url_err;
    protected $url_ok;
    protected $press;

    protected function setUp()
    {
        parent::setUp();

        $roleAdmin = App\Role::findAdmin();
        $admin = factory(App\User::class)->create();
        $admin->roles()->sync([$roleAdmin->id]);

        $this->faker = Faker\Factory::create();
        $this->admin = $admin;
        $this->url = '/roles/create';
        $this->url_err = '/roles/create';
        $this->url_ok = '/roles';
        $this->press = 'create_role';
    }

    public function testValid()
    {
        $name = $this->faker->name;
        $display_name = $this->faker->name;

        $this->actingAs($this->admin)
            ->visit($this->url)
                ->type($name, 'name')
                ->type($display_name, 'display_name')
                ->press($this->press)
                ->seePageIs($this->url_ok);

        $this->seeInDatabase('roles', ['name' => $name, 'display_name' => $display_name]);
    }

    public function testRequired()
    {
        $this->actingAs($this->admin)
            ->visit($this->url)
                ->press($this->press)
                ->see('Поле "Название" обязательно для заполнения.')
                ->see('Поле "Уникальный код" обязательно для заполнения.')
                ->seePageIs($this->url_err);
    }

    public function testUnique()
    {
        $name = $this->faker->name;
        $display_name = $this->faker->name;

        $this->actingAs($this->admin)
            ->visit($this->url)
                ->type($name, 'name')
                ->type($display_name, 'display_name')
                ->press($this->press)
                ->seePageIs($this->url_ok);

        $this->actingAs($this->admin)
            ->visit($this->url)
                ->type($name, 'name')
                ->type($display_name, 'display_name')
                ->press($this->press)
                ->see('Такое значение поля "Уникальный код" уже существует')
                ->seePageIs($this->url_err);
    }
}
