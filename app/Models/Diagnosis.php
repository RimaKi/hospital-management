<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'patientId',
        'doctorId',
        'description',
        'price',
        'paidAt'

    ];
    protected $appends = ['doctor','isCompleted'];
    protected $hidden = [
        'doctorId',
    ];

    public function getPatientAttribute()
    {
        return $this->hasOne(Patient::class, 'nationalId', 'patientId')->first();
    }

    public function getDoctorAttribute()
    {
        $doctor=$this->hasOne(Doctor::class, 'userId', 'doctorId')->first();
       return $doctor->user;
    }

    public function getPatientMedicineAttribute()
    {
        return $this->hasMany(PatientMedicine::class, 'diagnosisId', 'id')->get();
    }

    public function getAllBillsAttribute()
    {
        return $this->hasMany(Bill::class, 'diagnosticId', 'id')->get();
    }

    public function getNotPaidBillsAttribute()
    {
        return $this->hasMany(Bill::class, 'diagnosticId', 'id')->where('paidAt', '=', null)->get();
    }

    public function getIsCompletedAttribute(){
        $isCompleted = false;
       $q1=0;
       $q2=0;
       foreach ($this->getPatientMedicineAttribute() as $medicine) $q1+=$medicine->quantity;
       foreach ($this->getAllBillsAttribute() as $bill)$q2+=$bill->quantity;
       if($q1 ==$q2)$isCompleted = true;
       return $isCompleted;
    }


}
