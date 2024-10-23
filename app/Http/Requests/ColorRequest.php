<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ColorRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'color_name' => 'required',
            'color_code' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'color_name.required' => __('color.color_name_required'),
            'color_code.required' => __('color.color_code_required'),
        ];
    }
}
