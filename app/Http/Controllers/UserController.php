<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Session;
use Validator;

use App\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$users = User::all();
		if(count($users) == 0){
			Session::flash('warning', 'Пользователей нет');
		}
        return view('pages.users.index')->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		return view('pages.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'name'       => 'required|max:255',
            'email'      => 'required|unique:users|email|max:255',
            'password' => 'required|max:255',
            'password_confirmation' => 'required|max:255',
        );
		$validator = Validator::make($request->all(), $rules);
		if($request->input('password') != $request->input('password_confirmation')){
			$validator->after(function($validator){
				$validator->errors()->add('password', 'Пароли не совпадают');
			});
		}
		
		if ($validator->fails()) {
			 return redirect()->back()
                ->withErrors($validator)
				->withInput();
		}else{
			Session::flash('success', 'Пользователь успешно добавлен');
            return redirect()->route('users.index');
		}
		
		
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
    public function destroy($id)
    {
        //
    }
}
