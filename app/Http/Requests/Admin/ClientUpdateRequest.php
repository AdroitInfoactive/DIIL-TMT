<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ClientUpdateRequest extends FormRequest
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
        $id = $this->client;
        return [
            'name' => ['required', 'max:255', 'unique:clients,name,'.$id],
            'email' => ['required', 'email', 'max:255'],
            'gst_no' => ['nullable', 'max:30'],
            'address' => ['required', 'max:255'],
            'area' => ['nullable', 'max:255'],
            'city' => ['required', 'max:255'],
            'state' => ['required', 'max:255'],
            'country' => ['required', 'max:255'],
            'pincode' => ['required', 'max:10'],
            'primary_name' => ['required', 'max:255'],
            'primary_mobile' => ['required', 'max:15'],
            'primary_whatsapp' => ['nullable', 'max:15'],
            'secondary_name' => ['nullable', 'max:255'],
            'secondary_mobile' => ['nullable', 'max:15'],
            'secondary_whatsapp' => ['nullable', 'max:15'],
            'description' => ['nullable', 'max:500'],
            'status' => ['required', 'boolean'],
        ];
    }
}
