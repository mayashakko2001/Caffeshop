<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $fillable = [
        'catogery_id', 'image', 'quantity', 'price', 'description', 'title'
    ];

    public function orderProducts() { // استخدم اسمًا أكثر وضوحًا
        return $this->hasMany(OrderProduct::class, 'product_id'); // استخدم product_id كمفتاح خارجي
    }

    public function category() { // تأكد من استخدام category بدلاً من catogery
        return $this->belongsTo(Category::class, 'catogery_id'); // تأكد من استخدام Category
    }
}
