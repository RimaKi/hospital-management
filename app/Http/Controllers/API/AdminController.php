<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Services\DoctorService;
use App\Services\PatientService;
use App\Services\ScheduleService;
use App\Services\SpecializationService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{

    public function getStatistics() {
        $doctors = (new DoctorService())->getList();
        $patients = (new PatientService())->getList();
        $employees = (new UserService())->getList();
        $yearly = [];
        foreach (collect($patients)->sortBy("created_at")->groupBy("firstVisit") as $month => $patientsInMonth) {
            $yearly[$month] = count($patientsInMonth);
        }
        return response()->json([
            'error' => 0,
            'doctors' => $doctors->count(),
            'patients' => $patients->count(),
            'employees' => $employees->count() - $doctors->count(),
            'yearly' => $yearly,
        ]);
    }

    //user
    public function viewUser($id = null)
    {
        if ($id == null) {
            $users=[];
            foreach ((new UserService())->getList() as $u) {
                if ($u->hasRole("doctor")) {
                    $users[] = [...$u->toArray(), ...$u->doctor->toArray()];
                } else {
                    $users[] = $u;
                }
            }
            return response()->json([
                'error' => 0,
                'result' => $users
            ]);
        }
        return response()->json([
            'error' => 0,
            'result' => (new UserService())->getOne($id)
        ]);
    }

    public function deleteUser(Request $request)
    {
        $data = $request->only(['id']);
        $request->validate([
            'id' => ['numeric']
        ], $data);
        $user=(new UserService())->getFirst(['nationalId'=>$data['id']]);
        if($user->hasRole('doctor')){
            $user->removeRole('doctor');
            if (!(new DoctorService())->delete(['userId' => $data['id']])) {
                throw new \Exception('failed delete');
            }
        }
        if (!(new UserService())->delete(['nationalId' => $data['id']])) {
            throw new \Exception('failed delete');
        }
        return \response()->json([
            'error' => 0,
            'msg' => "deleted Successfully"
        ]);
    }
    //Specialization

    public function addSpecialization(Request $request)
    {
        $data = $request->only(['name']);
        $request->validate([
            'name' => ['required', 'string']
        ], $data);
        $data['uniqueId'] = Str::random(25);
        if (!(new SpecializationService())->save($data)) {
            throw new \Exception('failed');
        }
        return response()->json([
            'error' => 0,
            'msg' => 'successfully'
        ]);
    }

    public function editSpecialization(Request $request)
    {

        $data = $request->only(['name', 'uniqueId']);
        $request->validate([
            'name' => ['required', 'string'],
            'uniqueId' => ['required', 'string'],
        ], $data);

        if (!(new SpecializationService())->update($data, ['uniqueId' => $data['uniqueId']])) {
            throw new \Exception('failed');
        }
        return response()->json([
            'error',
            'msg' => ' edit successfully'
        ]);

    }

    public function deleteSpecialization(Request $request)
    {

        $data = $request->only(['uniqueId']);
        $request->validate([
            'uniqueId' => ['required', 'string'],
        ], $data);

        if (!(new SpecializationService())->delete(['uniqueId' => $data['uniqueId']])) {
            throw new \Exception('failed');
        }
        return response()->json([
            'error' => 0,
            'msg' => ' delete successfully'
        ]);

    }

    public function viewSpecializations()
    {
        return response()->json([
            'error' => 0,
            'result' => (new SpecializationService())->getList()
        ]);
    }


}
