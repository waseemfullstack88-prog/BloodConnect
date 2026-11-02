<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BloodRequest;
use App\Notifications\BloodRequestAlert;
use Illuminate\Support\Facades\Auth;

class BloodRequestController extends Controller
{
    public function create()
    {
        return view('requests.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_name' => 'required|max:255',
            'patient_cnic' => ['required', 'string', 'regex:/^[0-9]{5}-[0-9]{7}-[0-9]{1}$/'],
            'blood_group'  => 'required',
            'units'        => 'required|numeric|min:1|max:10',
            'location'     => 'required|max:255',
            'urgency'      => 'required|in:Normal,High,Critical',
            'description'  => 'nullable|max:1000',
        ]);

        $patientCnic = $validated['patient_cnic'];

        // 1. Same-day duplicate check (accidental double submit)
        $todayDuplicate = BloodRequest::where('patient_cnic', $patientCnic)
            ->whereDate('created_at', now()->toDateString())
            ->exists();
        if ($todayDuplicate) {
            return redirect()->back()->withErrors([
                'patient_cnic' => 'A blood request for this patient CNIC has already been submitted today. To prevent accidental double requests, duplicate submissions are blocked for 24 hours.'
            ])->withInput();
        }

        // 2. Single Patient mapping: 1 CNIC must uniquely map to 1 Patient Name
        $existingRequest = BloodRequest::where('patient_cnic', $patientCnic)->first();
        if ($existingRequest && strtolower(trim($existingRequest->patient_name)) !== strtolower(trim($validated['patient_name']))) {
            return redirect()->back()->withErrors([
                'patient_cnic' => "This CNIC is already registered in our system for patient name: '{$existingRequest->patient_name}'. It cannot be used for a different patient."
            ])->withInput();
        }

        // 3. Active Emergency request limit: 1 Patient can only have exactly 1 active emergency request
        $hasActiveRequest = BloodRequest::where('patient_cnic', $patientCnic)
            ->whereIn('status', ['Pending', 'pending'])
            ->exists();
        if ($hasActiveRequest) {
            return redirect()->back()->withErrors([
                'patient_cnic' => 'This patient already has an active emergency blood request pending in the system. Each patient is limited to exactly one active emergency request at a time.'
            ])->withInput();
        }

        // 4. Save the blood request
        $bloodRequest = BloodRequest::create([
            'user_id'           => Auth::id(),
            'patient_name'      => $validated['patient_name'],
            'patient_cnic'      => $patientCnic,
            'blood_group'       => $validated['blood_group'],
            'units'             => $validated['units'],
            'hospital_location' => $validated['location'],
            'urgency_level'     => $validated['urgency'],
            'description'       => $validated['description'] ?? null,
            'status'            => 'Pending',
        ]);

        // 2. Eager-load the requester so the notification class can access it
        $bloodRequest->load('user');

        // 3. Dispatch alerts to all matching donors (DB record + email)
        //    BloodRequestAlert::dispatch() handles finding donors internally.
        $notifiedCount = BloodRequestAlert::dispatch($bloodRequest);

        // 4. Redirect with feedback
        return redirect()->route('requests.create')
            ->with('success', "✅ Blood request posted! {$notifiedCount} donor(s) with {$bloodRequest->blood_group} blood group have been notified.");
    }
}
