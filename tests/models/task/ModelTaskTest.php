<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Carbon\Carbon;
use Faker\Factory;
use App\User;
use App\Project;
use App\Task;

class ModelTaskTest extends TestCase
{
	/**
	 * testAdd
	 * testAssignDirectors
	 * testDeleteDirectors
	 * testAssignEmployees
	 * testDeleteEmployees
	 * testAssignWatchers
	 * testDeleteWatchers
	 */


	use DatabaseTransactions;

	protected $faker;
	protected $project;


	protected function setUp()
	{
		parent::setUp();
		$this->faker = Factory::create();
		$this->project = factory(Project::class)->create();
	}


	public function testAdd()
	{
		$arrData = [
			'name' => $this->faker->text($maxNbChars = 200),
			'description' => $this->faker->text($maxNbChars = 400),
			'date_start' => Carbon::now(),
			'deadline' => Carbon::now()->addWeeks(2),
			'planned_duration' => $this->faker->randomDigitNotNull(),
			'project_id' => $this->project->id,
		];

		$task = Task::create($arrData);

		$this->seeInDatabase('tasks', $arrData);
	}


	public function testAssignDirectors()
	{
		$task = factory(Task::class)->create();
		$director = factory(User::class)->create();
		$director_second = factory(User::class)->create();

		$task
			->assignDirector($director)
			->assignDirector($director_second->id);

		$this->seeInDatabase('task_has_directors', ['task_id' => $task->id, 'user_id' => $director->id]);
		$this->seeInDatabase('task_has_directors', ['task_id' => $task->id, 'user_id' => $director_second->id]);
		$this->assertTrue(count($task->directors()->get()) === 2);
	}


	public function testDeleteDirectors()
	{
		$task = factory(Task::class)->create();
		$director = factory(User::class)->create();
		$director_second = factory(User::class)->create();

		$task
			->assignDirector($director)
			->assignDirector($director_second->id);

		$task->deleteDirector($director);
		$this->assertTrue(count($task->directors()->get()) === 1);

		$task->deleteDirector($director_second->id);
		$this->assertTrue(count($task->directors()->get()) === 0);
	}


	public function testAssignEmployees()
	{
		$task = factory(Task::class)->create();
		$employee = factory(User::class)->create();
		$employee_second = factory(User::class)->create();

		$task
			->assignEmployee($employee)
			->assignEmployee($employee_second->id);

		$this->seeInDatabase('task_has_employees', ['task_id' => $task->id, 'user_id' => $employee->id]);
		$this->seeInDatabase('task_has_employees', ['task_id' => $task->id, 'user_id' => $employee_second->id]);
		$this->assertTrue(count($task->employees()->get()) === 2);
	}


	public function testDeleteEmployees()
	{
		$task = factory(Task::class)->create();
		$employee = factory(User::class)->create();
		$employee_second = factory(User::class)->create();

		$task
			->assignEmployee($employee)
			->assignEmployee($employee_second->id);

		$task->deleteEmployee($employee);
		$this->assertTrue(count($task->employees()->get()) === 1);

		$task->deleteEmployee($employee_second->id);
		$this->assertTrue(count($task->employees()->get()) === 0);
	}


	public function testAssignWatchers()
	{
		$task = factory(Task::class)->create();
		$watcher = factory(User::class)->create();
		$watcher_second = factory(User::class)->create();

		$task
			->assignWatchers($watcher)
			->assignWatchers($watcher_second->id);

		$this->seeInDatabase('task_has_watchers', ['task_id' => $task->id, 'user_id' => $employee->id]);
		$this->seeInDatabase('task_has_watchers', ['task_id' => $task->id, 'user_id' => $employee_second->id]);
		$this->assertTrue(count($task->watchers()->get()) === 2);
	}


	public function testDeleteWatchers()
	{
		$task = factory(Task::class)->create();
		$watcher = factory(User::class)->create();
		$watcher_second = factory(User::class)->create();

		$task
			->assignWatchers($watcher)
			->assignWatchers($watcher_second->id);

		$task->deleteWatchers($watcher);
		$this->assertTrue(count($task->watchers()->get()) === 1);

		$task->deleteWatchers($watcher_second->id);
		$this->assertTrue(count($task->watchers()->get()) === 0);
	}

}
