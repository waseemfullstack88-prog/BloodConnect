<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonorAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_id',
        'blood_request_id',
        'blood_group',
        'urgency_level',
        'hospital_location',
        'patient_name',
        'units',
        'requester_name',
        'requester_phone',
        'requester_whatsapp',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    /**
     * The donor this alert belongs to.
     */
    public function donor()
    {
        return $this->belongsTo(User::class, 'donor_id');
    }

    /**
     * The blood request that triggered this alert.
     */
    public function bloodRequest()
    {
        return $this->belongsTo(BloodRequest::class);
    }

    /**
     * Scope: only unread alerts.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope: only read alerts.
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Mark this alert as read.
     */
    public function markAsRead(): void
    {
        $this->update(['is_read' => true]);
    }
}
