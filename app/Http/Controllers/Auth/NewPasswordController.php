<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Carbon\Carbon;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request)
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     * Uses our custom token + role-aware lookup.
     */
    public function store(Request $request)
    {
        $request->validate([
            'token'    => ['required'],
            'email'    => ['required', 'email'],
            'role'     => ['required', 'string', 'in:donor,recipient'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Find the user by email + role
        $user = User::where('email', $request->email)
                    ->where('role', $request->role)
                    ->first();

        if (! $user) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'No ' . ucfirst($request->role) . ' account found with that email address.']);
        }

        // Find the reset record (there may be multiple rows with same email)
        $resetRecord = DB::table('password_resets')
                          ->where('email', $request->email)
                          ->orderBy('created_at', 'desc')
                          ->first();

        if (! $resetRecord) {
            return back()->withInput($request->only('email'))
                         ->withErrors(['email' => 'This password reset token is invalid or has expired.']);
        }

        // Check token age (60 minutes)
        if (Carbon::parse($resetRecord->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_resets')->where('email', $request->email)->delete();
            return back()->withInput($request->only('email'))
                         ->withErrors(['email' => 'This password reset link has expired. Please request a new one.']);
        }

        // Verify the hashed token
        if (! Hash::check($request->token, $resetRecord->token)) {
            return back()->withInput($request->only('email'))
                         ->withErrors(['email' => 'This password reset token is invalid.']);
        }

        // Update the password
        $user->forceFill([
            'password'       => Hash::make($request->password),
            'remember_token' => Str::random(60),
        ])->save();

        // Clean up the reset token
        DB::table('password_resets')->where('email', $request->email)->delete();

        event(new PasswordReset($user));

        return redirect()->route('login')
                         ->with('success', '✅ Your password has been reset successfully! Please login with your new password.');
    }
}
