<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show the profile edit form.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user's profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'blood_group'       => 'required|string',
            'city'              => 'required|string|max:100',
            'phone'             => ['required', 'string', 'regex:/^03[0-9]{9}$/'],
            'whatsapp_number'   => ['required', 'string', 'regex:/^03[0-9]{9}$/'],
            'dob'               => 'nullable|date',
            'cnic'              => ['nullable', 'string', 'regex:/^[0-9]{5}-[0-9]{7}-[0-9]{1}$/', Rule::unique('users', 'cnic')->ignore($user->id)],
            'father_name'       => 'nullable|string|max:255',
            'hospital_name'     => 'nullable|string|max:255',
            'hospital_location' => 'nullable|string|max:255',
        ], [
            'phone.regex'           => 'Phone must be in format: 03xxxxxxxxx (11 digits).',
            'whatsapp_number.regex' => 'WhatsApp must be in format: 03xxxxxxxxx (11 digits).',
            'cnic.regex'            => 'CNIC must be in format: xxxxx-xxxxxxx-x.',
            'cnic.unique'           => 'This CNIC is already registered with another account.',
        ]);

        $user->update([
            'city'              => $validated['city'],
            'phone'             => $validated['phone'],
            'whatsapp_number'   => $validated['whatsapp_number'],
            'hospital_name'     => $validated['hospital_name'] ?? null,
            'hospital_location' => $validated['hospital_location'] ?? null,
        ]);

        return redirect()->route('profile.edit')->with('success', '✅ Profile updated successfully!');
    }
}
