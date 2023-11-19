<?php

namespace App\Services;


use App\Models\Bill;

class BillService extends ServiceHelper
{
    public function __construct()
    {
        $this->model = new Bill();
        $this->attributes = $this->model->getFillable();
    }
}
