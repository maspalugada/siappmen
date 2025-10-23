<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    protected $fillable = [
        'transaction_id',
        'instrument_id',
        'qty',
        'is_complete',
        'damaged_qty'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function instrument()
    {
        return $this->belongsTo(Instrument::class);
    }
}
