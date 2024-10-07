<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catogery extends Model
{
    protected $table ='catogeries';
    protected $fillable =[
        'name'
    ];
    public function product(){
        return $this->HasMany(products ::class ,'product_id');
    }
    use HasFactory;
}
