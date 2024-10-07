<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table='addresses';
protected $fillable=[

    'area','city','country','user_id'
];
public function user(){
    return $this->belongTo(users::class ,'user_id');
}
    use HasFactory;
}
