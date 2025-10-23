<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Instrument extends Model
{
    protected $fillable = [
        'code',
        'qr_code',
        'name',
        'description',
        'status',
        'unit_id',
        'is_serialized',
    ];

    protected static function booted()
    {
        static::creating(function ($instrument) {
            if (empty($instrument->qr_code)) {
                $instrument->qr_code = 'QR-' . strtoupper(Str::random(8));
            }
        });

        static::updating(function ($instrument) {
            if (empty($instrument->qr_code)) {
                $instrument->qr_code = 'QR-' . strtoupper(Str::random(8));
            }
        });
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
