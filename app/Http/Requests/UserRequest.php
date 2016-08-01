<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UserRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required|max:255',
            'email' => 'required|unique:users,email,' . $this->get('id') . '|email|max:255',
            'password' => 'min:6|max:255',
            'password_confirmation' => 'same:password',
        ];

        if($this->isAdd() || $this->doNeedPassword()){
            $rules['password'] = 'required|min:6|max:255';
            $rules['password_confirmation'] = 'required|same:password';
        }

        return $rules;
    }

    private function doNeedPassword()
    {
        return $this->get('password_confirmation') == ''
            && $this->get('password_confirmation') != $this->get('password');
    }
}
