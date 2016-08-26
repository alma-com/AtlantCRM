<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\RoleRequest;
use Alma;
use Session;
use Validator;
use App\Role;
use App\Permission;
use App\PermissionGroup;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $roles = Role::with('users')->get();

        return Alma::viewReturn(view('pages.roles.index', compact('roles')), $request);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $groups = PermissionGroup::with('permissions')->get();

        return Alma::viewReturn(view('pages.roles.create', compact('groups')), $request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  RoleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        $role = Role::create($request->all());
        $role->permissions()->sync($request->input('permissions', []));

        return Alma::successReturn('Роль успешно добавлена', [
            'request' => $request,
            'url' => route('roles.index'),
        ]);
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $role = Role::find($id);
        $groups = PermissionGroup::with('permissions')->get();

        if(count($role) == 0){
            Session::flash('warning', 'Роль не найдена');
        }

        return Alma::viewReturn(view('pages.roles.edit', compact('role', 'groups')), $request);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  RoleRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, $id)
    {
        $role = Role::findOrFail($id);
        $role->update($request->all());
        $role->permissions()->sync($request->input('permissions', []));

        return Alma::successReturn('Роль успешно изменена', [
            'request' => $request,
            'url' => route('roles.index'),
        ]);
    }



    /**
     * Обновление полей пользователей
     */
    public function updateItems(Request $request)
    {
        $rules = [];
        $itemArray = $request->input('item');
        foreach($itemArray as $key => $id_role) {
             $rules['display_name.'.$id_role] =  'required|max:255';
        }
        $validator = Validator::make($request->all(), $rules);

        $arrStatus = [
            'request' => $request,
            'validator' => $validator,
        ];

        if(count($itemArray) == 0){
            return Alma::infoReturn('Ничего не выбрано', $arrStatus);
        }

        if ($validator->fails()) {
            return Alma::failsReturn('Не удалось изменить', $arrStatus);
        }

        foreach($itemArray as $key => $id_role){
            $role = Role::find($id_role);
            $role->display_name = $request->input('display_name')[$id_role];
            $role->description = $request->input('description')[$id_role];
            $role->sort_order = $request->input('sort_order')[$id_role];
            $role->save();
        }

        $arrStatus['url'] = route('roles.index');
        return Alma::successReturn('Роли успешно изменены', $arrStatus);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $rules = [];
        $validator = Validator::make($request->all(), $rules);
        $arrStatus = [
            'request' => $request,
            'validator' => $validator,
        ];

        if ($validator->fails()) {
            return Alma::failsReturn('Не удалось удалить роль', $arrStatus);
        }

        $role = Role::find($id);
        $role->delete();

        $arrStatus['url'] = route('roles.index');
        return Alma::successReturn('Роль успешно удалена', $arrStatus);
    }



    /**
     * Удаление списка ролей
     */
    public function destroyItems(Request $request)
    {
        $rules = [];
        $validator = Validator::make($request->all(), $rules);
        $arrStatus = [
            'request' => $request,
            'validator' => $validator,
        ];

        $itemArray = $request->input('item');
        if(count($itemArray) == 0){
            return Alma::infoReturn('Ничего не выбрано', $arrStatus);
        }

        $arrStatus['validator'] = $validator;

        if ($validator->fails()) {
            return Alma::failsReturn('Не удалось удалить', $arrStatus);
        }

        foreach($itemArray as $key => $id_role){
            $role = Role::find($id_role);
            $role->delete();
        }

        $arrStatus['url'] = route('roles.index');
        return Alma::successReturn('Роли успешно удалены', $arrStatus);
    }
}
