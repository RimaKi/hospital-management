<?php

namespace Database\Factories;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    protected $model = Patient::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $created_at = $this->faker->dateTimeThisYear();
        return [
            'nationalId' => $this->faker->unique()->uuid(),
            'name' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            "blood" => collect(["O+","O-","AB+","AB-","A+","A-","B+","B-"])->shuffle()->take(1)->first(),
            "hasInsurance" => false,
            'birthday' => $this->faker->dateTimeBetween("-70 years", '-13 years'),
            'created_at' => $created_at,
            'updated_at' => $created_at,
        ];
    }
}
