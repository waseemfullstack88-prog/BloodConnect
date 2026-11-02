<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\BloodRequest;
use App\Models\Donation;
use App\Models\DonorAlert;

class AdminController extends Controller
{
    /**
     * Admin Dashboard — analytics overview with Chart.js data.
     */
    public function index()
    {
        $totalDonors     = User::where('role', 'donor')->count();
        $totalRecipients = User::where('role', 'recipient')->count();
        $totalRequests   = BloodRequest::count();
        $totalDonations  = Donation::count();
        $pendingRequests = BloodRequest::where('status', 'Pending')->count();
        $fulfilledRequests = BloodRequest::where('status', 'Fulfilled')->count();

        // Blood group distribution for chart
        $bloodGroupData = User::whereIn('role', ['donor', 'recipient'])
            ->select('blood_group', DB::raw('count(*) as total'))
            ->groupBy('blood_group')
            ->orderBy('blood_group')
            ->pluck('total', 'blood_group')
            ->toArray();

        // Monthly donations trend (last 12 months)
        $monthlyDonations = Donation::select(
                DB::raw("DATE_FORMAT(donation_date, '%Y-%m') as month"),
                DB::raw('count(*) as total')
            )
            ->where('donation_date', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Monthly requests trend (last 12 months)
        $monthlyRequests = BloodRequest::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('count(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Recent activity
        $recentUsers    = User::latest()->take(5)->get();
        $recentRequests = BloodRequest::with('user')->latest()->take(5)->get();
        $recentDonations = Donation::with('donor')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalDonors', 'totalRecipients', 'totalRequests', 'totalDonations',
            'pendingRequests', 'fulfilledRequests',
            'bloodGroupData', 'monthlyDonations', 'monthlyRequests',
            'recentUsers', 'recentRequests', 'recentDonations'
        ));
    }

