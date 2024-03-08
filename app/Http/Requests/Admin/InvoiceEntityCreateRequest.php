<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceEntityCreateRequest extends FormRequest
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
            'name' => ['required', 'max:255', 'unique:invoice_entities,name'],
            'gst_no' => ['nullable', 'max:30'],
            'invoice_prefix' => ['nullable', 'min:2','max:3'],
            'address' => ['nullable', 'max:255'],
            'area' => ['nullable', 'max:255'],
            'city' => ['nullable', 'max:255'],
            'state' => ['nullable', 'max:255'],
            'country' => ['nullable', 'max:255'],
            'pincode' => ['nullable', 'max:10'],
            'primary_name' => ['nullable', 'max:255'],
            'primary_email' => ['nullable', 'email', 'max:255'],
            'primary_mobile' => ['nullable', 'max:15'],
            'primary_designation' => ['nullable', 'max:255'],
            'account_name' => ['nullable', 'max:255'],
            'account_number' => ['nullable', 'max:255'],
            'ifsc_code' => ['nullable', 'max:255'],
            'bank_name' => ['nullable', 'max:255'],
            'branch' => ['nullable', 'max:255'],
            'description' => ['nullable', 'max:500'],
            'status' => ['required', 'boolean'],
        ];
    }
}
