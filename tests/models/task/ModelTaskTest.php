<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

//use Carbon\Carbon;
//use App\Task;

class ModelTaskTest extends TestCase
{
	use DatabaseTransactions;

	protected $faker;
	protected $director;
	protected $director_second;
	protected $employee;
	protected $employee_second;
	protected $watcher;
	protected $project;

	/*
	protected function setUp()
	{
		parent::setUp();
		$this->faker = Faker\Factory::create();
		$this->director = factory(App\User::class)->create();
		$this->director_second = factory(App\User::class)->create();
		$this->employee = factory(App\User::class)->create();
		$this->employee_second = factory(App\User::class)->create();
		$this->watcher = factory(App\User::class)->create();
		$this->project = factory(App\Project::class)->create();
	}


	public function testAdd()
	{
		$task = Task::add([
			'name' => $this->faker->text($maxNbChars = 200),
			'description' => $this->faker->text($maxNbChars = 400),
			'date_start' => Carbon::now(),
			'deadline' => Carbon::now()->addWeeks(2),
			'planned_duration' => 40,
			'project' => $this->project->id,
			'directors' => [
				$this->director->id,
				$this->director_second->id,
			],
			'employees' => [
				$this->employee->id,
				$this->employee_second->id,
			],
			'watchers' => [
				$this->watcher->id,
			],
		]);

		$this->assertTrue(true);
	}
	*/
}
