<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FormTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
	use DatabaseTransactions; 
	 
	/*
	*	Проверка формы добавления нового пользователя
	*	Правила:
	*	- Обязательные поля: name, email, password, password_confirmation
	*	- email уникальное поле
	*	- email должен быть формат e-mail
	*	- Если не заполено поле role, то по умолчанию user
	*	- password должен быть от 6 символов и более
	*	- password и password_confirmation должны совпадать
	*/
    public function testExample()
    {
		$arrValue = Array(
			'faker' => Faker\Factory::create(),
			'user' => factory(App\User::class)->create(),
			'url' => '/users/create',
			'url_err' => '/users/create',
			'url_ok' => '/users',
			'press' => 'create_user',
		);
		
		$this->validTest($arrValue);				//правильные значения
		$this->requiredTest($arrValue);			//наличие обязательных полей
		$this->existTest($arrValue);				//email уникальное поле
		$this->emailTest($arrValue);				//email должен быть формат e-mail
		//$this->roleTest($arrValue);				//Если не заполено поле role, то по умолчанию user
		$this->lengthPassTest($arrValue);		//password должен быть от 6 символов и более
		$this->passEqTest($arrValue);			//password и password_confirmation должны совпадать
		
    }
	
	
	
	/*
	* правильные значения
	*/
    public function validTest($arr)
    {
		$name = $arr['faker']->name;
		$email = $arr['faker']->email;
		$password = $arr['faker']->lexify('??????');
		
		$this->actingAs($arr['user'])
			->visit($arr['url'])
				->type($name, 'name')
				->type($email, 'email')
				->type($password, 'password')
				->type($password, 'password_confirmation')
				->press($arr['press'])
				->seePageIs($arr['url_ok']);
		
		$this->seeInDatabase('users', ['email' => $email, 'name' => $name]);
    }
	
	
	
	/*
	* наличие обязательных полей
	*/
    public function requiredTest($arr)
    {
		$this->actingAs($arr['user'])
			->visit($arr['url'])
				->press($arr['press'])
				->see('Поле "Имя" обязательно для заполнения')
				->see('Поле "E-mail" обязательно для заполнения')
				->see('Поле "Пароль" обязательно для заполнения')
				->see('Поле "Еще раз пароль" обязательно для заполнения')
				->seePageIs($arr['url_err']);
    }
	
	
	
	/*
	* email уникальное поле
	*/
    public function existTest($arr)
    {
		$email = $arr['faker']->email;
		$password = $arr['faker']->lexify('??????');
		
		$this->actingAs($arr['user'])
			->visit($arr['url'])
				->type($arr['faker']->name, 'name')
				->type($email, 'email')
				->type($password, 'password')
				->type($password, 'password_confirmation')
				->press($arr['press'])
				->seePageIs($arr['url_ok']);
				
				
		$this->actingAs($arr['user'])
			->visit($arr['url'])
				->type($arr['faker']->name, 'name')
				->type($email, 'email')
				->type($password, 'password')
				->type($password, 'password_confirmation')
				->press($arr['press'])
				->see('Такое значение поля "E-mail" уже существует')
				->seePageIs($arr['url_err']);
    }
	
	
	
	/*
	* email должен быть формат e-mail
	*/
    public function emailTest($arr)
    {
		$arrField = array('name', 'password', 'address', 'domainName');
		foreach($arrField as $field){
			$email = $arr['faker']->$field;
			$this->actingAs($arr['user'])
				->visit($arr['url'])
					->type($email, 'email')
					->press($arr['press'])
					->see('Поле "E-mail" имеет неверный формат')
					->seePageIs($arr['url_err']);
		}
    }
	
	
	
	/*
	* Если не заполено поле role, то по умолчанию user
	*/
    public function roleTest($arr)
    {
		$email = $arr['faker']->email;
		$password = $arr['faker']->lexify('??????');
		
		$this->actingAs($arr['user'])
			->visit($arr['url'])
				->type($arr['faker']->name, 'name')
				->type($email, 'email')
				->type($password, 'password')
				->type($password, 'password_confirmation')
				->press($arr['press'])
				->seePageIs($arr['url_ok']);
				
		$this->seeInDatabase('users', ['email' => $email, 'role'=> 'user']);
    }
	
	
	
	/*
	* password должен быть от 6 символов и более
	*/
    public function lengthPassTest($arr)
    {
		$password = $arr['faker']->lexify('?????');
		
		$this->actingAs($arr['user'])
			->visit($arr['url'])
				->type($arr['faker']->name, 'name')
				->type($arr['faker']->email, 'email')
				->type($password, 'password')
				->type($password, 'password_confirmation')
				->press($arr['press'])
				->see('Поле "Пароль" должно быть не менее 6 символов')
				->seePageIs($arr['url_err']);
    }
	
	
	
	/*
	* password и password_confirmation должны совпадать
	*/
    public function passEqTest($arr)
    {
		$password = $arr['faker']->lexify('??????');
		$password2 = $arr['faker']->lexify('?????????');
		
		$this->actingAs($arr['user'])
			->visit($arr['url'])
				->type($arr['faker']->name, 'name')
				->type($arr['faker']->email, 'email')
				->type($password, 'password')
				->type($password2, 'password_confirmation')
				->press($arr['press'])
				->see('Значение "Еще раз пароль" должно совпадать с "Пароль"')
				->seePageIs($arr['url_err']);
    }

	
}
