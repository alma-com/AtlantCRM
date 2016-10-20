<?php
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Checking form edit role
 * Rules:
 * - required fields: display_name, name
 * - name unique
 */
class FormEditRoleTest extends TestCase
{
    use DatabaseTransactions;

    protected $faker;
    protected $admin;
    protected $roleEdit;
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
        $this->roleEdit = factory(App\Role::class)->create();
        $this->url = '/roles/' . $this->roleEdit->id . '/edit';
        $this->url_err = '/roles/' . $this->roleEdit->id . '/edit';
        $this->url_ok = '/roles';
        $this->press = 'edit_role';
    }

    public function testNoChange()
    {
        $this->actingAs($this->admin)
            ->visit($this->url)
                ->press($this->press)
                ->seePageIs($this->url_ok);

        $this->seeInDatabase('roles', ['name' => $this->roleEdit->name, 'display_name' => $this->roleEdit->display_name]);
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
                ->type('', 'name')
                ->type('', 'display_name')
                ->press($this->press)
                ->see('Поле "Название" обязательно для заполнения.')
                ->see('Поле "Уникальный код" обязательно для заполнения.')
                ->seePageIs($this->url_err);
    }

    public function testUnique()
    {
        $newRole = factory(App\Role::class)->create();

        $this->actingAs($this->admin)
            ->visit($this->url)
                ->type($newRole->name, 'name')
                ->press($this->press)
                ->see('Такое значение поля "Уникальный код" уже существует')
                ->seePageIs($this->url_err);
    }
}
