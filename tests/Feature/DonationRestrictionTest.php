<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\BloodRequest;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DonationRestrictionTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use DatabaseTransactions;

    /**
     * Test that a donor in their 90-day recovery rest phase is blocked from donating.
     */
    public function test_resting_donor_is_blocked_from_donating()
    {
        // 1. Create a donor user in recovery resting phase (donated 45 days ago)
        $donor = User::create([
            'name'               => 'Test Resting Donor',
            'email'              => 'test_resting@bloodconnect.pk',
            'password'           => bcrypt('password'),
            'role'               => 'donor',
            'blood_group'        => 'O-',
            'city'               => 'Lahore',
            'phone'              => '03217654321',
            'last_donation_date' => now()->subDays(45),
            'recovery_notified'  => false,
        ]);

        // 2. Create a recipient user
        $recipient = User::create([
            'name'        => 'Test Recipient',
            'email'       => 'test_recipient@bloodconnect.pk',
            'password'    => bcrypt('password'),
            'role'        => 'recipient',
            'blood_group' => 'O-',
            'city'        => 'Lahore',
            'phone'       => '03211234567',
        ]);

        // 3. Create an active request for O- blood
        $bloodRequest = BloodRequest::create([
            'user_id'           => $recipient->id,
            'patient_name'      => 'Ahmad Ali',
            'blood_group'       => 'O-',
            'units'             => 2,
            'hospital_location' => 'Mayo Hospital, Lahore',
            'urgency_level'     => 'Critical',
            'status'            => 'Pending',
        ]);

        // 4. Act as the resting donor and try to confirm the donation
        $response = $this->actingAs($donor)
            ->post(route('donations.confirm'), [
                'blood_request_id' => $bloodRequest->id,
                'confirm_donation' => 'on',
            ]);

        // 5. Assert that the request is redirected back with an error flash message
        $response->assertStatus(302);
        $response->assertSessionHas('error');
        $this->assertStringContainsString('recovery resting phase', session('error'));

        // Assert that the request status is still Pending
        $this->assertEquals('Pending', $bloodRequest->fresh()->status);
    }

    /**
     * Test that an eligible donor (past 90-day recovery) can successfully donate.
     */
    public function test_eligible_donor_can_confirm_donation()
    {
        // 1. Create a donor user past their recovery resting phase (donated 95 days ago)
        $donor = User::create([
            'name'               => 'Test Eligible Donor',
            'email'              => 'test_eligible@bloodconnect.pk',
            'password'           => bcrypt('password'),
            'role'               => 'donor',
            'blood_group'        => 'O-',
            'city'               => 'Lahore',
            'phone'              => '03219876543',
            'last_donation_date' => now()->subDays(95),
            'recovery_notified'  => true, // previously notified
        ]);

        // 2. Create a recipient user
        $recipient = User::create([
            'name'        => 'Test Recipient 2',
            'email'       => 'test_recipient2@bloodconnect.pk',
            'password'    => bcrypt('password'),
            'role'        => 'recipient',
            'blood_group' => 'O-',
            'city'        => 'Lahore',
            'phone'       => '03211234568',
        ]);

        // 3. Create an active request for O- blood
        $bloodRequest = BloodRequest::create([
            'user_id'           => $recipient->id,
            'patient_name'      => 'Ali Raza',
            'blood_group'       => 'O-',
            'units'             => 1,
            'hospital_location' => 'Services Hospital, Lahore',
            'urgency_level'     => 'High',
            'status'            => 'Pending',
        ]);

        // 4. Act as the eligible donor and confirm the donation
        $response = $this->actingAs($donor)
            ->post(route('donations.confirm'), [
                'blood_request_id' => $bloodRequest->id,
                'confirm_donation' => 'on',
            ]);

        // 5. Assert that donation succeeds and redirects to dashboard with success message
        $response->assertStatus(302);
        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('success');

        // Assert that request status updated to Fulfilled
        $this->assertEquals('Fulfilled', $bloodRequest->fresh()->status);

        // Assert that donor profile last_donation_date is updated to today and recovery_notified reset to false
        $donorFresh = $donor->fresh();
        $this->assertEquals(now()->toDateString(), $donorFresh->last_donation_date->toDateString());
        $this->assertFalse($donorFresh->recovery_notified);
    }
}
