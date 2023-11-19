<?php
namespace App\Services;


use App\Models\PatientMedicine;


class PatientMedicineService extends ServiceHelper{
    public function __construct()
    {
        $this->model=new PatientMedicine();
        $this->orderBy='created_at';
        $this->isAscending=false;
        $this->attributes=$this->model->getFillable();
    }
}
