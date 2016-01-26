<?php

namespace App\Helpers;

use Session;
use HTML;
use Response;

class Alma
{
	/*
	* Формирование массива
		$resOk = array(
			'message' => 'Пользователь успешно добавлен',
			'url' => route('users.index'),
		);
		$resFail = array(
			'message' => 'Не удалось добавить пользователя',
		);
	*/
	public static function getArrayStatus($resOk, $resFail, $validator)
	{
		$res = $resOk;
		$res['status'] = 'success';
		if ($validator->fails()) {
			$res = $resFail;
			$res['status'] = 'warning';
			$res['errFields'] = $validator->messages();
			
			Session::flash($res['status'], HTML::ul($validator->errors()->all()));
			$res['description'] = (string) view('common.alert');
			Session::forget($res['status']);
		}
		return $res;
	}
	
	
	
	/**
	 * return когда ошибка при валидации
	 */
	public static function failsReturn($res, $validator, $request)
	{
		if($request->ajax()){
			return Response::json($res);
		}
		
		Session::flash($res['status'], HTML::ul($validator->errors()->all()));
		 return redirect()->back()
			->withErrors($validator)
			->withInput();
	}
	
	
	
	/**
	 * return когда успешная валидация
	 */
	public static function successReturn($res, $validator, $request)
	{
		if($request->ajax()){
			return Response::json($res);
		}
		
		Session::flash($res['status'], $res['message']);
		return redirect($res['url']);
	}
	
	
	
	/**
	 * return view и для ajax и не ajax
	 */
	public static function viewReturn($view, $request)
	{
		if($request->ajax()){
			return $view->renderSections();
		}
		return $view;
	}
	
			
}
