<?php
/*
 * File name: ClinicsTableSeeder.php
 * Last modified: 2024.04.11 at 13:35:11
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */
namespace Database\Seeders;

use App\Models\Clinic;
use App\Models\ClinicTax;
use App\Models\ClinicUser;
use Illuminate\Database\Seeder;
use DB;

class ClinicsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('clinics')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Clinic::factory()->count(18)->create();

        try {
            ClinicUser::factory()->count(10)->create();
        } catch (\Exception $e) {
        }
        try {
            ClinicUser::factory()->count(10)->create();
        } catch (\Exception $e) {
        }
        try {
            ClinicUser::factory()->count(10)->create();
        } catch (\Exception $e) {
        }
        try {
            ClinicTax::factory()->count(10)->create();
        } catch (\Exception $e) {
        }
        try {
            ClinicTax::factory()->count(10)->create();
        } catch (\Exception $e) {
        }
        try {
            ClinicTax::factory()->count(10)->create();
        } catch (\Exception $e) {
        }
    }
}
