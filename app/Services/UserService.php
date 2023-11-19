<?php
namespace App\Services;

use App\Models\User;

class UserService extends ServiceHelper{
    public function __construct()
    {
        $this->model=new User();
        $this->searchBy=['name','phone'];
        $this->orderBy='name';
        $this->attributes=[
            'id',
            'nationalId',
            'email',
            'birthday',
            'name',
            'phone',
            'password',
            'photo'
        ];
    }
}
