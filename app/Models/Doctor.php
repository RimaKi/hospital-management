<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;
    protected $primaryKey='userId';
    public $incrementing=false;
    protected $appends=['specialization'];
    protected $hidden=['specializationId','created_at',"updated_at"];
    protected $fillable=[
        'userId',
        'specializationId',
        'education',
        'graduation',
        'experience',
        'availableDays',
        'availableHours',
        'sessionTime',
    ];
    public function getUserAttribute(){
        return $this->hasOne(User::class,'id','userId')->first();
    }
    public function getSchedulesAttribute(){
        return $this->hasMany(Schedule::class,'doctorId','userId')->get();
    }
    public function getSpecializationAttribute(){
        $result = $this->hasOne(Specialization::class,'uniqueId','specializationId')->first();
        return $result != null ? $result["name"] : "";
    }

    public function getDiagnosesAttribute(){
        return $this->hasMany(Diagnosis::class,'doctorId','userId')->get();
    }

    public function getVisitsForWeekAttribute() {
        $result = [];
        if ($this->availableDays != "") {
            $dayNames = [Carbon::getDays()[6],...array_slice(Carbon::getDays(), 0,6)];
            foreach (explode(';', $this->availableHours) as $kk => $availableShift) {
                $array = [];
                foreach (explode(",", $availableShift) as $availableHour) {
                    $hour1 = explode('-', $availableHour)[0];
                    $hour2 = explode('-', $availableHour)[1];
                    $numberSession = ($hour2 - $hour1) * 60 / $this->sessionTime;
                    $j = 0;
                    $q = $hour1;
                    $totalTimeInMinutes = 0;
                    for ($i = 0; $i <= $numberSession; $i++) {
                        if ($totalTimeInMinutes  >= $hour2*60 - $this->sessionTime) {
                            break;
                        }
                        if ($j >= 60) {
                            $q += 1;
                            $j = $j - 60;
                        }
                        if ($j < 10) {
                            $j = "0$j";
                        }
                        $totalTimeInMinutes = $q * 60 + $j;
                        $time = "$q:$j";
                        array_push($array, $time);
                        $j += $this->sessionTime;
                    }
                }
                $availableDayIndex = explode(';', $this->availableDays)[$kk];
                $result[] = [
                    "day" => $dayNames[$availableDayIndex],
                    "dayIndex" => $availableDayIndex,
                    "times" => $array,
                ];
            }
        }
        return $result;
    }



}
