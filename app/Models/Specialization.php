<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialization extends Model
{
    use HasFactory;
    protected $primaryKey='uniqueId';
    public $incrementing=false;
    protected $fillable=[
        'uniqueId',
        'name',
    ];
    protected $hidden=[
        'created_at',
        'updated_at',
    ];
    public function getDoctorsAttribute(){
        return $this->hasMany(Doctor::class,'specializationId','uniqueId')->get();
    }
}
