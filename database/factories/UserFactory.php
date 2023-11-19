<?php

namespace Database\Factories;

use App\Models\User;
use App\Services\DoctorService;
use App\Services\SpecializationService;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;
    private string $role = "";

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'nationalId' => $this->faker->unique()->uuid(),
            'birthday' => $this->faker->dateTimeBetween("-80 years", '-18 years'),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('123123123'),
            'phone' => $this->faker->phoneNumber(),
        ];
    }

    public function configure()
    {
        return $this->afterMaking(function (User $user) {
        })->afterCreating(function (User $user) {
            $r = $this->role == "" ? collect(["admin","doctor","employee"])->shuffle()->take(1)->first() : $this->role;
            if ($r == "doctor") {
                (new DoctorService())->save([
                    "specializationId" => collect((new SpecializationService())->getList())->shuffle()->take(1)->first()["uniqueId"],
                    "userId" => $user->id,
                    "graduation" => $this->faker->dateTime()
                ]);
            }
            $user->assignRole($r);
        });
    }

}
