<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class NotifyEligibleDonors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'donors:notify-eligible';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find all donors whose 90-day recovery rest has completed and notify them via email that they are eligible to save lives again.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $eligibleDonors = \App\Models\User::where(function($query) {
                $query->where('role', 'donor')
                      ->orWhere(function($q) {
                          $q->where('role', 'recipient')
                            ->where('willing_to_donate', true);
                      });
            })
            ->whereNotNull('last_donation_date')
            ->where('last_donation_date', '<=', now()->subDays(90))
            ->where('recovery_notified', false)
            ->get();

        $this->info("Found " . $eligibleDonors->count() . " eligible donors to notify.");

        foreach ($eligibleDonors as $donor) {
            try {
                \Illuminate\Support\Facades\Mail::send(
                    'emails.recovery_complete',
                    ['donor' => $donor],
                function ($message) use ($donor) {
                        $message
                            ->to($donor->email, $donor->name)
                            ->from(config('mail.from.address'), config('mail.from.name'))
                            ->replyTo(config('mail.from.address'), config('mail.from.name'))
                            ->subject("BloodConnect: You are Eligible to Donate Again! 🩸");
                    }
                );

                $donor->recovery_notified = true;
                $donor->save();

                $this->info("Notified donor: {$donor->name} ({$donor->email})");
            } catch (\Exception $e) {
                $this->error("Failed to notify donor {$donor->id}: " . $e->getMessage());
                \Illuminate\Support\Facades\Log::warning("NotifyEligibleDonors: email to donor {$donor->id} failed: " . $e->getMessage());
            }
        }

        $this->info("Done notifying eligible donors.");
        return 0;
    }
}
