<?php

namespace Tests\Unit;

use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;

/**
 * Pure unit tests for User::isEligibleToDonate() — the 90-day recovery
 * rule that gates every donation confirmation in BloodConnect. These
 * instantiate User in memory (no database) so they exercise only the
 * eligibility logic itself.
 */
class DonorEligibilityTest extends TestCase
{
    public function test_donor_who_never_donated_is_eligible(): void
    {
        $donor = new User(['role' => 'donor', 'last_donation_date' => null]);

        $this->assertTrue($donor->isEligibleToDonate());
    }

    public function test_donor_past_90_day_recovery_window_is_eligible(): void
    {
        $donor = new User([
            'role' => 'donor',
            'last_donation_date' => Carbon::now()->subDays(91)->toDateString(),
        ]);

        $this->assertTrue($donor->isEligibleToDonate());
    }

    public function test_donor_exactly_90_days_ago_is_eligible(): void
    {
        $donor = new User([
            'role' => 'donor',
            'last_donation_date' => Carbon::now()->subDays(90)->toDateString(),
        ]);

        $this->assertTrue($donor->isEligibleToDonate());
    }

    public function test_donor_inside_90_day_recovery_window_is_blocked(): void
    {
        $donor = new User([
            'role' => 'donor',
            'last_donation_date' => Carbon::now()->subDays(10)->toDateString(),
        ]);

        $this->assertFalse($donor->isEligibleToDonate());
    }

    public function test_recipient_role_is_never_eligible_to_donate(): void
    {
        $recipient = new User([
            'role' => 'recipient',
            'last_donation_date' => null,
        ]);

        $this->assertFalse($recipient->isEligibleToDonate());
    }
}
