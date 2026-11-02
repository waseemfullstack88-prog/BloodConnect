<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Donation;

class DonationHistoryController extends Controller
{
    /**
     * Display the user's donation history with summary stats.
     */
    public function index()
    {
        $user = Auth::user();

        $donations = Donation::where('user_id', $user->id)
            ->orderBy('donation_date', 'desc')
            ->get();

        $totalDonations = $donations->count();
        $totalUnits     = $donations->sum('units');
        $lastDonation   = $user->last_donation_date;
        $livesImpacted  = $totalUnits * 3; // Each unit can save up to 3 lives

        return view('donations.history', compact('donations', 'totalDonations', 'totalUnits', 'lastDonation', 'livesImpacted'));
    }
}
