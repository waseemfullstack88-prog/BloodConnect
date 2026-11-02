<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRecipientFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     * Adds recipient-specific fields:
     * - needs_blood_for: who the blood is needed for (self, family, friend)
     * - willing_to_donate: whether the recipient is willing to donate in future
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('needs_blood_for')->nullable()->after('hospital_location');
            $table->boolean('willing_to_donate')->default(false)->after('needs_blood_for');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['needs_blood_for', 'willing_to_donate']);
        });
    }
}
