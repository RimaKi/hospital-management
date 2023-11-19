<?php
namespace App\Services;

use App\Models\Diagnosis;
use App\Models\Doctor;



class DiagnosisService extends ServiceHelper{
    public function __construct()
    {
        $this->model=new Diagnosis();
        $this->orderBy='created_at';
        $this->isAscending=false;
        $this->attributes=$this->model->getFillable();
    }
}
