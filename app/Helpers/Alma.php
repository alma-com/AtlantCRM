<?php

namespace App\Helpers;

use Session;
use HTML;

class Alma
{
	/*
	* Формирование массива
		$resOk = array(
			'status' => 'success',
			'message' => 'Пользователь успешно добавлен',
			'url' => route('users.index'),
		);
		$resFail = array(
			'status' => 'warning',
			'message' => 'Не удалось добавить пользователя',
		);
	*/
	public static function getArrayStatus($resOk, $resFail, $validator)
	{
		$res = $resOk;
		if ($validator->fails()) {
			$res = $resFail;
			$res['errFields'] = $validator->messages();
			
			Session::flash($res['status'], HTML::ul($validator->errors()->all()));
			$res['description'] = (string) view('common.alert');
			Session::forget($res['status']);
		}
		return $res;
	}
}
