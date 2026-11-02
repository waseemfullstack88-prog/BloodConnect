<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DonorController;
use App\Http\Controllers\BloodRequestController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DonationHistoryController;

// Home Page
Route::get('/', function () {
    $donorCount = \App\Models\User::where('role', 'donor')->count();
    return view('welcome', compact('donorCount'));
});

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

// Blood Requests
Route::middleware(['auth'])->group(function () {
    Route::get('/requests/create', [BloodRequestController::class, 'create'])->name('requests.create');
    Route::post('/requests/store', [BloodRequestController::class, 'store'])->name('requests.store');

    // Notifications
    Route::post('/notifications/{id}/read', [DashboardController::class, 'markNotificationRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [DashboardController::class, 'markAllRead'])->name('notifications.readAll');

    // Donations
    Route::post('/donations/confirm', [\App\Http\Controllers\DonationController::class, 'confirm'])->name('donations.confirm');

    // Donation History
    Route::get('/donations/history', [DonationHistoryController::class, 'index'])->name('donations.history');

    // Profile
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});

// Donors Search (Public)
Route::get('/donors', [DonorController::class, 'index'])->name('donors.index');
Route::get('/donors/search', [DonorController::class, 'search'])->name('donors.search');

Route::get('/stories', function () {
    return view('stories.index');
})->name('stories.index');

// Local Mail Viewer (For Testing)
Route::get('/inbox', function () {
    $emails = \App\Models\EmailLog::orderBy('created_at', 'desc')->get();
    return view('mail-viewer', compact('emails'));
})->name('inbox');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::post('/users/{id}/update', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::get('/requests', [AdminController::class, 'requests'])->name('requests');
    Route::post('/requests/{id}/status', [AdminController::class, 'updateRequestStatus'])->name('requests.status');
    Route::get('/donations', [AdminController::class, 'donations'])->name('donations');
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::get('/reports/download', [AdminController::class, 'downloadReport'])->name('reports.download');
});

require __DIR__.'/auth.php';

