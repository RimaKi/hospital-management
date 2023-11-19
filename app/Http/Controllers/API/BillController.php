<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\BillService;
use App\Services\DiagnosisService;
use App\Services\PatientService;
use Carbon\Carbon;
use PHPUnit\Util\Exception;

class BillController extends Controller
{
    public function viewBill($search)
    {
        return response()->json([
            'error' => 0,
            'diagnosis' => $this->get($search)['diagnosis'],
            'bills' => $this->get($search)['bills']
        ]);
    }
    public function pay($search){
        foreach ($this->get($search) as $key =>$item){
            foreach ($item as $i){
                if($key == 'diagnosis'){
                    if(!(new DiagnosisService())->update(['paidAt'=>Carbon::now()],['id'=>$i->id])){
                        throw new \Exception('failed');
                    }
                }else{
                    if(!(new BillService())->update(['paidAt'=>Carbon::now()],['id'=>$i->id])){
                        throw new \Exception('failed');
                    }
                }
            }
        }
        return response()->json([
            'error'=>0,
            'mag'=>'successfully'
        ]);
    }

    private function get($search){
        $patient = (new PatientService())->getFirst(['nationalId'=>$search]);
        if ($patient == null) {
            $patient = (new PatientService())->getFirst(['phone'=>$search]);
        }
        return [
            'diagnosis'=>$patient->notPaidDiagnoses ,
            'bills'=>$patient->notPaidBills
        ];
    }

}
