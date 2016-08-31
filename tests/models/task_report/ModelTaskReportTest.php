<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\TaskReport;

class ModelTaskReportTest extends TestCase
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
        $report = TaskReport::create([
            'name' => str_random(100),
            'comment' => str_random(1000),
            'hours' => $this->faker->randomDigit,
        ]);
        
        $this->assertFalse(is_null($report));
        $this->seeInDatabase('task_reports', [
            'name' => $report->name,
            'comment' => $report->comment,
            'hours' => $report->hours
        ]);
    }

    public function testUpdate()
    {
        $report = factory(App\TaskReport::class)->create();

        $newName = str_random(10);
        $report->name = $newName;
        $report->save();

        $this->seeInDatabase('task_reports', [
            'id' => $report->id,
            'name' => $newName
        ]);
    }

    public function testAssignUser()
    {
        $report = factory(App\TaskReport::class)->create();
        $user = factory(App\User::class)->create();

        $report->user()->associate($user);
        $report->save();

        $this->seeInDatabase('task_reports', ['id' => $report->id, 'user_id' => $user->id]);
        $this->assertTrue(count($report->user()->get()) === 1);
    }

    public function testRemoveUser()
    {
        $report = factory(App\TaskReport::class)->create();
        $user = factory(App\User::class)->create();

        $report->user()->associate($user);
        $report->save();

        $report->user()->dissociate();
        $report->save();

        $this->seeInDatabase('task_reports', ['id' => $report->id, 'user_id' => NULL]);
        $this->assertTrue(count($report->user()->get()) === 0);
    }

    public function testDelete()
    {
        $report = factory(App\TaskReport::class)->create();
        $report->delete();

        $this->assertTrue(is_null(TaskReport::find($report->id)));
    }
}
