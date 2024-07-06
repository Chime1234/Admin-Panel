<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDoctorIdToAppointments extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('appointments') && !Schema::hasColumn('appointments','doctor_id')) {
            Schema::table('appointments', function (Blueprint $table) {
                $table->integer('doctor_id')->nullable()->unsigned()->after('doctor');
                $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('set null')->onUpdate('set null');

            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
