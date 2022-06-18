<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'brand_id'                      => 'required|integer',
            'category_id'                   => 'required|integer',
            'unit_id'                       => 'required|integer',
            'name'                          => 'required|string',
            'barcode'                       => 'nullable|string',
            'stock_alert'                   => 'required|numeric',
            'purchase_price'                => 'required|numeric',
            'sale_price'                    => 'required|numeric',
            'wholesale_price'               => 'required|numeric',
            'description'                   => 'nullable|string',
        ];
    }
}
