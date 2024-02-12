<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductCreateRequest extends FormRequest
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
            'name' => ['required', 'max:255', 'unique:products,name'],
            'code' => ['nullable', 'max:255'],
            'description' => ['nullable', 'max:500'],
            'charge_tax' => ['sometimes', 'boolean'],
            'tax_id' => ['required_if:charge_tax,1'],
            'status' => ['required', 'boolean'],
        ];
    }
}
