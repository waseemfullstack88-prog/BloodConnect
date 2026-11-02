<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BloodRequest;
use App\Models\User;
use App\Models\Donation;
use App\Models\DonorAlert;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Redirect admins to the new Admin Panel
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $compatibleGroups   = ['A+','A-','B+','B-','AB+','AB-','O+','O-'];
        $recipientRequests  = collect();
        $availableDonors    = collect();
        $donorNotifications = collect();
        $unreadCount        = 0;

        if ($user->role === 'donor') {
            // All pending blood requests (visible to every donor matching their blood group)
            $recipientRequests = BloodRequest::whereIn('status', ['Pending', 'pending'])
                ->where('blood_group', $user->blood_group)
                ->with('user')
                ->latest()
                ->get();

            // Custom donor alerts from our donor_alerts table
            $donorNotifications = DonorAlert::where('donor_id', $user->id)
                ->latest()
                ->take(15)
                ->get();

            $unreadCount = DonorAlert::where('donor_id', $user->id)
                ->where('is_read', false)
                ->count();

        } elseif ($user->role === 'recipient') {
            if (!empty($user->blood_group)) {
                $compatibleGroups = [$user->blood_group];
            }

            $availableDonorsQuery = User::where(function($query) {
                    $query->where('role', 'donor')
                          ->orWhere(function($q) {
                              $q->where('role', 'recipient')
                                ->where('willing_to_donate', true);
                          });
                });

            if (!empty($compatibleGroups)) {
                $availableDonorsQuery->whereIn('blood_group', $compatibleGroups);
            }

            $availableDonors = $availableDonorsQuery
                ->orderByRaw('CASE WHEN last_donation_date IS NULL THEN 0 ELSE 1 END ASC')
                ->orderBy('last_donation_date', 'ASC')
                ->get();
                
            // If they are willing to donate, they also receive alerts!
            if ($user->willing_to_donate) {
                $donorNotifications = DonorAlert::where('donor_id', $user->id)
                    ->latest()
                    ->take(15)
                    ->get();

                $unreadCount = DonorAlert::where('donor_id', $user->id)
                    ->where('is_read', false)
                    ->count();
            }
        }

        $totalDonationsCount = Donation::where('user_id', $user->id)->count();
        $totalRequestsCount  = BloodRequest::where('user_id', $user->id)->count();

        $recentRequests = BloodRequest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'user',
            'totalDonationsCount',
            'totalRequestsCount',
            'recentRequests',
            'recipientRequests',
            'availableDonors',
            'donorNotifications',
            'unreadCount',
            'compatibleGroups'
        ));
    }

    /**
     * Mark a single DonorAlert as read.
     */
    public function markNotificationRead(Request $request, $alertId)
    {
        $alert = DonorAlert::where('donor_id', Auth::id())
            ->where('id', $alertId)
            ->firstOrFail();

        $alert->markAsRead();

        return redirect()->back();
    }

    /**
     * Mark all unread DonorAlerts as read for the current donor.
     */
    public function markAllRead()
    {
        DonorAlert::where('donor_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }
}
