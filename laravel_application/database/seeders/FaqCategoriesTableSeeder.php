<?php
/*
 * File name: FaqCategoriesTableSeeder.php
 * Last modified: 2021.03.01 at 21:56:10
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */
namespace Database\Seeders;

use DB;
use App\Models\FaqCategory;
use Illuminate\Database\Seeder;

class FaqCategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('faq_categories')->delete();

        FaqCategory::factory()->count(5)->create();
    }
}
