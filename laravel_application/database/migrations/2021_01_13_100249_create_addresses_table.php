<?php
/*
 * File name: 2021_01_13_200249_create_addresses_table.php
 * Last modified: 2021.02.28 at 14:45:20
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::dropIfExists('addresses');
        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description', 255)->nullable();
            $table->string('address', 255);
            $table->double('latitude', 20, 17)->default(0);
            $table->double('longitude', 20, 17)->default(0);
            $table->boolean('default')->nullable()->default(0);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::drop('addresses');
    }
}
