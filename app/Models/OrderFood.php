<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderFood extends Model
{
    protected $table = 'order_foods';
    protected $fillable = [  'user_id',
    'order_code' ,
    'address',
    'food_id',
    'qty',
    'total' ];
    use HasFactory;
}
