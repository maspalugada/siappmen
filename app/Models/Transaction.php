<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\{TransactionItem, Unit, User};

class Transaction extends Model
{
    protected $fillable = [
        'type',
        'reference_id',
        'user_id',
        'unit_id',
        'occurred_at',
        'notes'
    ];

    protected $dates = ['occurred_at'];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }
}
