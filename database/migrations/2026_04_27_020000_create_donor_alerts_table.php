<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonorAlertsTable extends Migration
{
    public function up()
    {
        Schema::create('donor_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('blood_request_id')->constrained('blood_requests')->onDelete('cascade');
            $table->string('blood_group');
            $table->string('urgency_level')->default('Normal');
            $table->string('hospital_location');
            $table->string('patient_name');
            $table->integer('units');
            $table->string('requester_name');
            $table->string('requester_phone')->nullable();
            $table->string('requester_whatsapp')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('donor_alerts');
    }
}
