<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPatientCnicToUsersAndBloodRequestsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('patient_cnic')->nullable()->after('needs_blood_for');
        });

        Schema::table('blood_requests', function (Blueprint $table) {
            $table->string('patient_cnic')->nullable()->after('patient_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('patient_cnic');
        });

        Schema::table('blood_requests', function (Blueprint $table) {
            $table->dropColumn('patient_cnic');
        });
    }
}
