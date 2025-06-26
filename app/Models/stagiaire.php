<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Stagiaire extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'image',
        'user_id',
        'image',
        'date_naissance',
        'date_debut',
        'description',
        'date_fin',
        'telephone',
        'status',
        'cv',
        'attestation_de_stage',


    ];

    public function getStatusAttribute($value)
    {
        if ($value === 'accepte' && $this->date_fin && Carbon::parse($this->date_fin)->lte(Carbon::now())) {
            return 'termine';
        }

        return $value;
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
