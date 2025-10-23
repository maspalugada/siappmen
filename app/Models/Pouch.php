<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pouch extends Model
{
    protected $fillable = ['pouch_code', 'instrument_id', 'status'];

    public function instrument()
    {
        return $this->belongsTo(Instrument::class);
    }
}
