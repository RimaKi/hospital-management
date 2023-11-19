<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\DoctorService;
use App\Services\SpecializationService;
use App\Services\UserService;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function getSchedules(){
        $doctor=auth()->user();
        $schedules=$doctor->schedules;
        return response()->json([
            'error'=>0,
            'doctor'=>$doctor,
            'schedules'=>$schedules
        ]);
    }

    public function getDoctors()
    {
        $result = [];
        foreach ((new DoctorService())->getList() as $doctor) {
            $result[] = [
                "doctor" => $doctor,
                "user" => $doctor->user
            ];
        }
        return response()->json([
            'error' => 0,
            'result' => $result
        ]);
    }

}
