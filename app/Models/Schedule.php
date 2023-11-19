<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctorId',
        'patientId',
        'selectedDate',
        'endTime',
        'isCanceled'
    ];
    protected $hidden=[
        'doctorId',
        'patientId',
    ];
    protected $appends=[
        'patient',
        'doctor'
    ];
    public function getPatientAttribute(){
        return $this->hasOne(Patient::class,'nationalId','patientId')->firstOrFail();
    }
    public function getDoctorAttribute(){
        return $this->hasOne(User::class,'id','doctorId')->firstOrFail();
    }
}
