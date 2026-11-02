<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDetailsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     * Viva Question: What is a migration?
     * Answer: A migration is like version control for your database. 
     * It allows you to define and modify your database schema using PHP code.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Viva Note: Adding custom fields for blood donation logic.
            $table->string('blood_group')->nullable();
            $table->string('phone')->nullable();
            $table->string('city')->nullable();
            $table->string('role')->default('user'); // user, admin, hospital, donor 
        });
    }

    /**
     * Reverse the migrations.
     * Viva Note: The 'down' method is used to undo the changes made by the 'up' method.
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['blood_group', 'phone', 'city', 'role']);
        });
    }
}
