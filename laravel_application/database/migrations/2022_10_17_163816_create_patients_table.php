<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePatientsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::dropIfExists('patients');
        Schema::create('patients', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('user_id')->unsigned();
            $table->string('first_name', 127);
            $table->string('last_name', 127);
            $table->string('phone_number', 50);
            $table->string('mobile_number', 50);
            $table->string('age', 127);
            $table->string('gender', 127);
            $table->string('weight', 127);
            $table->string('height', 127);
            $table->longText('medical_history')->nullable();
            $table->longText('notes')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::drop('patients');
    }
}
