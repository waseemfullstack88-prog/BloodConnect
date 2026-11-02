<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\BloodRequest;
use App\Models\Donation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database for production/testing.
     */
    public function run()
    {
        // Truncate tables for clean re-seeding
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        BloodRequest::truncate();
        Donation::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Create a Master Admin
        User::create([
            'name'            => 'Wassim Bari (Admin)',
            'email'           => 'admin@bloodconnect.pk',
            'password'        => Hash::make('password'),
            'role'            => 'admin',
            'blood_group'     => 'O+',
            'city'            => 'Lahore',
            'phone'           => '03001234567',
            'whatsapp_number' => '03001234567',
        ]);

        // 2. Dummy Donors
        $donors = [
            ['name' => 'Ali Raza', 'email' => 'ali@example.com', 'blood_group' => 'A+', 'city' => 'Lahore', 'cnic' => '35202-1111111-1'],
            ['name' => 'Ayesha Khan', 'email' => 'ayesha@example.com', 'blood_group' => 'B+', 'city' => 'Karachi', 'cnic' => '42201-2222222-2'],
            ['name' => 'Usman Ahmed', 'email' => 'usman@example.com', 'blood_group' => 'O-', 'city' => 'Islamabad', 'cnic' => '61101-3333333-3'],
            ['name' => 'Zainab Bibi', 'email' => 'zainab@example.com', 'blood_group' => 'AB+', 'city' => 'Lahore', 'cnic' => '35202-4444444-4'],
            ['name' => 'Bilal Tariq', 'email' => 'bilal@example.com', 'blood_group' => 'O+', 'city' => 'Rawalpindi', 'cnic' => '37405-5555555-5'],
        ];

        $donorIds = [];
        foreach ($donors as $d) {
            $user = User::create([
                'name'               => $d['name'],
                'email'              => $d['email'],
                'password'           => Hash::make('password'),
                'role'               => 'donor',
                'blood_group'        => $d['blood_group'],
                'city'               => $d['city'],
                'phone'              => '03' . rand(100000000, 499999999),
                'whatsapp_number'    => '03' . rand(100000000, 499999999),
                'cnic'               => $d['cnic'],
                'last_donation_date' => rand(0, 1) ? Carbon::now()->subMonths(rand(1, 10)) : null,
            ]);
            $donorIds[] = $user->id;
        }

        // 3. Dummy Recipients (e.g. patients / hospital users)
        $recipients = [
            ['name' => 'Sara Malik', 'email' => 'sara@example.com', 'blood_group' => 'A+', 'city' => 'Lahore'],
            ['name' => 'Kamran Shah', 'email' => 'kamran@example.com', 'blood_group' => 'B-', 'city' => 'Karachi'],
            ['name' => 'Faisal Qureshi', 'email' => 'faisal@example.com', 'blood_group' => 'AB-', 'city' => 'Multan'],
        ];

        $recipientIds = [];
        foreach ($recipients as $r) {
            $user = User::create([
                'name'               => $r['name'],
                'email'              => $r['email'],
                'password'           => Hash::make('password'),
                'role'               => 'recipient',
                'blood_group'        => $r['blood_group'],
                'city'               => $r['city'],
                'phone'              => '03' . rand(100000000, 499999999),
                'whatsapp_number'    => '03' . rand(100000000, 499999999),
                'willing_to_donate'  => rand(0, 1),
            ]);
            $recipientIds[] = $user->id;
        }

        // 4. Blood Requests
        $requestsData = [
            ['patient' => 'Patient X', 'group' => 'O-', 'units' => 2, 'urgency' => 'Critical', 'status' => 'Pending', 'hospital' => 'Mayo Hospital, Lahore', 'user' => $recipientIds[0]],
            ['patient' => 'Patient Y', 'group' => 'B+', 'units' => 1, 'urgency' => 'High', 'status' => 'Pending', 'hospital' => 'Aga Khan, Karachi', 'user' => $recipientIds[1]],
            ['patient' => 'Patient Z', 'group' => 'A+', 'units' => 3, 'urgency' => 'Normal', 'status' => 'Approved', 'hospital' => 'Jinnah Hospital, Lahore', 'user' => $donorIds[0]],
            ['patient' => 'Patient W', 'group' => 'AB+', 'units' => 2, 'urgency' => 'Critical', 'status' => 'Fulfilled', 'hospital' => 'PIMS, Islamabad', 'user' => $recipientIds[2]],
        ];

        foreach ($requestsData as $r) {
            BloodRequest::create([
                'user_id'           => $r['user'],
                'patient_name'      => $r['patient'],
                'patient_cnic'      => rand(10000,99999).'-'.rand(1000000,9999999).'-'.rand(1,9),
                'blood_group'       => $r['group'],
                'units'             => $r['units'],
                'hospital_location' => $r['hospital'],
                'urgency_level'     => $r['urgency'],
                'status'            => $r['status'],
                'description'       => 'Need blood urgently for surgery.',
                'created_at'        => Carbon::now()->subDays(rand(1, 30)),
            ]);
        }

        // 5. Donations
        for ($i = 0; $i < 15; $i++) {
            Donation::create([
                'user_id'       => $donorIds[array_rand($donorIds)],
                'recipient_id'  => rand(0, 1) ? $recipientIds[array_rand($recipientIds)] : null, // 50% chance to be direct to a recipient
                'blood_group'   => ['A+','B+','O+','O-','AB+'][rand(0,4)],
                'units'         => rand(1, 2),
                'donation_date' => Carbon::now()->subDays(rand(1, 100)),
                'status'        => 'Completed',
            ]);
        }

    }
}
