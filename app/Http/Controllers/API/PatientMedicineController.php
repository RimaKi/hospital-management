<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\PatientMAddRequest;
use App\Http\Requests\PatientMEditRequest;
use App\Models\PatientMedicine;
use App\Services\DiagnosisService;
use App\Services\PatientMedicineService;
use Illuminate\Http\Request;


class PatientMedicineController extends Controller
{
    public function store(PatientMAddRequest $request)
    {
        $data = $request->only(['medicineId', 'patientId', 'diagnosisId', 'quantity', 'description']);
        if (!(new PatientMedicineService())->save($data)) {
            throw new \Exception('failed');
        }
        return response()->json([
            'error' => 0,
            'msg' => 'save successfully'
        ]);
    }

    public function update(PatientMEditRequest $request, $id)
    {//for doctor
        $data = $request->only(['medicineId', 'patientId', 'diagnosisId', 'quantity', 'description']);
        if (!(new PatientMedicineService())->update($data, ['id' => $id])) {
            throw new \Exception('failed');
        }
        return response()->json([
            'error' => 0,
            'msg' => 'save successfully'
        ]);
    }

    public function delete($id)
    {
        if (!(new PatientMedicineService())->delete(['id' => $id])) {
            throw new \Exception('failed delete');
        }
        return response()->json([
            'error' => 0,
            'msg' => 'delete successfully'
        ]);
    }

    public function view(PatientMEditRequest $request)
    {
        $data = $request->only(['id', 'medicineId', 'patientId', 'diagnosisId', 'quantity', 'description']);
        $patienMedicins = (new PatientMedicineService())->getListQuery();
        foreach ($data as $i => $value) {
            $patienMedicins = $patienMedicins->where($i, $value);

        }
        return response()->json([
            'error' => 0,
            'msg' => $patienMedicins->get()
        ]);

    }

    public function addMedicines(Request $request,$patientId){

        foreach ($request->get('medicines') as $medicine){
            $medicine['patientId']=$patientId;
            $medicine['diagnosisId']=$request->get('diagnosisId');
            $m=new PatientMedicine($medicine);
            $m->save();
        }
        return response()->json([
            'error'=>0,
            'msg'=>'save successfully'
        ]);
    }



}
