<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'instrument_id', 'qty', 'notes'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function instrument()
    {
        return $this->belongsTo(Instrument::class);
    }
}
