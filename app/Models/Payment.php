<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table ='payments';
    protected $fillable =[
        'total_price','order_product_id','date_payment'
    ];
    public function orderProduct() {
        return $this->hasOne(OrderProduct::class, 'id', 'order_product_id');
    }
    use HasFactory;
}
