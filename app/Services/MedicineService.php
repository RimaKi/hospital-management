<?php

namespace App\Services;


use App\Models\Medicine;

class MedicineService extends ServiceHelper
{
    public function __construct()
    {
        $this->model = new Medicine();
        $this->searchBy = ['name'];
        $this->attributes = [
            'id',
            'name',
            'parentId',
            'price'
        ];
    }
}
