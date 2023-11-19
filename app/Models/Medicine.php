<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'parentId',
        'price'
    ];
    protected $appends=[
        'parent'
    ];
    protected $hidden=['created_at','updated_at','parentId'];

    public function getParentAttribute(){
        return $this->belongsTo(self::class,'parentId','id')->first();
    }
}
