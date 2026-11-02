<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class DonorController extends Controller
{
    /**
     * Display the donor listing.
     * Only shows eligible donors (never donated OR donated 3+ months ago).
     * Available (never donated) donors appear FIRST.
     */
    public function index()
    {
        $donors = $this->eligibleDonorsQuery()->get();
        return view('donors.index', compact('donors'));
    }

    /**
     * Search donors by blood group and/or city.
     */
    public function search(Request $request)
    {
        $bloodGroup = $request->input('blood_group');
        $city       = $request->input('city');
        $name       = $request->input('name');

        $query = $this->eligibleDonorsQuery();

        if ($bloodGroup) {
            $query->where('blood_group', $bloodGroup);
        }

        if ($city) {
            $cities = array_map('trim', explode(',', $city));
            $query->where(function ($q) use ($cities) {
                foreach ($cities as $c) {
                    $q->orWhere('city', 'LIKE', '%' . $c . '%');
                }
            });
        }

        if ($name) {
            $query->where('name', 'LIKE', '%' . trim($name) . '%');
        }

        $donors = $query->get();
        return view('donors.index', compact('donors', 'bloodGroup', 'city', 'name'));
    }

    /**
     * Base query: eligible donors sorted correctly.
     * - NULL last_donation_date → FIRST (never donated, always eligible)
     * - then oldest donation date first (longest since last donation)
     */
    private function eligibleDonorsQuery()
    {
        return User::where(function($query) {
                $query->where('role', 'donor')
                      ->orWhere(function($q) {
                          $q->where('role', 'recipient')
                            ->where('willing_to_donate', true);
                      });
            })
            ->withCount('donations')
            ->orderByRaw('CASE WHEN last_donation_date IS NULL THEN 0 ELSE 1 END ASC')
            ->orderBy('last_donation_date', 'ASC');
    }
}
