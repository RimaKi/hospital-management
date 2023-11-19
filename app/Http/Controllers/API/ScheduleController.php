<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Services\DoctorService;
use App\Services\ScheduleService;
use App\Services\SpecializationService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function newAppointment(Request $request)
    {
        $data = $request->only(['doctorId', 'patientId', 'selectedDate']);
        $request->validate([
            'doctorId' => ['required', 'string'],
            'patientId' => ['required', 'string'],
            'selectedDate' => ['required','date'],
        ], $data);
        $thisDate = (new ScheduleService())->getFirst(['selectedDate' => $data["selectedDate"],'doctorId'=>$data['doctorId'], 'isCanceled' => 0]);
        if ($thisDate != null) {
            throw new \Exception('This date is reserved');
        }
        $doctor = (new DoctorService())->getOne($data["doctorId"]);
        $data["endTime"] = Carbon::make($data["selectedDate"])->addMinutes($doctor->sessionTime);
        if(!(new ScheduleService())->save($data)){
            throw new \Exception('failed to add appointment');
        }
        return response()->json([
            'error' => 0,
            'msg' => 'Successfully added appointment'
        ]);
    }

    public function cancel(Request $request)
    {

        $data = $request->only(['id']);
        $request->validate([
            'id' => ['string'],
        ], $data);
        if ((new ScheduleService())->update(['isCanceled' => "1"],['id' => $data['id']])) {
            throw new \Exception('failed');
        }
        return response()->json([
            'error' => 1,
            'msg' => 'deleted '
        ]);

    }

    public function getAllSchedules(Request $request)
    {
        $data = $request->only(['doctorId', 'patientId', 'selectedDate', 'isCanceled']);
        $request->validate([
            'doctorId' => ['string'],
            'patientId' => ['string'],
            'selectedDate' => ['date'],
            'isCanceled' => ['boolean']
        ], $data);
        $schedules = (new ScheduleService())->getListQuery();
        if ($request->has('doctorId')) {
            $schedules = $schedules->where('doctorId', $data['doctorId']);
        }
        if ($request->has('patientId')) {
            $schedules = $schedules->where('patientId', $data['patientId']);
        }
        if ($request->has('selectedDate')) {
            $schedules = $schedules->where('selectedDate', '>=' , $data['selectedDate']);
        }
        if ($request->has('isCanceled')) {
            $schedules = $schedules->where('isCanceled', $data['isCanceled']);
        }

        return response()->json([
            'error' => 0,
            'result' => $schedules->get()
        ]);
    }


    public function getAvailableVisit(Request $request)
    {
        $data = $request->only(['id']);
        $request->validate([
            'id' => ['string'],
        ], $data);
        if ($request->has('id')) {
            $doctor = (new DoctorService())->getFirst(['userId' => $data['id']]);
        } else {
            $doctor = (new DoctorService())->getFirst(['id' => auth()->user()->doctor]);
        }
        return response()->json([
            "error" => 0,
            "result" => $doctor->visitsForWeek
        ]);
    }

    public function getAvailableAppointments(Request $request)
    {
        $data = $request->only(['doctorId', 'selectedDate']);
        $request->validate([
            'doctorId' => ['required','string'],
            'selectedDate' => ['required','date'],
        ], $data);
        $schedules = (new ScheduleService())->getListQuery()->where('doctorId', $data['doctorId'])->whereDate("selectedDate","=",$data["selectedDate"])->get();
        $doctor = (new DoctorService())->getFirst(['userId' => $data['doctorId']]);
        $result = [];
        $reservedAppointments = [];
        foreach ($schedules as $schedule) {
            $reservedAppointments[] = Carbon::make($schedule->selectedDate)->format("H:i");
        }
        $dayName = Carbon::make($data["selectedDate"])->dayName;
        foreach ($doctor->visitsForWeek as $availableDay) {
            if ($availableDay["day"] == $dayName) {
                if (count($reservedAppointments)) {
                    foreach ($availableDay["times"] as $time) {
                        if (! in_array($time, $reservedAppointments)) {
                            $result[] = $time;
                        }
                    }
                } else {
                    $result = $availableDay["times"];
                }
            }
        }

        return response()->json([
            'error' => 0,
            'result' => $result,
        ]);
    }
}
