<?php

namespace Tests\Feature\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register()
    {
        $response = $this->post('/register', [
            'role'                  => 'donor',
            'name'                  => 'Test User',
            'email'                 => 'test@example.com',
            'password'              => 'password',
            'password_confirmation' => 'password',
            'blood_group'           => 'O+',
            'city'                  => 'Lahore',
            'phone'                 => '03211234567',
            'whatsapp_number'       => '03211234567',
            'gender'                => 'male',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('register.otp'));

        $otp = (string) session('registration_otp');

        $verifyResponse = $this->post(route('register.otp.verify'), [
            'otp' => $otp,
        ]);

        $verifyResponse->assertStatus(302);
        $verifyResponse->assertRedirect(route('login'));
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }
}
