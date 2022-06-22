<?php

namespace App\Http\Requests\Indicator\Indicator;

use App\Abstracts\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndicatorRequest extends FormRequest
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
            'name' => ['required'],
            'code' => ['required'],
//            'user_id' => 'required|integer',
//            'start_date' => 'date',
//            'end_date' => 'date|after:start_date',
//            'base_line' => 'nullable|numeric',
//            'type' => ['required', Rule::in(['Manual', 'Homologated'])],
//            'indicator_units_id' => 'integer',
//            'indicator_sources_id' => 'required|integer',
//            'thresholds_id' => 'required|integer',
//            'threshold_type' => ['required',Rule::in(['Ascending', 'Tolerance', 'Descending'])],
//            'baseline_year' => 'required|numeric|min:2019|integer',
//            'results' => 'required|string',
//            'frequency' => ['required',Rule::in(['52', '12', '4', '3', '2', '1'])],
//            'company_id' => 'required|numeric',
//            'type_of_aggregation'=>[Rule::in(['sum', 'weighted', 'weighted_sum'])],
        ];
    }
}
