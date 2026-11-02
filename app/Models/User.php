<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * User Model
 * Viva Question: What is an Authenticatable class?
 * Answer: In Laravel, it's a class used for managing user authentication.
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     * Viva Note: These must match the migration fields.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'blood_group',
        'phone',
        'whatsapp_number',
        'city',
        'role',
        'cnic',
        'father_name',
        'hospital_name',
        'hospital_location',
        'dob',
        'last_donation_date',
        'needs_blood_for',
        'willing_to_donate',
        'patient_cnic',
        'recovery_notified',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at'  => 'datetime',
        'dob'                => 'date',
        'last_donation_date' => 'date',
        'willing_to_donate'  => 'boolean',
        'recovery_notified'  => 'boolean',
    ];

    /**
     * Check if this donor is currently eligible to donate
     * (never donated, or last donated 3+ months ago).
     */
    public function isEligibleToDonate(): bool
    {
        if ($this->role !== 'donor') {
            return false;
        }
        if (is_null($this->last_donation_date)) {
            return true;
        }
        return $this->last_donation_date->lte(now()->subDays(90));
    }

    /**
     * Get the blood requests created by the user.
     * Viva Question: What is a 'hasMany' relationship?
     * Answer: One user can have many blood requests (one-to-many relationship).
     */
    public function bloodRequests()
    {
        return $this->hasMany(BloodRequest::class);
    }

    /**
     * Get the donations performed by the user.
     */
    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    /**
     * Get the blood stocks maintained by the user (if hospital).
     */
    public function bloodStocks()
    {
        return $this->hasMany(BloodStock::class, 'hospital_id');
    }
}
