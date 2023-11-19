<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\DiagnosisAddRequest;
use App\Http\Requests\DiagnosisEditRequest;
use App\Http\Requests\DiagnosisRequest;
use App\Models\Diagnosis;
use App\Models\PatientMedicine;
use App\Services\DiagnosisService;
use Illuminate\Http\Request;


class DiagnosisController extends Controller
{
    public function store(DiagnosisRequest $request)
    {
        $data = $request->only(['patientId', 'description','price']);
        $data['doctorId'] = auth()->user()->id;
        if (!(new DiagnosisService())->save($data)) {
            throw new \Exception('failed save....');
        }
        return response()->json([
            'error' => 0,
            'msg' => 'save successfully'
        ]);
    }

    public function update(DiagnosisEditRequest $request)
    {
        $data = $request->only(['patientId', 'description', 'id','price']);
        if (!(new DiagnosisService())->update($data, ['id' => $data['id']])) {
            throw new \Exception('failed save....');
        }
        return response()->json([
            'error' => 0,
            'msg' => 'update successfully'
        ]);
    }

    public function delete($id)
    {
        if (!(new DiagnosisService())->delete(['id' => $id])) {
            throw new \Exception('failed delete');
        }
        return response()->json([
            'error' => 0,
            'msg' => 'delete successfully'
        ]);
    }

    public function view(Request $request,$id=null)
    {
        if ($id != null) {
            $diagnosis=(new DiagnosisService())->getFirst(['id' => $id]);
            return response()->json([
                'error' => 0,
                'diagnosis' =>array_merge($diagnosis->toArray(),["patientMedicine"=>$diagnosis->patientMedicine]),
            ]);
        }
        $data = $request->only(['patientId','doctorId']);
        $request->validate([
            "patientId" => ['string'],
            "doctorId" => ['numeric'],
        ], $request->all());
        $diagnoses = (new DiagnosisService())->getListQuery();
        foreach ($data as $i => $value) {
            $diagnoses = $diagnoses->where($i, $value);
        }
        return response()->json([
            'error' => 0,
            'msg' => $diagnoses->get()
        ]);

    }

    public function addDiagnosis(DiagnosisAddRequest $request,$patientId){
        $data1=$request->only([ 'description','price']);

        $data1['doctorId']=auth()->user()->id;
        $data1['patientId']=$patientId;
        $diagnosis=new Diagnosis($data1);
        $diagnosis->save();
        foreach ($request->get('medicines') as $medicine){
            $medicine['patientId']=$patientId;
            $medicine['diagnosisId']=$diagnosis->id;
            $m=new PatientMedicine($medicine);
            $m->save();
        }
        return response()->json([
            'error'=>0,
            'msg'=>'save successfully'
        ]);

    }


}
