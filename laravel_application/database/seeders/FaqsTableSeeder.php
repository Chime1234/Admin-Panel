<?php
/*
 * File name: FaqsTableSeeder.php
 * Last modified: 2021.03.01 at 21:56:10
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */
namespace Database\Seeders;

use DB;
use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run(): void
    {

        DB::table('faqs')->delete();
        Faq::factory()->count(30)->create();
    }
}
