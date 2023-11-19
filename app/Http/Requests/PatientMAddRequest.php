<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PatientMAddRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'medicineId'=>['required','numeric'],
            'patientId'=>['required','string'],
            'diagnosisId'=>['required','numeric'],
            'quantity'=>['required','numeric'],
            'description'=>['required','string']
        ];
    }
}
