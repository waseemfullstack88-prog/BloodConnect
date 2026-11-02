<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BloodRequest;
use App\Models\Donation;
use App\Models\DonorAlert;
use Illuminate\Support\Facades\Auth;

class DonationController extends Controller
{
    /**
     * Handle the confirmation of a blood donation by a donor.
     */
    public function confirm(Request $request)
    {
        $request->validate([
            'blood_request_id' => 'required|exists:blood_requests,id',
            'confirm_donation' => 'required|accepted', // ensure they checked the box
        ]);

        $donor = Auth::user();

        // Allow 'donor' role OR 'recipient' who is willing to donate
        if ($donor->role !== 'donor' && !$donor->willing_to_donate) {
            return redirect()->back()->with('error', 'Only donors or willing recipients can confirm a donation.');
        }

        // Strict 90-day recovery restriction backend check
        if ($donor->last_donation_date && $donor->last_donation_date->gt(now()->subDays(90))) {
            $daysRemaining = now()->diffInDays($donor->last_donation_date->copy()->addDays(90));
            return redirect()->back()->with('error', "Action Blocked: You are currently in your 90-day recovery resting phase. You can donate again in {$daysRemaining} days.");
        }

        $bloodRequest = BloodRequest::findOrFail($request->blood_request_id);

        // Optional: Check if already fulfilled
        if ($bloodRequest->status === 'Fulfilled') {
            return redirect()->back()->with('error', 'This request has already been fulfilled.');
        }

        // Strict Blood Group Check: Donor's blood group must match request blood group exactly
        if ($donor->blood_group !== $bloodRequest->blood_group) {
            return redirect()->back()->with('error', "Your blood group ({$donor->blood_group}) does not match the requested blood group ({$bloodRequest->blood_group}). You can only donate to recipients with your matching blood group.");
        }

        // 1. Update BloodRequest Status
        $bloodRequest->status = 'Fulfilled';
        $bloodRequest->save();

        // 2. Create Donation Record
        Donation::create([
            'user_id'       => $donor->id,
            'recipient_id'  => $bloodRequest->user_id,
            'blood_group'   => $donor->blood_group,
            'units'         => $bloodRequest->units,
            'donation_date' => now()->toDateString(),
            'status'        => 'Completed',
        ]);

        // 3. Update Donor's Profile (last_donation_date and reset recovery notification flag)
        $donor->last_donation_date = now();
        $donor->recovery_notified = false;
        $donor->save();

        // 4. Clean up ALL DonorAlerts related to this request across the entire system
        // (So it instantly disappears from every donor's critical alert feed)
        DonorAlert::where('blood_request_id', $bloodRequest->id)->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Thank you for your donation! Your profile has been updated and the blood request is marked as fulfilled.');
    }
}
