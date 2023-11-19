<?php
namespace App\Services;
use App\Models\Patient;
class PatientService extends ServiceHelper{
    public function __construct()
    {
        $this->model=new Patient();
        $this->searchBy=['name','phone'];
        $this->orderBy='name';
        $this->attributes=[
            'nationalId',
            'name',
            'phone',
            'emergencyPhone',
            'hasInsurance',
            'blood',
            'birthday',

        ];
    }
}
