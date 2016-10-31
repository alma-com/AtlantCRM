<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TestExamples
{
    public static function listOk()
    {
        return [
            'group' => ['testAccess' => 'access'],
            'permissions' => [ 'show' => 'show', 'add' => 'add'],
        ];
    }

    public static function listUpdatePermission()
    {
        return [
            'group' => ['testAccess' => 'access'],
            'permissions' => [ 'show' => 'changeShow'],
        ];
    }

    public static function listNoFoundGroup()
    {
        return [
            'changeGroup' => ['testAccess' => 'access'],
            'permissions' => [ 'show' => 'show', 'add' => 'add'],
        ];
    }

    public static function listMultipleGroup()
    {
        return [
            'group' => ['testAccess' => 'access', 'testAccessTwo' => 'access'],
            'permissions' => [ 'show' => 'show', 'add' => 'add'],
        ];
    }

    public static function listNoFoundPermission()
    {
        return [
            'group' => ['testAccess' => 'access'],
            'changePermissions' => [ 'show' => 'show', 'add' => 'add'],
        ];
    }
}


class PermissionUpdateTest extends TestCase
{
    use DatabaseTransactions;

    public function testOk()
    {
        Artisan::call('crm:permission-update', [
            'model' => 'TestExamples',
            '--function' => 'listOk',
        ]);

        $group = App\PermissionGroup::where('name', 'testAccess')->first();
        $permission = App\Permission::where('group_id', $group->id)->get();

        $this->assertTrue($group->exists());
        $this->assertEquals(2, count($permission));
    }

    public function testAlreadyExist()
    {
        Artisan::call('crm:permission-update', [
            'model' => 'TestExamples',
            '--function' => 'listOk',
        ]);

        Artisan::call('crm:permission-update', [
            'model' => 'TestExamples',
            '--function' => 'listOk',
        ]);

        $this->assertContains("group already exist", Artisan::output());
    }

    public function testUpdatePermission()
    {
        Artisan::call('crm:permission-update', [
            'model' => 'TestExamples',
            '--function' => 'listOk',
        ]);

        Artisan::call('crm:permission-update', [
            'model' => 'TestExamples',
            '--function' => 'listUpdatePermission',
            '--force' => true,
        ]);

        $group = App\PermissionGroup::where('name', 'testAccess')->first();
        $permission = App\Permission::where('group_id', $group->id)->get();

        $this->assertTrue($group->exists());
        $this->assertEquals(1, count($permission));
    }

    public function testNoFoundGroup()
    {
        Artisan::call('crm:permission-update', [
            'model' => 'TestExamples',
            '--function' => 'listNoFoundGroup',
        ]);

        $this->assertContains("not found key 'group'", Artisan::output());
    }

    public function testMultipleGroup()
    {
        Artisan::call('crm:permission-update', [
            'model' => 'TestExamples',
            '--function' => 'listMultipleGroup',
        ]);

        $this->assertContains("group should be one", Artisan::output());
    }

    public function testNoFoundPermission()
    {
        Artisan::call('crm:permission-update', [
            'model' => 'TestExamples',
            '--function' => 'listNoFoundPermission',
        ]);

        $this->assertContains("not found key 'permissions'", Artisan::output());
    }


}
