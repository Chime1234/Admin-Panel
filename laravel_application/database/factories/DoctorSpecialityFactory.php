<?php
/*
 * File name: DoctorSpecialityFactory.php
 * Last modified: 2024.03.02 at 14:35:34
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\DoctorSpeciality;
use App\Models\Speciality;
use Illuminate\Database\Eloquent\Factories\Factory;

class DoctorSpecialityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DoctorSpeciality::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'speciality_id' => Speciality::all()->random()->id,
            'doctor_id' => Clinic::all()->random()->id
        ];
    }
}
