<?php
namespace App\Services;

use App\Models\Doctor;



class DoctorService extends ServiceHelper{
    public function __construct()
    {
        $this->model=new Doctor();
        $this->searchBy=['name','phone'];
        $this->orderBy='userId';
        $this->attributes=[
            'userId',
            'specializationId',
            'education',
            'graduation',
            'experience',
            'availableDays',
            'availableHours',
            'sessionTime',

        ];
    }
}
