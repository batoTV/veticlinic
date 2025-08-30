<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
{
    use HasFactory;

    protected $fillable = [
        'pet_id',
        'checkup_date',
        'weight',
        'temperature',
        'attending_vet',
        'chief_complaints', // This was 'diagnosis'
        'diagnosis',
        'assessment',
        'plan',
    ];

    /**
     * Get the pet that the diagnosis belongs to.
     */
    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    /**
     * Get all of the images for the diagnosis.
     */
    public function images()
    {
        return $this->hasMany(DiagnosisImage::class);
    }
}
