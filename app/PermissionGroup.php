<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @static @property static string $defaultName
 *
 * @method assignPermission(int|string|object $perm)
 * @method deletePermission(int|string|object $perm)
 *
 * @static @method add(array $arrData)
 * @static @method del(int|string|object $group)
 * @static @method getByName(string $name)
 * @static @method getModel(int|string|object $group)
 * @static @method getModelDefault()
 * @static @method checkArrayGroup(array $arrData)
 *
 */
class PermissionGroup extends Model
{
    public static $defaultName = 'general';


    protected $fillable = [
        'name', 'display_name', 'description', 'sort_order',
    ];

    public function permissions()
    {
        return $this->hasMany('App\Permission', 'group_id');
    }



    /**
     * Assign permission to the group
     *
     * @param {int|string|object}
     * @param {int} id permission
     * @param {string} name permission
     * @param {object} object permission
     *
     * @returns {object}
     */
    public function assignPermission($perm = '')
    {
        $permission = Permission::getModel($perm);

        if (!is_null($permission)) {
            $this->permissions()->save($permission);
        }

        return $this;
    }



    /**
     * Delete permission to the group
     *
     * @param {int|string|object}
     * @param {int} id permission
     * @param {string} name permission
     * @param {object} object permission
     *
     * @returns {object}
     */
    public function deletePermission($perm = '')
    {
        $permission = Permission::getModel($perm);
        $groupDefault = self::getModelDefault();

        if (!is_null($permission)) {
            $groupDefault->assignPermission($permission);
        }

        return $this;
    }



    /**
     * Add new permission groups
     *
     * @param {array} $arrData array with data to be added
     * @param {string} $arrData['name'] name group
     * @param {string} $arrData['display_name'] display name of the group
     * @param {string} $arrData['description'] group description
     * @param {string} $arrData['sort_order'] sorting order
     *
     * @returns {object|null} - return object models or null
     */
    public static function add($arrData = array())
    {
        if (self::checkArrayGroup($arrData) === false) { return null; }

        if (is_string($arrData) === true && $arrData !== '') {
            $arrData = array('name' => $arrData);
        }
        $sort_order = PermissionGroup::max('sort_order')+10;
        $arrDefault = array(
            'name' => '',
            'display_name' => '',
            'description' => '',
            'sort_order' => $sort_order,
        );
        $res = array_merge($arrDefault, $arrData);

        $group = PermissionGroup::where('name', $res['name'])->first();
        if (is_null($group)) {
            $group = new PermissionGroup;
            $group->name = $res['name'];
            $group->display_name = $res['display_name'];
            $group->description = $res['description'];
            $group->sort_order = $res['sort_order'];
            $group->save();
            return $group;
        }

        return null;
    }



    /**
     * Delete groups
     *
     * @param {int|string|object}
     * @param {int} id group
     * @param {string} name group
     * @param {object} object group
     *
     * @returns {true}
     */
    public static function del($group = '')
    {
        $group = self::getModel($group);
        if (!is_null($group)) {
            $permission = $group->permissions()->get();
            if (count($permission) > 0) {
                foreach($permission as $key => $item){
                    $group->deletePermission($item);
                }
            }

            $group->delete();
        }
        return true;
    }



    /**
     * Getting a group by name
     *
     * @param {string} name group
     *
     * @returns {object|null}
     */
    public static function getByName($name = ''){
        $group = null;
        $id_group = '';
        if ($name != '') {
            $group = self::where('name', $name)->first();
        }

        if (is_null($group)) {
            $group = self::where('name', self::$defaultName)->first();
            if (is_null($group)) {
                $group = self::add(array('name' => self::$defaultName));
            }
        }

        return $group;
    }



    /**
     *  Getting the group by id or name or object
     *
     * @param {int|string|object}
     * @param {int} id group
     * @param {string} name group
     * @param {object} object group
     *
     * @returns {object|null}
     */
    public static function getModel($group = '')
    {
        $groupModel = null;
        if (is_string($group) === true) {
            $groupModel = self::getByName($group);
        }
        if (is_numeric($group) === true) {
            $groupModel = self::find($group);
        }
        if (is_object($group) === true) {
            $groupModel = self::find($group->id);
        }

        return $groupModel;
    }



    /**
     *  Getting the default model
     *
     * @returns {object}
     */
    public static function getModelDefault()
    {
        return self::getModel(self::$defaultName);
    }



    /**
     * Checking to add to the array group
     *
     * @returns {true|false}
     */
    public static function checkArrayGroup($arrData = array())
    {
        if (is_string($arrData) === true && $arrData !== '') {
            return true;
        }
        if (is_array($arrData) === true && array_key_exists('name', $arrData) === true) {
            return true;
        }

        return false;
    }
}
