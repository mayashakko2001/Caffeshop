<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $table='complaints';
    protected $fillable=[

        'description','user_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // Corrected to `belongsTo`
    }

    use HasFactory;
}
