<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table ='orders';
    protected $fillable =[
        'order_date','user_id' 
    ];

    public function user(){
        return $this->belongTo(users::class ,'user_id');
    }
    public function orders() {
        return $this->hasMany(OrderProduct::class, 'product_id');
    }
    public function products()
    {
        return $this->hasMany(OrderProduct::class);
    }
   
    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }
    use HasFactory;
}
