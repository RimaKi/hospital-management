<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\DoctorService;
use App\Services\PatientService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PatientController extends Controller
{
    public function add(Request $request)
    {
        try {
            $data = $request->only(['name','hasInsurance', 'phone', 'nationalId', 'birthday',
                'emergencyPhone', 'hasInsurance', 'blood']);
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'phone' => ['required',"string",'min:9'],
                'nationalId' => ['required', 'string', 'max:255'],
                'birthday' => ['required', 'date'],
                'emergencyPhone' => ["string",'min:9'],
                'hasInsurance' => [ 'boolean'],
                'blood' => ['string', 'max:3', 'min:1'],
            ], $data);
            if (!(new PatientService())->save($data)) {
                throw new \Exception('failed');
            }
            return \response()->json([
                'error' => 0,
                'msg' => 'successfully',
            ]);
        } catch (\Exception $e) {
            return \response()->json([
                'error' => 1,
                'msg' => $e->getMessage()
            ]);

        }
    }

    public function edit(Request $request)
    {
        try {
            $data = $request->only(['name', 'phone', 'nationalId', 'birthday',
                'emergencyPhone', 'hasInsurance', 'blood']);
            $request->validate([
                'name' => ['string', 'max:255'],
                'phone' => ['digits:10'],
                'nationalId' => ['required', 'string', 'max:255'],
                'birthday' => ['date'],
                'emergencyPhone' => ['string'],
                'hasInsurance' => ['boolean'],
                'blood' => ['string', 'max:2'],
            ], $data);
            if (!(new PatientService())->update($data, ['nationalId' => $data['nationalId']])) {
                throw new \Exception('failed');
            }
            return \response()->json([
                'error' => 0,
                'msg' => 'successfully',
            ]);
        } catch (\Exception $e) {
            return \response()->json([
                'error' => 1,
                'msg' => $e->getMessage()
            ]);

        }
    }

    public function view()
    {
        $patients = (new PatientService())->getList();
        return response()->json([
            'error' => 0,
            'result' => $patients
        ]);
    }

    public function delete(Request $request)
    {
        try {
            $data = $request->only(['id']);
            $request->validate([
                'id' => ['numeric']
            ], $data);
            if (!(new PatientService())->delete(['nationalId' => $data['id']])) {
                throw new \Exception('failed delete');
            }
            return \response()->json([
                'error' => 0,
                'msg' => "update Successfully"
            ]);
        } catch (\Exception $e) {
            return \response()->json([
                'error' => 1,
                'msg' => $e->getMessage()
            ]);
        }

    }

    public function schedule(Request $request)
    {
        $data = $request->only(['id']);
        $request->validate([
            'id' => ['string']
        ], $data);
        $patient = (new PatientService())->getFirst(['nationalId' => $data['id']]);
        return response()->json([
            'error' => 0,
            'schedules' => $patient->schedules

        ]);
    }
}
