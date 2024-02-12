<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ChargesUpdateRequest extends FormRequest
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
        $id = $this->charge;
        return [
            'name' => ['required', 'max:255', 'unique:charges,name,' . $id],
            'description' => ['nullable', 'max:255'],
            'calculation_type' => ['required', 'min:1', 'max:1', 'in:v,p'],
            'calculation_on' => ['required', 'min:1', 'max:1', 'in:f,w,n,g,t'],
            'referred_tax' => ['required_if:calculation_on,t'],
            'editable' => ['required', 'min:1', 'max:1', 'in:y,n'],
            'value' => ['required', 'numeric'],
            'status' => ['required', 'boolean'],
        ];
    }
}
