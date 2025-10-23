<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'unit_id',
        'delivered_by',
        'received_by',
        'date_delivered',
        'date_received',
        'status',
        'notes'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
