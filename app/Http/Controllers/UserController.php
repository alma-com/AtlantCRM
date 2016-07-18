<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\UserRequest;

use Auth;
use Session;
use Validator;
use HTML;
use Response;
use Alma;
use Hash;

use App\User;
use App\Role;

class UserController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::with('roles')->get();
        $roles = [];

        if(!is_null($users)){
            foreach($users as $key => $user){
                $roles[$user->id] = array();

                if(is_null($user->roles)){break;}

                foreach($user->roles as $key => $role){
                    $roles[$user->id][] = $role->display_name;
                }
            }
        }

        if(count($users) == 0){
            Session::flash('warning', 'Пользователей нет');
        }

        $view = view('pages.users.index')
            ->with('users', $users)
            ->with('roles', $roles);
        return Alma::viewReturn($view, $request);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $roles = Role::all();
        $view = view('pages.users.create')->with('roles', $roles);
        return Alma::viewReturn($view, $request);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $user = User::create($request->all());
        $user->roles()->sync($request->input('roles', []));

        $arrStatus = [
            'request' => $request,
            'url' => route('users.index'),
        ];

        return Alma::successReturn('Пользователь успешно добавлен', $arrStatus);
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
        $roles = Role::all();
        if(count($user) == 0){
            Session::flash('warning', 'Пользователь не найден');
        }

        $view = view('pages.users.edit')
            ->with('roles', $roles)
            ->with('user', $user);
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
        $rules = array(
            'name'       => 'required|max:255',
            'email'      => 'required|email|max:255|unique:users,email,'.$id,
            'password' => 'min:6|max:255',
            'password_confirmation' => 'same:password',
        );
        $validator = Validator::make($request->all(), $rules);

        $password = $request->input('password');
        $password_confirmation = $request->input('password_confirmation');

        if ($password_confirmation == '' && $password_confirmation != $password){
            $validator->after(function($validator){
                $validator->errors()->add('password_confirmation', 'Значение "Еще раз пароль" должно совпадать с "Пароль".');
            });
        }
        $arrStatus = array(
            'request' => $request,
            'validator' => $validator,
        );

        // Fails
        if ($validator->fails()) {
            return Alma::failsReturn('Не удалось изменить пользователя', $arrStatus);
        }


        // Success
        $user = User::find($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($password);
        $user->save();

        $roles = $request->input('roles');
        $roleAll = Role::all();
        if(!is_null($roleAll)){
            foreach($roleAll as $key => $item){

                if(count($roles) > 0 && in_array($item->id, $roles)){
                    $user->assignRole($item->id);
                }else{
                    $user->deleteRole($item->id);
                }

            }
        }

        $arrStatus['url'] = route('users.index');
        return Alma::successReturn('Пользователь успешно изменен', $arrStatus);
    }



    /**
     * Обновление полей пользователей
     */
    public function updateItems(Request $request)
    {
        $rules = array();
        $itemArray = $request->input('item');
        foreach($itemArray as $key => $id_user){
             $rules['name.'.$id_user] =  'required|max:255';
             $rules['email.'.$id_user] =  'required|email|max:255|unique:users,email,'.$id_user;
        }
        $validator = Validator::make($request->all(), $rules);
        $arrStatus = array(
            'request' => $request,
            'validator' => $validator,
        );


        if(count($itemArray) == 0){
            return Alma::infoReturn('Ничего не выбрано', $arrStatus);
        }

        // Fails
        if ($validator->fails()) {
            return Alma::failsReturn('Не удалось изменить', $arrStatus);
        }


        // Success
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
        $rules = array();
        $validator = Validator::make($request->all(), $rules);
        if (Auth::check() and Auth::user()->id == $id){
            $validator->after(function($validator){
                $validator->errors()->add('field', 'Нельзя удалить себя');
            });
        }
        $arrStatus = array(
            'request' => $request,
            'validator' => $validator,
        );

        // Fails
        if ($validator->fails()) {
            return Alma::failsReturn('Не удалось удалить пользователя', $arrStatus);
        }


        // Success
        User::del($id);

        $arrStatus['url'] = route('users.index');
        return Alma::successReturn('Пользователь успешно удален', $arrStatus);
    }



    /**
     * Удаление списка пользователей
     */
    public function destroyItems(Request $request)
    {
        $rules = array();
        $validator = Validator::make($request->all(), $rules);
        $arrStatus = array(
            'request' => $request,
            'validator' => $validator,
        );

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

        // Fails
        if ($validator->fails()) {
            return Alma::failsReturn('Не удалось удалить', $arrStatus);
        }


        // Success
        foreach($itemArray as $key => $id_user){
            User::del($id_user);
        }

        $arrStatus['url'] = route('users.index');
        return Alma::successReturn('Пользователи успешно удалены', $arrStatus);
    }
}
