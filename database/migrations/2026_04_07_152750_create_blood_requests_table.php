<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBloodRequestsTable extends Migration
{
    /**
     * Run the migrations.
     * Viva Question: What is a Schema?
     * Answer: A schema is the skeleton structure that represents the logical view of the entire database.
     */
    public function up()
    {
        Schema::create('blood_requests', function (Blueprint $table) {
            $table->id();
            // Viva Note: foreignId is a helper for creating unsigned big integer columns for foreign keys.
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('patient_name');
            $table->string('blood_group');
            $table->integer('units');
            $table->string('hospital_location');
            $table->string('urgency_level')->default('Normal'); // Normal, High, Critical
            $table->string('status')->default('Pending'); // Pending, Approved, Fulfilled, Rejected
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('blood_requests');
    }
}
