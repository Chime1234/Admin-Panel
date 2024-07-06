<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateMediaTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('media') && !Schema::hasColumn('media','conversions_disk')) {
            Schema::table('media', function (Blueprint $table) {
                $table->uuid()->after('id')->nullable()->unique();
                $table->string('conversions_disk')->after('disk')->nullable()->default('public');
                $table->json('generated_conversions')->after('custom_properties');
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
