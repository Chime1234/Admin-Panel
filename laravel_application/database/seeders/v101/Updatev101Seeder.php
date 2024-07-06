<?php
/*
 * File name: DatabaseSeeder.php
 * Last modified: 2021.09.16 at 12:29:38
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */
namespace Database\Seeders\v101;

use DB;
use Illuminate\Database\Seeder;

class Updatev101Seeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call(AppSettingsTableV101Seeder::class);
        $this->call(DoctorPatientsTableV101Seeder::class);





    }
}
