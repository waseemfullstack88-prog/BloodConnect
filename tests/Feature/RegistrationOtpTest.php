<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Mail\RegistrationOtpMail;

class RegistrationOtpTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use DatabaseTransactions;

    /**
     * Test that registering redirects to OTP verification page and emails OTP.
     */
    public function test_registration_initiates_otp_verification()
    {
        Mail::fake();

        $response = $this->post('/register', [
            'role'              => 'donor',
            'name'              => 'Kamran Khan',
            'email'             => 'kamran@example.com',
            'password'          => 'password',
            'password_confirmation' => 'password',
            'blood_group'       => 'B+',
            'city'              => 'Lahore',
            'phone'             => '03123456789',
            'whatsapp_number'   => '03123456789',
            'gender'            => 'male',
        ]);

        // 1. Assert redirection to the OTP page
        $response->assertStatus(302);
        $response->assertRedirect(route('register.otp'));

        // 2. Assert session contains OTP and registration data
        $this->assertTrue(session()->has('registration_data'));
        $this->assertTrue(session()->has('registration_otp'));
        $this->assertEquals('kamran@example.com', session('registration_data.email'));

        // 3. Assert OTP email was sent
        Mail::assertSent(RegistrationOtpMail::class, function ($mail) {
            return $mail->hasTo('kamran@example.com') && $mail->name === 'Kamran Khan';
        });

        // 4. Assert user is NOT yet in the database
        $this->assertDatabaseMissing('users', ['email' => 'kamran@example.com']);
    }

    /**
     * Test that invalid OTP is rejected.
     */
    public function test_invalid_otp_is_rejected()
    {
        // Place fake registration in session
        session()->put('registration_data', [
            'name' => 'Faisal Ali',
            'email' => 'faisal@example.com',
            'password' => 'password',
            'role' => 'recipient',
            'blood_group' => 'A-',
            'city' => 'Multan',
            'phone' => '03112233445',
            'whatsapp_number' => '03112233445',
            'gender' => 'male',
        ]);
        session()->put('registration_otp', '123456');
        session()->put('registration_otp_expires_at', now()->addMinutes(15));

        // Submit wrong OTP
        $response = $this->post(route('register.otp.verify'), [
            'otp' => '999999',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('otp');
        $this->assertDatabaseMissing('users', ['email' => 'faisal@example.com']);
    }

    /**
     * Test that correct OTP successfully registers and clears session.
     */
    public function test_correct_otp_registers_user()
    {
        // Place fake registration in session
        session()->put('registration_data', [
            'name' => 'Faisal Ali',
            'email' => 'faisal@example.com',
            'password' => 'password',
            'role' => 'recipient',
            'blood_group' => 'A-',
            'city' => 'Multan',
            'phone' => '03112233445',
            'whatsapp_number' => '03112233445',
            'gender' => 'male',
        ]);
        session()->put('registration_otp', '123456');
        session()->put('registration_otp_expires_at', now()->addMinutes(15));

        // Submit correct OTP
        $response = $this->post(route('register.otp.verify'), [
            'otp' => '123456',
        ]);

        // 1. Assert redirection to login with success message
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success');

        // 2. Assert user is created in database
        $this->assertDatabaseHas('users', ['email' => 'faisal@example.com']);

        // 3. Assert session data is cleared
        $this->assertFalse(session()->has('registration_data'));
        $this->assertFalse(session()->has('registration_otp'));
    }

    /**
     * Test that resending OTP generates new code and emails it.
     */
    public function test_resending_otp_updates_session_and_emails()
    {
        Mail::fake();

        session()->put('registration_data', [
            'name' => 'Faisal Ali',
            'email' => 'faisal@example.com',
        ]);
        session()->put('registration_otp', '111111');

        $response = $this->post(route('register.otp.resend'));

        $response->assertStatus(302);
        $response->assertRedirect(route('register.otp'));

        // Assert session updated with new OTP
        $this->assertNotEquals('111111', session('registration_otp'));

        // Assert fresh email sent
        Mail::assertSent(RegistrationOtpMail::class, function ($mail) {
            return $mail->hasTo('faisal@example.com');
        });
    }
}
