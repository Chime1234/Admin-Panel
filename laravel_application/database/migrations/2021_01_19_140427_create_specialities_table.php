<?php
/*
 * File name: 2021_01_19_140427_create_specialities_table.php
 * Last modified: 2021.04.20 at 11:19:32
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecialitiesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::dropIfExists('specialities');
        Schema::create('specialities', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('name')->nullable();
            $table->string('color', 36);
            $table->longText('description')->nullable();
            $table->integer('order')->nullable()->default(0);
            $table->boolean('featured')->nullable()->default(0);
            $table->integer('parent_id')->nullable()->unsigned();
            $table->timestamps();
            $table->foreign('parent_id')->references('id')->on('specialities')->onDelete('set null')->onUpdate('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::drop('specialities');
    }
}
