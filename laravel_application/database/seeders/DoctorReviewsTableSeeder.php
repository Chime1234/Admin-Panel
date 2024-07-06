<?php
/*
 * File name: DoctorReviewsTableSeeder.php
 * Last modified: 2021.02.02 at 10:59:31
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */
namespace Database\Seeders;

use DB;
use App\Models\DoctorReview;
use Illuminate\Database\Seeder;

class DoctorReviewsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('doctor_reviews')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DoctorReview::factory()->count(100)->create();

    }
}
