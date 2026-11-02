<?php

namespace App\Notifications;

use App\Models\BloodRequest;
use App\Models\DonorAlert;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

/**
 * BloodRequestAlert
 *
 * Custom notification system (PHP 8.0 compatible).
 * Bypasses Laravel's built-in Notification channels to avoid
 * brick/math enum incompatibility on PHP 8.0.
 *
 * Usage:
 *   BloodRequestAlert::dispatch($bloodRequest);
 *
 * This will:
 *   1. Find all donors with matching blood group
 *   2. Store a DonorAlert record for each donor (in-app bell)
 *   3. Send an HTML email to each donor (if SMTP is configured)
 */
class BloodRequestAlert
{
    public BloodRequest $bloodRequest;

    public function __construct(BloodRequest $bloodRequest)
    {
        $this->bloodRequest = $bloodRequest;
    }

    /**
     * Static entry point — find matching donors and alert them all.
     * Returns the count of donors notified.
     */
    public static function dispatch(BloodRequest $bloodRequest): int
    {
        $bloodRequest->loadMissing('user');

        // Find donors with SAME blood group
        $matchingDonors = User::where(function($query) {
                $query->where('role', 'donor')
                      ->orWhere(function($q) {
                          $q->where('role', 'recipient')
                            ->where('willing_to_donate', true);
                      });
            })
            ->where('blood_group', $bloodRequest->blood_group)
            ->where('id', '!=', $bloodRequest->user_id)
            ->get();

        $instance = new self($bloodRequest);

        foreach ($matchingDonors as $donor) {
            $instance->alertDonor($donor);
        }

        return $matchingDonors->count();
    }

    /**
     * Alert a single donor: store DB record + send email.
     */
    public function alertDonor(User $donor): void
    {
        // 1. Store in-app alert (always works, no external service needed)
        $this->storeAlert($donor);

        // 2. Send email (silently fails if SMTP not configured)
        $this->sendEmail($donor);
    }

    /**
     * Persist a DonorAlert row for the given donor.
     */
    private function storeAlert(User $donor): void
    {
        try {
            DonorAlert::create([
                'donor_id'           => $donor->id,
                'blood_request_id'   => $this->bloodRequest->id,
                'blood_group'        => $this->bloodRequest->blood_group,
                'urgency_level'      => $this->bloodRequest->urgency_level,
                'hospital_location'  => $this->bloodRequest->hospital_location,
                'patient_name'       => $this->bloodRequest->patient_name,
                'units'              => $this->bloodRequest->units,
                'requester_name'     => optional($this->bloodRequest->user)->name    ?? 'Unknown',
                'requester_phone'    => optional($this->bloodRequest->user)->phone    ?? '',
                'requester_whatsapp' => optional($this->bloodRequest->user)->whatsapp_number ?? '',
                'is_read'            => false,
            ]);
        } catch (\Exception $e) {
            Log::error('BloodRequestAlert: failed to store alert for donor ' . $donor->id . ': ' . $e->getMessage());
        }
    }

    /**
     * Send an HTML email to the donor.
     * Wrapped in try/catch — a missing SMTP config will not break the request flow.
     */
    private function sendEmail(User $donor): void
    {
        try {
            $req          = $this->bloodRequest;
            $requester    = $req->user;
            $urgencyColor = match ($req->urgency_level) {
                'Critical' => '#E63946',
                'High'     => '#f39c12',
                default    => '#2196F3',
            };

            Mail::send(
                'emails.blood_request_alert',
                [
                    'bloodRequest' => $req,
                    'requester'    => $requester,
                    'donor'        => $donor,
                    'urgencyColor' => $urgencyColor,
                ],
                function ($message) use ($donor, $req) {
                    $message
                        ->to($donor->email, $donor->name)
                        ->from(config('mail.from.address'), config('mail.from.name'))
                        ->replyTo(config('mail.from.address'), config('mail.from.name'))
                        ->subject("BloodConnect: Blood Request – {$req->blood_group} Needed at {$req->hospital_location}");
                }
            );
        } catch (\Exception $e) {
            // Log warning but do NOT rethrow — email failure is non-critical
            Log::warning('BloodRequestAlert: email to donor ' . $donor->id . ' failed: ' . $e->getMessage());
        }
    }
}
