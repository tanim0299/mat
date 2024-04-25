<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(Request $request): array
    {
        // return $request;
        $data = array(
            'role_id' => 'required',
            'name' => 'required',
            'email' => 'required|unique:users,email,'.$request->user,
            'phone' => 'required|unique:users,phone,'.$request->user,
        );
        if($request->_method == 'PUT')
        {
            // $data['password'] = 'min:6';
        }
        else
        {
            $data['password'] = 'required|min:6';
        }

        return $data;
    }

    public function messages() : array
    {
        return [
            'role_id.required' =>__('user.select_role'),
            'name.required' => __('user.name_required'),
            'email.required' => __('user.email_required'),
            'email.unique' => __('user.email_unique'),
            'phone.required' => __('user.phone_required'),
            'phone.unique' => __('user.phone_unique'),
            'password.requried' => __('user.passwrod_required'),
            'password.min' => __('user.password_min'),
        ];
    }
}
