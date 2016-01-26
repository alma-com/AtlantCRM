<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Auth;
use Session;
use Validator;
use HTML;
use Hash;
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
		
		//Array Status
		$res = Alma::getArrayStatus(
			array(
				'message' => 'Пользователь успешно добавлен',
				'url' => route('users.index'),
			),
			array('message' => 'Не удалось добавить пользователя',),
			$validator
		);
			
		// Fails
		if ($validator->fails()) {
			return Alma::failsReturn($res, $validator, $request);	
		}
		
		
		// Success
		$user = new User;
		$user->name = $request->get('name');
		$user->email = $request->get('email');
		$user->password = Hash::make($request->get('password'));
		$user->save();
		
		
		return Alma::successReturn($res, $validator, $request);	
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
    public function edit($id)
    {
        //
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
        //
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
		
		//Array Status
		$res = Alma::getArrayStatus(
			array(
				'message' => 'Пользователь успешно удален',
				'url' => route('users.index'),
			),
			array(
				'message' => 'Не удалось удалить пользователя',
			), 
			$validator
		);
		
		// Fails
		if ($validator->fails()) {
			return Alma::failsReturn($res, $validator, $request);	
		}
		
		
		// Success
		$user = User::find($id);
		$user->delete();
		
		
		return Alma::successReturn($res, $validator, $request);	
    }
	
	
	
	/**
     * Удаление списка пользователей
     */
    public function destroyAll(Request $request)
    {
		$rules = array();
        $validator = Validator::make($request->all(), $rules);
		
        $itemArray = $request->input('item');
		if(count($itemArray) > 0){
			foreach($itemArray as $key => $id_user){
				if (Auth::check() and Auth::user()->id == $id_user){
					$validator->after(function() use ($validator, $id_user){
						$validator->errors()->add('table_'.$id_user, 'Нельзя удалить себя');
					});
				}
			}
		}else{
			return Response::json(array(
				'status' => 'info',
				'message' => 'Ничего не выбрано',
			));
		}
			
		//Array Status
		$res = Alma::getArrayStatus(
			array(
				'message' => 'Пользователи успешно удалены',
				'url' => route('users.index'),
			),
			array(
				'message' => 'Не удалось удалить',
			), 
			$validator
		);
		
		// Fails
		if ($validator->fails()) {
			return Alma::failsReturn($res, $validator, $request);	
		}
		
		
		// Success
		foreach($itemArray as $key => $id_user){
			$this->destroy($request, $id_user);
		}
		
		
		return Alma::successReturn($res, $validator, $request);	
    }
}
