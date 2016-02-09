<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

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
		$roles = Role::all();	
		$view = view('pages.roles.index')->with('roles', $roles);
		return Alma::viewReturn($view, $request);
    }

	
	
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		$groups = PermissionGroup::with('permissions')->get();
		
        $view = view('pages.roles.create')->with('groups', $groups);
		return Alma::viewReturn($view, $request);
    }

	
	
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $res = array();
        $rules = array(
            'name'       => 'required|max:255',
            'display_name'       => 'required|max:255',
        );
		$validator = Validator::make($request->all(), $rules);
		$arrStatus = array(
			'request' => $request,
			'validator' => $validator,
		);
		
		// Fails
		if ($validator->fails()) {
			return Alma::failsReturn('Не удалось добавить роль', $arrStatus);
		}
		
		
		// Success
		$arrParam = array(
			'name' => $request->input('name'),
			'display_name' => $request->input('display_name'),
			'description' => $request->input('description'),
		);
		$role = Role::add($arrParam);
		
		$permissions = $request->input('permissions');
		if(count($permissions) > 0){
			foreach($permissions as $key => $id_perm){
				$role->assignPermission($id_perm);
			}
		}
		
		
		$arrStatus['url'] = route('roles.index');
		return Alma::successReturn('Роль успешно добавлена', $arrStatus);	
    }

	
	
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $res = array();
        $rules = array(
            'name'       => 'required|max:255',
            'display_name'       => 'required|max:255',
        );
		$validator = Validator::make($request->all(), $rules);
		$arrStatus = array(
			'request' => $request,
			'validator' => $validator,
		);
		
		// Fails
		if ($validator->fails()) {
			return Alma::failsReturn('Не удалось добавить роль', $arrStatus);
		}
		
		
		// Success
		$arrParam = array(
			'name' => $request->input('name'),
			'display_name' => $request->input('display_name'),
			'description' => $request->input('description'),
		);
		$role = Role::add($arrParam);
		
		$permissions = $request->input('permissions');
		if(count($permissions) > 0){
			foreach($permissions as $key => $id_perm){
				$role->assignPermission($id_perm);
			}
		}
		
		
		$arrStatus['url'] = route('roles.index');
		return Alma::successReturn('Роль успешно добавлена', $arrStatus);	
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
		
		if(is_null($role)){
			Session::flash('warning', 'Роль не найдена');
		}
		
		$view = view('pages.roles.edit')
			->with('role', $role)
			->with('groups', $groups);
		return Alma::viewReturn($view, $request);
    }

	
	
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       $res = array();
        $rules = array(
            'name'       => 'required|max:255',
            'display_name'       => 'required|max:255',
        );
		$validator = Validator::make($request->all(), $rules);
		$arrStatus = array(
			'request' => $request,
			'validator' => $validator,
		);
		
		// Fails
		if ($validator->fails()) {
			return Alma::failsReturn('Не удалось изменить роль', $arrStatus);
		}
		
		
		// Success
		$role = Role::find($id);
		$role->name = $request->input('name');
		$role->display_name = $request->input('display_name');
		$role->description = $request->input('description');
		$role->save();
		
		$permissions = $request->input('permissions');
		$permAll = Permission::all();
		if(!is_null($permAll)){
			foreach($permAll as $key => $item){
				
				if(count($permissions) > 0 && in_array($item->id, $permissions)){
					$role->assignPermission($item->id);
				}else{
					$role->deletePermission($item->id);
				}
				
			}
		}
		
		
		$arrStatus['url'] = route('roles.index');
		return Alma::successReturn('Роль успешно изменена', $arrStatus);	
    }

	
	
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
