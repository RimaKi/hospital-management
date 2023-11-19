<?php
namespace App\Services;

use App\Models\Specialization;


class SpecializationService extends ServiceHelper{
    public function __construct()
    {
        $this->model=new Specialization();
        $this->searchBy=['name','phone'];
        $this->orderBy='name';
        $this->attributes=[
            'name',
            'uniqueId'
        ];
    }
}
