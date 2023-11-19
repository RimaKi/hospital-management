<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;
    protected $fillable=[
        'id',
        'quantity',
        'medicineId',
        'diagnosticId',
        'patientId',
        'paidAt'
    ];
    protected $hidden=['medicineId','diagnosticId','patientId','created_at','updated_at'];
    protected $appends=['medicine','diagnosis','patient'];

    public function getMedicineAttribute(){
        return $this->hasOne(Medicine::class,'id','medicineId')->firstOrFail();
    }
    public function getDiagnosisAttribute(){
        return $this->hasOne(Diagnosis::class,'id','diagnosticId')->firstOrFail();
    }
    public function getPatientAttribute(){
        return $this->hasOne(Patient::class,'nationalId','patientId')->firstOrFail();
    }
}
