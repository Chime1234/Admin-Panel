<?php
/*
 * File name: ClinicTaxFactory.php
 * Last modified: 2024.02.15 at 14:42:15
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\ClinicTax;
use App\Models\Tax;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClinicTaxFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ClinicTax::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'tax_id' => Tax::all()->random()->id,
            'clinic_id' => Clinic::all()->random()->id
        ];
    }
}
