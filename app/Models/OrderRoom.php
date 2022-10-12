<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderRoom extends Model
{
    protected $fillable = [
    'user_id',
    'order_code',
    'start_from' ,
    'end_at' ,
    'room_id',
    'total',
    'status'
    ];

    use HasFactory;
}
