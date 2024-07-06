<?php
/*
 * File name: AvailabilityHoursTableSeeder.php
 * Last modified: 2021.02.01 at 22:22:23
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */
namespace Database\Seeders;

use DB;
use App\Models\AvailabilityHour;
use Illuminate\Database\Seeder;

class AvailabilityHoursTableSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('availability_hours')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        AvailabilityHour::factory()->count(150)->create();
    }
}
