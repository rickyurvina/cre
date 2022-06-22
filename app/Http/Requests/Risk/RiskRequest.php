<?php

namespace App\Http\Requests\Risk;

use App\Abstracts\Http\FormRequest;

class RiskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'description' => 'required',
            'incidence_date' => 'required'
        ];
    }
}
