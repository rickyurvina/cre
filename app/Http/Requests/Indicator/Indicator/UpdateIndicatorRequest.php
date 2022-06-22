<?php

namespace App\Http\Requests\Indicator\Indicator;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateIndicatorRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date|after:start_date',
            'frequency' => [Rule::in(['weekly', 'monthly', 'quarterly', 'four-monthly', 'biannual', 'annual'])],
            'threshold_type' => [Rule::in(['Ascending', 'Tolerance', 'Descending'])],
            'thresholds_id' => 'integer',
        ];
    }
}
