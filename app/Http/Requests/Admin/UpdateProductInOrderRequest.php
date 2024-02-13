<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductInOrderRequest extends FormRequest
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
            'product' => ['required', 'exists:products,id'],
            'description' => ['nullable'],
            'uom' => ['required', 'exists:sizes,id'],
            'quantity' => ['required', 'numeric'],
            'make' => ['required', 'exists:vendors,id'],
            'price' => ['required', 'numeric'],
            'taxes' => ['nullable'],
        ];
    }
}
