<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * BloodRequest Model
 * Viva Question: What is Eloquent ORM?
 * Answer: Eloquent is an object-relational mapper (ORM) that makes it 
 * pleasant to interact with your database. 
 * Each database table has a corresponding "Model" that is used to interact with that table.
 */
class BloodRequest extends Model
{
    use HasFactory;

    // Viva Note: $fillable property defines which fields can be mass-assigned.
    protected $fillable = [
        'user_id',
        'patient_name',
        'patient_cnic',
        'blood_group',
        'units',
        'hospital_location',
        'urgency_level',
        'status',
        'description',
    ];

    /**
     * Get the user who made the blood request.
     * Viva Question: What is a relationship in Eloquent?
     * Answer: Relationships allows linking one model to another. 
     * In this case, a blood request belongs to a specific user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
