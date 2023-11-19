<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientMedicinesAddRequest extends FormRequest
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
            'diagnosisId'=>['required','numeric'],
            'medicines'=>['required','array'],
            'medicines.*.medicineId'=>['required','string'],
            'medicines.*.quantity'=>['required','string'],
            'medicines.*.description'=>['required','string'],
        ];
    }
}
