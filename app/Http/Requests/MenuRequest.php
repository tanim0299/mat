<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class MenuRequest extends FormRequest
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
        $array['type'] = 'required';
        $array = [];
        $array['name'] = 'required';
        $array['status'] = 'required';
        $array['position'] = 'required';
        $array['order_by'] = 'required';
        if($request->type == 1)
        {
            $array['label_id'] = 'required';
            $array['icon'] = 'required';
        }
        if($request->type == 2)
        {
            $array['route'] = 'required';
            $array['system_name'] = 'required';
            $array['parent_id'] = 'required';
            $data['slug'] = 'required';
        }
        if($request->type == 2)
        {
            $array['label_id'] = 'required';
            $array['system_name'] = 'required';
            $array['route'] = 'required';
            $data['slug'] = 'required';
        }
        return $array;
    }

    public function messages()
    {
        return [
            'name.required' => __('menu.name_required'),
            'status.required' => __('menu.status_required'),
            'type.required' => __('menu.type_required'),
            'position.required' => __('menu.position_required'),
            'label_id.required' => __('menu.label_id_required'),
            'icon.required' => __('menu.icon_required'),
            'route.required' => __('menu.route_required'),
            'system_name.required' => __('menu.system_name_required'),
            'parent_id.required' => __('menu.parent_id_rquired'),
            'slug.required' => __('menu.slug_required'),
        ];
    }
}
