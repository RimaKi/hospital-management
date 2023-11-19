<?php
namespace App\Services;
use App\Models\Schedule;


class ScheduleService extends ServiceHelper{
    public function __construct()
    {
        $this->model=new Schedule();
        $this->searchBy=[ 'selectedDate'];
        $this->orderBy = "selectedDate";
        $this->attributes=[
            'id',
            'doctorId',
            'patientId',
            'selectedDate',
            'endTime',
            'isCanceled'
        ];
    }
}
