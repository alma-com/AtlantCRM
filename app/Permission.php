<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\PermissionGroup;

/**
 *
 * @static @method add(array $arrData, string $groupName)
 * @static @method del(int|string|object $permission)
 * @static @method getByName(string $name)
 * @static @method getModel(int|string|object $permission)
 * @static @method checkArrayPermission(array $arrData)
 *
 */
class Permission extends Model
{
    protected $fillable = [
        'name', 'display_name', 'description', 'sort_order', 'group_id',
    ];


    public function permissionGroups()
    {
        return $this->belongsTo('App\PermissionGroup', 'group_id');
    }

    public function roles()
    {
       return $this->belongsToMany('App\Role', 'role_has_permissions');
    }



    /**
     * Adding permissions
     *
     * @param {array} $arrData array with data to be added
     * @param {string} $arrData['name'] name permission
     * @param {string} $arrData['display_name'] display name of the permission
     * @param {string} $arrData['description'] permission description
     * @param {string} $arrData['sort_order'] sorting order
     * @param {int} $arrData['group_id'] id group
     * @param {string}    $groupName name group
     *
     * @returns {object|null} - return object models or null
     */
    public static function add($arrData = array(), $groupName = '')
    {
        if(self::checkArrayPermission($arrData) === false){return null;}

        if(is_string($arrData) === true && $arrData !== ''){
            $arrData = array('name' => $arrData);
        }

        $sort_order = Permission::max('sort_order')+10;
        $group =  PermissionGroup::getByName($groupName);

        $arrDefault = array(
            'name' => '',
            'display_name' => '',
            'description' => '',
            'sort_order' => $sort_order,
            'group_id' => $group->id,
        );
        $res = array_merge($arrDefault, $arrData);


        $permission = Permission::where('name', $res['name'])->first();
        if(count($permission) == 0){
            $permission = new Permission;
            $permission->name = $res['name'];
            $permission->display_name = $res['display_name'];
            $permission->description = $res['description'];
            $permission->sort_order = $res['sort_order'];
            $permission->group_id = $res['group_id'];
            $permission->save();
        }

        return $permission;
    }



    /**
     * Delete permission
     *
     * @param {int|string|object}
     * @param {int} id permission
     * @param {string} name permission
     * @param {object} object permission
     *
     * @returns {true}
     */
    public static function del($permission = '')
    {
        $permission = self::getModel($permission);
        if(!is_null($permission)){
            $permission->delete();
        }

        return true;
    }



    /**
     * Getting a permission by name
     *
     * @param {string} name permission
     *
     * @returns {object|null}
     */
    public static function getByName($name = ''){
        $permission = null;
        if($name != ''){
            $permission = self::where('name', $name)->first();
        }

        return $permission;
    }



    /**
     *  Getting the permission by id or name or object
     *
     * @param {int|string|object}
     * @param {int} id permission
     * @param {string} name permission
     * @param {object} object permission
     *
     * @returns {object|null}
     */
    public static function getModel($permission = '')
    {
        $permModel = null;
        if(is_string($permission) === true){
            $permModel = self::getByName($permission);
        }
        if(is_numeric($permission) === true){
            $permModel = self::find($permission);
        }
        if(is_object($permission) === true){
            $permModel = self::find($permission->id);
        }

        return $permModel;
    }



    /**
     * Checking to add to the array permission
     *
     * @returns {true|false}
     */
    public static function checkArrayPermission($arrData = array())
    {
        if(is_string($arrData) === true && $arrData !== ''){
            return true;
        }
        if(is_array($arrData) === true && array_key_exists('name', $arrData) === true){
            return true;
        }

        return false;
    }
}
