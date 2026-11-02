<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Donation Model
 * Viva Question: What is a relationship in Eloquent?
 * Answer: In this model, a donation belongs to a specific user (the donor).
 */
class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'recipient_id',
        'hospital_id',
        'blood_group',
        'units',
        'donation_date',
        'status',
    ];

    /**
     * Get the donor associated with this donation record.
     * Viva Note: The 'belongsTo' relationship is used when a model belongs to another.
     */
    public function donor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the recipient associated with this donation record.
     */
    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }
}
