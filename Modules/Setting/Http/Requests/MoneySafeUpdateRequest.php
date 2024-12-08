<?php

namespace Modules\Setting\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MoneySafeUpdateRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $id = $this->input('id');
        return [
            'name' => 'required|string|max:255|unique:money_safes,name,' . $id,
        ];
    }
    public function messages()
    {
        return [
            'name.required'=>__('lang.NameRequired'),
            'name.unique'=>__('lang.NameUnique'),
        ];
    }
}
