<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instrument extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'is_serialized',
        'status',
        'unit_id',
    ];

    /**
     * Relasi ke Unit (ruangan)
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    /**
     * Relasi ke OrderItem (jika nanti digunakan)
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
