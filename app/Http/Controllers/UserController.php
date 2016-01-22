<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

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
		if($request->ajax()){
			return $view->renderSections();
		}
		return $view;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		$view = view('pages.users.create');
		if($request->ajax()){
			return $view->renderSections();
		}
		return $view;
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
		$resOk = array(
			'status' => 'success',
			'message' => 'Пользователь успешно добавлен',
			'url' => route('users.index'),
		);
		$resFail = array(
			'status' => 'warning',
			'message' => 'Не удалось добавить пользователя',
		);
		$res = Alma::getArrayStatus($resOk, $resFail, $validator);
			
		
		// Fails
		if ($validator->fails()) {
			if($request->ajax()){
				return Response::json($res);
			}
			
			Session::flash($res['status'], HTML::ul($validator->errors()->all()));
			 return redirect()->back()
                ->withErrors($validator)
				->withInput();
		}
		
		
		// Success
		$user = new User;
		$user->name = $request->get('name');
		$user->email = $request->get('email');
		$user->password = Hash::make($request->get('password'));
		$user->save();
		

		if($request->ajax()){
			return Response::json($res);
		}
		
		Session::flash($res['status'], $res['message']);
		return redirect($res['url']);
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
