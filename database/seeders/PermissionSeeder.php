<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\User;
use App\Services\SpecializationService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        $roles=[
            'admin',
            'doctor',
            'employee',
            'pharmacist'
        ];
        foreach ($roles as $role){
            Role::create(['name'=>$role]);
        }
        $user=new User();
        $user->email='jad3an@gmail.com';
        $user->phone='0987654321';
        $user->nationalId='3634457';
        $user->name='jad3an';
        $user->birthday='1998-3-21';
        $user->password=Hash::make('123123123');
        $user->save();
        $user->assignRole('admin');

        Patient::factory(100)->create();
        $specializations = [
            "Cardiology",
            "Dermatology",
            "Neurology",
            "Gynecology",
            "Pediatrics",
            "Ophthalmology",
            "nephrology",
            "Oncology",
            "Hematology",
            "General Practitioner",
            "Emergency medicine",
        ];
        foreach ($specializations as $s) {
            (new SpecializationService())->save([
                "uniqueId" => Str::random(12),
                "name" => $s
            ]);
        }
        User::factory(20)->create();

    }
}
