<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Auth;
use Session;
use Validator;
use HTML;
use Response;
use Alma;

use App\User;

class UserController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		$users = User::all();
		if(count($users) == 0){
			Session::flash('warning', 'Пользователей нет');
		}
		
		$view = view('pages.users.index')->with('users', $users);
		return Alma::viewReturn($view, $request);
    }

	
	
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		$view = view('pages.users.create');
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
            'email'      => 'required|unique:users|email|max:255',
            'password' => 'required|min:6|max:255',
            'password_confirmation' => 'required|same:password',
        );
		$validator = Validator::make($request->all(), $rules);
		$arrStatus = array(
			'request' => $request,
			'validator' => $validator,
		);
				
		// Fails
		if ($validator->fails()) {
			return Alma::failsReturn('Не удалось добавить пользователя', $arrStatus);
		}
		
		
		// Success
		$arrParam = array(
			'name' => $request->input('name'),
			'email' => $request->input('email'),
			'password' => $request->input('password'),
		);
		User::updateData($arrParam);
		
		$arrStatus['url'] = route('users.index');
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
		if(count($user) == 0){
			Session::flash('warning', 'Пользователь не найден');
		}
		
		$view = view('pages.users.edit')->with('user', $user);
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
		$arrParam = array(
			'id' => $id,
			'name' => $request->input('name'),
			'email' => $request->input('email'),
			'password' => $password,
		);
		User::updateData($arrParam);
			
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
			$arrParam = array(
				'id' => $id_user,
				'name' => $request->input('name')[$id_user],
				'email' => $request->input('email')[$id_user],
			);
			User::updateData($arrParam);
		}
		
		$arrStatus['url'] = route('users.index');
		return Alma::successReturn('Пользователи успешно изменены', $arrStatus);
		
		return 1;
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
		User::deleteData($id);
			
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
			User::deleteData($id_user);
		}
		
		$arrStatus['url'] = route('users.index');
		return Alma::successReturn('Пользователи успешно удалены', $arrStatus);		
    }
}
