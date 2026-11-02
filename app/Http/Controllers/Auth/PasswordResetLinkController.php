<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     * Looks up users by both email AND role to handle our composite-unique setup.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'role'  => ['required', 'string', 'in:donor,recipient'],
        ], [
            'role.required' => 'Please select whether you are a Donor or Recipient.',
            'role.in'       => 'Invalid account type selected.',
        ]);

        // Find the user by email + role (our composite-unique system)
        $user = User::where('email', $request->email)
                    ->where('role', $request->role)
                    ->first();

        if (! $user) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'No ' . ucfirst($request->role) . ' account found with that email address. Please check your account type and email.']);
        }

        // Generate a secure token
        $token = Str::random(64);

        // Clear any old reset token for this email+role combo
        DB::table('password_resets')->where('email', $request->email)->delete();

        // Store the token (hashed)
        DB::table('password_resets')->insert([
            'email'      => $request->email,
            'token'      => Hash::make($token),
            'created_at' => Carbon::now(),
        ]);

        // Build the reset URL with role embedded
        $resetUrl = route('password.reset', $token) . '?email=' . urlencode($request->email) . '&role=' . urlencode($request->role);

        // Send the custom reset email
        try {
            Mail::send('emails.password-reset', [
                'name'     => $user->name,
                'resetUrl' => $resetUrl,
                'role'     => $user->role,
            ], function ($message) use ($user) {
                $message->to($user->email, $user->name)
                        ->subject('BloodConnect – Password Reset Request')
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });
        } catch (\Exception $e) {
            logger('Password Reset Mail Error: ' . $e->getMessage());
            return back()->withInput($request->only('email'))
                         ->withErrors(['email' => 'Failed to send the reset email. Please try again later.']);
        }

        return back()->with('status', __('We have emailed your password reset link! Please also check your spam/junk folder.'));
    }
}
