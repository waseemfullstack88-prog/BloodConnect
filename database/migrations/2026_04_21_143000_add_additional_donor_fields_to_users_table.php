<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('cnic')->nullable()->unique();
            $table->string('father_name')->nullable();
            $table->string('hospital_name')->nullable();
            $table->string('hospital_location')->nullable();
            $table->date('dob')->nullable();
            $table->date('last_donation_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'cnic',
                'father_name',
                'hospital_name',
                'hospital_location',
                'dob',
                'last_donation_date'
            ]);
        });
    }
};
