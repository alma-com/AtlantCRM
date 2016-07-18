<?php

namespace App\Helpers;

use Session;
use HTML;
use Response;

class Alma
{

    /**
     * Получение массива с default value
     * arrStatus(
     * 'request' => $request,
     *     'validator' => $validator,
     *     'url' => $url,                            -    редирект после операции
     *     'errFields' => $errFields,            -    выделение красной рамкой и мигание неверных полей
     *     'description' => $description,    -    вывод описания
     * )
     */
    public static function gerArrStatus($arrStatus)
    {
        $arrDefault = array(
            'request' => '',
            'validator' => '',
            'url' => '',
            'errFields' => '',
            'description' => '',
        );
        $res = array_merge($arrDefault, $arrStatus);
        if($res['description'] != ''){
            $res['description'] = self::gerDescription('info', $res['description']);
        }

        unset($res['request']);
        unset($res['validator']);
        return $res;
    }



    /**
     * Получение подробного описания
     */
    public static function gerDescription($status, $text)
    {
        $res = '';
        Session::flash($status, $text);
        $res = (string) view('common.alert');
        Session::forget($status);

        return $res;
    }



    /**
     * return когда ошибка при валидации
     */
    public static function failsReturn($message, $arrStatus)
    {
        $request = $arrStatus['request'];
        $validator = $arrStatus['validator'];
        $arrStatus['status'] = 'warning';
        $arrStatus['message'] = $message;
        $res = self::gerArrStatus($arrStatus);

        $res['errFields'] = $validator->messages();
        $res['description'] = self::gerDescription($res['status'], HTML::ul($validator->errors()->all()));

        if($request->ajax()){
            return Response::json($res);
        }

        Session::flash($res['status'], HTML::ul($validator->errors()->all()));
        if($res['url'] != ''){
            return redirect($res['url'])
                ->withErrors($validator)
                ->withInput();
        }
         return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }



    /**
     * return когда успешная валидация
     */
    public static function successReturn($message, $arrStatus)
    {
        $request = $arrStatus['request'];
        $arrStatus['status'] = 'success';
        $arrStatus['message'] = $message;
        $res = self::gerArrStatus($arrStatus);

        if($request->ajax()){
            return Response::json($res);
        }

        Session::flash($res['status'], $res['message']);
        if($res['url'] != ''){
            return redirect($res['url']);
        }
        return redirect()->back();
    }


    /**
     * return со статусом info
     */
    public static function infoReturn($message, $arrStatus)
    {
        $request = $arrStatus['request'];
        $arrStatus['status'] = 'info';
        $arrStatus['message'] = $message;
        $res = self::gerArrStatus($arrStatus);

        if($request->ajax()){
            return Response::json($res);
        }

        Session::flash($res['status'], $res['message']);
        if($res['url'] != ''){
            return redirect($res['url']);
        }
        return redirect()->back();
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
