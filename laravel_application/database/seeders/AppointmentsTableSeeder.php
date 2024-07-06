<?php
/*
 * File name: AppointmentsTableSeeder.php
 * Last modified: 2024.04.11 at 13:35:11
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace Database\Seeders;

use App\Models\Appointment;
use Illuminate\Database\Seeder;
use DB;

class AppointmentsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('appointments')->truncate();

        Appointment::factory()->count(20)->create();
    }
}
