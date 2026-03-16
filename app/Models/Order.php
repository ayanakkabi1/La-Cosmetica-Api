<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable=[
        'user_id' ,
        'status' ,
        'total_price'
    ];
    public function user(){
        return $this->belongTo(User::class);
    }
    public function items(){
        return $this->hasmany(OrderItem::class);
    }
}
