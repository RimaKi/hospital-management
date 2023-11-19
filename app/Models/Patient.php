<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory, HasFactory;
    protected $primaryKey='nationalId';
    public $incrementing=false;

    protected $fillable = [
        'nationalId',
        'name',
        'phone',
        'emergencyPhone',
        'hasInsurance',
        'blood',
        'birthday',
    ];

    public function getSchedulesAttribute()
    {
        return $this->hasMany(Schedule::class, 'patientId', 'nationalId')->get();
    }
    public function getFirstVisitAttribute(){
        $date = Carbon::make($this->attributes["created_at"]);
        return  $date->monthName . "_" . $date->year;
    }
    public function getDiagnosesAttribute(){
        return $this->hasMany(Diagnosis::class,'patientId','nationalId')->get();
    }
    public function getNotPaidDiagnosesAttribute(){
        return $this->hasMany(Diagnosis::class,'patientId','nationalId')->where('paidAt','=',null)->get();
    }
    public function getPatientMedicineAttribute(){
        return $this->hasMany(PatientMedicine::class,'patientId','nationalId')->get();
    }
    public function getBillsAttribute(){
        return $this->hasMany(Bill::class,'patientId','nationalId')->get();
    }
    public function getNotPaidBillsAttribute(){
        return $this->hasMany(Bill::class,'patientId','nationalId')->where('paidAt','=',null)->get();
    }
}
