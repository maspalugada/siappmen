<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\{OrderItem, Unit, User};

class Order extends Model
{
    protected $fillable = [
        'order_no',
        'unit_id',
        'requested_by',
        'date_request',
        'date_return_planned',
        'status'
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function distribution()
    {
        return $this->hasOne(\App\Models\Distribution::class);
    }

    public function scopePending($q)
    {
        return $q->where('status', 'pending');
    }
}
