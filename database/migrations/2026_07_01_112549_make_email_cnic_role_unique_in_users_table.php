<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class MakeEmailCnicRoleUniqueInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Drop existing unique indexes using raw SQL to avoid doctrine/dbal requirement
        try {
            DB::statement('ALTER TABLE users DROP INDEX users_email_unique');
        } catch (\Exception $e) {}

        try {
            DB::statement('ALTER TABLE users DROP INDEX users_cnic_unique');
        } catch (\Exception $e) {}

        Schema::table('users', function (Blueprint $table) {
            // Create composite unique indexes so an email/cnic can be used once per role
            $table->unique(['email', 'role'], 'users_email_role_unique');
            $table->unique(['cnic', 'role'], 'users_cnic_role_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        try {
            DB::statement('ALTER TABLE users DROP INDEX users_email_role_unique');
        } catch (\Exception $e) {}

        try {
            DB::statement('ALTER TABLE users DROP INDEX users_cnic_role_unique');
        } catch (\Exception $e) {}

        Schema::table('users', function (Blueprint $table) {
            $table->unique('email');
            $table->unique('cnic');
        });
    }
}
