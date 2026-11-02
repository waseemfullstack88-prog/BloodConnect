<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationOtpMail;
use Illuminate\Validation\Rule;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'role'              => ['required', 'string', 'in:donor,recipient'],
            'name'              => ['required', 'string', 'max:255'],
            'email'             => ['required', 'string', 'email', 'max:255', Rule::unique('users')->where('role', $request->role)],
            'password'          => ['required', 'confirmed', 'min:6'],
            'blood_group'       => ['required', 'string'],
            'city'              => ['required', 'string', 'max:100'],
            'phone'             => ['required', 'string', 'regex:/^03[0-9]{9}$/'],
            'whatsapp_number'   => ['required', 'string', 'regex:/^03[0-9]{9}$/'],
            'gender'            => ['required', 'string', 'in:male,female,other'],
            'dob'               => ['nullable', 'date'],
            'cnic'              => ['nullable', 'string', 'regex:/^[0-9]{5}-[0-9]{7}-[0-9]{1}$/', Rule::unique('users', 'cnic')->where('role', $request->role)],
            'father_name'       => ['nullable', 'string', 'max:255'],
            'hospital_name'     => ['nullable', 'string', 'max:255'],
            'hospital_location' => ['nullable', 'string', 'max:255'],
            'needs_blood_for'   => ['nullable', 'string', 'in:self,family,friend'],
            'patient_cnic'      => ['nullable', 'required_if:needs_blood_for,family,friend', 'string', 'regex:/^[0-9]{5}-[0-9]{7}-[0-9]{1}$/'],
            'willing_to_donate' => ['nullable', 'string', 'in:yes,no'],
        ], [
            'name.required'            => 'Full name is required.',
            'email.required'           => 'Email address is required.',
            'email.unique'             => 'This email is already registered. Please login instead.',
            'password.required'        => 'Password is required.',
            'password.confirmed'       => 'Passwords do not match. Please re-enter.',
            'password.min'             => 'Password must be at least 6 characters.',
            'blood_group.required'     => 'Please select your blood group.',
            'city.required'            => 'City is required.',
            'phone.required'           => 'Phone number is required.',
            'phone.regex'              => 'Phone must be in format: 03xxxxxxxxx (11 digits, starts with 03).',
            'whatsapp_number.required' => 'WhatsApp number is required.',
            'whatsapp_number.regex'    => 'WhatsApp must be in format: 03xxxxxxxxx (11 digits, starts with 03).',
            'cnic.unique'              => 'This CNIC is already registered with another account.',
        ]);

        $willingToDonate = $request->input('willing_to_donate') === 'yes';
        $role = $request->role; // Strictly honor the requested role to maintain isolation

        // Generate 6-digit OTP
        $otp = rand(100000, 999999);
        logger("Generated Registration OTP: {$otp} for {$request->email}");

        // Store registration details and OTP in session
        session()->put('registration_data', [
            'name'               => $request->name,
            'email'              => $request->email,
            'password'           => Hash::make($request->password),
            'blood_group'        => $request->blood_group,
            'city'               => $request->city,
            'phone'              => $request->phone,
            'whatsapp_number'    => $request->whatsapp_number,
            'gender'             => $request->gender,
            'dob'                => $request->dob,
            'cnic'               => $request->cnic,
            'father_name'        => $request->father_name,
            'hospital_name'      => $request->hospital_name,
            'hospital_location'  => $request->hospital_location,
            'role'               => $role,
            'needs_blood_for'    => $request->needs_blood_for,
            'willing_to_donate'  => $willingToDonate,
            'patient_cnic'       => ($request->needs_blood_for === 'family' || $request->needs_blood_for === 'friend') ? $request->patient_cnic : null,
        ]);
        session()->put('registration_otp', $otp);
        session()->put('registration_otp_expires_at', now()->addMinutes(15));

        // Send OTP via Email
        try {
            Mail::to($request->email)->send(new RegistrationOtpMail($request->name, $otp));
        } catch (\Exception $e) {
            logger('Registration OTP Mail Error: ' . $e->getMessage());
        }

        return redirect()->route('register.otp')->with('success', '🔑 A 6-digit verification code has been sent to your email.');
    }

    /**
     * Display the OTP verification form.
     */
    public function showOtpForm()
    {
        if (!session()->has('registration_data') || !session()->has('registration_otp')) {
            return redirect()->route('register')->withErrors(['registration' => 'No active registration request found. Please fill the form first.']);
        }

        return view('auth.verify-otp');
    }

    /**
     * Verify the entered OTP and register the user.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ], [
            'otp.required' => 'Please enter the 6-digit OTP code.',
            'otp.size'     => 'The OTP must be exactly 6 digits.',
        ]);

        if (!session()->has('registration_data') || !session()->has('registration_otp')) {
            return redirect()->route('register')->withErrors(['registration' => 'Your session has expired. Please register again.']);
        }

        $sessionOtp   = session()->get('registration_otp');
        $expiresAt    = session()->get('registration_otp_expires_at');
        $registerData = session()->get('registration_data');

        // Check expiration
        if (now()->gt($expiresAt)) {
            return redirect()->route('register.otp')->withErrors(['otp' => 'Your OTP has expired. Please click "Resend OTP" to request a new one.']);
        }

        // Validate code
        if (trim($request->otp) !== trim($sessionOtp)) {
            return redirect()->route('register.otp')->withErrors(['otp' => 'Invalid OTP code. Please verify the code from your email and try again.']);
        }

        // Create the user in the database
        $user = User::create($registerData);

        // Auto-create donor account if recipient is willing to donate
        if ($user->role === 'recipient' && $user->willing_to_donate) {
            $existingDonor = User::where('email', $user->email)->where('role', 'donor')->first();
            if (!$existingDonor) {
                $donorData = $registerData;
                $donorData['role'] = 'donor';
                $donorData['needs_blood_for'] = null;
                $donorData['patient_cnic'] = null;
                $donorData['hospital_name'] = null;
                $donorData['hospital_location'] = null;
                $donorData['willing_to_donate'] = false;
                User::create($donorData);
            }
        }

        // Clear registration session data
        session()->forget(['registration_data', 'registration_otp', 'registration_otp_expires_at']);

        $successMessage = ($user->role === 'recipient' && $user->willing_to_donate)
            ? '✅ OTP Verified! Registered as Recipient + Donor successfully! Please login to continue.'
            : '✅ OTP Verified! Registration successful! Please login to continue.';

        return redirect()->route('login')->with('success', $successMessage);
    }

    /**
     * Resend a fresh OTP code to the user.
     */
    public function resendOtp(Request $request)
    {
        if (!session()->has('registration_data')) {
            return redirect()->route('register')->withErrors(['registration' => 'No registration session found. Please register again.']);
        }

        $registerData = session()->get('registration_data');
        $otp          = rand(100000, 999999);
        logger("Generated Registration OTP (Resent): {$otp} for {$registerData['email']}");

        // Update session
        session()->put('registration_otp', $otp);
        session()->put('registration_otp_expires_at', now()->addMinutes(15));

        // Resend email
        try {
            Mail::to($registerData['email'])->send(new RegistrationOtpMail($registerData['name'], $otp));
        } catch (\Exception $e) {
            logger('Resend Registration OTP Mail Error: ' . $e->getMessage());
        }

        return redirect()->route('register.otp')->with('success', '🔄 A fresh verification OTP has been sent to your email.');
    }
}
