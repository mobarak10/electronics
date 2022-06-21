<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InstallmentCollectionRequest extends FormRequest
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
            'hire_sale_id' => 'required|integer',
            'date' => 'required|date',
            'customer_id' => 'required|integer',
            'payment_amount' => 'required|numeric',
            'remission' => 'nullable|numeric',
            'adjustment' => 'nullable|numeric',
            'paid_by' => 'nullable|string',
        ];
    }
}
