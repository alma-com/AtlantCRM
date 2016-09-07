<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\UserRequest;
use Auth;
use Session;
use Validator;
use Response;
use Alma;
use App\User;
use App\Role;

class UserController extends Controller
{
    /**
    * Instantiate a new new controller instance.
    *
    * @return void
    */
    public function __construct()
    {
        $this->middleware('access:show_user');
        $this->middleware('access:add_user')->only('create', 'store');
        $this->middleware('access:edit_user')->only('edit', 'update', 'updateItems');
        $this->middleware('access:delete_user')->only('destroy', 'destroyItems');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::with('roles')->get();

        return Alma::viewReturn(view('pages.users.index', compact('users')), $request);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $roles = Role::ordered()->toArray();

        return Alma::viewReturn(view('pages.users.create', compact('roles')), $request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  UserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $user = User::create($request->all());

        if(Auth::user()->access('change_role_user')) {
            $user->roles()->sync($request->input('roles', []));
        }

        return Alma::successReturn('Пользователь успешно добавлен', [
            'request' => $request,
            'url' => route('users.index'),
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
        $user = User::find($id);
        $roles = Role::ordered()->toArray();

        if(count($user) == 0){
            Session::flash('warning', 'Пользователь не найден');
        }

        return Alma::viewReturn(view('pages.users.edit', compact('user', 'roles')), $request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UserRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->all());
        if(Auth::user()->access('change_role_user')) {
            $user->roles()->sync($request->input('roles', []));
        }

        return Alma::successReturn('Пользователь успешно изменен', [
            'request' => $request,
            'url' => route('users.index'),
        ]);
    }

    /**
     * Update list items
     */
    public function updateItems(Request $request)
    {
        $rules = [];
        $itemArray = $request->input('item');
        foreach($itemArray as $key => $id_user) {
             $rules['name.'.$id_user] = 'required|max:255';
             $rules['email.'.$id_user] = 'required|email|max:255|unique:users,email,' . $id_user;
        }
        $validator = Validator::make($request->all(), $rules);

        $arrStatus = [
            'request' => $request,
            'validator' => $validator,
        ];

        if(count($itemArray) === 0){
            return Alma::infoReturn('Ничего не выбрано', $arrStatus);
        }

        if ($validator->fails()) {
            return Alma::failsReturn('Не удалось изменить', $arrStatus);
        }

        foreach($itemArray as $key => $id_user){
            $user = User::find($id_user);
            $user->name = $request->input('name')[$id_user];
            $user->email = $request->input('email')[$id_user];
            $user->save();
        }

        $arrStatus['url'] = route('users.index');
        return Alma::successReturn('Пользователи успешно изменены', $arrStatus);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $rules = [];
        $validator = Validator::make($request->all(), $rules);
        if (Auth::check() and Auth::user()->id == $id){
            $validator->after(function($validator){
                $validator->errors()->add('field', 'Нельзя удалить себя');
            });
        }
        $arrStatus = [
            'request' => $request,
            'validator' => $validator,
        ];

        if ($validator->fails()) {
            return Alma::failsReturn('Не удалось удалить пользователя', $arrStatus);
        }

        $user = User::find($id);
        $user->delete();

        $arrStatus['url'] = route('users.index');
        return Alma::successReturn('Пользователь успешно удален', $arrStatus);
    }



    /**
     * destroy list items
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

        foreach($itemArray as $key => $id_user){
            if (Auth::check() and Auth::user()->id == $id_user){
                $validator->after(function() use ($validator, $id_user){
                    $validator->errors()->add('table_'.$id_user, 'Нельзя удалить себя');
                });
            }
        }
        $arrStatus['validator'] = $validator;

        if ($validator->fails()) {
            return Alma::failsReturn('Не удалось удалить', $arrStatus);
        }

        foreach($itemArray as $key => $id_user){
            $user = User::find($id_user);
            $user->delete();
        }

        $arrStatus['url'] = route('users.index');
        return Alma::successReturn('Пользователи успешно удалены', $arrStatus);
    }
}
