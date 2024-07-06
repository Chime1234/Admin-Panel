<?php
/*
 * File name: ClinicFactory.php
 * Last modified: 2024.08.04 at 18:10:26
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace Database\Factories;

use App\Models\Address;
use App\Models\Clinic;
use App\Models\ClinicLevel;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClinicFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Clinic::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Public Health Clinic', 'Flu Shot Clinic', 'The Pain Medic', 'Universal Body Clinic', 'Animal Health Centre', 'Teen Clinic', 'Dentists', 'Mission Hospital Inc', 'High Point Medical Center', 'Annie Penn Memorial Clinic', 'Wayne Memorial', 'Medwest Harris', 'Blue Ridge Healthcare Clinics']) . " " . fake()->company,
            'description' => fake()->text,
            'address_id' => Address::all()->random()->id,
            'clinic_level_id' => ClinicLevel::all()->random()->id,
            'phone_number' => fake()->phoneNumber,
            'mobile_number' => fake()->phoneNumber,
            'availability_range' => fake()->randomFloat(2, 6000, 15000),
            'available' => fake()->boolean(95),
            'featured' => fake()->boolean(40),
            'accepted' => fake()->boolean(95),
        ];
    }
}
