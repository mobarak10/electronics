<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RetailSaleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer' => 'required|array',
            'date' => 'required|date',
            'payment' => 'required|array',
            'products' => 'required|array',
        ];
    }
}
