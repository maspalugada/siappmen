<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pouch extends Model
{
    use HasFactory;
    protected $fillable = ['pouch_code', 'instrument_id', 'status'];

    public function instrument()
    {
        return $this->belongsTo(Instrument::class);
    }
}
