<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonationsTable extends Migration
{
    /**
     * Run the migrations.
     * Viva Question: What is a foreign key?
     * Answer: A foreign key is a column or group of columns in a relational database 
     * table that provides a link between data in two tables.
     */
    public function up()
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Link to donor in users table
            $table->unsignedBigInteger('hospital_id')->nullable(); // Link to the hospital entity
            $table->string('blood_group');
            $table->integer('units');
            $table->date('donation_date');
            $table->string('status')->default('Completed'); // Scheduled, Completed, Cancelled
            $table->timestamps();

            // Viva Note: Defining foreign key relationships manually here.
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('donations');
    }
}
