<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Session;
use Validator;
use HTML;
use Hash;

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
    public function create(Request $request)
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
            'password' => 'required|min:6|max:255',
            'password_confirmation' => 'required|same:password',
        );
		$validator = Validator::make($request->all(), $rules);


		if ($validator->fails()) {
			Session::flash('warning', HTML::ul($validator->errors()->all()));
			 return redirect()->back()
                ->withErrors($validator)
				->withInput();
		}else{

			$user = new User;
            $user->name = $request->get('name');
            $user->email = $request->get('email');
            $user->password = Hash::make($request->get('password'));
            $user->save();

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
