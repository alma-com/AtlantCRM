<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\PermissionGroup;
use App\Permission;
use Slug;

class PermissionUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:update {model} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $class = $this->argument('model');
        $list = $class::setPermissions();

        $message = '';
        $message .= $this->keyExistMessage('group');
        $message .= $this->keyExistMessage('permissions');
        $message .= $this->groupMultipleMessage();
        if (!$this->option('force')) {
            $message .= $this->groupExistMessage();
        }

        if ($message !== '') {
            $this->info($message);
            return false;
        }

        $group = $this->createOrUpdateGroup($list['group']);
        $this->syncPermissions($list['permissions'], $group->id);
    }

    /**
     * Key exist message
     * @param  string $key
     * @return string
     */
    public function keyExistMessage($key)
    {
        $class = $this->argument('model');
        $list = $class::setPermissions();
        if (!array_key_exists($key, $list)) {
            return "not found key '".$key."' in ".$class."::setPermissions()\n";
        }

        return '';
    }

    /**
     * if group multible then return message
     * @param  string $key
     * @return string
     */
    public function groupMultipleMessage()
    {
        $class = $this->argument('model');
        $list = $class::setPermissions();
        $group = array_key_exists('group', $list) ? $list['group'] : [];

        if (count($list['group']) === 0 || count($list['group']) > 1) {
            return "group should be one. Now groups: ".count($list['group'])."\n";
        }

        return '';
    }

    /**
     * if group exists then return message
     * @return string
     */
    public function groupExistMessage()
    {
        $class = $this->argument('model');
        $list = $class::setPermissions();
        $group = array_key_exists('group', $list) ? $list['group'] : [];
        $groupKey = Slug::make(key($group));

        if (PermissionGroup::where('name', $groupKey)->exists()) {
            $run = "\n  php artisan permission:update '{$class}' --force";
            return "group already exist. If you need update then run: {$run}\n";
        }

        return '';
    }

    /**
     * Synchronization permissions - create, update and destroy
     * @param  array $listPerm
     * @param  int $groupId
     * @return void
     */
    public function syncPermissions($listPerm, $groupId)
    {
        $permDestroy = Permission::where('group_id', $groupId)
            ->whereNotIn('name', array_keys($listPerm))
            ->delete();

        $order = 0;
        foreach ($listPerm as $name => $displayName) {
            $this->createOrUpdatePermission([
                'name' => $name,
                'displayName' => $displayName,
                'groupId' => $groupId,
                'order' => $order,
            ]);
            $order += 1;
        }
    }

    /**
     * Create or update Group by name
     * @param  array $data
     * @return Eloquent
     */
    public function createOrUpdateGroup($data)
    {
        $groupKey = Slug::make(key($data));
        $group = PermissionGroup::where('name', $groupKey)->first();
        if (count($group) === 0) {
            $group = new PermissionGroup();
        }
        $group->name = $groupKey;
        $group->display_name = array_shift($data);
        $group->save();

        return $group;
    }

    /**
     * Create or update Permission by name and group id
     * @param  array $data
     * @return Eloquent
     */
    public function createOrUpdatePermission($data)
    {
        $perm = Permission::where('name', $data['name'])
            ->where('group_id', $data['groupId'])
            ->first();

        if (count($perm) === 0) {
            $perm = new Permission();
        }

        $perm->name = $data['name'];
        $perm->display_name = $data['displayName'];
        $perm->group_id = $data['groupId'];
        $perm->sort_order = $data['order'];
        $perm->save();

        return $perm;
    }
}