    /**
     * List all users with search/filter.
     */
    public function users(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        if ($request->filled('blood_group')) {
            $query->where('blood_group', $request->blood_group);
        }
        if ($request->filled('city')) {
            $query->where('city', 'LIKE', '%' . $request->city . '%');
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('email', 'LIKE', '%' . $search . '%')
                  ->orWhere('cnic', 'LIKE', '%' . $search . '%');
            });
        }

        $users = $query->latest()->paginate(20);
        return view('admin.users', compact('users'));
    }

    /**
     * Show edit form for a user.
     */
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit-user', compact('user'));
    }

    /**
     * Update a user's details.
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'               => 'required|string|max:255',
            'email'              => 'required|email|max:255',
            'role'               => 'required|in:donor,recipient,admin',
            'blood_group'        => 'nullable|string',
            'city'               => 'nullable|string|max:100',
            'phone'              => 'nullable|string|max:20',
            'whatsapp_number'    => 'nullable|string|max:20',
            'cnic'               => 'nullable|string|max:20',
            'father_name'        => 'nullable|string|max:255',
            'gender'             => 'nullable|in:Male,Female,Other',
            'dob'                => 'nullable|date',
            'hospital_name'      => 'nullable|string|max:255',
            'hospital_location'  => 'nullable|string|max:255',
            'last_donation_date' => 'nullable|date',
            'willing_to_donate'  => 'nullable|boolean',
            'password'           => 'nullable|string|min:8|confirmed',
        ]);

        // Only update password if provided
        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['willing_to_donate'] = $request->has('willing_to_donate') ? 1 : 0;

        $user->update($validated);

        return redirect()->route('admin.users')->with('success', "User '{$user->name}' updated successfully.");
    }

    /**
     * Delete a user — cascades all related records.
     */
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        $name = $user->name;

        // Remove all related data so the system stays consistent
        DB::transaction(function () use ($user) {
            // Donations where user is the donor
            Donation::where('user_id', $user->id)->delete();

            // Donations where user is the recipient
            Donation::where('recipient_id', $user->id)->delete();

            // Blood requests submitted by this user
            BloodRequest::where('user_id', $user->id)->delete();

            // Donor alerts linked to this user
            DonorAlert::where('donor_id', $user->id)->delete();

            // Laravel notifications
            DB::table('notifications')->where('notifiable_id', $user->id)->delete();

            // Finally remove the user
            $user->delete();
        });

        return redirect()->route('admin.users')->with('success', "User '{$name}' and all related records have been permanently deleted.");
    }

    /**
     * List all blood requests.
     */
    public function requests(Request $request)
    {
        $query = BloodRequest::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('blood_group')) {
            $query->where('blood_group', $request->blood_group);
        }
        if ($request->filled('urgency')) {
            $query->where('urgency_level', $request->urgency);
        }

        $requests = $query->latest()->paginate(20);
        return view('admin.requests', compact('requests'));
    }

    /**
     * Update a blood request's status.
     */
    public function updateRequestStatus(Request $request, $id)
    {
        $bloodRequest = BloodRequest::findOrFail($id);
        $request->validate(['status' => 'required|in:Pending,Approved,Fulfilled,Rejected']);

        $bloodRequest->status = $request->status;
        $bloodRequest->save();

        // If rejected, clean up related donor alerts
        if ($request->status === 'Rejected' || $request->status === 'Fulfilled') {
            DonorAlert::where('blood_request_id', $id)->delete();
        }

        return redirect()->back()->with('success', "Request #{$id} status updated to '{$request->status}'.");
    }

    /**
     * List all donations.
     */
    public function donations(Request $request)
    {
        $query = Donation::with(['donor', 'recipient']);

        if ($request->filled('blood_group')) {
            $query->where('blood_group', $request->blood_group);
        }

        $donations = $query->latest()->paginate(20);
        return view('admin.donations', compact('donations'));
    }

    /**
     * Reports page.
     */
    public function reports()
    {
        return view('admin.reports');
    }

    /**
     * Download CSV report.
     */
    public function downloadReport(Request $request)
    {
        $type = $request->input('type', 'donors');
        $from = $request->input('from');
        $to   = $request->input('to');

        $filename = "bloodconnect_{$type}_report_" . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($type, $from, $to) {
            $file = fopen('php://output', 'w');

            switch ($type) {
                case 'donors':
                    fputcsv($file, ['ID', 'Name', 'Email', 'Blood Group', 'City', 'Phone', 'WhatsApp', 'CNIC', 'Last Donation', 'Registered']);
                    $query = User::where('role', 'donor');
                    if ($from) $query->whereDate('created_at', '>=', $from);
                    if ($to) $query->whereDate('created_at', '<=', $to);
                    foreach ($query->cursor() as $user) {
                        fputcsv($file, [
                            $user->id, $user->name, $user->email, $user->blood_group,
                            $user->city, $user->phone, $user->whatsapp_number, $user->cnic,
                            $user->last_donation_date ? $user->last_donation_date->format('Y-m-d') : 'Never',
                            $user->created_at->format('Y-m-d'),
                        ]);
                    }
                    break;

                case 'recipients':
                    fputcsv($file, ['ID', 'Name', 'Email', 'Blood Group', 'City', 'Phone', 'Hospital', 'Willing to Donate', 'Registered']);
                    $query = User::where('role', 'recipient');
                    if ($from) $query->whereDate('created_at', '>=', $from);
                    if ($to) $query->whereDate('created_at', '<=', $to);
                    foreach ($query->cursor() as $user) {
                        fputcsv($file, [
                            $user->id, $user->name, $user->email, $user->blood_group,
                            $user->city, $user->phone, $user->hospital_name,
                            $user->willing_to_donate ? 'Yes' : 'No',
                            $user->created_at->format('Y-m-d'),
                        ]);
                    }
                    break;

                case 'requests':
                    fputcsv($file, ['ID', 'Patient Name', 'Patient CNIC', 'Blood Group', 'Units', 'Hospital', 'Urgency', 'Status', 'Requested By', 'Date']);
                    $query = BloodRequest::with('user');
                    if ($from) $query->whereDate('created_at', '>=', $from);
                    if ($to) $query->whereDate('created_at', '<=', $to);
                    foreach ($query->cursor() as $req) {
                        fputcsv($file, [
                            $req->id, $req->patient_name, $req->patient_cnic ?? 'N/A', $req->blood_group,
                            $req->units, $req->hospital_location, $req->urgency_level, $req->status,
                            optional($req->user)->name ?? 'Unknown',
                            $req->created_at->format('Y-m-d'),
                        ]);
                    }
                    break;

                case 'donations':
                    fputcsv($file, ['ID', 'Donor Name', 'Blood Group', 'Units', 'Donation Date', 'Status']);
                    $query = Donation::with('donor');
                    if ($from) $query->whereDate('donation_date', '>=', $from);
                    if ($to) $query->whereDate('donation_date', '<=', $to);
                    foreach ($query->cursor() as $donation) {
                        fputcsv($file, [
                            $donation->id, optional($donation->donor)->name ?? 'Unknown',
                            $donation->blood_group, $donation->units,
                            $donation->donation_date, $donation->status,
                        ]);
                    }
                    break;
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
