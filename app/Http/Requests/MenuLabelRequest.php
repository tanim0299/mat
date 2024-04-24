<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuLabelRequest extends FormRequest
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
            'type' => 'required',
            'label_name' => 'required',
            'status' => 'required',
        ];
    }

    public function messages() : array
    {
        return [
            'type.required' => __('menu_label.type_required'),
            'label_name.required' => __('menu_label.label_name_required'),
            'status.required' => __('common.status_required'),
        ];
    }
}
