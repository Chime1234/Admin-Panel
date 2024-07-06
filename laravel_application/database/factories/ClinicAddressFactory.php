<?php
/*
 * File name: ClinicAddressFactory.php
 * Last modified: 2024.04.20 at 11:19:32
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace Database\Factories;

use App\Models\Address;
use App\Models\Clinic;
use App\Models\ClinicAddress;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClinicAddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ClinicAddress::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'address_id' => Address::all()->random()->id,
            'clinic_id' => Clinic::all()->random()->id
        ];
    }
}
