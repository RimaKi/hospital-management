<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientMedicine extends Model
{
    use HasFactory;
    protected $fillable=[
        "id",
        'medicineId',
        'patientId',
        'diagnosisId',
        'quantity',
        'description'
    ];
    protected $appends=['remaining','medicine'];
    protected $hidden=['medicineId'];
    public function getPatientAttribute(){
        return $this->hasOne(Patient::class,'nationalId','patientId')->first();
    }
    public function getDiagnosisAttribute(){
        return $this->hasOne(Diagnosis::class,'id','diagnosisId')->first();
    }
    public function getMedicineAttribute(){
        return $this->hasMany(Medicine::class,'id','diagnosisId')->get();
    }
    public function getRemainingAttribute(){
        $quantity=0;
        foreach ($this->getDiagnosisAttribute()->allBills as $bill){
            if($this->getAttribute('medicineId')==$bill->medicineId){
                $quantity+=$bill->quantity;
            }
        }
        return $this->getAttribute('quantity') -$quantity;
    }

}
